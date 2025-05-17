<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar usuário administrador
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Criar usuário comum
        User::factory()->create([
            'name' => 'Usuário',
            'email' => 'user@example.com',
            'role' => 'user',
        ]);

        // Executar os seeders de categorias e produtos
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
