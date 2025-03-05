<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, Notifiable;

    protected $fillable
        = [
            'name',
            'email',
            'password',
            'verification_token',
            "email_verified_at",
        ];

    protected $hidden
        = [
            'password',
            'remember_token',
        ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Получить указанные данные для JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

}
