<?php

use Illuminate\Support\Facades\Route;
use Irfansjah\Gtx\Facade\Gtx;

Route::group([
    'namespace' => 'Auth',
], function () {
    // Authentication Routes...
    Route::get('login', 'LoginController@showLoginForm')->name('login_page');
    Route::post('login', 'LoginController@login')->name('login');
    Route::post('logout', 'LoginController@logout')->name('logout');
});

Route::group([
    'middleware' => [
        'auth:admin',
    ],
], function () {
    Route::get('/', 'AdminController@index')->name('dashboard');
    Route::get('home', 'AdminController@index')->name('dashboard');
    Route::get('dashboard', 'AdminController@index')->name('dashboard');

    Gtx::RegisterRoute();


    Route::get('/permissions', '\Irfansjah\Gtx\Controllers\Admin\PermissionManagementController@index')->name('permissions');
    Route::get('/permissions/create', '\Irfansjah\Gtx\Controllers\Admin\PermissionManagementController@create')->name('permissions.create');
    Route::post('/permissions/store', '\Irfansjah\Gtx\Controllers\Admin\PermissionManagementController@store')->name('permissions.store');
    Route::get('/permissions/edit', '\Irfansjah\Gtx\Controllers\Admin\PermissionManagementController@edit')->name('permissions.edit');
    Route::post('/permissions/update', '\Irfansjah\Gtx\Controllers\Admin\PermissionManagementController@update')->name('permissions.update');
    Route::get('/permissions/delete', '\Irfansjah\Gtx\Controllers\Admin\PermissionManagementController@destroy')->name('permissions.destroy');
    Route::get('/roles',        '\Irfansjah\Gtx\Controllers\Admin\RoleManagementController@index')->name('roles');
    Route::get('/roles/delete', '\Irfansjah\Gtx\Controllers\Admin\RoleManagementController@destroy')->name('roles.destroy');
    Route::get('/roles/edit',   '\Irfansjah\Gtx\Controllers\Admin\RoleManagementController@edit')->name('roles.edit');
    Route::post('/roles/update','\Irfansjah\Gtx\Controllers\Admin\RoleManagementController@update')->name('roles.update');
    Route::get('/roles/create', '\Irfansjah\Gtx\Controllers\Admin\RoleManagementController@create')->name('roles.create');
    Route::post('/roles/store', '\Irfansjah\Gtx\Controllers\Admin\RoleManagementController@store')->name('roles.store');

    Route::get('/users',        '\Irfansjah\Gtx\Controllers\Admin\UserManagementController@index')->name('users');
    Route::get('/users/delete', '\Irfansjah\Gtx\Controllers\Admin\UserManagementController@destroy')->name('users.destroy');
    Route::get('/users/edit',   '\Irfansjah\Gtx\Controllers\Admin\UserManagementController@edit')->name('users.edit');
    Route::get('/users/show',   '\Irfansjah\Gtx\Controllers\Admin\UserManagementController@show')->name('users.show');
    Route::post('/users/updatepassword','\Irfansjah\Gtx\Controllers\Admin\UserManagementController@updatepassword')->name('users.updatepassword');
    Route::post('/users/uploadavatar','\Irfansjah\Gtx\Controllers\Admin\UserManagementController@setavatar')->name('users.updateavatar');
    Route::post('/users/update','\Irfansjah\Gtx\Controllers\Admin\UserManagementController@update')->name('users.update');
    Route::get('/users/create', '\Irfansjah\Gtx\Controllers\Admin\UserManagementController@create')->name('users.create');
    Route::post('/users/store', '\Irfansjah\Gtx\Controllers\Admin\UserManagementController@store')->name('users.store');


    Route::get('/members',        '\Irfansjah\Gtx\Controllers\Admin\MemberManagementController@index')->name('members');
    Route::get('/members/delete', '\Irfansjah\Gtx\Controllers\Admin\MemberManagementController@destroy')->name('members.destroy');
    Route::get('/members/edit',   '\Irfansjah\Gtx\Controllers\Admin\MemberManagementController@edit')->name('members.edit');
    Route::get('/members/show',   '\Irfansjah\Gtx\Controllers\Admin\MemberManagementController@show')->name('members.show');
    Route::post('/members/updatepassword','\Irfansjah\Gtx\Controllers\Admin\MemberManagementController@updatepassword')->name('members.updatepassword');
    Route::post('/members/uploadavatar','\Irfansjah\Gtx\Controllers\Admin\MemberManagementController@setavatar')->name('members.updateavatar');
    Route::post('/members/update','\Irfansjah\Gtx\Controllers\Admin\MemberManagementController@update')->name('members.update');
    Route::get('/members/create', '\Irfansjah\Gtx\Controllers\Admin\MemberManagementController@create')->name('members.create');
    Route::post('/members/store', '\Irfansjah\Gtx\Controllers\Admin\MemberManagementController@store')->name('members.store');



    Route::middleware(['permission:browse_country']) ->get('/countries',        '\Irfansjah\Gtx\Controllers\Admin\CountryController@index')   ->name('countries');
    Route::middleware(['permission:add_country'])    ->get('/countries/create',    '\Irfansjah\Gtx\Controllers\Admin\CountryController@create')  ->name('countries.create');
    Route::middleware(['permission:add_country'])   ->post('/countries/store',    '\Irfansjah\Gtx\Controllers\Admin\CountryController@store')   ->name('countries.store');
    Route::middleware(['permission:delete_country']) ->get('/countries/delete', '\Irfansjah\Gtx\Controllers\Admin\CountryController@destroy') ->name('countries.destroy');
    Route::middleware(['permission:edit_country'])   ->get('/countries/edit',    '\Irfansjah\Gtx\Controllers\Admin\CountryController@edit')    ->name('countries.edit');
    Route::middleware(['permission:edit_country'])  ->post('/countries/update',  '\Irfansjah\Gtx\Controllers\Admin\CountryController@update')  ->name('countries.update');

    Route::middleware(['permission:browse_province']) ->get('/provinces',        '\Irfansjah\Gtx\Controllers\Admin\ProvinceController@index')   ->name('provinces');
    Route::middleware(['permission:add_province'])    ->get('/provinces/create', '\Irfansjah\Gtx\Controllers\Admin\ProvinceController@create')  ->name('provinces.create');
    Route::middleware(['permission:add_province'])   ->post('/provinces/store',  '\Irfansjah\Gtx\Controllers\Admin\ProvinceController@store')   ->name('provinces.store');
    Route::middleware(['permission:delete_province']) ->get('/provinces/delete', '\Irfansjah\Gtx\Controllers\Admin\ProvinceController@destroy') ->name('provinces.destroy');
    Route::middleware(['permission:edit_province'])   ->get('/provinces/edit',   '\Irfansjah\Gtx\Controllers\Admin\ProvinceController@edit')    ->name('provinces.edit');
    Route::middleware(['permission:edit_province'])  ->post('/provinces/update', '\Irfansjah\Gtx\Controllers\Admin\ProvinceController@update')  ->name('provinces.update');


    Route::middleware(['permission:browse_city']) ->get('/cities',        '\Irfansjah\Gtx\Controllers\Admin\CityController@index')   ->name('cities');
    Route::middleware(['permission:add_city'])    ->get('/cities/create', '\Irfansjah\Gtx\Controllers\Admin\CityController@create')  ->name('cities.create');
    Route::middleware(['permission:add_city'])   ->post('/cities/store',  '\Irfansjah\Gtx\Controllers\Admin\CityController@store')   ->name('cities.store');
    Route::middleware(['permission:delete_city']) ->get('/cities/delete', '\Irfansjah\Gtx\Controllers\Admin\CityController@destroy') ->name('cities.destroy');
    Route::middleware(['permission:edit_city'])   ->get('/cities/edit',   '\Irfansjah\Gtx\Controllers\Admin\CityController@edit')    ->name('cities.edit');
    Route::middleware(['permission:edit_city'])  ->post('/cities/update', '\Irfansjah\Gtx\Controllers\Admin\CityController@update')  ->name('cities.update');


    Route::middleware(['permission:browse_type']) ->get('/types',        '\Irfansjah\Gtx\Controllers\Admin\EntityTypeController@index')   ->name('types');
    Route::middleware(['permission:add_type'])    ->get('/types/create', '\Irfansjah\Gtx\Controllers\Admin\EntityTypeController@create')  ->name('types.create');
    Route::middleware(['permission:add_type'])   ->post('/types/store',  '\Irfansjah\Gtx\Controllers\Admin\EntityTypeController@store')   ->name('types.store');
    Route::middleware(['permission:delete_type']) ->get('/types/delete', '\Irfansjah\Gtx\Controllers\Admin\EntityTypeController@destroy') ->name('types.destroy');
    Route::middleware(['permission:edit_type'])   ->get('/types/edit',   '\Irfansjah\Gtx\Controllers\Admin\EntityTypeController@edit')    ->name('types.edit');
    Route::middleware(['permission:edit_type'])  ->post('/types/update', '\Irfansjah\Gtx\Controllers\Admin\EntityTypeController@update')  ->name('types.update');


    Route::middleware(['permission:master_data'])   ->get('/categories',        '\Irfansjah\Gtx\Controllers\Admin\CategoryController@index')   ->name('categories');
    Route::middleware(['permission:master_data'])   ->get('/categories/create', '\Irfansjah\Gtx\Controllers\Admin\CategoryController@create')  ->name('categories.create');
    Route::middleware(['permission:master_data'])  ->post('/categories/store',  '\Irfansjah\Gtx\Controllers\Admin\CategoryController@store')   ->name('categories.store');
    Route::middleware(['permission:master_data'])   ->get('/categories/delete', '\Irfansjah\Gtx\Controllers\Admin\CategoryController@destroy') ->name('categories.destroy');
    Route::middleware(['permission:master_data'])   ->get('/categories/edit',   '\Irfansjah\Gtx\Controllers\Admin\CategoryController@edit')    ->name('categories.edit');
    Route::middleware(['permission:master_data'])  ->post('/categories/update', '\Irfansjah\Gtx\Controllers\Admin\CategoryController@update')  ->name('categories.update');

    Route::middleware(['permission:master_data'])   ->get('/multi-categories',        '\Irfansjah\Gtx\Controllers\Admin\MultiLevelCategoryController@index')   ->name('multi-categories');
    Route::middleware(['permission:master_data'])   ->get('/multi-categories/create', '\Irfansjah\Gtx\Controllers\Admin\MultiLevelCategoryController@create')  ->name('multi-categories.create');
    Route::middleware(['permission:master_data'])  ->post('/multi-categories/store',  '\Irfansjah\Gtx\Controllers\Admin\MultiLevelCategoryController@store')   ->name('multi-categories.store');
    Route::middleware(['permission:master_data'])   ->get('/multi-categories/delete', '\Irfansjah\Gtx\Controllers\Admin\MultiLevelCategoryController@destroy') ->name('multi-categories.destroy');
    Route::middleware(['permission:master_data'])   ->get('/multi-categories/edit',   '\Irfansjah\Gtx\Controllers\Admin\MultiLevelCategoryController@edit')    ->name('multi-categories.edit');
    Route::middleware(['permission:master_data'])  ->post('/multi-categories/update', '\Irfansjah\Gtx\Controllers\Admin\MultiLevelCategoryController@update')  ->name('multi-categories.update');

});
