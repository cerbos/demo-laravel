<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('expenses')->updateOrInsert([
            'id' => 1,
            'amount' => 500,
            'approved_by_id' => 1,
            'region' => 'EMEA',
            'status' => 'OPEN',
            'owner_id' => 1,
            'vendor' => 'Flux Water Gear'
        ]);

        DB::table('expenses')->updateOrInsert([
            'id' => 2,
            'amount' => 2500,
            'approved_by_id' => 3,
            'region' => 'EMEA',
            'status' => 'APPROVED',
            'owner_id' => 1,
            'vendor' => 'Vortex Solar'
        ]);

        DB::table('expenses')->updateOrInsert([
            'id' => 3,
            'amount' => 12000,
            'region' => 'EMEA',
            'status' => 'OPEN',
            'owner_id' => 1,
            'vendor' => 'Global Airlines'
        ]);

        DB::table('expenses')->updateOrInsert([
            'id' => 4,
            'amount' => 2421,
            'region' => 'EMEA',
            'status' => 'OPEN',
            'owner_id' => 3,
            'vendor' => 'Vortex Solar'
        ]);

        DB::table('expenses')->updateOrInsert([
            'id' => 5,
            'amount' => 2500,
            'approved_by_id' => 3,
            'region' => 'EMEA',
            'status' => 'REJECTED',
            'owner_id' => 1,
            'vendor' => 'Vortex Solar'
        ]);
    }
}
