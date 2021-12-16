<?php

use Illuminate\Http\Request;

/**
 * Return the authenticated user.
 *
 * @return App\Models\User
 */
function user(Request $request)
{
    return $request->user();
}

