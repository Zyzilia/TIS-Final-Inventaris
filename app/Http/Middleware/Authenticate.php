<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as BaseAuthenticate;
use Illuminate\Http\Request;

class Authenticate extends BaseAuthenticate
{
    /**
     * Override redirectTo to avoid calling a route helper for API requests
     * which may not have a `login` route defined in this application.
     *
     * Returning null causes the AuthenticationException to be thrown
     * without an unusable redirect target.
     */
    protected function redirectTo(Request $request)
    {
        return null;
    }
}
