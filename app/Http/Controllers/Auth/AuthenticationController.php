<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Repos\Auth\AuthRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{Cookie, Session};
use Illuminate\View\View;

class AuthenticationController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (Cookie::has('auth'))
            return redirect('patient');

        Session::forget('authenticationErrors'); // очищает сессию
        Session::forget('inputErrors');

        return view('authentication');
    }

    public function login(AuthRequest $request): RedirectResponse
    {
        $authRepository = $this->application->make(AuthRepository::class);

        return redirect('patient');
    }
}
