<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductIndexRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request): JsonResponse
    {
        $filters = $request->validated();

        $products = Product::query()
            ->when($request->filled('q'), function ($query) use ($filters) {
                $query->where('name', 'like', '%' . $filters['q'] . '%');
            })
            ->when($request->filled('price_from'), function ($query) use ($filters) {
                $query->where('price', '>=', $filters['price_from']);
            })
            ->when($request->filled('price_to'), function ($query) use ($filters) {
                $query->where('price', '<=', $filters['price_to']);
            })
            ->when($request->filled('category_id'), function ($query) use ($filters) {
                $query->where('category_id', $filters['category_id']);
            })
            ->when($request->has('in_stock'), function ($query) use ($request) {
                $query->where('in_stock', $request->boolean('in_stock'));
            })
            ->when($request->filled('rating_from'), function ($query) use ($filters) {
                $query->where('rating', '>=', $filters['rating_from']);
            });

        $this->applySorting($products, $filters['sort'] ?? 'newest');

        $paginatedProducts = $products->paginate($filters['per_page'] ?? 15)->withQueryString();

        return response()->json($paginatedProducts);
    }

    private function applySorting($query, string $sort): void
    {
        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'rating_desc' => $query->orderByDesc('rating')->orderByDesc('id'),
            default => $query->latest(),
        };
    }
}
