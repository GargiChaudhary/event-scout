<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

final class Login
{
    /** @param  array{}  $args */
    public function __invoke($_, array $args)
    {
        // Plain Laravel: Auth::guard()
        // Laravel Sanctum:
        $guard = Auth::guard(Arr::first(config('sanctum.guard', 'web')));

        if (!$guard->attempt($args)) {
            throw new \GraphQL\Error\Error('Invalid credentials.');
        }

        $user = $guard->user();
        $user["accessToken"] = $user->createToken("backend")->plainTextToken;

        return $user;
    }
}