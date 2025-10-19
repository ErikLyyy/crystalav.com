<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RequestController;
use App\Http\Controllers\Admin\ResellerController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SidebarController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/category/{menu_slug}', [HomeController::class, 'category'])->name('category');
Route::get('/category/{menu_slug}/{category_slug}', [HomeController::class, 'list_product'])->name('list_product');
Route::get('/category/{menu_slug}/{category_slug}/{product_slug}', [HomeController::class, 'detail_product'])->name('detail_product');
Route::get('/rental_request', [HomeController::class, 'list_product'])->name('list_product');
Route::get('/add_cart_ajax', [HomeController::class, 'add_cart_ajax'])->name('add_cart_ajax');
Route::get('/search/suggestions', [HomeController::class, 'suggestions'])->name('search.suggestions');
Route::get('/quote', [HomeController::class, 'quote'])->name('quote');
Route::get('/request', [HomeController::class, 'request'])->name('request');
Route::post('/request/sendRequestMail', [HomeController::class, 'sendRequestMail'])->name('send_request_mail');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact/store', [HomeController::class, 'store'])->name('contact.store');

Route::prefix('/admin')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::controller(AboutController::class)->prefix('/about')->group(function () {
        Route::get('/', 'show')->name('about-us.show');
        Route::get('/add', 'add')->name('about-us.add');
        Route::post('/store', 'store')->name('about-us.store');
        Route::get('/edit/{id}', 'edit')->name('about-us.edit');
        Route::post('/update/{id}', 'update')->name('about-us.update');
        Route::get('/forceDelete/{id}', 'forceDelete')->name('about-us.forceDelete');
        Route::get('/delete/{id}', 'delete')->name('about-us.delete');
        Route::get('/restore/{id}', 'restore')->name('about-us.restore');
        Route::get('/action', 'handleAction')->name('about-us.action');
    });
    Route::controller(ResellerController::class)->prefix('/reseller')->group(function () {
        Route::get('/', 'show')->name('reseller.show');
        Route::get('/add', 'add')->name('reseller.add');
        Route::post('/store', 'store')->name('reseller.store');
        Route::get('/edit/{id}', 'edit')->name('reseller.edit');
        Route::post('/update/{id}', 'update')->name('reseller.update');
        Route::get('/forceDelete/{id}', 'forceDelete')->name('reseller.forceDelete');
        Route::get('/delete/{id}', 'delete')->name('reseller.delete');
        Route::get('/restore/{id}', 'restore')->name('reseller.restore');
        Route::get('/action', 'handleAction')->name('reseller.action');
    });
    Route::controller(MenuController::class)->prefix('/menu')->group(function () {
        Route::get('/', 'show')->name('menu.show');
        Route::get('/add', 'add')->name('menu.add');
        Route::post('/store', 'store')->name('menu.store');
        Route::get('/edit/{id}', 'edit')->name('menu.edit');
        Route::post('/update/{id}', 'update')->name('menu.update');
        Route::get('/forceDelete/{id}', 'forceDelete')->name('menu.forceDelete');
        Route::get('/delete/{id}', 'delete')->name('menu.delete');
        Route::get('/restore/{id}', 'restore')->name('menu.restore');
        Route::get('/action', 'handleAction')->name('menu.action');
    });
    Route::controller(CategoriesController::class)->prefix('/categories')->group(function () {
        Route::get('/', 'show')->name('category.show');
        Route::get('/add', 'add')->name('category.add');
        Route::post('/store', 'store')->name('category.store');
        Route::get('/edit/{id}', 'edit')->name('category.edit');
        Route::post('/update/{id}', 'update')->name('category.update');
        Route::get('/forceDelete/{id}', 'forceDelete')->name('category.forceDelete');
        Route::get('/delete/{id}', 'delete')->name('category.delete');
        Route::get('/restore/{id}', 'restore')->name('category.restore');
        Route::get('/action', 'handleAction')->name('category.action');
    });
    Route::controller(SidebarController::class)->prefix('/sidebar')->group(function () {
        Route::get('/show/{type}', 'show')->name('sidebar.show');
        Route::get('/add/{type}', 'add')->name('sidebar.add');
        Route::post('/store/{type}', 'store')->name('sidebar.store');
        Route::get('/edit/{id}', 'edit')->name('sidebar.edit');
        Route::post('/update/{type}/{id}', 'update')->name('sidebar.update');
        Route::get('/forceDelete/{id}', 'forceDelete')->name('sidebar.forceDelete');
        Route::get('/delete/{id}', 'delete')->name('sidebar.delete');
        Route::get('/restore/{id}', 'restore')->name('sidebar.restore');
        Route::get('/action', 'handleAction')->name('sidebar.action');
        Route::get('/ajax', 'ajax')->name('sidebar.ajax');
    });
    Route::controller(ProductController::class)->prefix('/product')->group(function () {
        Route::get('/', 'show')->name('product.show');
        Route::get('/add', 'add')->name('product.add');
        Route::post('/store', 'store')->name('product.store');
        Route::get('/edit/{id}', 'edit')->name('product.edit');
        Route::post('/update/{id}', 'update')->name('product.update');
        Route::get('/delete/{id}', 'delete')->name('product.delete');
        Route::get('/forceDelete/{id}', 'forceDelete')->name('product.forceDelete');
        Route::get('/restore/{id}', 'restore')->name('product.restore');
        Route::get('/action', 'handleAction')->name('product.action');
        Route::post('/deleteMedia', 'deleteMedia');
        Route::post('/uploadMedia', 'uploadMedia');
        Route::get('/ajax', 'ajax');
        Route::get('/keysearch', 'keysearchShow')->name('keysearch.show');
        Route::get('/keysearch/delete/{id}', 'keysearchDelete')->name('keysearch.delete');
        Route::get('/keysearch/action/', 'keysearch.action');
    });
    Route::controller(ContactController::class)->prefix('/contact')->group(function () {
        Route::get('/', 'show')->name('contact.show');
        Route::get('/read/{id}', 'read')->name('contact.read');
        Route::get('/delete/{id}', 'delete')->name('contact.delete');
        Route::get('/action', 'handleAction')->name('contact.action');
        Route::get('/forceDelete/{id}', 'forceDelete')->name('contact.forceDelete');
        Route::get('/restore/{id}', 'restore')->name('contact.restore');
    });
    Route::controller(RequestController::class)->prefix('/request')->group(function () {
        Route::get('/', 'show')->name('request.show');
        Route::get('/read/{id}', 'read')->name('request.read');
        Route::get('/delete/{id}', 'delete')->name('request.delete');
        Route::get('/action', 'handleAction')->name('request.action');
        Route::get('/forceDelete/{id}', 'forceDelete')->name('request.forceDelete');
        Route::get('/restore/{id}', 'restore')->name('request.restore');
    });
    Route::controller(UserController::class)->prefix('/user')->group(function () {
        Route::get('/', 'show')->name('user.show');
        Route::get('/add', 'add')->name('user.add');
        Route::post('/store', 'store')->name('user.store');
        Route::get('/edit/{id}', 'edit')->name('user.edit');
        Route::post('/update/{id}', 'update')->name('user.update');
        Route::get('/delete/{id}', 'delete')->name('user.delete');
        Route::get('/action', 'handleAction')->name('user.action');
        Route::get('/forceDelete/{id}', 'forceDelete')->name('user.forceDelete');
        Route::get('/restore/{id}', 'restore')->name('user.restore');
        Route::get('/profile', 'profile')->name('user.profile');
    });
    Route::controller(RoleController::class)->prefix('/role')->group(function () {
        Route::get('/', 'show')->name('role.show');
        Route::get('/add', 'add')->name('role.add');
        Route::post('/store', 'store')->name('role.store');
        Route::get('/edit/{id}', 'edit')->name('role.edit');
        Route::post('/update/{id}', 'update')->name('role.update');
        Route::get('/delete/{id}', 'delete')->name('role.delete');
        Route::get('/action', 'handleAction')->name('role.action');
    });
});
