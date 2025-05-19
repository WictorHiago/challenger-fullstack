<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Bolo de Chocolate',
            'price' => 10.00,
            'description' => 'Bolo de chocolate',
            'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ9dIGyQEBGg9mI5L1-ndp3_OFBUzqLrJbPlA&s',
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'Bolo de Chocolate',
            'price' => 10.00,
            'description' => 'Bolo de chocolate',
            'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQP2-boHwip3_54eZk49l7GTHztzXZWDb7r4A&s',
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'Pizza de peperoni',
            'price' => 10.00,
            'description' => 'Pizza de peperoni',
            'image_url' => 'https://www.minhareceita.com.br/app/uploads/2022/12/pizza-de-pepperoni-caseira-portal-minha-receita.jpg',
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'Pizza portuguesa',
            'price' => 10.00,
            'description' => 'Pizza portuguesa',
            'image_url' => 'https://www.estadao.com.br/resizer/v2/SGSA2RXZQRASROXQVI2STOP4UU.jpg?quality=80&auth=dbf67e9a85a08e95d0a0df51c22bc71c6cbf5bbd6f6b939183d2b1cfec0ca598&width=1200&height=900&smart=true',
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'Coca-Cola 2L',
            'price' => 10.00,
            'description' => 'Coca-Cola 2L',
            'image_url' => 'https://mercantilatacado.vtexassets.com/arquivos/ids/168716-800-auto?v=638342826764700000&width=800&height=auto&aspect=true',
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'Pepsi 2L',
            'price' => 10.00,
            'description' => 'Pepsi 2L',
            'image_url' => 'https://carrefourbrfood.vtexassets.com/arquivos/ids/171081772/667473583148090012d28b77_1.jpg?v=638606493071270000',
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'Pastel',
            'price' => 10.00,
            'description' => 'Pastel',
            'image_url' => 'https://www.yoki.com.br/_next/image?url=https%3A%2F%2Fprodcontent.yoki.com.br%2Fwp-content%2Fuploads%2F2024%2F09%2FPastel-Mineiro-800x450-1.jpg&w=1400&q=75',
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'Esfiha',
            'price' => 10.00,
            'description' => 'Esfiha',
            'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRjF9aTkfZ1Zxsejr10i3n90Sy6OsyCUV1b9A&s',
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'Esfiha fechada',
            'price' => 10.00,
            'description' => 'Esfiha fechada',
            'image_url' => 'https://guiadacozinha.com.br/wp-content/uploads/2019/10/esfirra-carne-tradicional.jpg',
            'category_id' => 1,
        ]);

    }
}
