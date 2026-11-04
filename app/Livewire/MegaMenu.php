<?php

namespace App\Livewire;

use App\Enums\CategoryGroup;
use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;
use Livewire\Component;

class MegaMenu extends Component
{
    public string $searchQuery = '';

    public array $searchResults = [];

    public bool $showSearch = false;

    public int $wishlistCount = 0;

    public bool $mobileMenuOpen = false;

    public function mount(): void
    {
        $this->updateWishlistCount();
    }

    #[On('wishlist-updated')]
    public function updateWishlistCount(): void
    {
        if (Auth::guard('web')->check()) {
            $this->wishlistCount = Wishlist::where('user_id', Auth::guard('web')->id())->count();
        } else {
            $this->wishlistCount = 0;
        }
    }

    public function updatedSearchQuery(): void
    {
        $term = trim($this->searchQuery);
        if (strlen($term) < 2) {
            $this->searchResults = [];

            return;
        }

        $products = Product::published()
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%")
                    ->orWhere('colour', 'like', "%{$term}%");
            })
            ->with(['primaryImage', 'categories'])
            ->take(6)
            ->get();

        $this->searchResults = $products->map(fn ($p) => [
            'name' => $p->name,
            'sku' => $p->sku,
            'url' => route('products.show', $p->slug),
            'price' => $p->formatted_price,
            'thumbnail' => $p->primary_image_url,
            'category' => $p->categories->first()?->name,
        ])->toArray();
    }

    public function clearSearch(): void
    {
        $this->searchQuery = '';
        $this->searchResults = [];
        $this->showSearch = false;
    }

    public function render()
    {
        $keys = ['weave', 'fabric', 'occasion', 'kanjeevaram', 'other'];

        $fetchGroups = function () {
            $all = Category::active()->ordered()->get(['id', 'name', 'slug', 'group']);

            $mapCategory = fn ($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
                'group' => $c->group instanceof CategoryGroup ? $c->group->value : (string) $c->group,
            ];

            return [
                'weave' => $all->filter(fn ($c) => ($c->group instanceof CategoryGroup ? $c->group : CategoryGroup::tryFrom($c->group)) === CategoryGroup::Weave)->map($mapCategory)->values()->all(),
                'fabric' => $all->filter(fn ($c) => ($c->group instanceof CategoryGroup ? $c->group : CategoryGroup::tryFrom($c->group)) === CategoryGroup::Fabric)->map($mapCategory)->values()->all(),
                'occasion' => $all->filter(fn ($c) => ($c->group instanceof CategoryGroup ? $c->group : CategoryGroup::tryFrom($c->group)) === CategoryGroup::Occasion)->map($mapCategory)->values()->all(),
                'kanjeevaram' => $all->filter(fn ($c) => ($c->group instanceof CategoryGroup ? $c->group : CategoryGroup::tryFrom($c->group)) === CategoryGroup::Kanjeevaram)->map($mapCategory)->values()->all(),
                'other' => $all->filter(fn ($c) => ($c->group instanceof CategoryGroup ? $c->group : CategoryGroup::tryFrom($c->group)) === CategoryGroup::Other)->map($mapCategory)->values()->all(),
            ];
        };

        // Cache category groups hierarchy; auto-invalidated when categories are saved/deleted
        $categoriesByGroup = Cache::remember(Category::MEGA_MENU_CACHE_KEY, 86400, $fetchGroups);

        // Defensive guard: if cached data is malformed or corrupted by incomplete class deserialization
        $isCorrupt = ! is_array($categoriesByGroup);
        if (! $isCorrupt) {
            foreach ($keys as $key) {
                if (! isset($categoriesByGroup[$key]) || ! is_iterable($categoriesByGroup[$key]) || $categoriesByGroup[$key] instanceof \__PHP_Incomplete_Class) {
                    $isCorrupt = true;
                    break;
                }
            }
        }

        if ($isCorrupt) {
            Cache::forget(Category::MEGA_MENU_CACHE_KEY);
            $categoriesByGroup = $fetchGroups();
            Cache::put(Category::MEGA_MENU_CACHE_KEY, $categoriesByGroup, 86400);
        }

        // Defensive guard: ensure each group is an iterable Collection
        foreach ($keys as $key) {
            if (! isset($categoriesByGroup[$key]) || ! is_iterable($categoriesByGroup[$key])) {
                $categoriesByGroup[$key] = collect();
            } else {
                $categoriesByGroup[$key] = collect($categoriesByGroup[$key]);
            }
        }

        return view('livewire.mega-menu', compact('categoriesByGroup'));
    }
}
