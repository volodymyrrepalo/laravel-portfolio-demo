<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        /** @var User|null $user */
        $user = $request->user();

        if ($user === null) {
            return view('auth.access', ['intendedRoute' => 'profile']);
        }

        if (! $user->hasVerifiedEmail()) {
            $request->session()->put('auth.access.intended_route', 'profile');

            return redirect()->route('verification.notice');
        }

        return view('profile', ['user' => $user]);
    }
}
