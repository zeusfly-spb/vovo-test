<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requiredCategories = [
            'Электроника',
            'Одежда и обувь',
            'Дом и сад',
            'Спорт и отдых',
            'Книги',
            'Детские товары',
            'Автотовары',
            'Косметика и парфюмерия',
            'Зоотовары',
            'Строительство и ремонт',
            'Медицина и здоровье',
        ];

        $existingCategoryNames = Category::query()->pluck('name')->all();
        $missingCategories = array_diff($requiredCategories, $existingCategoryNames);

        foreach ($missingCategories as $categoryName) {
            Category::query()->create(['name' => $categoryName]);
        }
    }
}
