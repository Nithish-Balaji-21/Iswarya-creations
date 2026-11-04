<?php

namespace Tests\Feature;

use App\Enums\CategoryGroup;
use App\Livewire\MegaMenu;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Tests\TestCase;

class MegaMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_mega_menu_renders_categories_after_refresh_and_actions(): void
    {
        config(['cache.default' => 'file']); // Forces serialize / unserialize
        Cache::flush();
        Category::factory()->create([
            'name' => 'Korvai Handloom',
            'slug' => 'korvai-handloom',
            'group' => CategoryGroup::Weave,
            'is_active' => true,
        ]);

        Category::factory()->create([
            'name' => 'Mulberry Silk Saree',
            'slug' => 'mulberry-silk',
            'group' => CategoryGroup::Fabric,
            'is_active' => true,
        ]);

        Category::factory()->create([
            'name' => 'Muhurtham Saree',
            'slug' => 'muhurtham-saree',
            'group' => CategoryGroup::Occasion,
            'is_active' => true,
        ]);

        // First render (populates cache)
        $component = Livewire::test(MegaMenu::class);
        $component->assertSee('Korvai Handloom');
        $component->assertSee('Mulberry Silk Saree');
        $component->assertSee('Muhurtham Saree');

        // Simulate interaction (Livewire action / click)
        $component->set('searchQuery', 'Korvai');
        $component->assertSee('Korvai Handloom');

        // Second render from cache (like refresh)
        $component2 = Livewire::test(MegaMenu::class);
        $component2->assertSee('Korvai Handloom');
        $component2->assertSee('Mulberry Silk Saree');
        $component2->assertSee('Muhurtham Saree');

        // Full HTTP GET request (fresh page load & refresh)
        $res1 = $this->get(route('home'));
        $res1->assertStatus(200);
        $res1->assertSee('Korvai Handloom');
        $res1->assertSee('Mulberry Silk Saree');
        $res1->assertSee('Muhurtham Saree');

        // Second HTTP GET request (serving from warm cache)
        $res2 = $this->get(route('home'));
        $res2->assertStatus(200);
        $res2->assertSee('Korvai Handloom');
        $res2->assertSee('Mulberry Silk Saree');
        $res2->assertSee('Muhurtham Saree');
    }
}
