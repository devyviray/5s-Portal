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
    // faq
    Route::get('/faqs-page', 'FaqController@indexPage');

    // Reports
    Route::get('/reports', 'ReportController@index')->name('reports');
    Route::get('/reports-all', 'ReportController@indexData');
    Route::post('/report-approve', 'ReportController@approveReportPerUser');
    Route::post('/report-checking', 'ReportController@checkingReportPerUser');
    Route::get('/view-report/{reportId}', 'ReportController@show');
    Route::get('/reports-per-user/{reportId}', 'ReportController@getReportsPerUser');
});

// Accessible routes for admin , inspector and IT
Route::group(['middleware' => ['auth', 'role:it|administrator|inspector|department member']], function () {

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
     Route::get('/user-process-owner/{companyId}/{locationId}', 'UserController@getProcessOwnerPerCompany');

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
    Route::delete('/checklist/{batchId}', 'ChecklistController@destroy');

    // faq
    Route::get('/faqs', 'FaqController@index')->name('faqs');
    Route::get('/faqs-all', 'FaqController@indexData');
    Route::post('/faq', 'FaqController@store');
    Route::patch('/faq/{faq}', 'FaqController@update');
    Route::delete('/faq/{faq}', 'FaqController@destroy');

    //Report
    Route::get('/create-report', 'ReportController@create');
    Route::post('/report-filtered', 'ReportController@getFilteredReports');
    Route::get('/validate-report/{reportId}', 'ReportController@validateIndex');
    Route::post('/report-validate', 'ReportController@validateReportPerUser');
    Route::post('/report', 'ReportController@store');
    Route::patch('/report/{report}', 'ReportController@update');
    Route::delete('/report/{report}', 'ReportController@destroy');
    Route::get('/create-report', 'ReportController@create');
    Route::get('/trend-and-analysis', 'ReportController@trendIndex');
    Route::get('/trend-and-analysis-data', 'ReportController@trendAndAnalysisData');
    Route::get('/generate-pdf', 'ReportController@generatePDF');

    // operation line
    Route::get('/operation-lines', 'OperationLineController@index')->name('operation-lines');
    Route::get('/operation-lines-all', 'OperationLineController@indexData');
    Route::post('/operation-line', 'OperationLineController@store');
    Route::patch('/operation-line/{operationLine}', 'OperationLineController@update');
    Route::delete('/operation-line/{operationLine}', 'OperationLineController@destroy');

    // categories
    Route::get('/categories', 'CategoryController@index')->name('categories');
    Route::get('/categories-all', 'CategoryController@indexData');
    Route::post('/category', 'CategoryController@store');
    Route::patch('/category/{category}', 'CategoryController@update');
    Route::delete('/category/{category}', 'CategoryController@destroy');
 
    // areas
    Route::get('/areas', 'AreaController@index')->name('categories');
    Route::get('/areas-all', 'AreaController@indexData');
    Route::post('/area', 'AreaController@store');
    Route::patch('/area/{area}', 'AreaController@update');
    Route::delete('/area/{area}', 'AreaController@destroy');

    // company categories
    Route::get('/company-areas', 'CompanyCategoryController@index')->name('company-categories');
    Route::get('/company-areas-all', 'CompanyCategoryController@indexData');
    Route::post('/company-area', 'CompanyCategoryController@store');
    Route::patch('/company-area/{companyCategory}', 'CompanyCategoryController@update');
    Route::delete('/company-area/{companyCategory}', 'CompanyCategoryController@destroy');
    Route::get('/company-areas-per-company/{companyId}/{locationId}/{categoryId}/{operationLineId}', 'CompanyCategoryController@companyAreaPerCompany');

});
