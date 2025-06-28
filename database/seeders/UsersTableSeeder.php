<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WebRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use DateTime;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $amount = 10000;

        $web_role = WebRole::where('name', 'basic_user')->first();
        if($web_role == '') {
            $web_role = WebRole::create([
                'name'  => 'basic_user'
            ]);
        }
        $role_id = $web_role->id;

        $latest_user = User::orderByDesc('seq')->first();
        $last_seq = $latest_user ? $latest_user->seq + 1 : 1;

        // Insert in chunks
        $chunkSize = 100;
        $users = [];
        for ($i = 1; $i <= $amount; $i++) {
            $last_seq++;
            $users[] = [
                'id'            => \Str::uuid()->toString(),
                'seq'           => $last_seq,
                'email'         => fake()->email,
                'full_name'     => fake()->username,
                'password'      => Hash::make('password'),
                'web_role_id'   => $role_id,
                'created_at'    => $this->genRandDateTime(),
            ];
            if (count($users) >= $chunkSize) {
                User::insert($users);
                $users = [];
            }
        }
        if (!empty($users)) User::insert($users);
    }

    public function genRandDateTime(): DateTime
    {
        $start = strtotime('2020-01-01 00:00:00');
        $end = time();

        // Generate random timestamp between start and end
        $randomTimestamp = mt_rand($start, $end);

        return (new DateTime())->setTimestamp($randomTimestamp);
    }
}
