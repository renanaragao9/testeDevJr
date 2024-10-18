<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Chame os seeders de categoria e produto
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
