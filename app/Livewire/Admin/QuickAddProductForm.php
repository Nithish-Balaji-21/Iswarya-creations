<?php

namespace App\Livewire\Admin;

use App\Enums\ProductAvailability;
use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\Image\ImageOptimizationService;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class QuickAddProductForm extends Component
{
    use WithFileUploads;

    public string $sku = '';

    public string $name = '';

    public ?string $price = null;

    public ?string $discount_price = null;

    public ?string $colour = '';

    public ?int $stock_quantity = null;

    public array $selectedCategories = [];

    public bool $isPublished = true;

    public array $photos = []; // Multi-file uploads up to 10

    public string $price_note = '';

    public string $short_description = '';

    public function mount(): void
    {
        $this->sku = Product::generateNextSku();
    }

    protected function rules(): array
    {
        return [
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'colour' => ['nullable', 'string', 'max:100'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'selectedCategories' => ['required', 'array', 'min:1'],
            'selectedCategories.*' => ['exists:categories,id'],
            'photos' => ['required', 'array', 'min:1', 'max:10'],
            'photos.*' => ['image', 'max:25600'], // 25MB max per image
        ];
    }

    protected $messages = [
        'photos.required' => 'Please upload at least one photo of the saree.',
        'photos.max' => 'You can upload up to 10 photos per saree.',
        'selectedCategories.required' => 'Please select at least one category/collection.',
    ];

    public function removePhoto(int $index): void
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
    }

    public function save(ImageOptimizationService $imageService)
    {
        $this->validate();

        $status = $this->isPublished ? ProductStatus::Published : ProductStatus::Draft;

        // Create product record
        $product = Product::create([
            'sku' => $this->sku,
            'name' => $this->name,
            'slug' => Str::slug($this->name).'-'.strtolower($this->sku),
            'short_description' => ! empty($this->short_description)
                ? $this->short_description
                : 'Handwoven pure silk saree from our Peelamedu, Coimbatore flagship.',
            'description' => '<p>Crafted by master weavers using certified pure mulberry silk and tested zari. Direct handloom quality.</p>',
            'colour' => $this->colour ?: null,
            'price' => $this->price !== null && $this->price !== '' ? (float) $this->price : null,
            'discount_price' => $this->discount_price !== null && $this->discount_price !== '' ? (float) $this->discount_price : null,
            'price_note' => $this->price_note ?: null,
            'availability' => ProductAvailability::Available,
            'stock_quantity' => $this->stock_quantity,
            'status' => $status,
            'published_at' => $this->isPublished ? now() : null,
            'specs' => [
                'zari_type' => 'Tested Half-Fine Gold Zari',
                'blouse_piece' => 'Included (Matching unstitched)',
                'care' => 'Dry Clean Only',
                'weave_technique' => 'Handloom Weave',
                'origin' => 'Peelamedu, Coimbatore',
            ],
        ]);

        // Attach categories
        $product->categories()->sync($this->selectedCategories);

        // Process uploaded images into 3 WebP variants (400px, 900px, 1600px)
        foreach ($this->photos as $index => $photo) {
            $variants = $imageService->process($photo, 'products');

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $variants['large'],
                'medium_path' => $variants['medium'],
                'thumb_path' => $variants['thumb'],
                'is_primary' => $index === 0, // First photo is default primary
                'sort_order' => $index,
            ]);
        }

        session()->flash('success', "Saree {$product->sku} ({$product->name}) has been saved and published!");

        return $this->redirectRoute('admin.products.index');
    }

    public function render()
    {
        $categories = Category::active()->ordered()->get()->groupBy('group.value');

        return view('livewire.admin.quick-add-product-form', compact('categories'));
    }
}
