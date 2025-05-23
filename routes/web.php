<?php

/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

// Redirecionamentos principais
//Route::redirect('/home', '/dashboard');
Route::redirect('/', 'forms')->name('home');

// Rotas de Formulário
Route::namespace('Form')->group(function () {
    Route::get('forms/{form}/view', 'FormController@viewForm')->name('forms.view');
    Route::post('forms/{form}/responses', 'ResponseController@store')->name('forms.responses.store');
});

// Rotas de Autenticação
Route::namespace('Auth')->group(function () {
    // Registro
    Route::get('register', 'RegisterController@showRegistrationForm')->name('register.show');
    Route::post('register', 'RegisterController@register')->name('register');

    // Login
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');

    // Logout
    Route::post('logout', 'LoginController@logout')->name('logout');

    // Verificação de E-mail
    Route::get('email/verify', 'VerificationController@show')
        ->middleware('auth')->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', 'VerificationController@verify')
        ->middleware(['auth', 'signed'])->name('verification.verify');

    // Reset de Senha
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'ResetPasswordController@reset')->name('password.update');
});

// Rotas de Dashboard
Route::get('profile', 'ProfileController@index')->name('profile.index');
Route::put('profile', 'ProfileController@update')->name('profile.update');

// Rotas protegidas por autenticação e verificação de e-mail
Route::middleware(['auth', 'verified'])->namespace('Form')->group(function () {
    // Rotas de Formulário
    Route::get('forms', 'FormController@index')->name('forms.index');
    Route::get('forms/create', 'FormController@create')->name('forms.create');
    Route::post('forms', 'FormController@store')->name('forms.store');
    Route::get('forms/{form}', 'FormController@show')->name('forms.show');
    Route::get('forms/{form}/edit', 'FormController@edit')->name('forms.edit');
    Route::put('forms/{form}', 'FormController@update')->name('forms.update');
    Route::delete('forms/{form}', 'FormController@destroy')->name('forms.destroy');

    Route::post('forms/{form}/draft', 'FormController@draftForm')->name('forms.draft');
    Route::get('forms/{form}/preview', 'FormController@previewForm')->name('forms.preview');
    Route::post('forms/{form}/open', 'FormController@openFormForResponse')->name('forms.open');
    Route::post('forms/{form}/close', 'FormController@closeFormToResponse')->name('forms.close');

    Route::post('forms/{form}/share-via-email', 'FormController@shareViaEmail')->name('form.share.email');
    Route::post('forms/{form}/form-availability', 'FormAvailabilityController@save')->name('form.availability.save');
    Route::delete('forms/{form}/form-availability/reset', 'FormAvailabilityController@reset')->name('form.availability.reset');

    // Rotas de Campos de Formulário
    Route::post('forms/{form}/fields/add', 'FieldController@store')->name('forms.fields.store');
    Route::post('forms/{form}/fields/delete', 'FieldController@destroy')->name('forms.fields.destroy');

    // Rotas de Respostas de Formulário
    Route::get('forms/{form}/responses', 'ResponseController@index')->name('forms.responses.index');
    Route::get('forms/{form}/responses/download', 'ResponseController@export')->name('forms.response.export');
    Route::delete('forms/{form}/responses', 'ResponseController@destroyAll')->name('forms.responses.destroy.all');
    Route::delete('forms/{form}/responses/{response}', 'ResponseController@destroy')->name('forms.responses.destroy.single');

    // Rotas de Colaboradores de Formulário
    Route::post('forms/{form}/collaborators', 'CollaboratorController@store')->name('form.collaborators.store');
    Route::delete('forms/{form}/collaborators/{collaborator}', 'CollaboratorController@destroy')->name('form.collaborator.destroy');
});
