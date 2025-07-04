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
use App\Http\Controllers\Admin\ReniecController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\SettingController;

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
        
            // AGREGAR estas rutas después de la línea:
            // Route::get('/kardex/productos/{id}/multi-precios', [KardexController::class, 'getMultiPrecios'])->name('kardex.productos.multi-precios');

            // Rutas para importación de productos
            Route::get('/kardex/productos/template/download', [KardexController::class, 'downloadTemplate'])->name('kardex.productos.template.download');
            Route::post('/kardex/productos/import/process', [KardexController::class, 'processImport'])->name('kardex.productos.import.process');
            Route::post('/kardex/productos/import/confirm', [KardexController::class, 'confirmImport'])->name('kardex.productos.import.confirm');
            Route::get('/kardex/categorias/list', [KardexController::class, 'getCategorias'])->name('kardex.categorias.list');
            Route::get('/kardex/unidades/list', [KardexController::class, 'getUnidades'])->name('kardex.unidades.list');

            // Después de las otras rutas de admin
            Route::get('/barcode/generate', [BarcodeController::class, 'generate']);

            Route::get('/compras', [ComprasController::class, 'index'])->name('compras.index');
            // Mostrar formulario para crear una nueva compra
            Route::get('/admin', [ComprasController::class, 'create'])->name('compras.create');


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
        
            Route::post('/reniec/buscar-documento', [ReniecController::class, 'buscarDocumento'])->name('reniec.buscar-documento');
            // Nuevas rutas para búsqueda de productos
            Route::get('/compras/productos/buscar', [ComprasController::class, 'buscarProductos'])->name('compras.productos.buscar');
            Route::get('/compras/productos/{id}/precios', [ComprasController::class, 'obtenerPreciosProducto'])->name('compras.productos.precios');
            // Guardar nueva compra
            Route::post('/compras/store', [ComprasController::class, 'store'])->name('compras.store');


           // Agrupar dentro de admin/compras
            Route::prefix('compras')->name('compras.')->group(function () {
                Route::get('/obtener', [ComprasController::class, 'obtenerCompras'])->name('obtener');
                Route::get('/{id}/detalle', [ComprasController::class, 'obtenerDetalle'])->name('detalle');
                Route::get('/{id}/reporte', [ComprasController::class, 'generarReportePDF'])->name('reporte');
                Route::get('/{id}/productos-recepcion', [ComprasController::class, 'obtenerProductosRecepcion'])->name('productos-recepcion');
                Route::post('/{id}/recepcionar', [ComprasController::class, 'recepcionarProductos'])->name('recepcionar');
                Route::post('/pago/{id}/marcar-pagado', [ComprasController::class, 'marcarPagoPagado'])->name('marcar-pago-pagado');
                Route::get('/buscar-productos', [ComprasController::class, 'buscarProductos'])->name('buscar-productos');
                Route::get('/producto/{id}/precios', [ComprasController::class, 'obtenerPreciosProducto'])->name('producto-precios');
            });

            
            // Rutas para búsqueda de productos
            Route::get('/buscar-productos', [ComprasController::class, 'buscarProductos'])->name('buscar-productos');
            Route::get('/producto/{id}/precios', [ComprasController::class, 'obtenerPreciosProducto'])->name('producto-precios');


            // Agregar después de la línea: Route::get('/compras/productos/{id}/precios', [ComprasController::class, 'obtenerPreciosProducto'])->name('compras.productos.precios');
            Route::get('/tipo-cambio/obtener', [App\Http\Controllers\Admin\TipoCambioController::class, 'obtenerTipoCambio'])->name('tipo-cambio.obtener');
        });
});


