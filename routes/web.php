<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\PatientCardController;

/** @var Router $router */
use Illuminate\Routing\Router;

$router->group(['prefix' => '/', 'as' => 'patient'], static function (Router $router) {
    $router->get('', [PatientCardController::class, 'index']);
    $router->post('update/{id}', [PatientCardController::class, 'update'])->name('.update');
    $router->post('delete/{id}', [PatientCardController::class, 'destroy'])->name('.destroy');
})->middleware('authCookie');

$router->group(['prefix' => '/authentication', 'as' => 'auth'], static function (Router $router) {
    $router->get('', [AuthenticationController::class, 'index']);
    $router->post('login', [AuthenticationController::class, 'login'])->name('.login');
});

$router->group(['prefix' => '/registration', 'as' => 'registration'], static function (Router $router) {
    $router->get('', [RegistrationController::class, 'index']);
    $router->post('register', [RegistrationController::class, 'register'])->name('.register');
});

