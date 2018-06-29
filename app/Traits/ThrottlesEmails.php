<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

trait ThrottlesEmails
{
    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param User $user
     * @return bool
     */
    protected function hasTooManyEmailAttempts($user)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($user), $this->maxAttempts(), $this->decayMinutes()
        );
    }

    /**
     * Increment the login attempts for the user.
     *
     * @param  User $user
     * @return void
     */
    protected function incrementEmailAttempts($user)
    {
        $this->limiter()->hit(
            $this->throttleKey($user), $this->decayMinutes()
        );
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  User $user
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendEmailLockoutResponse($user)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($user)
        );

        throw ValidationException::withMessages([
            'email' => [Lang::get('auth.throttle_email', ['seconds' => $seconds])],
        ])->status(423);
    }

    /**
     * Clear the login locks for the given user credentials.
     *
     * @param User $user
     * @return void
     */
    protected function clearEmailAttempts($user)
    {
        $this->limiter()->clear($this->throttleKey($user));
    }

    /**
     * Get the throttle key for the given request.
     *
     * @param User $user
     * @return string
     */
    protected function throttleKey($user)
    {
        return Str::lower($user->name).'|'.$user->verification_token;
    }

    /**
     * Get the rate limiter instance.
     *
     * @return \Illuminate\Cache\RateLimiter
     */
    protected function limiter()
    {
        return app(RateLimiter::class);
    }

    /**
     * Get the maximum number of attempts to allow.
     *
     * @return int
     */
    public function maxAttempts()
    {
        return property_exists($this, 'maxAttempts') ? $this->maxAttempts : 5;
    }

    /**
     * Get the number of minutes to throttle for.
     *
     * @return int
     */
    public function decayMinutes()
    {
        return property_exists($this, 'decayMinutes') ? $this->decayMinutes : 5;
    }
}
