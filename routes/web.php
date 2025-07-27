<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RequestController;
use App\Http\Controllers\Admin\ResellerController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SidebarController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::prefix('/admin')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::controller(AboutController::class)->prefix('/about')->group(function () {
        Route::get('/', 'show')->name('about_show');
        Route::get('/add', 'add')->name('about_add');
        Route::post('/store', 'store')->name('about_store');
        Route::get('/edit/{id}', 'edit')->name('about_edit');
        Route::post('/update/{id}', 'update')->name('about_update');
        Route::get('/forceDelete/{id}', 'forceDelete')->name('about_force_delete');
        Route::get('/delete/{id}', 'delete')->name('about_delete');
        Route::get('/restore/{id}', 'restore')->name('about_restore');
        Route::get('/action', 'handleAction')->name('about_action');
    });
    Route::controller(ResellerController::class)->prefix('/reseller')->group(function () {
        Route::get('/', 'show')->name('reseller_show');
        Route::get('/add', 'add')->name('reseller_add');
        Route::post('/store', 'store')->name('reseller_store');
        Route::get('/edit/{id}', 'edit')->name('reseller_edit');
        Route::post('/update/{id}', 'update')->name('reseller_update');
        Route::get('/forceDelete/{id}', 'forceDelete')->name('reseller_force_delete');
        Route::get('/delete/{id}', 'delete')->name('reseller_delete');
        Route::get('/restore/{id}', 'restore')->name('reseller_restore');
        Route::get('/action', 'handleAction')->name('reseller_action');
    });
    Route::controller(MenuController::class)->prefix('/menu')->group(function () {
        Route::get('/', 'show')->name('menu_show');
        Route::get('/add', 'add')->name('menu_add');
        Route::post('/store', 'store')->name('menu_store');
        Route::get('/edit/{id}', 'edit')->name('menu_edit');
        Route::post('/update/{id}', 'update')->name('menu_update');
        Route::get('/delete/{id}', 'delete')->name('menu_delete');
    });
    Route::controller(CategoriesController::class)->prefix('/categories')->group(function () {
        Route::get('/', 'show')->name('categories_show');
        Route::get('/add', 'add')->name('categories_add');
        Route::post('/store', 'store')->name('categories_store');
        Route::get('/edit/{id}', 'edit')->name('categories_edit');
        Route::post('/update/{id}', 'update')->name('categories_update');
        Route::get('/delete/{id}', 'delete')->name('categories_delete');
    });
    Route::controller(SidebarController::class)->prefix('/sidebar')->group(function () {
        Route::get('/', 'show')->name('sidebar_show');
        Route::get('/add', 'add')->name('sidebar_add');
        Route::post('/store', 'store')->name('sidebar_store');
        Route::get('/edit/{id}', 'edit')->name('sidebar_edit');
        Route::post('/update/{id}', 'update')->name('sidebar_update');
        Route::get('/delete/{id}', 'delete')->name('sidebar_delete');
    });
    Route::controller(ProductController::class)->prefix('/product')->group(function () {
        Route::get('/', 'show')->name('product_show');
        Route::get('/add', 'add')->name('product_add');
        Route::post('/store', 'store')->name('product_store');
        Route::get('/edit/{id}', 'edit')->name('product_edit');
        Route::post('/update/{id}', 'update')->name('product_update');
        Route::get('/delete/{id}', 'delete')->name('product_delete');
    });
    Route::controller(ContactController::class)->prefix('/contact')->group(function () {
        Route::get('/', 'list_contacts')->name('contact_list');
        Route::get('/show/{id}', 'show')->name('contact_show');
        Route::get('/delete/{id}', 'delete')->name('contact_delete');
    });
    Route::controller(RequestController::class)->prefix('/request')->group(function () {
        Route::get('/', 'show')->name('request_show');
        Route::get('/add', 'add')->name('request_add');
        Route::post('/store', 'store')->name('request_store');
        Route::get('/edit/{id}', 'edit')->name('request_edit');
        Route::post('/update/{id}', 'update')->name('request_update');
        Route::get('/delete/{id}', 'delete')->name('request_delete');
    });
    Route::controller(UserController::class)->prefix('/user')->group(function () {
        Route::get('/', 'show')->name('user_show');
        Route::get('/add', 'add')->name('user_add');
        Route::post('/store', 'store')->name('user_store');
        Route::get('/edit/{id}', 'edit')->name('user_edit');
        Route::post('/update/{id}', 'update')->name('user_update');
        Route::get('/delete/{id}', 'delete')->name('user_delete');
    });
    Route::controller(RoleController::class)->prefix('/role')->group(function () {
        Route::get('/', 'show')->name('role_show');
        Route::get('/add', 'add')->name('role_add');
        Route::post('/store', 'store')->name('role_store');
        Route::get('/edit/{id}', 'edit')->name('role_edit');
        Route::post('/update/{id}', 'update')->name('role_update');
        Route::get('/delete/{id}', 'delete')->name('role_delete');
    });
    Route::controller(PermissionController::class)->prefix('/permission')->group(function () {
        Route::get('/', 'show')->name('permission_show');
        Route::get('/add', 'add')->name('permission_add');
        Route::post('/store', 'store')->name('permission_store');
        Route::get('/edit/{id}', 'edit')->name('permission_edit');
        Route::post('/update/{id}', 'update')->name('permission_update');
        Route::get('/delete/{id}', 'delete')->name('permission_delete');
    });
});
