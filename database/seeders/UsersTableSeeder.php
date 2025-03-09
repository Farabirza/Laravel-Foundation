<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WebRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $amount = 30;

        $web_role = WebRole::where('name', 'basic_user')->first();
        if($web_role == '') {
            $web_role = WebRole::create([
                'name'  => 'basic_user'
            ]);
        }
        $role_id = $web_role->id;

        for($i = 1; $i <= $amount; $i++) {
            User::create([
                'email'         => fake()->email,
                'username'      => fake()->username,
                'password'      => hash::make('password'),
                'web_role_id'   => $role_id
            ]);
        }
    }
}
