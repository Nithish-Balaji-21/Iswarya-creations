<?php

namespace Tests\Unit;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\NewsletterSubscriber;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\VideoAppointment;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_and_product_relationships(): void
    {
        $parent = Category::factory()->create(['name' => 'Kanjeevaram']);
        $child = Category::factory()->create(['name' => 'Bridal Kanjeevaram', 'parent_id' => $parent->id]);

        $this->assertEquals($parent->id, $child->parent->id);
        $this->assertTrue($parent->children->contains($child));

        $product = Product::factory()->create(['name' => 'Pure Zari Silk Saree']);
        $product->categories()->attach($child->id);

        $this->assertTrue($product->categories->contains($child));
        $this->assertTrue($child->products->contains($product));
    }

    public function test_product_images_and_primary_image_relationships(): void
    {
        $product = Product::factory()->create();

        $img1 = ProductImage::factory()->create([
            'product_id' => $product->id,
            'is_primary' => false,
            'sort_order' => 1,
        ]);
        $img2 = ProductImage::factory()->create([
            'product_id' => $product->id,
            'is_primary' => true,
            'sort_order' => 0,
        ]);

        $this->assertCount(2, $product->images);
        $this->assertEquals($img2->id, $product->primaryImage->id);
    }

    public function test_user_wishlists_and_enquiries_relationships(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $wishlist = Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $enquiry = Enquiry::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $appointment = VideoAppointment::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($user->wishlists->contains($wishlist));
        $this->assertTrue($user->wishlistProducts->contains($product));
        $this->assertTrue($user->enquiries->contains($enquiry));
        $this->assertTrue($user->videoAppointments->contains($appointment));
    }

    public function test_banner_and_newsletter_scopes(): void
    {
        $activeBanner = Banner::factory()->create(['is_active' => true, 'sort_order' => 1]);
        $inactiveBanner = Banner::factory()->create(['is_active' => false, 'sort_order' => 0]);

        $banners = Banner::active()->ordered()->get();
        $this->assertTrue($banners->contains($activeBanner));
        $this->assertFalse($banners->contains($inactiveBanner));

        $sub = NewsletterSubscriber::create(['email' => 'active@example.com', 'subscribed_at' => now()]);
        $unsub = NewsletterSubscriber::create(['email' => 'unsub@example.com', 'subscribed_at' => now(), 'unsubscribed_at' => now()]);

        $this->assertTrue(NewsletterSubscriber::active()->get()->contains($sub));
        $this->assertFalse(NewsletterSubscriber::active()->get()->contains($unsub));
    }
}

