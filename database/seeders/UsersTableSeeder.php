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
            "email"            => 'boaty@boaty.com',
            "password"         =>  bcrypt('ZuP3r4Dm1NB04Ty#'),
            "first_name"       => 'Boaty',
            "last_name"        => 'Boaty',
            'roles'            => ['admin','owner','client'],
            "phone"            => '-',
            "address"          => '-',
            "birthday"         => '01/01/1990',
            "photo"            => '/img/users/noimage.png',
            'crypto_address'   => '-',
        ]);
    }
}
