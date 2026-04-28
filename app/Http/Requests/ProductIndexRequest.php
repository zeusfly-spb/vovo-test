<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if (! $this->has('in_stock')) {
            return;
        }

        $inStock = $this->query('in_stock');

        if (is_string($inStock)) {
            $normalizedValue = strtolower($inStock);

            if (in_array($normalizedValue, ['true', '1'], true)) {
                $this->merge(['in_stock' => true]);
            }

            if (in_array($normalizedValue, ['false', '0'], true)) {
                $this->merge(['in_stock' => false]);
            }
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:255'],
            'price_from' => ['nullable', 'numeric', 'min:0'],
            'price_to' => ['nullable', 'numeric', 'min:0', 'gte:price_from'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'in_stock' => ['nullable', 'boolean'],
            'rating_from' => ['nullable', 'numeric', 'between:0,5'],
            'sort' => ['nullable', 'string', Rule::in(['price_asc', 'price_desc', 'rating_desc', 'newest'])],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
