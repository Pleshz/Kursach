<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $cars = [
            [
                'slug' => 'hyundai-solaris',
                'name' => 'Hyundai Solaris',
                'segment' => 'Эконом',
                'transmission' => 'Автомат',
                'fuel' => 'Бензин',
                'description' => 'Городской седан для повседневных поездок и деловых встреч.',
                'image_url' => 'https://images.unsplash.com/photo-1542282088-fe8426682b8f?auto=format&fit=crop&w=1200&q=80',
                'latitude' => 58.0105,
                'longitude' => 56.2502,
                'rate_minute' => 10,
                'rate_hour' => 350,
                'rate_day' => 1900,
                'range_km' => 450,
                'is_active' => true,
            ],
            [
                'slug' => 'kia-rio-x-line',
                'name' => 'Kia Rio X-Line',
                'segment' => 'Комфорт',
                'transmission' => 'Автомат',
                'fuel' => 'Бензин',
                'description' => 'Универсал с увеличенным клиренсом для города и пригорода.',
                'image_url' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1200&q=80',
                'latitude' => 58.0064,
                'longitude' => 56.2371,
                'rate_minute' => 12,
                'rate_hour' => 420,
                'rate_day' => 2200,
                'range_km' => 480,
                'is_active' => true,
            ],
            [
                'slug' => 'skoda-octavia',
                'name' => 'Skoda Octavia',
                'segment' => 'Комфорт',
                'transmission' => 'Автомат',
                'fuel' => 'Дизель',
                'description' => 'Просторный салон и увеличенный багажник для семейных поездок.',
                'image_url' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1200&q=80',
                'latitude' => 57.9982,
                'longitude' => 56.2694,
                'rate_minute' => 14,
                'rate_hour' => 480,
                'rate_day' => 2600,
                'range_km' => 650,
                'is_active' => true,
            ],
            [
                'slug' => 'tesla-model-3',
                'name' => 'Tesla Model 3',
                'segment' => 'Электро',
                'transmission' => 'Автомат',
                'fuel' => 'Электро',
                'description' => 'Электроседан с быстрым разгоном и доступом к зарядной сети.',
                'image_url' => 'https://images.unsplash.com/photo-1619767886558-efdc259cde1a?auto=format&fit=crop&w=1200&q=80',
                'latitude' => 58.0141,
                'longitude' => 56.2168,
                'rate_minute' => 18,
                'rate_hour' => 650,
                'rate_day' => 3900,
                'range_km' => 380,
                'is_active' => true,
            ],
            [
                'slug' => 'bmw-3-series',
                'name' => 'BMW 3 Series',
                'segment' => 'Бизнес',
                'transmission' => 'Автомат',
                'fuel' => 'Бензин',
                'description' => 'Бизнес-седан для деловых поездок и встреч с клиентами.',
                'image_url' => 'https://images.unsplash.com/photo-1549924231-f129b911e442?auto=format&fit=crop&w=1200&q=80',
                'latitude' => 58.0237,
                'longitude' => 56.2489,
                'rate_minute' => 20,
                'rate_hour' => 720,
                'rate_day' => 4300,
                'range_km' => 520,
                'is_active' => true,
            ],
            [
                'slug' => 'renault-kaptur',
                'name' => 'Renault Kaptur',
                'segment' => 'Комфорт',
                'transmission' => 'Автомат',
                'fuel' => 'Бензин',
                'description' => 'Кроссовер для поездок по городу и за его пределами.',
                'image_url' => 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?auto=format&fit=crop&w=1200&q=80',
                'latitude' => 57.9904,
                'longitude' => 56.2387,
                'rate_minute' => 13,
                'rate_hour' => 460,
                'rate_day' => 2500,
                'range_km' => 500,
                'is_active' => true,
            ],
        ];

        foreach ($cars as $car) {
            Car::query()->updateOrCreate(['slug' => $car['slug']], $car);
        }
    }
}

