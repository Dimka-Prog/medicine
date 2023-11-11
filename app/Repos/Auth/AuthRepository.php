<?php

namespace App\Repos\Auth;

use App\Models\UsersModel;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    private UsersModel $model;

    public function __construct()
    {
        $this->model = new UsersModel();
    }

    public function create(int $passport, string $email, string $password): void
    {
        $this->model->PassportNum = $passport;
        $this->model->Email = $email;
        $this->model->Password = Hash::make($password);

        $this->model->save();
    }
}
