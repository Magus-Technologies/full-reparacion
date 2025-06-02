<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RepairController;
use App\Http\Controllers\Admin\RepairPDFController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ClientController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/services', [ServiceController::class, 'index'])->name('services');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/check-status', [HomeController::class, 'checkStatus'])->name('check-status');

Route::middleware(['auth'])->group(function () {
    // Rutas de administración
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('repairs', RepairController::class);
        Route::get('/repairs/get-client-dni/{id}', [RepairController::class, 'getClientDNI'])->name('admin.repairs.get-client-dni');

        Route::get('/repairs/{id}/pdf', [RepairPDFController::class, 'generatePDF'])->name('repairs.pdf');

        Route::get('/repairs/{id}/share-whatsapp', [RepairPDFController::class, 'shareWhatsApp'])->name('repairs.share-whatsapp');

        Route::resource('clients', ClientController::class);
        // Dentro del grupo de rutas admin
        Route::get('clients/search-dni/{dni}', [ClientController::class, 'searchDNI'])->name('clients.search-dni');
        Route::resource('inquiries', InquiryController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('users', UserController::class);
        Route::get('settings', [SettingController::class, 'index'])->name('settings');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    });
});


