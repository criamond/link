<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, Notifiable;
    use HasFactory;

    protected $fillable
        = [
            'name',
            'email',
            'password',
            'verification_token',
            "email_verified_at",
            "role",
        ];

    protected $hidden
        = [
            'password',
            'remember_token',
            'verification_token',
        ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

}
