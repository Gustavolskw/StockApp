<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('access_type')->insert([
            'access_name' => 'ADMIN',
            'access_level' => 3
        ]);
        DB::table('access_type')->insert([
            'access_name' => 'MANAGER',
            'access_level' => 2
        ]);
        DB::table('access_type')->insert([
            'access_name' => 'EMPLOYEE',
            'access_level' => 1
        ]);
        DB::table('access_type')->insert([
            'access_name' => 'USER',
            'access_level' => 0
        ]);
    }
}
