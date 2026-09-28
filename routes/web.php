<?php

use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/styleguide', 'styleguide')->name('styleguide');

Route::get('/profile', ProfileController::class)->name('profile');

Route::get('/map', MapController::class)->name('map');

Route::get('/favorites', FavoritesController::class)->name('favorites');

Route::get('/email/verify', function (Request $request): RedirectResponse|View {
    $intendedRoute = session('auth.access.intended_route', 'profile');

    if (! in_array($intendedRoute, ['profile', 'map', 'favorites'], true)) {
        $intendedRoute = 'profile';
    }

    if ($request->user()->hasVerifiedEmail()) {
        return redirect()->route($intendedRoute);
    }

    return view('auth.verify-email', ['intendedRoute' => $intendedRoute]);
})->middleware('auth')->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request): RedirectResponse {
    $intendedRoute = session('auth.access.intended_route', 'profile');

    if (! in_array($intendedRoute, ['profile', 'map', 'favorites'], true)) {
        $intendedRoute = 'profile';
    }

    if ($request->user()->hasVerifiedEmail()) {
        return redirect()->route($intendedRoute);
    }

    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'A new verification link has been sent to your email address.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->whereNumber('id')
    ->name('verification.verify');
