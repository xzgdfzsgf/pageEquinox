<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminBasicAuth
{
    /**
     * Minimal HTTP Basic guard for the CSV exports, driven by .env credentials.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $password = (string) config('equinox.admin_password');
        abort_if($password === '', 404);

        $userOk = hash_equals((string) config('equinox.admin_user'), (string) $request->getUser());
        $passOk = hash_equals($password, (string) $request->getPassword());

        if (! $userOk || ! $passOk) {
            return response('Authentication required.', 401, ['WWW-Authenticate' => 'Basic realm="EQUINOX admin"']);
        }

        return $next($request);
    }
}
