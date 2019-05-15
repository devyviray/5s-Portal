<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function(){
    // Departments
    Route::get('/departments', 'DepartmentController@index')->name('departments');
    Route::get('/departments-all', 'DepartmentController@indexData');
    Route::post('/department', 'DepartmentController@store');
    Route::patch('/department/{department}', 'DepartmentController@update');
    Route::delete('/department/{department}', 'DepartmentController@destroy');

    // locations
    Route::get('/locations', 'LocationController@index')->name('locations');
    Route::get('/locations-all', 'LocationController@indexData');
    Route::post('/location', 'LocationController@store');
    Route::patch('/location/{location}', 'LocationController@update');
    Route::delete('/location/{location}', 'LocationController@destroy');

    // companies
    Route::get('/companies', 'CompanyController@index')->name('companies');
    Route::get('/companies-all', 'CompanyController@indexData');
    Route::post('/company', 'CompanyController@store');
    Route::patch('/company/{company}', 'CompanyController@update');
    Route::delete('/company/{company}', 'CompanyController@destroy');
    Route::get('/company-location/{id}', 'CompanyController@companyLocation');

    // users
    Route::get('/users', 'UserController@index')->name('users');
    Route::get('/users-all', 'UserController@indexData');
    Route::post('/user', 'UserController@store');
    Route::patch('/user/{user}', 'UserController@update');
    Route::delete('/user/{user}', 'UserController@destroy');

    // roles
    Route::get('/roles', 'RoleController@index')->name('roles');
    Route::get('/roles-all', 'RoleController@indexData');
    Route::post('/role', 'RoleController@store');
    Route::patch('/role/{role}', 'RoleController@update');
    Route::delete('/role/{role}', 'RoleController@destroy');

    // checklist
    Route::get('/checklists', 'ChecklistController@index')->name('checklists');
    Route::get('/checklists-all', 'ChecklistController@indexData');
    Route::post('/checklist', 'ChecklistController@store');
    Route::patch('/checklist/{checklist}', 'ChecklistController@update');
    Route::delete('/checklist/{checklist}', 'ChecklistController@destroy');

    // faq
    Route::get('/faqs', 'FaqController@index')->name('faqs');
    Route::get('/faqs-all', 'FaqController@indexData');
    Route::post('/faq', 'FaqController@store');
    Route::patch('/faq/{faq}', 'FaqController@update');
    Route::delete('/faq/{faq}', 'FaqController@destroy');
    Route::get('/faqs-page', 'FaqController@indexPage');

    // checklist
    Route::get('/reports', 'ReportController@index')->name('reports');
    Route::get('/reports-all', 'ReportController@indexData');
    Route::post('/report', 'ReportController@store');
    Route::patch('/report/{report}', 'ReportController@update');
    Route::delete('/report/{report}', 'ReportController@destroy');
});
