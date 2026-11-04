<?php

namespace Tests\Feature;

use App\Enums\CategoryGroup;
use App\Enums\EnquiryStatus;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class GroupByTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_grouping_by_classification_works_accurately(): void
    {
        Category::factory()->create(['group' => CategoryGroup::Kanjeevaram, 'name' => 'Bridal']);
        Category::factory()->create(['group' => CategoryGroup::Kanjeevaram, 'name' => 'Soft Silk']);
        Category::factory()->create(['group' => CategoryGroup::Weave, 'name' => 'Korvai']);
        Category::factory()->create(['group' => CategoryGroup::Fabric, 'name' => 'Pure Mulberry']);

        $grouped = Category::active()->ordered()->get()->groupBy('group.value');

        $this->assertCount(2, $grouped->get('kanjeevaram'));
        $this->assertCount(1, $grouped->get('weave'));
        $this->assertCount(1, $grouped->get('fabric'));
    }

    public function test_sql_aggregate_group_by_enquiry_status_is_compatible(): void
    {
        Enquiry::factory()->count(3)->create(['status' => EnquiryStatus::New]);
        Enquiry::factory()->count(2)->create(['status' => EnquiryStatus::Contacted]);
        Enquiry::factory()->count(1)->create(['status' => EnquiryStatus::Closed]);

        $statusCounts = Enquiry::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $this->assertEquals(3, $statusCounts[EnquiryStatus::New->value]);
        $this->assertEquals(2, $statusCounts[EnquiryStatus::Contacted->value]);
        $this->assertEquals(1, $statusCounts[EnquiryStatus::Closed->value]);
    }

    public function test_group_by_with_having_clause_and_aggregates(): void
    {
        $cat1 = Category::factory()->create(['name' => 'Cat1', 'group' => CategoryGroup::Kanjeevaram]);
        $cat2 = Category::factory()->create(['name' => 'Cat2', 'group' => CategoryGroup::Weave]);

        $prods1 = Product::factory()->count(4)->create();
        $prods2 = Product::factory()->count(1)->create();

        $cat1->products()->attach($prods1->pluck('id'));
        $cat2->products()->attach($prods2->pluck('id'));

        $results = DB::table('category_product')
            ->select('category_id', DB::raw('COUNT(product_id) as product_count'))
            ->groupBy('category_id')
            ->having('product_count', '>=', 2)
            ->get();

        $this->assertCount(1, $results);
        $this->assertEquals($cat1->id, $results->first()->category_id);
        $this->assertEquals(4, $results->first()->product_count);
    }
}

