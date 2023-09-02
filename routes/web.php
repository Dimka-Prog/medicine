<?php

/** @var Router $router */
use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

$router->group(['prefix' => '/'], static function (Router $router) {
    $router->get('', 'PatientCardController@index')->name('patient.card');
    $router->post('update/{id}', 'PatientCardController@update')->name('patient.update');
    $router->post('delete/{id}', 'PatientCardController@destroy')->name('patient.destroy');
})->middleware('authCookie');

$router->group(['prefix' => '/authentication'], static function (Router $router) {
    $router->get('', 'Auth\AuthenticationController@index')->name('auth');
    $router->post('login', 'Auth\AuthenticationController@login')->name('auth.login');
});

$router->group(['prefix' => '/registration'], static function (Router $router) {
    $router->get('', 'Auth\RegistrationController@index')->name('registration');
    $router->post('register', 'Auth\RegistrationController@register')->name('registration.register');
});

