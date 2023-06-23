<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "email"            => 'admin@boaty.com',
            "password"         =>  bcrypt('admin'),
            "first_name"       => 'Jhon',
            "last_name"        => 'Doe',
            'roles'            => ['admin','owner','client'],
            "phone"            => '-',
            "address"          => '-',
            "birthday"         => '01/01/1990',
            "photo"            => '/img/users/noimage.png',
            'crypto_address'   => '-',
            'crypto_currency'  => '-',
        ]);

        User::create([
            "email"            => 'propietario@boaty.com',
            "password"         =>  bcrypt('owner'),
            "first_name"       => 'Demi',
            "last_name"        => 'Moore',
            'roles'            => ['admin', 'owner', 'client'],
            "phone"            => '-',
            "address"          => '-',
            "birthday"         => '01/01/1990',
            "photo"            => '/img/users/noimage.png',
            'crypto_address'   => '-',
            'crypto_currency'  => '-',
        ]);

        User::create([
            "email"            => 'cliente@boaty.com',
            "password"         =>  bcrypt('cliente'),
            "first_name"       => 'Cindy',
            "last_name"        => 'Crawford',
            'roles'            => ['client'],
            "phone"            => '-',
            "address"          => '-',
            "birthday"         => '01/01/1990',
            "photo"            => '/img/users/noimage.png',
            'crypto_address'   => '-',
            'crypto_currency'  => '-',
        ]);
    }
}
