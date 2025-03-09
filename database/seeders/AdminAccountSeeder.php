<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Theme;
use App\Models\Invitation;
use Illuminate\Database\Seeder;
use App\Models\WeddingConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use DB;

class AdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('username', 'admin')->first();

        DB::beginTransaction();
        if(!$admin) {
            try {
                $admin = User::create([
                    'username' => 'admin',
                    'email' => 'adm.ruangnamu@gmail.com',
                    'password' => bcrypt('....'),
                    'email_verified_At' => now(),
                ]);
            } catch (\Throwable $e) {
                DB::rollBack();
                echo $e->getMessage();
            }
        }
        DB::commit();
    }
}
