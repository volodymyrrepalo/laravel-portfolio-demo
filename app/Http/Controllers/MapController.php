<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MapController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        /** @var User|null $user */
        $user = $request->user();

        if ($user === null) {
            return view('auth.access', ['intendedRoute' => 'map']);
        }

        if (! $user->hasVerifiedEmail()) {
            $request->session()->put('auth.access.intended_route', 'map');

            return redirect()->route('verification.notice');
        }

        $focusedPinId = $request->integer('pin');

        if ($focusedPinId <= 0 || ! $user->mapPins()->whereKey($focusedPinId)->exists()) {
            $focusedPinId = null;
        }

        return view('map', [
            'focusedPinId' => $focusedPinId,
            'user' => $user,
        ]);
    }
}
