<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\GenerateUuid;
use Illuminate\Foundation\Auth\User as Authenticatable;

class WebRole extends Authenticatable
{
    use GenerateUuid;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasMany(User::class, 'web_role_id');
    }
}
