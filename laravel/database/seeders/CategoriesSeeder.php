<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'doces',
        ]);

        Category::create([
            'name' => 'salgados',
        ]);

        Category::create([
            'name' => 'bebidas',
        ]);

        Category::create([
            'name' => 'massas',
        ]);
    }
}
