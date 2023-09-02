@extends('layouts.layoutAuthorization')

@section('title')
    Аутентификация
@endsection

@section('Authorization')
    <body class="text-center">
    <main class="container w-25 min-width">
        <form action="{{ route('user.authentication') }}" method="POST">
            @csrf
            <img class="mb-4 rounded-circle" src="{{ asset('/images/doctorIcon.png') }}" alt="" width="100"
                 height="100">
            <h1 class="h3 mb-3 fw-normal text-secondary">Вход в аккаунт</h1>

            @if(session()->has('authenticationErrors'))
                <div class="alert alert-danger text-start">
                    <ul class="m-0">
                        @foreach(session('authenticationErrors') as $error)
                            <li>{!! $error !!}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-floating">
                <input type="email"
                       class="form-control @if(session()->has('inputErrors') && in_array('emailError', session('inputErrors'))) is-invalid @endif"
                       id="inputEmail" name="email" value="{{ $oldInput['email'] ?? '' }}" placeholder="Email">
                <label for="inputEmail" class="text-secondary">Email</label>
            </div>
            <div class="form-floating">
                <input type="password"
                       class="form-control @if(session()->has('inputErrors') && in_array('passwordError', session('inputErrors'))) is-invalid @endif"
                       id="inputPassword" name="password" value="{{ $oldInput['password'] ?? '' }}"
                       placeholder="Password">
                <label for="inputPassword" class="text-secondary">Пароль</label>
            </div>

            <div class="checkbox mb-3 mt-2">
                <label>
                    <input type="checkbox" name="rememberMe" value="remember-me"> Запомнить меня
                </label>
            </div>
            <button type="submit" class="w-100 btn btn-lg btn-primary" name="buttonLogin">Войти</button>
            <button type="submit" class="mt-2 w-100 btn btn-lg btn-primary" name="buttonRegistration">Регистрация
            </button>
            <p class="mt-5 mb-3 text-body-secondary">© 2022–2023</p>
        </form>
    </main>
    </body>
@endsection
