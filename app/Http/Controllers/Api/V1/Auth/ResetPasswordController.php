<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use function event;
use function redirect;
use function response;

class ResetPasswordController extends Controller
{
    /**
     * Send forgot password link to user's email
     *
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function email(Request $request): Response|Application|ResponseFactory
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response(['status' => __($status)], 200)
            : response(['email' => __($status)], 400);
    }

    /**
     * Redirect to reset password route
     *
     * @param Request $request
     * @param string $token
     * @return Application|RedirectResponse|Redirector
     */
    public function reset(Request $request, string $token): Redirector|RedirectResponse|Application
    {
        return redirect(config('app.frontend_url') . "/?token={$token}&email={$request->email}");
    }

    /**
     * Reset user's password
     *
     * @param ResetPasswordRequest $request
     * @return Application|ResponseFactory|Response
     */
    public function update(ResetPasswordRequest $request): Response|Application|ResponseFactory
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response(['status' => __($status)], 200)
            : response(['email' => __($status)], 400);
    }
}
