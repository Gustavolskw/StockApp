<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        //$this->call(AccessSeeder::class);

        $faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {
            $name = $faker->userName();
            DB::connection('mysql')->table('users')->insert([
                'name' => $name,
                'email' => $name . '@email.com',
                'access_id' => Random::generate(1, '1-4'),
                'password' => Hash::make('123456'),
            ]);
        }
    }
}
