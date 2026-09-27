<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileAccessTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_profile_renders_the_access_form(): void
    {
        $this->get('/profile')
            ->assertSeeText('Sign in')
            ->assertSeeText('Create account');

        Livewire::test('auth.profile-access')
            ->call('showRegistration')
            ->assertSee('Use at least 8 characters, including letters, numbers, and symbols.');
    }

    public function test_verified_user_can_view_the_profile_placeholder(): void
    {
        $user = User::factory()->create(['name' => 'Moss Lantern']);

        $this->actingAs($user)
            ->get('/profile')
            ->assertSeeText('Welcome back, Moss Lantern.');
    }

    public function test_unverified_user_is_redirected_to_the_verification_notice(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->get('/profile')
            ->assertRedirectToRoute('verification.notice');
    }

    public function test_registration_creates_an_unverified_user_and_sends_a_verification_notification(): void
    {
        Notification::fake([VerifyEmail::class]);

        Livewire::test('auth.profile-access')
            ->call('showRegistration')
            ->set('registrationName', 'Moss Lantern')
            ->set('registrationEmail', 'moss@example.test')
            ->set('registrationPassword', 'Mossy!123')
            ->set('registrationPasswordConfirmation', 'Mossy!123')
            ->call('register')
            ->assertRedirect(route('verification.notice'));

        $user = User::query()->where('email', 'moss@example.test')->firstOrFail();

        $this->assertModelExists($user);
        $this->assertNull($user->email_verified_at);
        $this->assertAuthenticatedAs($user);
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_registration_rejects_invalid_email_weak_password_and_mismatched_confirmation(): void
    {
        Livewire::test('auth.profile-access')
            ->call('showRegistration')
            ->set('registrationName', 'Moss Lantern')
            ->set('registrationEmail', 'not-an-email')
            ->set('registrationPassword', 'short')
            ->set('registrationPasswordConfirmation', 'different')
            ->call('register')
            ->assertHasErrors(['registrationEmail', 'registrationPassword']);

        $this->assertDatabaseMissing('users', ['email' => 'not-an-email']);
    }

    public function test_registration_rejects_an_existing_email_address(): void
    {
        User::factory()->create(['email' => 'moss@example.test']);

        Livewire::test('auth.profile-access')
            ->call('showRegistration')
            ->set('registrationName', 'Another Moss')
            ->set('registrationEmail', 'moss@example.test')
            ->set('registrationPassword', 'Mossy!123')
            ->set('registrationPasswordConfirmation', 'Mossy!123')
            ->call('register')
            ->assertHasErrors(['registrationEmail']);
    }

    public function test_valid_credentials_authenticate_the_user_and_redirect_to_profile(): void
    {
        $user = User::factory()->create(['email' => 'moss@example.test']);

        Livewire::test('auth.profile-access')
            ->set('loginEmail', 'moss@example.test')
            ->set('loginPassword', 'password')
            ->call('login')
            ->assertRedirect(route('profile'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_keep_the_user_on_the_access_form(): void
    {
        User::factory()->create(['email' => 'moss@example.test']);

        Livewire::test('auth.profile-access')
            ->set('loginEmail', 'moss@example.test')
            ->set('loginPassword', 'not-the-password')
            ->call('login')
            ->assertHasErrors(['loginEmail']);

        $this->assertGuest();
    }

    public function test_login_is_rate_limited_after_five_failed_attempts(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            Livewire::test('auth.profile-access')
                ->set('loginEmail', 'moss@example.test')
                ->set('loginPassword', 'not-the-password')
                ->call('login')
                ->assertHasErrors(['loginEmail']);
        }

        Livewire::test('auth.profile-access')
            ->set('loginEmail', 'moss@example.test')
            ->set('loginPassword', 'not-the-password')
            ->call('login')
            ->assertHasErrors(['loginEmail'])
            ->assertSee('Too many attempts. Please try again in');
    }

    public function test_verification_link_verifies_and_authenticates_the_user(): void
    {
        $user = User::factory()->unverified()->create(['name' => 'Moss Lantern']);
        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(30),
            ['id' => $user->id, 'hash' => sha1($user->email)],
        );

        $this->get($url)
            ->assertSeeText('Welcome, Moss Lantern.')
            ->assertSeeText('Go to Profile');

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $this->assertAuthenticatedAs($user);
    }

    public function test_mismatched_signed_verification_link_is_forbidden(): void
    {
        $user = User::factory()->unverified()->create();
        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(30),
            ['id' => $user->id, 'hash' => 'invalid'],
        );

        $this->get($url)->assertForbidden();
    }

    public function test_expired_verification_link_is_forbidden(): void
    {
        $user = User::factory()->unverified()->create();
        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->subMinute(),
            ['id' => $user->id, 'hash' => sha1($user->email)],
        );

        $this->get($url)->assertForbidden();
    }

    public function test_verification_notice_can_resend_the_notification(): void
    {
        Notification::fake([VerifyEmail::class]);
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->post(route('verification.send'))
            ->assertRedirect()
            ->assertSessionHas('status', 'A new verification link has been sent to your email address.');

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_confirmation_page_escapes_the_display_name(): void
    {
        $user = User::factory()->unverified()->create(['name' => '<script>alert(1)</script>']);
        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(30),
            ['id' => $user->id, 'hash' => sha1($user->email)],
        );

        $this->get($url)->assertDontSee('<script>alert(1)</script>', false);
    }
}
