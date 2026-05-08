<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('admin_email')) {
            return $next($request);
        }

        if ($request->session()->has('customer_email')) {
            return redirect('/customer');
        }

        return redirect('/login');
    }
}
