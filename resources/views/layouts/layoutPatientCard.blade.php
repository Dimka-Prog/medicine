<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://getbootstrap.com/docs/5.2/examples/sign-in/signin.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/patientCard.css') }}">

    <script src="https://getbootstrap.com/docs/5.3/assets/js/color-modes.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
            integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
            integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <title>@yield('title')</title>
</head>
<body class="flex-column p-0">
<header class="d-flex p-3 mb-3 w-100 justify-content-end">
    <div class="text-end pt-1 me-lg-2" style="font-size: 18px;">
        {{ $fio }}<br>
        <strong>{{ $specialization }}</strong>
    </div>
    <div class="dropdown text-end order-lg-2">
        <a class="d-block link-body-emphasis text-decoration-none dropdown-toggle-split" data-bs-toggle="dropdown"
           aria-expanded="false">
            <img src="{{ asset('images/mainIcon.jpg') }}" alt="mdo" width="70" height="70"
                 class="rounded-circle shadow">
        </a>
        <form action="{{ route('user.patientCard') }}" method="POST">
            @csrf
            <ul class="dropdown-menu p-2" style="font-size: 15px;">
                <li>
                    <button class="dropdown-item rounded-2 hover-background-blue">Профиль</button>
                </li>
                <li>
                    <button class="dropdown-item rounded-2 hover-background-blue">Настройки</button>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <button type="submit"
                            class="dropdown-item rounded-2 active-background-red hover-background-red text-red"
                            name="buttonLogout">Выйти
                    </button>
                </li>
            </ul>
        </form>
    </div>
</header>
@yield('Main')
<script src="{{ asset('js/patientCard.js') }}"></script>
</body>
</html>
