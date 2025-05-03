<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Logs;
use App\Models\WebRole;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class InitiateApp extends Command
{
    protected $logs;

    protected $signature = 'command:initiate';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
        $this->logs = new Logs('InitiateApp');
    }

    public function handle()
    {
        echo date('Y-m-d H:i:s')."\t Initiating Laravel App \r\n";
        $this->logs->write("Initiating App");
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
            // Artisan::call('db:seed', ['--class' => 'UsersTableSeeder']);
            $superadmin = User::updateOrCreate([
                'email'         => 'superadmin@mail.com',
            ], [
                'username'      => 'superadmin',
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
    }
}
