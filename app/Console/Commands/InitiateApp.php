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
        $this->logs->write("Initiating App");
        $error_status = false;

        DB::beginTransaction();

        $web_roles = ['superadmin', 'admin', 'basic_user'];
        $superadmin_id = '';
        foreach($web_roles as $role) {
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
            // Artisan::call('db:seed', ['--class' => 'UsersTableSeeder']);
            $superadmin = User::create([
                'email'         => 'superadmin@mail.com',
                'username'      => 'superadmin',
                'password'      => Hash::make('....'),
                'web_role_id'   => $superadmin_id,
            ]);
        } catch(\Exception $e) {
            $this->logs->write($e->getMessage());
        }

        if($error_status) {
            $this->logs->write("Initiating process error, rollback");
            DB::rollBack();
        } else {
            DB::commit();
        }
    }
}
