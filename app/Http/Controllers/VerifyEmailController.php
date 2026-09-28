<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VerifyEmailController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, int $id, string $hash): View
    {
        $user = User::query()->findOrFail($id);

        abort_unless(
            hash_equals(sha1($user->getEmailForVerification()), $hash),
            403,
        );

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            event(new Verified($user));
        }

        Auth::login($user);
        $request->session()->regenerate();

        $intendedRoute = $request->session()->pull('auth.access.intended_route', 'profile');

        if (! in_array($intendedRoute, ['profile', 'map'], true)) {
            $intendedRoute = 'profile';
        }

        return view('auth.email-confirmed', [
            'user' => $user,
            'intendedRoute' => $intendedRoute,
        ]);
    }
}
