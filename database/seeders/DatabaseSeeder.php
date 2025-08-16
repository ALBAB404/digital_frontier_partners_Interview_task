<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\api\NearbyBooksTestUserSeeder;
use Database\Seeders\api\BookSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            NearbyBooksTestUserSeeder::class,
            BookSeeder::class,
        ]);
    }
}
