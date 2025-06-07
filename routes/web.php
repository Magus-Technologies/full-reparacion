<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RepairController;
use App\Http\Controllers\Admin\RepairPDFController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\KardexController;
use App\Http\Controllers\Admin\ComprasController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\UnidadMedidaController;
use App\Http\Controllers\Admin\BarcodeController;
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
        
        Route::get('/kardex', [KardexController::class, 'index'])->name('kardex.index');

        // Rutas AJAX para productos
         Route::get('/kardex/productos/export-excel', [KardexController::class, 'exportExcel'])->name('kardex.productos.export-excel');
        Route::get('/kardex/productos/data', [KardexController::class, 'getProductos'])->name('kardex.productos.data');
        Route::post('/kardex/productos/store', [KardexController::class, 'store'])->name('kardex.productos.store');
        Route::get('/kardex/productos/{id}', [KardexController::class, 'show'])->name('kardex.productos.show');
        Route::put('/kardex/productos/{id}', [KardexController::class, 'update'])->name('kardex.productos.update');
        Route::delete('/kardex/productos/delete', [KardexController::class, 'destroy'])->name('kardex.productos.destroy');
        Route::get('/kardex/productos/{id}/precios', [KardexController::class, 'getPrecios'])->name('kardex.productos.precios');
        Route::post('/kardex/productos/precios/update', [KardexController::class, 'updatePrecios'])->name('kardex.productos.precios.update');
        Route::post('/kardex/productos/stock/add', [KardexController::class, 'addStock'])->name('kardex.productos.stock.add');
        Route::post('/kardex/productos/precios/save', [KardexController::class, 'savePrecios'])->name('kardex.productos.precios.save');
        Route::get('/kardex/productos/{id}/multi-precios', [KardexController::class, 'getMultiPrecios'])->name('kardex.productos.multi-precios');
        // Después de: Route::get('/kardex/productos/{id}/multi-precios', [KardexController::class, 'getMultiPrecios'])->name('kardex.productos.multi-precios');
       
        // Después de las otras rutas de admin
        Route::get('/barcode/generate', [BarcodeController::class, 'generate']);

        Route::get('/compras', [ComprasController::class, 'index'])->name('compras.index');
        Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
        // Rutas para unidades de medida - Vista principal
        Route::get('/unidades', [UnidadMedidaController::class, 'index'])->name('unidades.index');

        // Rutas para categorías AJAX
        Route::get('/categorias/get', [CategoriaController::class, 'getCategorias']);
        Route::post('/categorias/save', [CategoriaController::class, 'saveCategoria']);
        Route::post('/categorias/getOne', [CategoriaController::class, 'getOneCategoria']);
        Route::post('/categorias/update', [CategoriaController::class, 'updateCategoria']);
        Route::post('/categorias/delete', [CategoriaController::class, 'deleteCategoria']);

        // Rutas para unidades de medida AJAX
        Route::get('/unidades/get', [UnidadMedidaController::class, 'getUnidades'])->name('unidades.get');
        Route::post('/unidades/save', [UnidadMedidaController::class, 'saveUnidad'])->name('unidades.save');
        Route::post('/unidades/getOne', [UnidadMedidaController::class, 'getOneUnidad'])->name('unidades.getOne');
        Route::post('/unidades/update', [UnidadMedidaController::class, 'updateUnidad'])->name('unidades.update');
        Route::post('/unidades/delete', [UnidadMedidaController::class, 'deleteUnidad'])->name('unidades.delete');
    });
});


