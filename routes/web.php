<?php

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

Route::get('/email/verify', function (Request $request): RedirectResponse|View {
    if ($request->user()->hasVerifiedEmail()) {
        return redirect()->route('profile');
    }

    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request): RedirectResponse {
    if ($request->user()->hasVerifiedEmail()) {
        return redirect()->route('profile');
    }

    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'A new verification link has been sent to your email address.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->whereNumber('id')
    ->name('verification.verify');
