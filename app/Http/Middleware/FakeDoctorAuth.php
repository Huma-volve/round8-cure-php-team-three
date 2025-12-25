<?php

namespace App\Http\Middleware;

use App\Models\Doctor;
use Closure;

class FakeDoctorAuth
{
    public function handle($request, Closure $next)
    {
        $doctor = Doctor::first(); // أي طبيب للتجربة

        if ($doctor) {
            auth()->setUser($doctor);
        }

        return $next($request);
    }
}

