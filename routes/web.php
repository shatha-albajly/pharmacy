<?php

use App\Enum\RoleEnum;

use App\Http\Controllers\web;
use App\Http\Controllers\admin;
use App\Http\Controllers\client;
use App\Http\Controllers\pharmacy;
use App\Http\Controllers\Auth\RegisterPharmacyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use Barryvdh\Debugbar\Facades\Debugbar;

// TODO
// disable Debug
Debugbar::disable();
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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
// Route::get('/clients', function () {
//   return view('admin.clients');
// });

// Route::get('/ads', function () {
//   return view('admin.ads');
// });

// Route::get('/pharmacies-users', function () {
//   return view('admin.pharmacies-users');
// });

// Route::get('/manage-pages', function () {
//   return view('admin.manage-pages');
// });

Route::controller(web\HomeController::class)->group(function () {
  Route::get('/', 'index')->name('home');
  Route::get('/pharmacies', 'showPharmacies')->name('pharmacies');
  Route::get('/pharmacies/profile/{id}', 'showPharmacy')->name('pharmacy.profile');
});


/*
|--------------------------------------------------------------------------
| Register Pharmacy Routes
|--------------------------------------------------------------------------
*/
Route::controller(RegisterPharmacyController::class)->group(function () {
  Route::get('/register/pharmacy', 'index')->name('register.pharmacy');
  Route::post('/register/pharmacy', 'store')->name('register.pharmacy.store');
});

/*
|--------------------------------------------------------------------------
| Pharmacies Routes
|--------------------------------------------------------------------------
*/
Route::prefix('/dashboard/pharmacies')->middleware(['auth', 'role:' . RoleEnum::PHARMACY])
  ->name('pharmacies.')->group(function () {

    Route::resource('/', pharmacy\PharmacyController::class);

    Route::controller(pharmacy\SettingController::class)->group(function () {

      Route::post('/update/logo', 'updateLogo')->name('update.logo');
    });
  });

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('/admin')->middleware(['auth'])
  ->name('admin.')->group(function () {

    Route::get('/', [admin\AdminController::class, 'index'])->name('index');

    // admin profile
    Route::get('profile', [admin\AdminProfileController::class, 'index'])
      ->name('profile');

    Route::post('profile', [admin\AdminProfileController::class, 'updateProfile'])
      ->name('update-profile');
    /*------------------------------ ads ------------------------------*/
    Route::resource('/ads', admin\AdController::class);

    /*------------------------------ website content ------------------------------*/
    Route::prefix('site')->controller(admin\SiteController::class)
      ->group(function () {
        Route::get('/', 'index')->name('site');
        Route::put('/about-us', 'updateAboutUs')
          ->name('updateAboutUs');

        Route::post('/services', 'addService')->name('addService');
        Route::put('/services/{service}', 'updateService')->name('updateService');
        Route::delete('/services/{service}', 'deleteService')->name('deleteService');

        Route::put('/contact-us', 'updateContactUs')->name('updateContactUs');

        Route::put('/social', 'updateSocial')->name('updateSocial');
      });

    /*------------------------------ clients ------------------------------*/
    Route::controller(admin\ClientController::class)->group(function () {
      Route::get('/clients', 'index')
        ->name('clients');

      Route::post('/clients/toggle/{id}',  'clientToggle')
        ->name('clients.toggle');
    });

    /*------------------------------ orders ------------------------------*/
    // Route::get('/orders', [admin\OrderController::class, 'index'])
    //   ->name('admin.orders'); // TODO

    // pharmacies
    Route::controller(admin\PharmacyController::class)->group(function () {
      Route::get('/pharmacies',  'index')
        ->name('pharmacies');

      Route::post('/pharmacies/toggle/{id}',  'pharmacyToggle')
        ->name('pharmacies.toggle');
    });
  });

/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
*/
Route::prefix('/dashboard/clients')->name('clients.')->group(function () {
  Route::get('/', [client\ClientProfileController::class, 'index'])
    ->name('profile');

  Route::post('/', [client\ClientProfileController::class, 'updateProfile'])
    ->name('update-profile');
});

Auth::routes(['verify' => true]);
