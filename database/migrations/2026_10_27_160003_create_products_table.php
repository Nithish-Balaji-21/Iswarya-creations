<?php

use App\Enums\ProductAvailability;
use App\Enums\ProductStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('colour')->nullable()->index();
            $table->decimal('price', 10, 2)->nullable()->index();
            $table->decimal('discount_price', 10, 2)->nullable()->index();
            $table->string('price_note')->nullable();
            $table->string('availability')->default(ProductAvailability::Available->value)->index();
            $table->unsignedInteger('stock_quantity')->nullable();
            $table->string('status')->default(ProductStatus::Draft->value)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->unsignedInteger('view_count')->default(0)->index();
            $table->json('specs')->nullable(); // zari type, blouse piece, care, weave technique
            $table->softDeletes();
            $table->timestamps();

            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->fullText(['name', 'short_description']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
