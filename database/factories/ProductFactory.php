<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected static array $productsByCategory = [
        'Электроника' => [
            'Смартфон Xiaomi Note 12',
            'Ноутбук Lenovo IdeaPad 3',
            'Наушники Sony WH-1000XM5',
            'Телевизор Samsung 55"',
            'Планшет Huawei MatePad',
            'Смарт-часы Apple Watch SE',
            'Клавиатура Logitech MX Keys',
            'Мышь Razer DeathAdder',
            'Флешка Kingston 64GB',
            'Внешний SSD Samsung T7',
            'Моноблок Apple iMac',
            'Сетевой фильтр APC',
        ],
        'Одежда и обувь' => [
            'Футболка хлопковая',
            'Джинсы мужские классические',
            'Куртка зимняя пуховик',
            'Платье женское летнее',
            'Кроссовки Nike Air',
            'Свитер шерстяной',
            'Шапка зимняя вязаная',
            'Ремень кожаный',
            'Носки термо',
            'Шорты спортивные',
            'Пальто кашемировое',
        ],
        'Дом и сад' => [
            'Диван угловой',
            'Стол обеденный',
            'Набор кастрюль Tefal',
            'Пылесос робот Xiaomi',
            'Лопата садовая',
            'Горшок для цветов',
            'Постельное белье сатин',
            'Стул офисный',
            'Набор полотенец',
            'Светильник LED',
            'Швабра с отжимом',
        ],
        'Спорт и отдых' => [
            'Беговая дорожка',
            'Гантели разборные',
            'Велосипед горный',
            'Мяч футбольный',
            'Коврик для йоги',
            'Фитнес-браслет Honor',
            'Лыжи горные',
            'Турник настенный',
            'Роликовые коньки',
            'Палатка туристическая',
            'Скакалка',
        ],
        'Книги' => [
            'Преступление и наказание',
            'Мастер и Маргарита',
            '1984',
            'Война и мир (2 тома)',
            'Гарри Поттер и философский камень',
            'Атлант расправил плечи',
            'Три товарища',
            'Код Да Винчи',
            'Маленький принц',
            'Алые паруса',
            'Дюна',
        ],
        'Автотовары' => [
            'Моторное масло Shell 5W-40',
            'Зимние шины Michelin',
            'Видеорегистратор 2 канала',
            'Аккумулятор Varta 60Ah',
            'Коврики в салон',
            'Набор инструментов авто',
            'Автомобильное зарядное',
            'Щетки стеклоочистителя',
            'Ароматизатор в авто',
            'Чехлы на сиденья',
            'Омывайка зимняя',
        ],
        'Детские товары' => [
            'Коляска прогулочная',
            'Детское автокресло',
            'Конструктор LEGO City',
            'Развивающий коврик',
            'Детские санки',
            'Набор для творчества',
            'Пеленки для новорожденных',
            'Детская кроватка',
            'Погремушка',
            'Набор пустышек',
            'Балансир-доска',
        ],
        'Косметика и парфюмерия' => [
            'Парфюм Chanel No.5',
            'Крем для лица увлажняющий',
            'Тушь для ресниц Maybelline',
            'Шампунь против перхоти',
            'Гель для душа мужской',
            'Помада губная матовая',
            'Маска для лица тканевая',
            'Маникюрный набор',
            'Сыворотка для лица',
            'Дезодорант кристалл',
            'Ватные диски',
        ],
        'Зоотовары' => [
            'Корм сухой Purina для кошек',
            'Клетка для хомяка',
            'Игрушка для собак мяч',
            'Когтеточка для кошек',
            'Наполнитель древесный',
            'Переноска для животных',
            'Шлейка для собак',
            'Витамины для кошек',
            'Поилка автоматическая',
            'Когтерезка',
            'Лежак круглый',
        ],
        'Строительство и ремонт' => [
            'Перфоратор Makita',
            'Лазерный уровень',
            'Шуруповерт аккумуляторный Bosch',
            'Рулетка 5 м',
            'Набор сверл по бетону',
            'Краска акриловая белая 3 кг',
            'Валик малярный',
            'Малярный скотч',
            'Углошлифовальная машина',
            'Строительный фен',
        ],
        'Медицина и здоровье' => [
            'Тонометр автоматический',
            'Пульсоксиметр',
            'Витамин D3',
            'Ингалятор компрессорный',
            'Ортопедическая подушка',
            'Градусник электронный',
            'Набор пластырей',
            'Магний B6',
            'Эластичный бинт',
            'Небулайзер',
        ],
    ];

    protected static array $usedProductIndexesByCategory = [];
    
    public function definition(): array
    {
        $supportedCategoryNames = array_keys(self::$productsByCategory);
        $categories = Category::query()
            ->whereIn('name', $supportedCategoryNames)
            ->get();

        $availableCategories = $categories->filter(function (Category $category) {
            $usedIndexes = self::$usedProductIndexesByCategory[$category->name] ?? [];
            return count($usedIndexes) < count(self::$productsByCategory[$category->name]);
        })->values();

        if ($availableCategories->isEmpty()) {
            self::$usedProductIndexesByCategory = [];
            $availableCategories = $categories->values();
        }

        $category = $availableCategories->random();
        $products = self::$productsByCategory[$category->name];
        $usedIndexes = self::$usedProductIndexesByCategory[$category->name] ?? [];
        $availableIndexes = array_values(array_diff(array_keys($products), $usedIndexes));
        $productIndex = $availableIndexes[array_rand($availableIndexes)];

        self::$usedProductIndexesByCategory[$category->name][] = $productIndex;
        $product = $products[$productIndex];

        return [
            'category_id' => $category->id,
            'name' => $product,
            'price' => $this->faker->randomFloat(2, 100, 10000),
            'in_stock' => $this->faker->boolean(80),
            'rating' => $this->faker->randomFloat(1, 1, 5),
        ];
    }
    
}