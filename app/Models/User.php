<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\GenerateUuid;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, GenerateUuid;

    protected $guarded = ['id'];
    protected $appends = ['picture_url', 'created_date'];
    public $incrementing = false;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $latestSeq = User::max('seq') ?? 0;
            $user->seq = $latestSeq + 1;
        });
    }

    public function getPictureUrlAttribute()
    {
        if (!$this->picture) {
            return asset('images/assets/user.jpg'); // Default profile image
        }
        return url('images/users/' . $this->picture);

        // If stored in 'storage/app/public/users/', use:
        // return Storage::url('users/' . $this->picture);

        // If stored in 'public/images/users/', use:
        // return url('images/users/' . $this->picture);

        // If stored in AWS S3 or another disk, use:
        // return Storage::disk('s3')->url('users/' . $this->picture);
    }

    public function getCreatedDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('Y-m-d');
    }

    public function web_role()
    {
        return $this->belongsTo(WebRole::class, 'web_role_id');
    }
}
