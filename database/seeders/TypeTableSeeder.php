<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['Velero','Yate','Goleta','Catamaran','Lancha'];

        foreach ($types as $type) {
            Type::create([
                'name'=>$type,
            ]);
        }
    }
}
