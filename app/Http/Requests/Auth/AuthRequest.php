<?php

namespace App\Http\Requests\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthRequest extends Request
{
    public function __construct()
    {
        Validator::make($this->request->all(), [
            'email' => 'required|min:4|max:60', // required - поле, обязательное для заполнения
            'password' => 'required'
        ], [
            'email.required' => 'Поле <strong>Email</strong> обязательно для заполнения',
            'email.min' => '<strong>Email</strong> должен содержать <strong>не менее 4 символов</strong>',
            'email.max' => '<strong>Email</strong> должнен быть <strong>не более 60 символов</strong>',
            'password.required' => 'Поле <strong>Пароль</strong> обязательно для заполнения'
        ]);

        parent::__construct();
    }

}
