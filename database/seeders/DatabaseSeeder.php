<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // SOLO PARA TESTING LOCAL
            UsersTableSeeder::class,
            // BoatTableSeeder::class,
            // GalleryTableSeeder::class,

            TypeTableSeeder::class,
            PlatformTableSeeder::class,
        ]);
    }
}
