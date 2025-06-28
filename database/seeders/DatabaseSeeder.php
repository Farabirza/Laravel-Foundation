<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\WebRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    protected $logs;

    public function run(): void
    {
        $error_status = false;
        DB::beginTransaction();

        $web_roles = ['superadmin', 'admin', 'basic_user'];
        $superadmin_id = '';
        foreach($web_roles as $role) {
            echo date('Y-m-d H:i:s')."\t Create role: $role \r\n";
            try {
                $web_role = WebRole::create([
                    'name'  => $role,
                ]);
                if($role == 'superadmin') $superadmin_id = $web_role->id;
            } catch(\Exception $e) {
                $error_status = true;
                $this->logs->write($e->getMessage());
            }
        }

        try {
            echo date('Y-m-d H:i:s')."\t Create user: superadmin \r\n";
            $superadmin = User::updateOrCreate([
                'email'         => 'superadmin@mail.com',
            ], [
                'full_name'     => 'superadmin',
                'password'      => Hash::make('....'),
                'web_role_id'   => $superadmin_id,
            ]);
        } catch(\Exception $e) {
            $this->logs->write($e->getMessage());
        }

        if($error_status) {
            echo date('Y-m-d H:i:s')."\t Initiating process error, rollback \r\n";
            $this->logs->write("Initiating process error, rollback");
            DB::rollBack();
        } else {
            echo date('Y-m-d H:i:s')."\t Process complete \r\n";
            DB::commit();
        }

        $this->call([
        ]);
    }
}
