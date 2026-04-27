<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected static array $categories = [
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
    
    public function definition(): array
    {
        $name = $this->faker->randomElement(self::$categories);
        
        return [
            'name' => $name,
        ];
    }
}
