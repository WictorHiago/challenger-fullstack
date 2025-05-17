<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Processadores',
            'Teclados',
            'Mouses',
            'Headsets',
            'Memórias RAM',
            'HDDs',
            'SSDs NVMe',
            'Placas de Vídeo',
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
            ]);
        }
    }
}
