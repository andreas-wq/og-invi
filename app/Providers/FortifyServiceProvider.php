<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn () => view('auth.login')->render());

        Fortify::resetPasswordView(fn (Request $request) => view('auth.reset-password', [
            'title' => 'Reset password',
            'description' => 'Please enter your new password below',
            'token' => $request->route('token'),
            'email' => $request->email,
        ])->render());

        Fortify::requestPasswordResetLinkView(fn () => view('auth.forgot-password', [
            'title' => 'Forgot password',
            'description' => 'Enter your email to receive a password reset link',
        ])->render());

        Fortify::verifyEmailView(fn () => view('auth.verify-email', [
            'title' => 'Email verification',
            'description' => 'Please verify your email address by clicking on the link we just emailed to you.',
        ])->render());

        Fortify::registerView(fn () => view('auth.register', [
            'title' => 'Create an account',
            'description' => 'Enter your details below to create your account',
        ])->render());

        Fortify::twoFactorChallengeView(fn () => view('auth.two-factor-challenge', [
            'title' => 'Two-factor authentication',
            'description' => 'Enter the authentication code provided by your authenticator application.',
        ])->render());

        Fortify::confirmPasswordView(fn () => view('auth.confirm-password', [
            'title' => 'Confirm password',
            'description' => 'This is a secure area of the application. Please confirm your password before continuing.',
        ])->render());
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('passkeys', function (Request $request) {
            return Limit::perMinute(10)->by(
                ($request->input('credential.id') ?: $request->session()->getId()).'|'.$request->ip(),
            );
        });
    }
}
