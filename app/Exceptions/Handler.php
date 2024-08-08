<?php
public function render($request, Throwable $exception)
{
    if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
        $user = Auth::user();
        if ($user && $user->status == 0) {
            Auth::logout();
            return redirect('/login')->withErrors(['email' => 'Sorry your account is suspended contact the administrator']);
        }
    }

    return parent::render($request, $exception);
}