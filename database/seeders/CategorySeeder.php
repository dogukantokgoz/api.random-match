<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Genel Sohbet',
                'description' => 'Her konuda sohbet edebileceğiniz kategori',
            ],
            [
                'name' => 'Oyun',
                'description' => 'Oyunlar hakkında sohbet etmek için',
            ],
            [
                'name' => 'Müzik',
                'description' => 'Müzik hakkında konuşmak için',
            ],
            [
                'name' => 'Spor',
                'description' => 'Spor konularını tartışmak için',
            ],
            [
                'name' => 'Teknoloji',
                'description' => 'Teknoloji ve bilim konuları için',
            ],
            [
                'name' => 'Sanat',
                'description' => 'Sanat ve kültür konuları için',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
