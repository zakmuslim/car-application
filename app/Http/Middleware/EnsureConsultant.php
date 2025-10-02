<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureConsultant {
    public function handle(Request $request, Closure $next) {
        if (!auth()->check() || !auth()->user()->role != 'consultant') {
            abort(403); 
        }            
        return $next($request);
    }
}