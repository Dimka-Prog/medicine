@extends('layouts.layoutAuthorization')

@section('title')
    Регистрация
@endsection

@section('Authorization')
    <body class="flex-column p-0">
    <form action="{{ route('user.authentication') }}" method="GET" class="w-100 mb-4">
        <button type="submit" class="d-inline-flex align-items-center btn text-primary btn-lg px-4 pt-3 border-0"
                id="buttonBackAuthentication">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                 class="bi bi-arrow-bar-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                      d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5ZM10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5Z"/>
            </svg>
            Войти
        </button>
    </form>
    <main class="text-center container w-25 min-width">
        <form action="{{ route('user.registration') }}" method="POST">
            @csrf
            <img class="mb-4 rounded-circle" src="{{ asset('/images/regDoctorIcon.png') }}" alt="" width="100"
                 height="100">

            <h1 class="h4 mb-3 fw-normal text-dark">Персональные данные</h1>

            @if(session()->has('personalDataErrors'))
                <div class="alert alert-danger text-start">
                    <ul class="m-0">
                        @foreach(session('personalDataErrors') as $error)
                            <li>{!! $error !!}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-floating">
                <input type="text"
                       class="form-control @if(session()->has('inputErrors') && in_array('fioError', session('inputErrors'))) is-invalid @endif"
                       id="inputFIO" name="FIO" value="{{ $oldInput['FIO'] ?? '' }}" placeholder="FIO">
                <label for="inputFIO" class="text-secondary">ФИО</label>
            </div>
            <div class="form-floating">
                <input type="text"
                       class="form-control @if(session()->has('inputErrors') && in_array('passportError', session('inputErrors'))) is-invalid @endif"
                       id="inputPassport" oninput="onlyNumbers('inputPassport')" name="passport"
                       value="{{ $oldInput['passport'] ?? '' }}" placeholder="Passport">
                <label for="inputPassport" class="text-secondary">Паспорт (номер)</label>
            </div>
            <div class="form-floating">
                <input type="text"
                       class="form-control @if(session()->has('inputErrors') && in_array('diplomaError', session('inputErrors'))) is-invalid @endif"
                       id="inputDiploma" oninput="onlyNumbers('inputDiploma')" name="diploma"
                       value="{{ $oldInput['diploma'] ?? '' }}" placeholder="Diploma">
                <label for="inputDiploma" class="text-secondary">Диплом (номер)</label>
            </div>
            <div class="form-floating">
                <input type="text"
                       class="form-control @if(session()->has('inputErrors') && in_array('organizationError', session('inputErrors'))) is-invalid @endif"
                       id="inputOrganization" name="organization" value="{{ $oldInput['organization'] ?? '' }}"
                       placeholder="Organization">
                <label for="inputOrganization" class="text-secondary">Название организации</label>
            </div>
            <div class="form-floating">
                <input type="text"
                       class="form-control @if(session()->has('inputErrors') && in_array('specializationError', session('inputErrors'))) is-invalid @endif"
                       id="inputSpecialization" name="specialization" value="{{ $oldInput['specialization'] ?? '' }}"
                       placeholder="Specialization">
                <label for="inputSpecialization" class="text-secondary">Специализация</label>
            </div>

            <h1 class="h4 mb-3 mt-4 fw-normal text-dark">Данные аутентификации</h1>

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
                       id="inputEmail"
                       name="email" value="{{ $oldInput['email'] ?? '' }}" placeholder="Email">
                <label for="inputEmail" class="text-secondary">Email</label>
            </div>
            <div class="form-floating">
                <input type="password"
                       class="form-control @if(session()->has('inputErrors') && in_array('passwordError', session('inputErrors'))) is-invalid @endif"
                       id="inputPassword"
                       name="password" value="{{ $oldInput['password'] ?? '' }}" placeholder="Password">
                <label for="inputPassword" class="text-secondary">Пароль</label>
            </div>

            <button type="submit" class="mt-4 w-100 btn btn-lg btn-primary">Регистрация</button>
            <p class="mt-5 mb-3 text-body-secondary">© 2022–2023</p>
        </form>
    </main>
    </body>
@endsection
