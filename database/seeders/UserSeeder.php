<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->updateOrInsert([
            'id' => 1,
            'name' => 'Sally',
            'email' => 'sally@sally.co',
            'password' => Hash::make('123'),
            'roles' => 'USER',
            'department' => 'SALES',
            'region' => 'EMEA'
        ]);

        DB::table('users')->updateOrInsert([
            'id' => 2,
            'name' => 'Ian',
            'email' => 'ian@ian.co',
            'password' => Hash::make('123'),
            'roles' => 'ADMIN',
            'department' => 'IT'
        ]);

        DB::table('users')->updateOrInsert([
            'id' => 3,
            'name' => 'frank',
            'email' => 'frank@frank.co',
            'password' => Hash::make('123'),
            'roles' => 'USER',
            'department' => 'FINANCE',
            'region' => 'EMEA'
        ]);

        DB::table('users')->updateOrInsert([
            'id' => 4,
            'name' => 'derek',
            'email' => 'derek@derek.co',
            'password' => Hash::make('123'),
            'roles' => 'USER,MANAGER',
            'department' => 'FINANCE',
            'region' => 'EMEA'
        ]);

        DB::table('users')->updateOrInsert([
            'id' => 5,
            'name' => 'simon',
            'email' => 'simon@simon.co',
            'password' => Hash::make('123'),
            'roles' => 'USER,MANAGER',
            'department' => 'SALES',
            'region' => 'NA'
        ]);

        DB::table('users')->updateOrInsert([
            'id' => 6,
            'name' => 'mark',
            'email' => 'mark@mark.co',
            'password' => Hash::make('123'),
            'roles' => 'USER,MANAGER',
            'department' => 'SALES',
            'region' => 'EMEA'
        ]);

        DB::table('users')->updateOrInsert([
            'id' => 7,
            'name' => 'sydney',
            'email' => 'sydney@sydney.co',
            'password' => Hash::make('123'),
            'roles' => 'USER',
            'department' => 'SALES',
            'region' => 'NA'
        ]);
    }
}
