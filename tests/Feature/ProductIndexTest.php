<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_paginated_products(): void
    {
        $category = Category::factory()->create();
        Product::factory()->count(20)->create(['category_id' => $category->id]);

        $response = $this->getJson('/api/products');

        $response
            ->assertOk()
            ->assertJsonCount(15, 'data')
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'category_id',
                        'in_stock',
                        'rating',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'per_page',
                'total',
            ]);
    }

    public function test_it_filters_products_by_query_parameters(): void
    {
        $electronics = Category::factory()->create(['name' => 'Электроника']);
        $books = Category::factory()->create(['name' => 'Книги']);

        $matchingProduct = Product::factory()->create([
            'name' => 'Смартфон Pro Max',
            'price' => 999.99,
            'category_id' => $electronics->id,
            'in_stock' => true,
            'rating' => 4.8,
        ]);

        Product::factory()->create([
            'name' => 'Смартфон Basic',
            'price' => 99.99,
            'category_id' => $electronics->id,
            'in_stock' => true,
            'rating' => 4.9,
        ]);

        Product::factory()->create([
            'name' => 'Смартфон Out of Stock',
            'price' => 1099.99,
            'category_id' => $electronics->id,
            'in_stock' => false,
            'rating' => 4.9,
        ]);

        Product::factory()->create([
            'name' => 'Книга по архитектуре',
            'price' => 999.99,
            'category_id' => $books->id,
            'in_stock' => true,
            'rating' => 4.9,
        ]);

        $response = $this->getJson('/api/products?q=Смартфон&price_from=500&price_to=1000&category_id=' . $electronics->id . '&in_stock=true&rating_from=4.7');

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $matchingProduct->id);
    }

    public function test_it_sorts_products_by_requested_option(): void
    {
        $category = Category::factory()->create();

        Product::factory()->create([
            'name' => 'Budget',
            'price' => 100,
            'category_id' => $category->id,
            'rating' => 3.1,
        ]);

        Product::factory()->create([
            'name' => 'Mid',
            'price' => 500,
            'category_id' => $category->id,
            'rating' => 4.9,
        ]);

        Product::factory()->create([
            'name' => 'Premium',
            'price' => 900,
            'category_id' => $category->id,
            'rating' => 4.2,
        ]);

        $priceResponse = $this->getJson('/api/products?sort=price_desc&per_page=3');
        $ratingResponse = $this->getJson('/api/products?sort=rating_desc&per_page=3');

        $priceResponse->assertOk();
        $this->assertSame(
            ['Premium', 'Mid', 'Budget'],
            array_column($priceResponse->json('data'), 'name')
        );

        $ratingResponse->assertOk();
        $this->assertSame(
            ['Mid', 'Premium', 'Budget'],
            array_column($ratingResponse->json('data'), 'name')
        );
    }

    public function test_it_validates_query_parameters(): void
    {
        $response = $this->getJson('/api/products?sort=invalid&rating_from=7');

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['sort', 'rating_from']);
    }
}
