<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'payment/response',
    ];
}

