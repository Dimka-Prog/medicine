<?php

use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\PatientCardController;
use Illuminate\Support\Facades\Route;

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

Route::name('user.')->group(function()
{
    Route::match(['get', 'post'],'/patient/card', [PatientCardController::class, 'correctionTreatment'])
        ->middleware('authCookie')
        ->name('patientCard');

    Route::match(['get', 'post'], '/authentication', [AuthorizationController::class, 'authentication'])->name('authentication');

    Route::match(['get', 'post'],'/registration', [AuthorizationController::class, 'registration'])->name('registration');
});

