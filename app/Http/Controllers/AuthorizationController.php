<?php

namespace App\Http\Controllers;

use App\Models\{UsersInfoModel, UsersModel};
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Cookie, Hash, Session, Validator};
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthorizationController extends Controller
{
    public function authentication(Request $request): View|RedirectResponse
    {
        if (Cookie::has('auth'))
            return redirect()->route('user.patientCard');

        if ($request->has('buttonRegistration'))
            return redirect()->route('user.registration');

        $modelUsers = new UsersModel();
        $users = $modelUsers->getTable();

        $authenticationErrors = [];

        if ($request->isMethod('POST')) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|min:4|max:60', // required - поле, обязательное для заполнения
                'password' => 'required'
            ], [
                'email.required' => 'Поле <strong>Email</strong> обязательно для заполнения',
                'email.min' => '<strong>Email</strong> должен содержать <strong>не менее 4 символов</strong>',
                'email.max' => '<strong>Email</strong> должнен быть <strong>не более 60 символов</strong>',
                'password.required' => 'Поле <strong>Пароль</strong> обязательно для заполнения'
            ]);

            if ($validator->fails()) { // если есть ошибки валидации
                foreach ($validator->errors()->all() as $error)
                    $authenticationErrors[] = $error;
            } else {
                $formEmail = (string)$request->input('email');
                $formPassword = (string)$request->input('password');

                $user = $users->where('Email', $formEmail)->first();

                if ($user) { // проверяет на существование пользователя по результату запроса на Email
                    if (Hash::check($formPassword, $user->Password)) {
                        $rememberToken = Str::random(60);
                        $cookieTime = 1440; // Установка времени жизни куки на сутки

                        if ($request->input('rememberMe'))
                            $cookieTime = 525600; // Установка времени жизни куки на 1 год

                        Cookie::queue('auth', $rememberToken, $cookieTime);
                        Cookie::queue('passport', $user->PassportNum, $cookieTime);

                        $modelUsers->getTable()->where('PassportNum', $user->PassportNum)->update(['remember_token' => $rememberToken]);

                        return redirect()->intended(route('user.patientCard'));
                    } else
                        $authenticationErrors[] = "Введен неверный пароль. Пожалуйста, попробуйте снова.";
                } else
                    $authenticationErrors[] = "Email <strong>$formEmail</strong> еще не зарегистрирован";
            }
        }

        Session::forget('authenticationErrors'); // очищает сессию
        Session::forget('inputErrors');

        if (!empty($authenticationErrors)) { // есть ли сообщения об ошибках
            Session::flash('authenticationErrors', $authenticationErrors);

            $inputErrors = [];
            foreach ($authenticationErrors as $error) {
                if (str_contains($error, 'Email'))
                    $inputErrors[] = 'emailError';
                else
                    $inputErrors[] = 'passwordError';
            }
            Session::flash('inputErrors', $inputErrors);
        }

        return view('authentication')->with('oldInput', $request->all());
    }


    public function registration(Request $request): View|RedirectResponse
    {
        if (Cookie::has('auth'))
            return redirect()->route('user.patientCard');

        $usersInfo = new UsersInfoModel();
        $users = new UsersModel();

        $authenticationErrors = [];
        $personalDataErrors = [];

        if ($request->isMethod('POST')) {
            $validator = Validator::make($request->all(), [
                'FIO' => 'required|max:100',
                'passport' => 'required|regex:/^\d{6}$/', // проверяет ввод на фицры длинною 6 символов
                'diploma' => 'required|regex:/^\d{7}$/',
                'organization' => 'nullable|max:70',
                'specialization' => 'required|max:40',
                'email' => 'required|min:4|max:255',
                'password' => 'required|min:4'
            ], [
                'FIO.required' => 'Поле <strong>ФИО</strong> обязательно для заполнения',
                'FIO.max' => '<strong>ФИО</strong> должно быть <strong>не более 100 символов</strong>',
                'passport.required' => 'Поле <strong>Паспорт</strong> обязательно для заполнения',
                'passport.regex' => '<strong>Номер паспорта</strong> должен содержать <strong>6 цифр</strong>',
                'diploma.required' => 'Поле <strong>Диплом</strong> обязательно для заполнения',
                'diploma.regex' => '<strong>Номер диплома</strong> должен содержать <strong>7 цифр</strong>',
                'organization.max' => '<strong>Название организации</strong> должно быть <strong>не более 70 символов</strong>',
                'specialization.required' => '<strong>Название специализации</strong> обзательно для заполнения',
                'specialization.max' => '<strong>Название специализации</strong> должно быть <strong>не более 40 символов</strong>',
                'email.required' => 'Поле <strong>Email</strong> обязательно для заполнения',
                'email.min' => '<strong>Email</strong> должен содержать <strong>не менее 4 символов</strong>',
                'email.max' => '<strong>Email</strong> должнен быть <strong>не более 255 символов</strong>',
                'password.required' => 'Поле <strong>Пароль</strong> обязательно для заполнения',
                'password.min' => '<strong>Пароль</strong> должен содержать <strong>не менее 4 символов</strong>'
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    if (str_contains($error, 'Email') || str_contains($error, 'Пароль'))
                        $authenticationErrors[] = $error;
                    else
                        $personalDataErrors[] = $error;
                }
            } else {
                $formFIO = $request->input('FIO');
                $formPassport = $request->input('passport');
                $formDiploma = $request->input('diploma');
                $formOrganization = $request->input('organization');
                $formSpecialization = $request->input('specialization');
                $formEmail = $request->input('email');
                $formPassword = $request->input('password');

                $userInfoPassport = $usersInfo->getTable()->where('PassportNum', (int)$formPassport)->first();
                $userInfoDiploma = $usersInfo->getTable()->where('DiplomaNum', (int)$formDiploma)->first();
                $user = $users->getTable()->where('Email', (string)$formEmail)->first();

                if ($userInfoPassport)
                    $personalDataErrors[] = "Пользователь с номером паспорта <strong>$formPassport</strong> уже зарегистрирован";
                elseif ($userInfoDiploma)
                    $personalDataErrors[] = "Пользователь с номером диплома <strong>$formDiploma</strong> уже зарегистрирован";
                else {
                    if ($user)
                        $authenticationErrors[] = "Email <strong>$formEmail</strong> уже зарегистрирован";
                    else {
                        $usersInfo->createUsersInfo($formFIO, $formPassport, $formDiploma, $formOrganization, $formSpecialization);
                        $users->createUsers($formPassport, $formEmail, $formPassword);

                        return redirect()->route('user.authentication');
                    }
                }
            }
        }

        $inputErrors = [];

        Session::forget('authenticationErrors');
        Session::forget('personalDataErrors');
        Session::forget('inputErrors');

        Session::put('registered', true);

        if (!empty($authenticationErrors)) {
            Session::flash('authenticationErrors', $authenticationErrors);

            foreach ($authenticationErrors as $error) {
                if (str_contains($error, 'Email'))
                    $inputErrors[] = 'emailError';
                else
                    $inputErrors[] = 'passwordError';
            }
        }

        if (!empty($personalDataErrors)) {
            Session::flash('personalDataErrors', $personalDataErrors);

            foreach ($personalDataErrors as $error) {
                if (str_contains($error, 'ФИО'))
                    $inputErrors[] = 'fioError';
                elseif (str_contains($error, 'Паспорт') || str_contains($error, 'паспорта'))
                    $inputErrors[] = 'passportError';
                elseif (str_contains($error, 'Диплом') || str_contains($error, 'диплома'))
                    $inputErrors[] = 'diplomaError';
                elseif (str_contains($error, 'организации'))
                    $inputErrors[] = 'organizationError';
                else
                    $inputErrors[] = 'specializationError';
            }
        }

        if (!empty($inputErrors))
            Session::flash('inputErrors', $inputErrors);

        return view('registration')->with('oldInput', $request->all());
    }
}
