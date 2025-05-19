<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chamar os seeders personalizados
        $this->call([
            UserSeeder::class,
            CategoriesSeeder::class,
            ProductsSeeder::class,
        ]);
        
        // Ou, se quiser manter o usuário de teste, adicione o campo role
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'role' => 'user', // Adicionando o campo role
        // ]);
    }
}
