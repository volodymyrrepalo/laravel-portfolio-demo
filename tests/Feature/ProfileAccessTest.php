<?php

namespace Tests\Feature;

use App\Models\MapPin;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
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

    public function test_verified_user_can_view_their_account_dashboard_and_private_activity(): void
    {
        $user = User::factory()->create(['name' => 'Moss Lantern']);
        $mapPin = MapPin::factory()->for($user)->create();
        $user->favoriteMapPins()->attach($mapPin);

        $this->actingAs($user)
            ->get('/profile')
            ->assertSeeText('Your profile')
            ->assertSeeText('Your details')
            ->assertSeeText('Change password')
            ->assertSeeText('Saved pins')
            ->assertSeeText('Favorites')
            ->assertSeeText('Delete account')
            ->assertSee('>1<', false);
    }

    public function test_user_can_update_their_display_name(): void
    {
        $user = User::factory()->create(['name' => 'Old name']);

        Livewire::actingAs($user)
            ->test('profile.account-dashboard')
            ->set('name', 'Moss Lantern')
            ->call('updateAccount')
            ->assertSet('statusMessage', 'Account details saved.');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Moss Lantern',
        ]);
    }

    public function test_user_can_change_their_email_after_confirming_their_password(): void
    {
        Notification::fake([VerifyEmail::class]);
        $user = User::factory()->create(['email' => 'old@example.test']);

        Livewire::actingAs($user)
            ->test('profile.account-dashboard')
            ->set('email', 'new@example.test')
            ->set('currentPasswordForEmail', 'password')
            ->call('updateAccount')
            ->assertRedirect(route('verification.notice'));

        $user->refresh();

        $this->assertSame('new@example.test', $user->email);
        $this->assertNull($user->email_verified_at);
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_email_change_requires_a_unique_email_and_current_password(): void
    {
        $user = User::factory()->create(['email' => 'moss@example.test']);
        User::factory()->create(['email' => 'already-used@example.test']);

        Livewire::actingAs($user)
            ->test('profile.account-dashboard')
            ->set('email', 'already-used@example.test')
            ->set('currentPasswordForEmail', 'incorrect-password')
            ->call('updateAccount')
            ->assertHasErrors(['email', 'currentPasswordForEmail']);

        $this->assertSame('moss@example.test', $user->fresh()->email);
    }

    public function test_user_can_change_their_password_and_remain_authenticated(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('profile.account-dashboard')
            ->set('currentPassword', 'password')
            ->set('newPassword', 'Mossy!123')
            ->set('newPasswordConfirmation', 'Mossy!123')
            ->call('updatePassword')
            ->assertSet('statusMessage', 'Password updated. Other sessions have been signed out.');

        $this->assertTrue(Hash::check('Mossy!123', $user->fresh()->password));
        $this->assertAuthenticatedAs($user);
    }

    public function test_password_change_rejects_an_invalid_current_or_weak_new_password(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('profile.account-dashboard')
            ->set('currentPassword', 'incorrect-password')
            ->set('newPassword', 'short')
            ->set('newPasswordConfirmation', 'different')
            ->call('updatePassword')
            ->assertHasErrors(['currentPassword', 'newPassword']);
    }

    public function test_user_can_log_out_from_their_profile(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('profile.account-dashboard')
            ->call('logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }

    public function test_user_can_permanently_delete_their_account_and_private_records(): void
    {
        $user = User::factory()->create();
        $mapPin = MapPin::factory()->for($user)->create();
        $user->favoriteMapPins()->attach($mapPin);

        Livewire::actingAs($user)
            ->test('profile.account-dashboard')
            ->set('currentPasswordForDeletion', 'password')
            ->call('deleteAccount')
            ->assertRedirect('/');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('map_pins', ['id' => $mapPin->id]);
        $this->assertDatabaseCount('map_pin_favorites', 0);
        $this->assertGuest();
    }

    public function test_account_deletion_requires_the_current_password(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('profile.account-dashboard')
            ->set('currentPasswordForDeletion', 'incorrect-password')
            ->call('deleteAccount')
            ->assertHasErrors(['currentPasswordForDeletion']);

        $this->assertModelExists($user);
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
