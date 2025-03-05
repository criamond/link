<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except
        = [
            'api/*',
        ];

    public function __construct(Application $app, Encrypter $encrypter)
    {
        array_push($this->except, env('APP_DOMAIN'));
        parent::__construct($app, $encrypter);
    }
}
