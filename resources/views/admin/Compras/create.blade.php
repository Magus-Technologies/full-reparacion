@extends('layouts.admin')

@section('content')
<script src="{{ asset('js/qrCode.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">Compra</h6>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);" class="text-gray-800 font-bold no-underline hover:text-gray-900" style="all: unset; display: inline; font-weight: bold; color: #1f2937;">
                        Almacén
                    </a>
                </li>
                <li class="breadcrumb-item me-2">
                    <a href="{{ route('admin.compras.index') }}" style="all: unset; color: #1755eb; font-weight: 600; cursor: pointer;">
                        Compras
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page" style="all: unset; color: #1755eb; font-weight: 600; cursor: default;">
                    Nueva compra
                </li>
            </ol>

        </div>
        <div class="col-md-4">
            <div class="float-end d-none d-md-block">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title-desc">
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-lg-12">
                            <hr class="line-body" />
                        </div>

                        <div id="" class="col-xs-12 col-sm-12 col-md-12 no-padding">
                            <div class="col-xs-12 col-sm-12 col-md-12 no-padding">
                                <div class="row" id="container-main">
                                    <div class="col-12 row">
                                        <div class="col-md-8">
                                            <div class="panel">
                                                <div class="panel-body">
                                                    <button id="btn-scan-qr" class="btn btn-primary mb-3">
                                                        Escanear QR
                                                    </button>
                                                    <canvas hidden="" id="qr-canvas" style="width: 300px;"></canvas>
                                                    
                                                    <div id="reader" style="width: 100%; display: none;"></div>
                                                    <div id="reader-controls" style="display: none; margin-top: 10px;">
                                                        <button id="btn-stop-scan" class="btn btn-danger">Detener Escáner</button>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <form id="frmCompraProducto" class="form-horizontal">
                                                                <div class="form-group row mb-3">
                                                                    <label class="col-lg-2 control-label">Buscar</label>
                                                                    <div class="col-lg-8">
                                                                        <div style="display: flex;">
                                                                            <div class="col-lg-8" style="padding-left: 0;">
                                                                                <div style="position: relative;">
                                                                                    <input type="text" class="form-control" id="descripcionBuscar" placeholder="Buscar por código o nombre..." autocomplete="off">
                                                                                    <ul id="lista-autocompletado" class="list-group" style="position: absolute; top: 100%; left: 0; right: 0; z-index: 1000; display: none; max-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-top: none;"></ul>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-2">
                                                                                <!--<select class="form-control" id="almacen">
                                                                                    <option value="1">Almacen 1</option>
                                                                                    <option value="2">Tienda 1</option>
                                                                                </select>-->
                                                                            </div>
                                                                            <div class="col-md-2">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group row mb-3">
                                                                    <label class="col-lg-2 control-label">Descripcion</label>
                                                                    <div class="col-lg-10">
                                                                        <input type="text" class="form-control" placeholder="Producto" id="descripcionProducto" readonly="">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group row mb-3" id="seccion-precios" style="display: none;">
                                                                    <label class="col-lg-2 control-label">Precio de Compra</label>
                                                                    <div class="col-lg-10">
                                                                        <select class="form-control" id="selectPrecios" style="width: 100%;">
                                                                            <option value="">Seleccione un precio</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row mb-3">
                                                                <label class="col-lg-2 control-label">Stock Actual</label>
                                                                <div class="col-lg-10">
                                                                    <div class="row align-items-end">
                                                                        <!-- Stock actual -->
                                                                        <div class="col-lg-3">
                                                                            <input disabled class="form-control text-center" type="text" placeholder="0" id="stockActual" name="stockActual">
                                                                        </div>

                                                                        <!-- Cantidad -->
                                                                        <div class="col-lg-3">
                                                                            <label for="cantidad" class="form-label">Cantidad</label>
                                                                            <input class="form-control text-center only-number" type="text" placeholder="0" id="cantidad" name="cantidad" autocomplete="off">
                                                                        </div>

                                                                        <!-- Precio -->
                                                                        <div class="col-lg-3">
                                                                            <label for="precio" class="form-label">Precio</label>
                                                                            <input class="form-control text-end only-number" type="text" placeholder="0.00" id="precio" readonly>
                                                                        </div>

                                                                        <!-- Botón agregar -->
                                                                        <div class="col-lg-3">
                                                                            <button id="btn-agregar" type="button" class="btn btn-success w-100">
                                                                                <i class="fa fa-check"></i> Agregar
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Modal para mostrar productos -->
                                                                <div id="modal_ver_detalle" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <input type="hidden" name="idProducto" id="idProducto" value="">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="myModalLabel">Productos</h5>
                                                                            </div>
                                                                            <div class="modal-body" id="modal_detalle">
                                                                                <table class="table">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th style="width: 10%;text-align: center;">Item</th>
                                                                                            <th style="width: 70%;text-align: center;">Producto</th>
                                                                                            <th style="width: 10%;text-align: center;">Stock</th>
                                                                                            <th style="width: 10%;text-align: center;">Agregar</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody id="productos-tbody">
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button class="btn-accion" data-dismiss="modal" aria-label="Close">Guardar</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>

                                                        <div id="seccion_productos" class="col-md-12 mt-5" style="margin-top: 25px;">
                                                            <div class="form-group">
                                                                <div style="width: 100%; height: 20px; border-bottom: 2px solid #0866c6; text-align: left">
                                                                    <span style="font-size: 16px; font-weight: bold; background-color: #ffffff; padding: 1px 4px;">
                                                                        Productos
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Item</th>
                                                                        <th>Producto</th>
                                                                        <th>Cantidad</th>
                                                                        <th>P. Unit.</th>
                                                                        <th>Parcial</th>
                                                                        <th>Escanear</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="productos-lista">
                                                                </tbody>
                                                            </table>

                                                            <!-- Sección de Observaciones - Lado Izquierdo -->
                                                            <div class="mt-4">
                                                                <div class="form-group">
                                                                    <div style="width: 100%; height: 20px; border-bottom: 2px solid #0866c6; text-align: left; margin-bottom: 20px;">
                                                                        <span style="font-size: 16px; font-weight: bold; background-color: #ffffff; padding: 1px 4px;">
                                                                            <i class="fa fa-sticky-note" style="margin-right: 8px; color: #0866c6;"></i>
                                                                            Observaciones
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <textarea 
                                                                        id="observaciones" 
                                                                        name="observaciones" 
                                                                        class="form-control" 
                                                                        rows="4" 
                                                                        placeholder="Ingrese observaciones adicionales sobre esta compra (opcional)..."
                                                                        style="resize: vertical; border: 1px solid #d1d3e2; border-radius: 6px; padding: 12px; font-size: 14px; line-height: 1.5;"
                                                                        maxlength="500"></textarea>
                                                                    
                                                                    <div class="text-end mt-2">
                                                                        <small class="text-muted">
                                                                            <span id="contador-caracteres">0</span>/500 caracteres
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="col-md-12">
                                                        <div class="widget padding-0 white-bg">
                                                            <div class="padding-20 text-center">
                                                                <form class="form-horizontal">
                                                                    <div class="row form-group">
                                                                        <div class="col-md-6 form-group">
                                                                            <label class="control-label">Documento</label>
                                                                            <div class="col-md-12">
                                                                                <select id="tipo_doc" class="form-control">
                                                                                    <option value="2">FACTURA</option>
                                                                                    <option value="12">NOTA DE COMPRA</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="control-label">Tipo Pago</label>
                                                                            <select id="tipo_pago" class="form-control">
                                                                                <option value="1">Contado</option>
                                                                                <option value="2">Credito</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <div class="col-lg-12 row">
                                                                            <div class="col-lg-6">
                                                                                <label class="text-center col-md-12">Serie</label>
                                                                                <input id="serie" type="text" class="form-control text-center">
                                                                            </div>
                                                                            <div class="col-lg-6">
                                                                                <label class="text-center col-md-12">Numero</label>
                                                                                <input id="numero" type="text" class="form-control text-center">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <label class="text-center col-md-12">Moneda</label>
                                                                            <select id="moneda" class="form-control">
                                                                                <option value="S">Soles (PEN)</option>
                                                                                <option value="D">Dólares (USD)</option>
                                                                            </select>
                                                                        </div>

                                                                        <!-- Etiqueta de conversión (inicialmente oculta) -->
                                                                        <div id="etiqueta-conversion" class="col-lg-12 mt-3" style="display: none;">
                                                                            <div class="alert alert-dark text-center" style="background-color: #2c3e50; color: white; border: none; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                                                                                <i class="fa fa-exchange-alt me-2"></i>
                                                                                <strong>Mostrando montos en dólares según tasa de cambio actual: <span id="tasa-mostrada">S/. 0.00</span></strong>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Sección de tasa de cambio (inicialmente oculta) -->
                                                                        <div id="seccion-tasa-cambio" class="col-lg-12 mt-3" style="display: none;">
                                                                            <div class="card shadow-sm" style="border: 1px solid #e3e6f0; border-radius: 8px;">
                                                                                <div class="card-body" style="padding: 20px;">
                                                                                    <h6 class="card-title mb-3" style="color: #5a5c69; font-weight: 600;">
                                                                                        <i class="fa fa-dollar-sign" style="margin-right: 8px; color: #0866c6;"></i>
                                                                                        Configuración de Tipo de Cambio
                                                                                    </h6>
                                                                                    
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <label class="form-label">Tasa Actual (API)</label>
                                                                                            <input type="text" id="tasa-actual" class="form-control" readonly style="background-color: #f8f9fa;">
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <label class="form-label">Tasa Personalizada</label>
                                                                                            <input type="number" id="tasa-personalizada" class="form-control" step="0.001" min="0.001" placeholder="Ej: 3.75">
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class="text-center mt-3">
                                                                                        <small class="text-muted">
                                                                                            <i class="fa fa-info-circle"></i>
                                                                                            Si ingresa una tasa personalizada, se usará esa para todos los cálculos
                                                                                        </small>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-lg-12 text-center">Fecha</label>
                                                                        <div class="col-lg-12">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label">Emision</label>
                                                                                        <div class="col-lg-12">
                                                                                            <input id="fecha" type="date" class="form-control text-center" value="{{ date('Y-m-d') }}">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label">Vencimiento</label>
                                                                                        <div class="col-lg-12">
                                                                                            <input id="fechaVen" type="date" class="form-control text-center" value="{{ date('Y-m-d') }}">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div id="dias-pago-section" class="form-group" style="display: none;">
                                                                        <label class="control-label">Dias de pago</label>
                                                                        <div class="col-lg-12">
                                                                            <input id="dias_pago" type="text" class="form-control text-center">
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <!-- Sección de Cuotas (solo visible para crédito) -->
                                                                    <div id="seccion-cuotas" class="form-group mb-5" style="display: none; margin-bottom: 2rem;">
                                                                        <div class="col-lg-12">
                                                                            <div class="card shadow-sm" style="border: 1px solid #e3e6f0; border-radius: 8px;">
                                                                                <div class="card-body" style="padding: 20px;">
                                                                                    <h6 class="card-title mb-3" style="color: #5a5c69; font-weight: 600;">
                                                                                        <i class="fa fa-credit-card" style="margin-right: 8px; color: #0866c6;"></i>
                                                                                        Configurar Cuotas de Pago
                                                                                    </h6>
                                                                                    
                                                                                    <div class="row mb-3">
                                                                                        <div class="col-md-6">
                                                                                            <label class="form-label">Número de Cuotas</label>
                                                                                            <input type="number" id="numero-cuotas" class="form-control" min="1" max="12" placeholder="Ej: 3">
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <label class="form-label">Monto Total</label>
                                                                                            <input type="text" id="monto-total-cuotas" class="form-control" readonly style="background-color: #f8f9fa; margin-top: 24px;">
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class="text-center mb-3">
                                                                                        <button type="button" id="btn-generar-cuotas" class="btn btn-primary">
                                                                                            <i class="fa fa-calculator"></i> Generar Cuotas
                                                                                        </button>
                                                                                        <button type="button" id="btn-configurar-cuotas" class="btn btn-info" style="display: none; margin-top: 10px">
                                                                                            <i class="fa fa-cog"></i> Configurar Cuotas
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Modal para configurar cuotas -->
                                                                    <div class="modal fade" id="modal-cuotas" tabindex="-1" aria-labelledby="modalCuotasLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="modalCuotasLabel">
                                                                                        <i class="fa fa-credit-card text-primary"></i>
                                                                                        Configurar Cuotas de Pago
                                                                                    </h5>
                                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="row mb-3">
                                                                                        <div class="col-md-6">
                                                                                            <label class="form-label">Fecha Base</label>
                                                                                            <input type="date" id="fecha-base-cuotas" class="form-control" readonly>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <label class="form-label">Monto Total a Dividir</label>
                                                                                            <input type="text" id="monto-total-modal" class="form-control" readonly style="background-color: #f8f9fa;">
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <!-- Tabla de cuotas -->
                                                                                    <div class="table-responsive">
                                                                                        <table class="table table-bordered table-hover">
                                                                                            <thead class="table-light">
                                                                                                <tr>
                                                                                                    <th style="width: 15%;">#</th>
                                                                                                    <th style="width: 30%;">Monto</th>
                                                                                                    <th style="width: 30%;">Fecha</th>
                                                                                                    <th style="width: 25%;">Estado</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody id="tabla-cuotas-body">
                                                                                                <!-- Las cuotas se generarán aquí dinámicamente -->
                                                                                            </tbody>
                                                                                            <tfoot class="table-light">
                                                                                                <tr>
                                                                                                    <th colspan="3">Total Cuotas:</th>
                                                                                                    <th id="total-cuotas-calculado">S/ 0.00</th>
                                                                                                </tr>
                                                                                            </tfoot>
                                                                                        </table>
                                                                                    </div>
                                                                                    
                                                                                    <!-- Resumen -->
                                                                                    <div class="alert alert-info mt-3">
                                                                                        <i class="fa fa-info-circle"></i>
                                                                                        <strong>Resumen:</strong> <span id="resumen-cuotas">No hay cuotas configuradas</span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                                    <button type="button" id="btn-guardar-cuotas" class="btn btn-accion">
                                                                                        <i class="fa fa-save me-2"></i> Guardar Cuotas
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="col-lg-4 control-label" style="text-align:center;">Proveedor</label>
                                                                    </div>

                                                                    <div class="form-group mb-3">
                                                                        <div class="col-lg-12">
                                                                            <div class="input-group">
                                                                                <input id="num_doc" type="text" placeholder="Ingrese Documento" class="form-control" maxlength="11">
                                                                                <div class="input-group-addon">
                                                                                    <button id="btn-buscar-doc" class="btn btn-primary" type="button">
                                                                                        <i class="fa fa-search"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group mb-3">
                                                                        <div class="col-lg-12">
                                                                            <input id="nom_cli" type="text" placeholder="Nombre del proveedor" class="form-control" autocomplete="off">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group mb-3">
                                                                        <div class="col-lg-12">
                                                                            <input id="dir_cli" type="text" placeholder="Direccion 1" class="form-control" autocomplete="off">
                                                                        </div>
                                                                    </div>

                                                                    <!-- Sección de IGV -->
                                                                    <div class="form-group mb-4">
                                                                        <div class="col-lg-12">
                                                                            <div class="card shadow-sm" style="border: 1px solid #e3e6f0; border-radius: 8px;">
                                                                                <div class="card-body" style="padding: 20px;">
                                                                                    <h6 class="card-title mb-3" style="color: #5a5c69; font-weight: 600;">
                                                                                        <i class="fa fa-percentage" style="margin-right: 8px; color: #0866c6;"></i>
                                                                                        Configuración de IGV
                                                                                    </h6>
                                                                                    
                                                                                    <div class="form-check mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="aplicar_igv" name="aplicar_igv">
                                                                                        <label class="form-check-label fw-bold" for="aplicar_igv">
                                                                                            ¿Aplicar IGV a esta compra?
                                                                                        </label>
                                                                                    </div>
                                                                                    
                                                                                    <div id="seccion-igv-incluido" class="form-check mb-3" style="display: none; margin-left: 25px;">
                                                                                        <input class="form-check-input" type="checkbox" id="precios_incluyen_igv" name="precios_incluyen_igv">
                                                                                        <label class="form-check-label" for="precios_incluyen_igv">
                                                                                            Los precios ingresados ya incluyen IGV
                                                                                        </label>
                                                                                        <small class="form-text text-muted d-block mt-1">
                                                                                            Si está marcado: se extrae el IGV. Si no: se agrega el IGV (18%)
                                                                                        </small>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Sección de Tipo de Registro - Insertar antes del botón Guardar -->
                                                                    <div class="form-group mb-4">
                                                                        <div class="col-lg-12">
                                                                            <div class="card shadow-sm" style="border: 1px solid #e3e6f0; border-radius: 8px;">
                                                                                <div class="card-body" style="padding: 20px;">
                                                                                    <h6 class="card-title mb-3" style="color: #5a5c69; font-weight: 600; margin-bottom: 15px;">
                                                                                        <i class="fa fa-clipboard-list" style="margin-right: 8px; color: #0866c6;"></i>
                                                                                        ¿Cómo registrarás esta compra?
                                                                                    </h6>
                                                                                    
                                                                                    <div class="row">
                                                                                        <div class="col-12">
                                                                                            <!-- Opción 1: Con productos entregados -->
                                                                                            <div class="form-check mb-3" style="padding-left: 0;">
                                                                                                <label class="form-check-label d-flex align-items-center p-3 border rounded" 
                                                                                                    style="cursor: pointer; transition: all 0.3s ease; border-color: #d1d3e2 !important;"
                                                                                                    for="tipo_registro_completo"
                                                                                                    onmouseover="this.style.backgroundColor='#f8f9fc'; this.style.borderColor='#0866c6';"
                                                                                                    onmouseout="this.style.backgroundColor=''; this.style.borderColor='#d1d3e2';">
                                                                                                    <input class="form-check-input me-3" type="radio" name="tipo_registro" 
                                                                                                        id="tipo_registro_completo" value="completo" checked
                                                                                                        style="width: 20px; height: 20px; border-radius: 50%; border: 2px solid #d1d3e2; 
                                                                                                                background-color: #fff; flex-shrink: 0; margin-right: 12px; margin-top: 0; 
                                                                                                                appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                                                                                                    <div class="d-flex align-items-center">
                                                                                                        <div class="me-3" style="margin-right: 15px;">
                                                                                                            <i class="fa fa-box text-success" style="font-size: 20px;"></i>
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <div class="fw-bold" style="font-weight: 600; color: #5a5c69;">
                                                                                                                Con productos entregados ahora
                                                                                                            </div>
                                                                                                            <small class="text-muted">
                                                                                                                Registro completo con detalle de productos recibidos
                                                                                                            </small>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </label>
                                                                                            </div>
                                                                                            
                                                                                            <!-- Opción 2: Solo datos de compra -->
                                                                                            <div class="form-check mb-3" style="padding-left: 0;">
                                                                                                <label class="form-check-label d-flex align-items-center p-3 border rounded" 
                                                                                                    style="cursor: pointer; transition: all 0.3s ease; border-color: #d1d3e2 !important;"
                                                                                                    for="tipo_registro_pendiente"
                                                                                                    onmouseover="this.style.backgroundColor='#f8f9fc'; this.style.borderColor='#0866c6';"
                                                                                                    onmouseout="this.style.backgroundColor=''; this.style.borderColor='#d1d3e2';">
                                                                                                    <input class="form-check-input me-3" type="radio" name="tipo_registro" 
                                                                                                        id="tipo_registro_pendiente" value="pendiente"
                                                                                                        style="width: 20px; height: 20px; border-radius: 50%; border: 2px solid #d1d3e2; 
                                                                                                                background-color: #fff; flex-shrink: 0; margin-right: 12px; margin-top: 0; 
                                                                                                                appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                                                                                                    <div class="d-flex align-items-center">
                                                                                                        <div class="me-3" style="margin-right: 15px;">
                                                                                                            <i class="fa fa-truck text-warning" style="font-size: 20px;"></i>
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <div class="fw-bold" style="font-weight: 600; color: #5a5c69;">
                                                                                                                Solo guardar datos de la compra
                                                                                                            </div>
                                                                                                            <small class="text-muted">
                                                                                                                Los productos se recibirán después
                                                                                                            </small>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group mb-3">
                                                                        <div class="col-lg-12">
                                                                            <button id="btn-guardar-compra" type="button" class="btn btn-lg btn-accion">
                                                                                <i class="fa fa-save"></i>&nbsp;Guardar
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="bg-primary text-center p-3" style="color: white; padding-bottom: 2rem;">
                                                                <h1 class="mb-2 font-400" id="lbl_suma_pedido">S/ 0.00</h1>
                                                                <div class="text-uppercase">Suma Pedido</div>
                                                                
                                                                <!-- 🔥 NUEVA SECCIÓN: Mostrar IGV calculado -->
                                                                <div id="seccion-igv-total" style="display: none; margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.3);">
                                                                    <div style="font-size: 14px; margin-bottom: 5px;">IGV (18%)</div>
                                                                    <div style="font-size: 18px; font-weight: bold;" id="lbl_igv_calculado">S/ 0.00</div>
                                                                    <div style="font-size: 14px; margin-top: 10px;">TOTAL CON IGV</div>
                                                                    <div style="font-size: 22px; font-weight: bold;" id="lbl_total_con_igv">S/ 0.00</div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Dias de Pagos -->
                                    <div class="modal fade" id="modal-dias-pagos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Dias de Pagos</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Fecha Emision</label>
                                                            <input id="fecha-modal" disabled type="date" class="form-control">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Monto TotalVenta</label>
                                                            <input id="total-modal" disabled type="text" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Dias de pagos</label>
                                                        <input placeholder="10,20,30,........" id="dias-pago-modal" type="text" class="form-control">
                                                        <div class="form-text">Separe por comas los días de pagos</div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table class="text-center table-sm table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Fecha</th>
                                                                        <th>Monto</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="dias-lista-tbody">
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th colspan="2">Total</th>
                                                                        <th id="total-dias">0.00</th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-info" data-bs-dismiss="modal">Guardar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal de Escaneo de Códigos -->
                                    <div class="modal fade" id="modal-escaneo-codigos" tabindex="-1" aria-labelledby="modalEscaneoLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title" id="modalEscaneoLabel">
                                                        <i class="fa fa-qrcode"></i> ESCANEAR CÓDIGOS ÚNICOS DEL PRODUCTO
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row mb-4">
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Producto:</label>
                                                            <input type="text" id="producto-escaneo" class="form-control" readonly style="background-color: #f8f9fa;">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold">Cantidad esperada:</label>
                                                            <input type="text" id="cantidad-esperada" class="form-control" readonly style="background-color: #f8f9fa;">
                                                        </div>
                                                    </div>
                                                    
                                                    <hr>
                                                    
                                                    <div class="row mb-4">
                                                        <div class="col-md-8">
                                                            <label class="form-label">Código escaneado:</label>
                                                            <input type="text" id="codigo-escaneado" class="form-control" placeholder="Ingrese o escanee el código">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">&nbsp;</label>
                                                            <button type="button" id="btn-agregar-codigo-manual" class="btn btn-info w-100">
                                                                <i class="fa fa-plus"></i> Agregar código
                                                            </button>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Códigos escaneados:</label>
                                                        <div id="lista-codigos-escaneados" style="background-color: #111; color: #fff; padding: 15px; border-radius: 8px; min-height: 150px; font-family: 'Roboto Mono', 'Courier New', monospace; font-size: 14px; max-height: 200px; overflow-y: auto;">
                                                            <div class="text-center text-muted" id="mensaje-vacio">
                                                                <i class="fa fa-barcode" style="font-size: 24px; margin-bottom: 10px;"></i><br>
                                                                No hay códigos escaneados
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="alert alert-info mb-0">
                                                                <strong>Progreso:</strong> <span id="contador-codigos">0 / 0</span> códigos escaneados
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div id="estado-escaneo" class="alert alert-warning mb-0">
                                                                <i class="fa fa-clock"></i> Esperando códigos...
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                        <i class="fa fa-times"></i> Cancelar
                                                    </button>
                                                    <button type="button" id="btn-guardar-escaneados" class="btn-accion" disabled>
                                                        <i class="fa fa-check"></i>&nbsp;Guardar escaneados ✅
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery PRIMERO -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AGREGAR DATATABLES -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Agregar después de las otras librerías JS -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // 3. AGREGAR estas variables globales al inicio del JavaScript
    let html5QrcodeScanner = null;
    let isScanning = false;
    // Agregar esta variable al inicio del JavaScript, después de las otras variables globales:
    let productosEnTabla = []; // Para almacenar los datos completos de productos agregados

    // 4. AGREGAR esta función para manejar el mapeo de tipos de documento
    function mapearTipoDocumento(tipoSunat) {
        const mapeoDocumentos = {
            '01': '2', // FACTURA
            '03': '12', // BOLETA -> NOTA DE COMPRA (según tu select)
            '07': '12', // NOTA DE CRÉDITO -> NOTA DE COMPRA
            '08': '12'  // NOTA DE DÉBITO -> NOTA DE COMPRA
        };
        return mapeoDocumentos[tipoSunat] || '12'; // Por defecto NOTA DE COMPRA
    }

  // REEMPLAZAR la función iniciarEscanerQR() existente con esta versión modificada:

function iniciarEscanerQR() {
    if (isScanning) {
        Swal.fire({
            icon: 'warning',
            title: 'Escáner activo',
            text: 'El escáner ya está funcionando'
        });
        return;
    }

    // Mostrar el div del reader
    document.getElementById('reader').style.display = 'block';
    document.getElementById('reader-controls').style.display = 'block';
    
    // Configurar el escáner
    html5QrcodeScanner = new Html5Qrcode("reader");
    
    // Función para iniciar el escáner con configuración específica
    function iniciarConConfiguracion(config) {
        return html5QrcodeScanner.start(
            config,
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            (decodedText, decodedResult) => {
                // QR leído exitosamente
                procesarQRLeido(decodedText);
            },
            (errorMessage) => {
                // Error de lectura (normal mientras busca QR)
                console.log(`Error de lectura: ${errorMessage}`);
            }
        );
    }
    
    // Primero intentar con cámara trasera (environment)
    iniciarConConfiguracion({ facingMode: "environment" })
        .then(() => {
            console.log('Cámara trasera iniciada exitosamente');
            isScanning = true;
        })
        .catch(err => {
            console.log('No se pudo usar cámara trasera, intentando con frontal:', err);
            
            // Si falla, intentar con cámara frontal (user)
            iniciarConConfiguracion({ facingMode: "user" })
                .then(() => {
                    console.log('Cámara frontal iniciada exitosamente');
                    isScanning = true;
                })
                .catch(err => {
                    console.log('No se pudo usar cámara frontal, intentando con cualquier cámara:', err);
                    
                    // Si ambas fallan, usar el método original (cualquier cámara disponible)
                    Html5Qrcode.getCameras().then(devices => {
                        if (devices && devices.length) {
                            const cameraId = devices[0].id;
                            
                            iniciarConConfiguracion(cameraId)
                                .then(() => {
                                    console.log('Cámara por ID iniciada exitosamente');
                                    isScanning = true;
                                })
                                .catch(finalErr => {
                                    console.error('Error final al iniciar cámara:', finalErr);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error de cámara',
                                        text: 'No se pudo acceder a ninguna cámara'
                                    });
                                    detenerEscaner();
                                });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Sin cámara',
                                text: 'No se detectó ninguna cámara disponible'
                            });
                            detenerEscaner();
                        }
                    }).catch(err => {
                        console.error('Error al obtener lista de cámaras:', err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo acceder a las cámaras del dispositivo'
                        });
                        detenerEscaner();
                    });
                });
        });
}

   // Busca la función procesarQRLeido completa y reemplázala por esta:
    function procesarQRLeido(textoQR) {
        console.log('=== DEBUG QR COMPLETO ===');
        console.log('Texto QR original:', textoQR);
        
        // Detener el escáner primero
        detenerEscaner();
        
        // Hacer split del contenido del QR
        const datos = textoQR.split('|');
        console.log('Datos separados:', datos);
        console.log('Cantidad de campos:', datos.length);
        
        // FORZAR los valores directamente según tu ejemplo
        let serie, numero, total;
        
        if (datos.length === 8) {
            // Tu ejemplo: 20454562349|03|F001-2|0|202.00|2025-04-26|06|15603903444
            console.log('=== PROCESANDO QR DE 8 CAMPOS ===');
            
            // Campo 2: F001-2
            const serieNumero = datos[2];
            console.log('Campo serie-numero:', serieNumero);
            
            if (serieNumero.includes('-')) {
                const partes = serieNumero.split('-');
                serie = partes[0]; // F001
                numero = partes[1]; // 2
            } else {
                serie = serieNumero.substring(0, 4);
                numero = serieNumero.substring(4);
            }
            
            // Campo 4: 202.00
            total = datos[4];
            
            console.log('Serie extraída:', serie);
            console.log('Número extraído:', numero);
            console.log('Total extraído:', total);
            
            // FORZAR valores directamente en los campos
            setTimeout(() => {
                $('#serie').val(serie);
                $('#numero').val(numero);
                
                // Forzar el total
                const totalNum = parseFloat(total);
                if (!isNaN(totalNum)) {
                    const moneda = $('#moneda').val() === '1' ? 'S/ ' : '$ ';
                    $('#lbl_suma_pedido').text(moneda + totalNum.toFixed(2));
                }
                
                // Otros campos
                $('#num_doc').val(datos[0]); // RUC
                $('#fecha').val(datos[5]); // Fecha
                $('#fechaVen').val(datos[5]); // Fecha vencimiento
                
                // Tipo documento - Mapeo según códigos SUNAT
                if (datos[1] === '01') {
                    $('#tipo_doc').val('2'); // FACTURA
                } else {
                    $('#tipo_doc').val('12'); // NOTA DE COMPRA (para todos los demás códigos)
                }
                
                console.log('=== VALORES ASIGNADOS ===');
                console.log('Serie en campo:', $('#serie').val());
                console.log('Número en campo:', $('#numero').val());
                console.log('Total en display:', $('#lbl_suma_pedido').text());
                
            }, 100); // Pequeño delay para asegurar que los elementos existan
            
        } else if (datos.length === 10) {
            // Estructura de 10 campos
            console.log('=== PROCESANDO QR DE 10 CAMPOS ===');
            
            serie = datos[2];
            numero = datos[3];
            total = datos[5];
            
            console.log('Serie extraída:', serie);
            console.log('Número extraído:', numero);
            console.log('Total extraído:', total);
            
            // FORZAR valores directamente
            setTimeout(() => {
                $('#serie').val(serie);
                $('#numero').val(numero);
                
                const totalNum = parseFloat(total);
                if (!isNaN(totalNum)) {
                    const moneda = $('#moneda').val() === '1' ? 'S/ ' : '$ ';
                    $('#lbl_suma_pedido').text(moneda + totalNum.toFixed(2));
                }
                
                $('#num_doc').val(datos[0]);
                $('#fecha').val(datos[6]);
                $('#fechaVen').val(datos[6]);
                
                if (datos[1] === '01') {
                    $('#tipo_doc').val('2');
                } else {
                    $('#tipo_doc').val('12');
                }
                
            }, 100);
            
        } else {
            console.error('Estructura QR no reconocida. Campos:', datos.length);
            Swal.fire({
                icon: 'error',
                title: 'QR no válido',
                text: 'El código QR no tiene el formato esperado'
            });
            return;
        }
        
        // Preguntar por moneda
        Swal.fire({
            title: '¿En qué moneda fue la compra?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Soles (PEN)',
            cancelButtonText: 'Dólares (USD)',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#moneda').val('1');
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                $('#moneda').val('2');
            }
            
            // Actualizar el display del total con la moneda correcta
            const totalActual = parseFloat(total);
            if (!isNaN(totalActual)) {
                const moneda = $('#moneda').val() === '1' ? 'S/ ' : '$ ';
                $('#lbl_suma_pedido').text(moneda + totalActual.toFixed(2));
            }
            
            Swal.fire({
                icon: 'success',
                title: '¡Datos cargados!',
                text: 'Revisa que todos los campos estén correctos',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }


    // 8. AGREGAR esta función para detener el escáner
    function detenerEscaner() {
        if (html5QrcodeScanner && isScanning) {
            html5QrcodeScanner.stop().then(() => {
                console.log('Escáner detenido');
            }).catch(err => {
                console.error('Error al detener escáner:', err);
            });
        }
        
        // Ocultar el reader
        document.getElementById('reader').style.display = 'none';
        document.getElementById('reader-controls').style.display = 'none';
        
        isScanning = false;
        html5QrcodeScanner = null;
    }

    // 9. AGREGAR los event listeners (colocar en el documento ready)
    $(document).ready(function() {
        // Event listener para el botón "Escanear QR"
        $('#btn-scan-qr').click(function() {
            iniciarEscanerQR();
        });
        
        // Event listener para el botón "Detener Escáner"
        $('#btn-stop-scan').click(function() {
            detenerEscaner();
        });
    });

  // JavaScript actualizado para manejar radio buttons personalizados
// Reemplazar la función anterior por esta

    $(document).ready(function() {
        // Función para actualizar el estilo de los radio buttons
        function actualizarEstiloRadioButtons() {
            $('input[name="tipo_registro"]').each(function() {
                var $radio = $(this);
                var $label = $radio.closest('label');
                
                if ($radio.is(':checked')) {
                    // Estilo para radio button seleccionado
                    $radio.css({
                        'background-color': '#0866c6',
                        'border-color': '#0866c6'
                    });
                    
                    // Estilo para label seleccionado
                    $label.css({
                        'border-color': '#0866c6',
                        'background-color': '#f8f9fc'
                    });
                    
                    // Agregar punto interior
                    if (!$radio.next('.radio-dot').length) {
                        $radio.after('<span class="radio-dot" style="position: absolute; width: 8px; height: 8px; background-color: #fff; border-radius: 50%; top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none;"></span>');
                    }
                } else {
                    // Estilo para radio button no seleccionado
                    $radio.css({
                        'background-color': '#fff',
                        'border-color': '#d1d3e2'
                    });
                    
                    // Estilo para label no seleccionado
                    $label.css({
                        'border-color': '#d1d3e2',
                        'background-color': ''
                    });
                    
                    // Remover punto interior
                    $radio.next('.radio-dot').remove();
                }
            });
        }
        
        // Manejar el cambio de tipo de registro
        $('input[name="tipo_registro"]').change(function() {
            var tipoSeleccionado = $(this).val();

            // Actualizar estilos de radio buttons
            actualizarEstiloRadioButtons();

            // ✅ Actualizar estado de botones de escaneo
            actualizarEstadoBotonesEscaneo();

            // ❌ Ya no ocultamos ni mostramos la sección
        });
        
        // Inicializar el estado al cargar la página
        actualizarEstiloRadioButtons();
        
        var tipoInicial = $('input[name="tipo_registro"]:checked').val();
        if (tipoInicial === 'pendiente') {
            $('#seccion_productos').hide();
        }
    });

    // Función para obtener el tipo de registro seleccionado

    function getTipoRegistro() {
        return $('input[name="tipo_registro"]:checked').val();
    }

    function actualizarEstadoBotonesEscaneo() {
        const tipoRegistro = getTipoRegistro()

        if (tipoRegistro === "completo") {
            $(".btn-escanear").prop("disabled", false).removeClass("disabled")
        } else {
            $(".btn-escanear").prop("disabled", true).addClass("disabled")
        }
    }
    
    // Detectar cambios en el tipo de registro
    $('input[name="tipo_registro"]').on('change', function() {
        actualizarEstadoBotonesEscaneo()
    })

    // Variables globales para cuotas
    let cuotasConfiguradas = [];

    // Función para mostrar/ocultar sección de cuotas según tipo de pago
    function manejarTipoPago() {
        const tipoPago = $('#tipo_pago').val();
        const seccionCuotas = $('#seccion-cuotas');
        
        if (tipoPago === '2') { // Crédito
            seccionCuotas.slideDown(300);
            actualizarMontoTotalCuotas();
        } else { // Contado
            seccionCuotas.slideUp(300);
            cuotasConfiguradas = [];
        }
    }

    // Reemplazar la función actualizarMontoTotalCuotas() existente por esta:
    function actualizarMontoTotalCuotas() {
        const textoSuma = $('#lbl_suma_pedido').text().trim();
        
        // 🔥 CORRECCIÓN: Asegurar que el símbolo de moneda sea correcto
        let montoParaCuotas = textoSuma;
        const monedaActual = $('#moneda').val();
        
        // Si el texto no tiene el símbolo correcto, corregirlo
        if (monedaActual === 'S' && !textoSuma.includes('S/')) {
            const numero = parseFloat(textoSuma.replace(/[^\d.,]/g, '').replace(',', '.'));
            montoParaCuotas = 'S/ ' + numero.toFixed(2);
        } else if (monedaActual === 'D' && !textoSuma.includes('$')) {
            const numero = parseFloat(textoSuma.replace(/[^\d.,]/g, '').replace(',', '.'));
            montoParaCuotas = '$ ' + numero.toFixed(2);
        }
        
        $('#monto-total-cuotas').val(montoParaCuotas);
        $('#monto-total-modal').val(montoParaCuotas);
        
        console.log('Monto total para cuotas actualizado:', montoParaCuotas);
    }

    // Función para generar cuotas automáticamente
    function generarCuotasAutomaticas() {
        const numeroCuotas = parseInt($('#numero-cuotas').val());
        const textoMontoTotal = $('#monto-total-cuotas').val();
        
        if (!numeroCuotas || numeroCuotas < 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Número de cuotas requerido',
                text: 'Por favor ingresa un número válido de cuotas'
            });
            return;
        }
        
        if (!textoMontoTotal || textoMontoTotal === 'S/ 0.00' || textoMontoTotal === '$ 0.00') {
            Swal.fire({
                icon: 'warning',
                title: 'Sin monto total',
                text: 'Primero debe tener productos agregados para calcular el total'
            });
            return;
        }
        
       // Extraer el monto numérico directamente desde el valor de SUMA
        const montoNumerico = parseFloat(textoMontoTotal.replace(/[^\d.,]/g, '').replace(',', '.'));
        const montoPorCuota = (montoNumerico / numeroCuotas).toFixed(2);
        const fechaBase = new Date($('#fecha').val() || new Date());
        
        // Limpiar cuotas anteriores
        cuotasConfiguradas = [];
        
        // Generar cuotas
        for (let i = 1; i <= numeroCuotas; i++) {
            const fechaCuota = new Date(fechaBase);
            fechaCuota.setMonth(fechaCuota.getMonth() + i); // Agregar i meses
            
            cuotasConfiguradas.push({
                numero: i,
                monto: parseFloat(montoPorCuota),
                fecha: fechaCuota.toISOString().split('T')[0],
                estado: 'Pendiente'
            });
        }
        
        // Mostrar el botón para configurar
        $('#btn-configurar-cuotas').show();
        
        Swal.fire({
            icon: 'success',
            title: '¡Cuotas generadas!',
            text: `Se generaron ${numeroCuotas} cuotas de ${textoMontoTotal.substring(0, 2)} ${montoPorCuota} cada una`,
            timer: 2000,
            showConfirmButton: false
        });

        sincronizarFechaVencimiento();
    }

    // Función para mostrar modal de cuotas
    function mostrarModalCuotas() {
        if (cuotasConfiguradas.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Sin cuotas',
                text: 'Primero genera las cuotas automáticamente'
            });
            return;
        }
        
        // Actualizar campos del modal
        $('#fecha-base-cuotas').val($('#fecha').val());
        actualizarTablaCuotas();
        $('#modal-cuotas').modal('show');
    }

    // Función para actualizar la tabla de cuotas en el modal
    function actualizarTablaCuotas() {
        const tbody = $('#tabla-cuotas-body');
        tbody.empty();
        
        let totalCuotas = 0;
        const moneda = $('#monto-total-cuotas').val().substring(0, 2);
        
        cuotasConfiguradas.forEach((cuota, index) => {
            totalCuotas += cuota.monto;
            
            const fila = `
                <tr>
                    <td class="text-center font-weight-bold">${cuota.numero}</td>
                    <td>
                        <input type="number" 
                            class="form-control cuota-monto" 
                            data-index="${index}" 
                            value="${cuota.monto.toFixed(2)}" 
                            step="0.01" 
                            min="0.01">
                    </td>
                    <td>
                        <input type="date" 
                            class="form-control cuota-fecha" 
                            data-index="${index}" 
                            value="${cuota.fecha}">
                    </td>
                    <td>
                        <select class="form-control cuota-estado" data-index="${index}">
                            <option value="Pendiente" ${cuota.estado === 'Pendiente' ? 'selected' : ''}>Pendiente</option>
                            <option value="Pagado" ${cuota.estado === 'Pagado' ? 'selected' : ''}>Pagado</option>
                            <option value="Vencido" ${cuota.estado === 'Vencido' ? 'selected' : ''}>Vencido</option>
                        </select>
                    </td>
                </tr>
            `;
            tbody.append(fila);
        });
        
        // Actualizar total
        $('#total-cuotas-calculado').text(`${moneda} ${totalCuotas.toFixed(2)}`);
        
        // Actualizar resumen
        const resumen = `${cuotasConfiguradas.length} cuotas configuradas - Total: ${moneda} ${totalCuotas.toFixed(2)}`;
        $('#resumen-cuotas').text(resumen);
    }

    // Función para guardar cambios en las cuotas
    function guardarCuotas() {
        // Recopilar datos actualizados de la tabla
        $('.cuota-monto').each(function() {
            const index = $(this).data('index');
            cuotasConfiguradas[index].monto = parseFloat($(this).val());
        });
        
        $('.cuota-fecha').each(function() {
            const index = $(this).data('index');
            cuotasConfiguradas[index].fecha = $(this).val();
        });
        
        $('.cuota-estado').each(function() {
            const index = $(this).data('index');
            cuotasConfiguradas[index].estado = $(this).val();
        });
        
        // Validar que las cuotas sumen el total
        const totalCuotas = cuotasConfiguradas.reduce((sum, cuota) => sum + cuota.monto, 0);
        const montoOriginal = parseFloat($('#monto-total-cuotas').val().replace(/[^\d.,]/g, '').replace(',', '.'));
        
        // 🔥 CORRECCIÓN: Aplicar tolerancia de ±0.01 para redondeos
        const tolerancia = 0.01;
        if (Math.abs(totalCuotas - montoOriginal) > tolerancia) {
            Swal.fire({
                icon: 'warning',
                title: 'Error en totales',
                text: `Las cuotas suman ${totalCuotas.toFixed(2)} pero el total debe ser ${montoOriginal.toFixed(2)}`
            });
            return;
        }
        
        $('#modal-cuotas').modal('hide');
        
        Swal.fire({
            icon: 'success',
            title: '¡Cuotas guardadas!',
            text: `${cuotasConfiguradas.length} cuotas configuradas correctamente`,
            timer: 2000,
            showConfirmButton: false
        });

        sincronizarFechaVencimiento();
    }

    // Event listeners
    $(document).ready(function() {
        // Manejar cambio de tipo de pago
        $('#tipo_pago').change(function() {
            manejarTipoPago();
        });
        
        // Actualizar monto total cuando cambie la suma del pedido
        const observer = new MutationObserver(function() {
            if ($('#tipo_pago').val() === '2') {
                actualizarMontoTotalCuotas();
            }
        });
        
        observer.observe(document.getElementById('lbl_suma_pedido'), {
            childList: true,
            subtree: true
        });
        
        // Botón generar cuotas
        $('#btn-generar-cuotas').click(function() {
            generarCuotasAutomaticas();
        });
        
        // Botón configurar cuotas
        $('#btn-configurar-cuotas').click(function() {
            mostrarModalCuotas();
        });
        
        // Botón guardar cuotas en modal
        $('#btn-guardar-cuotas').click(function() {
            guardarCuotas();
        });
        
        // Actualizar totales cuando cambien los montos en el modal
        $(document).on('input', '.cuota-monto', function() {
            let totalCuotas = 0;
            $('.cuota-monto').each(function() {
                totalCuotas += parseFloat($(this).val()) || 0;
            });
            
            const moneda = $('#monto-total-cuotas').val().substring(0, 2);
            $('#total-cuotas-calculado').text(`${moneda} ${totalCuotas.toFixed(2)}`);
            
            const resumen = `${cuotasConfiguradas.length} cuotas configuradas - Total: ${moneda} ${totalCuotas.toFixed(2)}`;
            $('#resumen-cuotas').text(resumen);
        });
        
        // Inicializar estado
        manejarTipoPago();
        
        // ===== AGREGAR ESTOS EVENT LISTENERS AQUÍ =====
        // Event listeners para sistema de conversión de monedas
        $('#moneda').on('change', function() {
            console.log('Cambio de moneda detectado:', $(this).val()); // Para debug
            manejarCambioMoneda();
        });

        $('#tasa-personalizada').on('input', function() {
            console.log('Cambio de tasa personalizada:', $(this).val()); // Para debug
            actualizarConversionPorTasaPersonalizada();
        });

        // Inicializar sistema de conversión
        console.log('Inicializando sistema de conversión...'); // Para debug
        obtenerTasaCambioActual();

        // Agregar dentro del $(document).ready() existente:
        $('#btn-guardar-compra').on('click', function() {
            guardarCompras();
        });

        // Busca el $(document).ready() existente y agrega estos event listeners:

        // Event listeners para IGV (BUSCAR Y REEMPLAZAR)
        $('#aplicar_igv').change(function() {
            manejarCambioIGV();
        });

        $('#precios_incluyen_igv').change(function() {
            if ($('#aplicar_igv').is(':checked')) {
                calcularIGVSobreTotal(); // Solo recalcular, no modificar precios individuales
            }
        });

    });

    // AGREGAR este event listener en el $(document).ready() existente:

    // REEMPLAZAR el event listener existente para eliminar productos por este:
    $(document).on('click', '.btn-eliminar-producto', function() {
        const $fila = $(this).closest('tr');
        const nombreProducto = $fila.find('td:eq(1)').text();
        const productoIndex = parseInt($fila.data('producto-index'));
        
        Swal.fire({
            title: '¿Eliminar producto?',
            text: `¿Está seguro de eliminar "${nombreProducto}" de la lista?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Eliminar de la tabla
                $fila.remove();
                
                // Eliminar del array de productos
                productosEnTabla = productosEnTabla.filter(p => p.index !== productoIndex);
                
                // Renumerar las filas
                $('#productos-lista tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
                
                // Actualizar total
                actualizarTotalPedido();
                
                // Actualizar estado de botones de escaneo
                actualizarEstadoBotonesEscaneo();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Producto eliminado',
                    text: 'El producto ha sido eliminado correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });

    // Función para obtener las cuotas configuradas (para usar en el guardado final)
    function obtenerCuotasParaGuardar() {
        return cuotasConfiguradas.map(cuota => ({
            monto: cuota.monto,
            fecha: cuota.fecha,
            estado: cuota.estado
            // id_compra se agregará en el backend
        }));
    }

    // Reemplazar la función guardarCompras() existente por esta:
    function guardarCompras() {
        // Validar que hay productos
        if ($('#productos-lista tr').length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Sin productos',
                text: 'Debe agregar al menos un producto'
            });
            return;
        }

        // Validar campos obligatorios
        if (!validarCamposObligatorios()) {
            return;
        }

        // Recopilar datos
        const datosCompra = recopilarDatosCompra();
        
        // Mostrar confirmación
        Swal.fire({
            title: '¿Guardar compra?',
            text: 'Se guardará la compra con todos los datos ingresados',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                enviarDatosCompra(datosCompra);
            }
        });
    }

    function validarCamposObligatorios() {
        const campos = [
            { id: '#num_doc', nombre: 'Documento del proveedor' },
            { id: '#nom_cli', nombre: 'Nombre del proveedor' },
            { id: '#dir_cli', nombre: 'Dirección' },
            { id: '#serie', nombre: 'Serie' },
            { id: '#numero', nombre: 'Número' }
        ];

        for (let campo of campos) {
            if (!$(campo.id).val().trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo requerido',
                    text: `El campo "${campo.nombre}" es obligatorio`
                });
                $(campo.id).focus();
                return false;
            }
        }

        // Validar tasa de cambio si es en dólares
        if ($('#moneda').val() === 'D') {
            const tasa = obtenerTasaEfectiva();
            if (tasa <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tasa de cambio requerida',
                    text: 'Debe configurar una tasa de cambio válida para compras en dólares'
                });
                return false;
            }
        }

        // Validar cuotas si es crédito
        if ($('#tipo_pago').val() === '2' && cuotasConfiguradas.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Cuotas requeridas',
                text: 'Debe configurar las cuotas para pagos a crédito'
            });
            return false;
        }

        // Validar productos únicos si es registro completo
        if (!validarProductosUnicos()) {
            return false;
        }

        return true;

    }

    // Busca la función recopilarDatosCompra() y reemplázala completamente:
    function recopilarDatosCompra() {
        const datos = {
            id_tido: $('#tipo_doc').val(),
            id_tipo_pago: $('#tipo_pago').val(),
            num_doc: $('#num_doc').val().trim(),
            nom_cli: $('#nom_cli').val().trim(),
            dir_cli: $('#dir_cli').val().trim(),
            serie: $('#serie').val().trim(),
            numero: $('#numero').val().trim(),
            fecha_emision: $('#fecha').val(),
            fecha_vencimiento: $('#fechaVen').val(),
            moneda: $('#moneda').val(),
            observaciones: $('#observaciones').val().trim(),
            tipo_registro: getTipoRegistro(),
            tiene_igv: $('#aplicar_igv').is(':checked') ? 'S' : 'N',
            total_igv: window.totalIGVCalculado || 0,
            productos: [],
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        // Extraer total desde la interfaz
        const totalText = $('#lbl_suma_pedido').text().trim();
        datos.total = parseFloat(totalText.replace(/[^\d.,]/g, '').replace(',', '.'));

        // 🔥 CORRECCIÓN CRÍTICA: Enviar datos según la moneda actual
        if (datos.moneda === 'D') {
            // Para dólares: enviar tasa y total en soles
            datos.tasa_cambio = obtenerTasaEfectiva();
            datos.total_soles = preciosOriginalesSoles.total || (datos.total * datos.tasa_cambio);
            
            // Productos: enviar precios en dólares pero con referencia en soles
            productosEnTabla.forEach(producto => {
                // Calcular precio en dólares para envío
                const precioEnDolares = (producto.precio_soles / datos.tasa_cambio);
                const subtotalEnDolares = (producto.subtotal_soles / datos.tasa_cambio);
                
                datos.productos.push({
                    id_producto: producto.id_producto,
                    cantidad: producto.cantidad,
                    precio: precioEnDolares.toFixed(2), // Precio en dólares
                    subtotal: subtotalEnDolares.toFixed(2), // Subtotal en dólares
                    precio_soles: producto.precio_soles // Precio en soles para BD
                });
            });
        } else {
            // Para soles: envío normal
            datos.total_soles = datos.total;
            
            productosEnTabla.forEach(producto => {
                datos.productos.push({
                    id_producto: producto.id_producto,
                    cantidad: producto.cantidad,
                    precio: producto.precio_soles,
                    subtotal: producto.subtotal_soles
                });
            });
        }

        // Agregar cuotas si es crédito
        if (datos.id_tipo_pago == 2 && cuotasConfiguradas.length > 0) {
            datos.cuotas = cuotasConfiguradas.map(cuota => ({
                monto: cuota.montoOriginalSoles || cuota.monto,
                fecha: cuota.fecha,
                estado: cuota.estado === 'Pendiente' ? 'P' : 'C'
            }));
        }

        // Agregar códigos únicos si corresponde
        if (datos.tipo_registro === 'completo') {
            datos.codigos_unicos = obtenerCodigosUnicosEscaneados();
        }

        console.log('📋 Datos recopilados para enviar:', datos);
        return datos;
    }

    function enviarDatosCompra(datos) {
        // Mostrar loading
        Swal.fire({
            title: 'Guardando compra...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '{{ route("admin.compras.store") }}',
            method: 'POST',
            data: datos,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Compra guardada!',
                        text: response.message,
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        // Redirigir o limpiar formulario
                        window.location.href = '{{ route("admin.compras.index") }}';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                let mensaje = 'Error al guardar la compra';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    mensaje = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: mensaje
                });
            }
        });
    }

    // Busca esta función y reemplázala:
    function obtenerCodigosUnicosEscaneados() {
        const codigosParaEnviar = [];
        
        // Recorrer productos en tabla para obtener códigos escaneados
        productosEnTabla.forEach(producto => {
            if (producto.tipo_control === 'UNICO' && codigosEscaneados.length > 0) {
                // Tomar códigos según la cantidad del producto
                const codigosProducto = codigosEscaneados.slice(0, producto.cantidad);
                
                codigosProducto.forEach(codigo => {
                    codigosParaEnviar.push({
                        id_producto: producto.id_producto,
                        codigo: codigo
                    });
                });
                
                // Remover códigos ya asignados
                codigosEscaneados.splice(0, producto.cantidad);
            }
        });
        
        console.log('🔍 Códigos únicos para enviar:', codigosParaEnviar);
        return codigosParaEnviar;
    }

     // Función para buscar documento en RENIEC
    function buscarDocumentoReniec() {
        const documento = $('#num_doc').val().trim();
        const btnBuscar = $('#btn-buscar-doc');
        const inputNombre = $('#nom_cli');

        console.log('[🔍 buscarDocumentoReniec] Documento ingresado:', documento);

        // Validar que el documento no esté vacío
        if (!documento) {
            console.warn('[⚠️ Validación] Documento vacío');
            mostrarAlerta('Por favor, ingrese un número de documento', 'warning');
            return;
        }

        // Validar longitud del documento
        if (documento.length !== 8 && documento.length !== 11) {
            console.warn('[⚠️ Validación] Longitud inválida:', documento.length);
            mostrarAlerta('El documento debe tener 8 dígitos (DNI) o 11 dígitos (RUC)', 'warning');
            return;
        }

        // Validar que solo contenga números
        if (!/^\d+$/.test(documento)) {
            console.warn('[⚠️ Validación] Documento contiene caracteres no numéricos');
            mostrarAlerta('El documento solo debe contener números', 'warning');
            return;
        }

        // Deshabilitar botón y mostrar loading
        btnBuscar.prop('disabled', true);
        btnBuscar.html('<i class="fa fa-spinner fa-spin"></i>');
        inputNombre.val('Buscando...');

        console.log('[📡 AJAX] Enviando solicitud a RENIEC...');

        // Realizar petición AJAX
        $.ajax({
            url: '{{ route("admin.reniec.buscar-documento") }}',
            type: 'POST',
            data: {
                documento: documento,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
                console.log('[✅ AJAX Success] Respuesta recibida:', response);

                if (response.success) {
                    inputNombre.val(response.data.nombre);
                    mostrarAlerta('Documento encontrado correctamente', 'success');
                } else {
                    console.warn('[❌ RENIEC] Documento no encontrado:', response.message);
                    inputNombre.val('');
                    mostrarAlerta(response.message || 'No se encontraron datos', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('[🛑 AJAX Error]', {
                    xhr: xhr,
                    status: status,
                    error: error
                });

                inputNombre.val('');
                let mensaje = 'Error al buscar el documento';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    mensaje = xhr.responseJSON.message;
                }

                mostrarAlerta(mensaje, 'error');
            },
            complete: function() {
                console.log('[✅ AJAX Complete] Petición finalizada');
                // Restaurar botón
                btnBuscar.prop('disabled', false);
                btnBuscar.html('<i class="fa fa-search"></i>');
            }
        });
    }

    // Función para mostrar alertas
    function mostrarAlerta(mensaje, tipo) {
        // Usar SweetAlert2 si está disponible
        if (typeof Swal !== 'undefined') {
            const tipoSwal = {
                'success': 'success',
                'error': 'error',
                'warning': 'warning',
                'info': 'info'
            };
            
            Swal.fire({
                text: mensaje,
                icon: tipoSwal[tipo] || 'info',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        } else {
            // Fallback a alert nativo
            alert(mensaje);
        }
    }

    // Función para limpiar campos
    function limpiarCamposDocumento() {
        $('#num_doc').val('');
        $('#nom_cli').val('');
    }

    // Función para validar entrada solo números
    function validarSoloNumeros(input) {
        $(input).on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    // Función para inicializar eventos
    function inicializarBusquedaReniec() {
        // Evento click del botón buscar
        $('#btn-buscar-doc').on('click', function(e) {
            e.preventDefault();
            buscarDocumentoReniec();
        });
        
        // Evento Enter en el campo documento
        $('#num_doc').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                buscarDocumentoReniec();
            }
        });
        
        // Validar solo números en el campo documento
        validarSoloNumeros('#num_doc');
        
        // Limpiar nombre al cambiar documento
        $('#num_doc').on('input', function() {
            if ($(this).val().length === 0) {
                $('#nom_cli').val('');
            }
        });
    }

    // Inicializar cuando el documento esté listo
    $(document).ready(function() {
        inicializarBusquedaReniec();
    });

    // Contador de caracteres para observaciones
    $(document).ready(function() {
        $('#observaciones').on('input', function() {
            const caracteresActuales = $(this).val().length;
            $('#contador-caracteres').text(caracteresActuales);
            
            // Cambiar color si se acerca al límite
            if (caracteresActuales > 450) {
                $('#contador-caracteres').css('color', '#e74c3c');
            } else if (caracteresActuales > 400) {
                $('#contador-caracteres').css('color', '#f39c12');
            } else {
                $('#contador-caracteres').css('color', '#6c757d');
            }
        });
    });

    // AGREGA estas funciones al final de tu archivo JavaScript, antes del último });

    // Variables globales para el sistema de búsqueda
    let productoSeleccionado = null
    let codigosEscaneados = []
    let cantidadEsperada = 0
  

    // Validación en tiempo real para tasa personalizada
    $('#tasa-personalizada').on('input', function() {
        const valor = $(this).val();
        const $input = $(this);
        
        // Remover clases de validación anteriores
        $input.removeClass('is-valid is-invalid');
        
        if (valor === '') {
            // Campo vacío - válido (usará tasa API)
            $input.addClass('is-valid');
            return;
        }
        
        const numero = parseFloat(valor);
        
        if (isNaN(numero) || numero <= 0) {
            // Valor inválido
            $input.addClass('is-invalid');
        } else if (numero < 1 || numero > 10) {
            // Valor sospechoso pero técnicamente válido
            $input.addClass('is-invalid');
            
            // Mostrar advertencia
            if (numero < 1) {
                console.warn('⚠️ Tasa muy baja:', numero);
            } else {
                console.warn('⚠️ Tasa muy alta:', numero);
            }
        } else {
            // Valor válido
            $input.addClass('is-valid');
        }
    });

    // Inicializar autocompletado
    $(document).ready(() => {
        // Configurar autocompletado
        $("#descripcionBuscar").on("input", function () {
            const termino = $(this).val()

            if (termino.length < 2) {
            $("#lista-autocompletado").hide()
            return
            }

            $.ajax({
            url: "/admin/compras/productos/buscar",
            method: "GET",
            data: { q: termino },
            success: (productos) => {
                mostrarResultadosAutocompletado(productos)
            },
            error: () => {
                $("#lista-autocompletado").hide()
            },
            })
        })

        // Ocultar autocompletado al hacer clic fuera
        $(document).on("click", (e) => {
            if (!$(e.target).closest("#descripcionBuscar, #lista-autocompletado").length) {
            $("#lista-autocompletado").hide()
            }
        })

        // Manejar selección de producto
        $(document).on("click", ".item-autocompletado", function () {
            const producto = $(this).data("producto")
            seleccionarProducto(producto)
        })

        // Manejar cambio de precio
        $("#selectPrecios").on("change", function () {
            const precioSeleccionado = $(this).val()
            $("#precio").val(precioSeleccionado)
        })

        // Manejar botón agregar
        $("#btn-agregar").on("click", () => {
            agregarProductoATabla()
        })

        // Manejar botones de escaneo
        $(document).on("click", ".btn-escanear", function () {
            const fila = $(this).closest("tr")
            const nombreProducto = fila.find("td:eq(1)").text()
            const cantidad = Number.parseInt(fila.find("td:eq(2)").text())

            abrirModalEscaneo(nombreProducto, cantidad)
        })

        // Limpiar input al abrir el modal
        $("#modal-escaneo-codigos").on('shown.bs.modal', function() {
            $("#codigo-escaneado").val("").focus()
        })

        // Prevenir que se cierre el modal accidentalmente con Enter
        $("#codigo-escaneado").on("keydown", function(e) {
            if (e.which === 13) {
                e.preventDefault() // Prevenir submit del form
            }
        })

        // Manejar escaneo de códigos con Enter (keydown más confiable y delegado para evitar problemas de carga dinámica)
        $(document).on("keydown", "#codigo-escaneado", function (e) {
            if (e.key === "Enter" || e.which === 13) {
                e.preventDefault() // Prevenir submit del formulario si está dentro de uno
                const codigo = $(this).val().trim()
                if (codigo) {
                    agregarCodigoEscaneado(codigo)
                    $(this).val("") // Limpiar campo
                    $(this).focus() // Volver a enfocar para seguir escaneando
                }
            }
        })

        // Manejar botón agregar código manual
        $("#btn-agregar-codigo-manual").on("click", function() {
            const codigo = $("#codigo-escaneado").val().trim()
            if (codigo) {
                agregarCodigoEscaneado(codigo)
                $("#codigo-escaneado").val("")
                $("#codigo-escaneado").focus() // Mantener el foco para seguir agregando
            } else {
                alert("Por favor ingrese un código")
            }
        })

        document.addEventListener("DOMContentLoaded", function () {
            configurarEscaneoConEnter()
        })

        function configurarEscaneoConEnter() {
            const input = document.getElementById("codigo-escaneado")
            if (!input) {
                console.warn("[configurarEscaneoConEnter] Input #codigo-escaneado NO encontrado")
                return
            }

            console.log("[configurarEscaneoConEnter] Event listener agregado al input")

            input.addEventListener("keydown", function (e) {
                console.log("[Evento keydown] Tecla presionada:", e.key, " | Código:", e.which)

                if (e.key === "Enter" || e.which === 13) {
                    e.preventDefault()
                    const codigo = input.value.trim()
                    console.log("[Enter detectado] Valor del código:", codigo)

                    if (codigo) {
                        agregarCodigoEscaneado(codigo)
                        input.value = ""
                        input.focus()
                    } else {
                        console.warn("[Error] Código vacío")
                        alert("Por favor ingrese un código")
                    }
                }
            })
        }

        // Manejar botón guardar escaneados
        $("#btn-guardar-escaneados").on("click", () => {
            guardarCodigosEscaneados()
        })

        // Llamar esta función cuando cambie el tipo de registro
        $('input[name="tipo_registro"]').change(() => {
            // ... código existente ...

            // Agregar al final:
            actualizarEstadoBotonesEscaneo()
        })
    })

    // Mostrar resultados del autocompletado
    function mostrarResultadosAutocompletado(productos) {
    const lista = $("#lista-autocompletado")
    lista.empty()

    if (productos.length === 0) {
        lista.hide()
        return
    }

    productos.forEach((producto) => {
        const item = $(`
                <li class="list-group-item item-autocompletado" style="cursor: pointer; padding: 10px; border-bottom: 1px solid #eee;">
                    <div style="font-weight: bold; color: #0866c6;">${producto.codigo}</div>
                    <div>${producto.nombre}</div>
                    <div style="font-size: 12px; color: #666;">P.Venta S/. ${producto.precio} | Stock: ${producto.stock}</div>
                </li>
            `)

        item.data("producto", producto)
        lista.append(item)
    })

    lista.show()
    }

    // Seleccionar producto del autocompletado
    function seleccionarProducto(producto) {
    productoSeleccionado = producto

    // Llenar campos
    $("#descripcionBuscar").val(producto.codigo + " | " + producto.nombre)
    $("#descripcionProducto").val(producto.nombre)
    $("#stockActual").val(producto.stock)

    // Ocultar autocompletado
    $("#lista-autocompletado").hide()

    // Cargar precios
    cargarPreciosProducto(producto.id)

    // Mostrar sección de precios
    $("#seccion-precios").show()
    }

    // Cargar precios del producto
    function cargarPreciosProducto(idProducto) {
        $.ajax({
            url: `/admin/compras/productos/${idProducto}/precios`,
            method: "GET",
            success: (response) => {
            if (response.success) {
                const select = $("#selectPrecios")
                select.empty().append('<option value="">Seleccione un precio</option>')

                Object.keys(response.precios).forEach((key) => {
                const precio = response.precios[key]
                select.append(`
                                <option value="${precio.precio}">
                                    ${precio.nombre} | S/. ${Number.parseFloat(precio.precio).toFixed(2)}
                                </option>
                            `)
                })
            }
            },
            error: () => {
            alert("Error al cargar los precios del producto")
            },
        })
    }

    // REEMPLAZAR la función agregarProductoATabla() existente por esta:
    function agregarProductoATabla() {
        if (!productoSeleccionado) {
            alert("Debe seleccionar un producto")
            return
        }

        const cantidad = $("#cantidad").val()
        const precioSoles = $("#precio").val() // Este precio SIEMPRE viene en soles desde la BD

        if (!cantidad || cantidad <= 0) {
            alert("Debe ingresar una cantidad válida")
            return
        }

        if (!precioSoles || precioSoles <= 0) {
            alert("Debe seleccionar un precio")
            return
        }

        // 🔥 SIEMPRE agregar en SOLES primero (precio base)
        const parcialSoles = (parseFloat(cantidad) * parseFloat(precioSoles)).toFixed(2);
        const precioSolesFormateado = parseFloat(precioSoles).toFixed(2);
        
        const tipoRegistro = getTipoRegistro()
        
        // Determinar si mostrar botón escanear
        let columnaEscanear = ""
        if (tipoRegistro === "completo" && productoSeleccionado.tipo_control === "UNICO") {
            columnaEscanear = `<button type="button" class="btn btn-sm btn-warning btn-escanear">
                                    <i class="fa fa-qrcode"></i> Escanear
                                </button>`
        } else {
            columnaEscanear = '<span class="text-muted">-</span>'
        }

        const monedaActual = $('#moneda').val();
        let precioMostrar, parcialMostrar;
        
        if (monedaActual === 'D') {
            // Si estamos en modo dólares, convertir inmediatamente
            const tasa = obtenerTasaEfectiva();
            if (tasa > 0) {
                const precioDolares = (parseFloat(precioSoles) / tasa).toFixed(2);
                const parcialDolares = (parseFloat(parcialSoles) / tasa).toFixed(2);
                precioMostrar = '$ ' + precioDolares;
                parcialMostrar = '$ ' + parcialDolares;
            } else {
                precioMostrar = 'S/. ' + precioSolesFormateado;
                parcialMostrar = 'S/. ' + parcialSoles;
            }
        } else {
            // Modo soles normal
            precioMostrar = 'S/. ' + precioSolesFormateado;
            parcialMostrar = 'S/. ' + parcialSoles;
        }

        const numeroFila = $("#productos-lista tr").length;
        
        const fila = `
            <tr data-producto-index="${numeroFila}">
                <td>${numeroFila + 1}</td>
                <td>${productoSeleccionado.nombre}</td>
                <td>${cantidad}</td>
                <td>${precioMostrar}</td>
                <td>${parcialMostrar}</td>
                <td>${columnaEscanear}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger btn-eliminar-producto">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `

        $("#productos-lista").append(fila)

        // 🔥 GUARDAR DATOS COMPLETOS DEL PRODUCTO
        productosEnTabla.push({
            id_producto: productoSeleccionado.id,
            nombre: productoSeleccionado.nombre,
            cantidad: parseFloat(cantidad),
            precio_soles: parseFloat(precioSoles),
            subtotal_soles: parseFloat(parcialSoles),
            tipo_control: productoSeleccionado.tipo_control,
            index: numeroFila
        });

        // Guardar precios originales en soles para conversión de moneda
        preciosOriginalesSoles[`fila_${numeroFila}_precio`] = parseFloat(precioSoles);
        preciosOriginalesSoles[`fila_${numeroFila}_parcial`] = parseFloat(parcialSoles);

        // Actualizar estado de botones de escaneo
        actualizarEstadoBotonesEscaneo()

        // Limpiar formulario
        limpiarFormularioProducto()

        // Actualizar total
        actualizarTotalPedido()
    }

    // Limpiar formulario de producto
    function limpiarFormularioProducto() {
        $("#descripcionBuscar").val("")
        $("#descripcionProducto").val("")
        $("#stockActual").val("")
        $("#cantidad").val("")
        $("#precio").val("")
        $("#selectPrecios").val("")
        $("#seccion-precios").hide()
        productoSeleccionado = null
    }

    // Abrir modal de escaneo
    function abrirModalEscaneo(nombreProducto, cantidad) {
        $("#producto-escaneo").val(nombreProducto)
        $("#cantidad-esperada").val(cantidad)
        cantidadEsperada = cantidad
        codigosEscaneados = []

        actualizarListaCodigosEscaneados()
        actualizarEstadoEscaneo()

        $("#modal-escaneo-codigos").modal("show")

        setTimeout(() => {
            $("#codigo-escaneado").focus()
        }, 500)
    }

    // Agregar código escaneado
    // Agregar código escaneado
    function agregarCodigoEscaneado(codigo) {
        // Validar que el código no esté vacío
        if (!codigo || codigo.trim() === "") {
            alert("El código no puede estar vacío")
            return
        }

        // Limpiar el código (remover espacios extra)
        codigo = codigo.trim()

        // Verificar si el código ya existe
        if (codigosEscaneados.includes(codigo)) {
            alert("Este código ya ha sido escaneado")
            return
        }

        // Verificar si ya se alcanzó el límite
        if (codigosEscaneados.length >= cantidadEsperada) {
            alert("Ya se han escaneado todos los códigos necesarios")
            return
        }

        // Agregar el código
        codigosEscaneados.push(codigo)

        // Reproducir sonido de escáner
        reproducirSonidoEscaner()

        // Actualizar interfaz
        actualizarListaCodigosEscaneados()
        actualizarEstadoEscaneo()

        // Mostrar mensaje de éxito breve
        console.log(`✅ Código agregado: ${codigo} (${codigosEscaneados.length}/${cantidadEsperada})`)
    }

    // Actualizar lista de códigos escaneados
    function actualizarListaCodigosEscaneados() {
    const lista = $("#lista-codigos-escaneados")

    if (codigosEscaneados.length === 0) {
        lista.html(`
                <div class="text-center text-muted" id="mensaje-vacio">
                    <i class="fa fa-barcode" style="font-size: 24px; margin-bottom: 10px;"></i><br>
                    No hay códigos escaneados
                </div>
            `)
        return
    }

    let html = ""
    codigosEscaneados.forEach((codigo, index) => {
        html += `
                <div style="border-bottom: 1px solid #333; padding: 8px 0; margin-bottom: 5px;">
                    ${codigo}
                </div>
            `
    })

    lista.html(html)
    }

    // Actualizar estado del escaneo
    function actualizarEstadoEscaneo() {
        const contador = $("#contador-codigos")
        const estado = $("#estado-escaneo")
        const btnGuardar = $("#btn-guardar-escaneados")

        contador.text(`${codigosEscaneados.length} / ${cantidadEsperada}`)

        if (codigosEscaneados.length === 0) {
            estado.removeClass("alert-success alert-warning").addClass("alert-warning")
            estado.html('<i class="fa fa-clock"></i> Esperando códigos...')
            btnGuardar.prop("disabled", true)
        } else if (codigosEscaneados.length < cantidadEsperada) {
            estado.removeClass("alert-success alert-warning").addClass("alert-warning")
            estado.html(`<i class="fa fa-hourglass-half"></i> Faltan ${cantidadEsperada - codigosEscaneados.length} códigos`)
            btnGuardar.prop("disabled", false)
        } else {
            estado.removeClass("alert-warning").addClass("alert-success")
            estado.html('<i class="fa fa-check"></i> ✔️ Todos los códigos escaneados correctamente')
            btnGuardar.prop("disabled", false)
        }
    }

    // Reproducir sonido de escáner
    function reproducirSonidoEscaner() {
        // Crear sonido usando Web Audio API
        const audioContext = new (window.AudioContext || window.webkitAudioContext)()
        const oscillator = audioContext.createOscillator()
        const gainNode = audioContext.createGain()

        oscillator.connect(gainNode)
        gainNode.connect(audioContext.destination)

        oscillator.frequency.value = 800
        oscillator.type = "square"

        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime)
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1)

        oscillator.start(audioContext.currentTime)
        oscillator.stop(audioContext.currentTime + 0.1)
    }

    // Guardar códigos escaneados
    function guardarCodigosEscaneados() {
        if (codigosEscaneados.length === 0) {
            Swal.fire({ // MODIFICADO
                icon: 'warning', // MODIFICADO
                title: 'Sin códigos', // MODIFICADO
                text: 'No hay códigos para guardar' // MODIFICADO
            }) // MODIFICADO
            return
        }

        // Aquí puedes agregar la lógica para guardar los códigos en la base de datos
        console.log("Códigos escaneados:", codigosEscaneados)

        Swal.fire({ // MODIFICADO
            icon: 'success', // MODIFICADO
            title: '¡Códigos guardados!', // MODIFICADO
            text: `Se guardaron ${codigosEscaneados.length} códigos correctamente`, // MODIFICADO
            timer: 2000, // MODIFICADO
            showConfirmButton: false // MODIFICADO
        }) // MODIFICADO
        $("#modal-escaneo-codigos").modal("hide")
    }

    // REEMPLAZAR la función actualizarTotalPedido() por esta CORREGIDA:
    function actualizarTotalPedido() {
        let total = 0;
        let simboloMoneda = 'S/ ';

        // Determinar moneda actual
        const monedaActual = $('#moneda').val();
        if (monedaActual === 'D') {
            simboloMoneda = '$ ';
        }

        console.log('🧮 Calculando total del pedido...');

        $('#productos-lista tr').each(function (index) {
            const parcialText = $(this).find('td:eq(4)').text().trim();
            console.log(`Fila ${index}: Texto original: "${parcialText}"`);
            
            // Método más preciso para extraer el número
            let parcial = 0;
            
            if (parcialText.includes('$')) {
                // Es dólar: $ 13.96
                parcial = parseFloat(parcialText.replace('$', '').trim());
            } else if (parcialText.includes('S/.')) {
                // Es sol: S/. 114.61
                parcial = parseFloat(parcialText.replace('S/.', '').trim());
            } else {
                // Fallback: remover todo excepto números y punto decimal
                const numeroLimpio = parcialText.replace(/[^0-9.]/g, '');
                parcial = parseFloat(numeroLimpio);
            }
            
            console.log(`Fila ${index}: Valor extraído: ${parcial}`);
            
            if (!isNaN(parcial) && parcial > 0) {
                total += parcial;
            }
        });

        console.log(`💰 Total calculado: ${total}`);
        $('#lbl_suma_pedido').text(simboloMoneda + total.toFixed(2));
        
        // Actualizar precios originales si estamos en soles
        if (monedaActual === 'S') {
            preciosOriginalesSoles.total = total;
            console.log('💾 Total guardado en soles:', total);
        }
        
        // Actualizar monto de cuotas si está en modo crédito
        if ($('#tipo_pago').val() === '2') {
            actualizarMontoTotalCuotas();
        }

        // Al final de la función actualizarTotalPedido(), agregar:
        // Recalcular IGV si está activado
        if ($('#aplicar_igv').is(':checked')) {
            calcularIGVSobreTotal();
        }
    }
    // ===== SISTEMA DE CONVERSIÓN DE MONEDAS =====

    // Variables globales para conversión
    let tasaActualAPI = 0;
    let tasaPersonalizada = 0;
    let preciosOriginalesSoles = {}; // Almacenar precios originales en soles
    let monedaAnterior = 'S'; // Para detectar cambios

    // Función para guardar precios originales (solo cuando están en soles)
    function guardarPreciosOriginalesParaConversion() {
        // Solo guardar si estamos en soles Y no hay precios guardados
        const monedaActual = $('#moneda').val();
        if (monedaActual === 'S' && Object.keys(preciosOriginalesSoles).length === 0) {
            console.log('💾 Guardando precios originales en soles...');
            
            $('#productos-lista tr').each(function(index) {
                const $fila = $(this);
                
                const precioText = $fila.find('td:eq(3)').text();
                const precio = parseFloat(precioText.replace(/[^\d.,]/g, '').replace(',', '.'));
                preciosOriginalesSoles[`fila_${index}_precio`] = precio;
                
                const parcialText = $fila.find('td:eq(4)').text();
                const parcial = parseFloat(parcialText.replace(/[^\d.,]/g, '').replace(',', '.'));
                preciosOriginalesSoles[`fila_${index}_parcial`] = parcial;
            });
            
            // Guardar total
            const totalText = $('#lbl_suma_pedido').text();
            const total = parseFloat(totalText.replace(/[^\d.,]/g, '').replace(',', '.'));
            preciosOriginalesSoles.total = total;
            
            console.log('💾 Precios originales guardados:', preciosOriginalesSoles);
        }
    }

    // Función para aplicar conversión a dólares (CORREGIDA)
    function aplicarConversionADolares() {
        const tasa = obtenerTasaEfectiva();
        
        if (tasa <= 0) {
            console.error('❌ Tasa inválida:', tasa);
            Swal.fire({
                icon: 'error',
                title: 'Error de tasa',
                text: 'No se pudo obtener la tasa de cambio'
            });
            return;
        }
        
        console.log('💱 Convirtiendo a dólares con tasa:', tasa);
        console.log('💱 Fórmula: dolares = soles / tasa');
        
        // Guardar precios originales si es necesario
        guardarPreciosOriginalesParaConversion();
        
        // Convertir cada fila de productos
        $('#productos-lista tr').each(function(index) {
            const $fila = $(this);
            
            // Obtener precios originales en soles
            let precioOriginalSoles, parcialOriginalSoles;
            
            if (preciosOriginalesSoles[`fila_${index}_precio`]) {
                // Usar precio guardado
                precioOriginalSoles = preciosOriginalesSoles[`fila_${index}_precio`];
                parcialOriginalSoles = preciosOriginalesSoles[`fila_${index}_parcial`];
            } else {
                // Obtener precio actual (debe estar en soles)
                const precioText = $fila.find('td:eq(3)').text();
                const parcialText = $fila.find('td:eq(4)').text();
                
                precioOriginalSoles = parseFloat(precioText.replace(/[^\d.,]/g, '').replace(',', '.'));
                parcialOriginalSoles = parseFloat(parcialText.replace(/[^\d.,]/g, '').replace(',', '.'));
                
                // Guardar para futuras conversiones
                preciosOriginalesSoles[`fila_${index}_precio`] = precioOriginalSoles;
                preciosOriginalesSoles[`fila_${index}_parcial`] = parcialOriginalSoles;
            }
            
            if (precioOriginalSoles && parcialOriginalSoles) {
                // FÓRMULA CORRECTA: dolares = soles / tasa
                const precioDolares = (precioOriginalSoles / tasa).toFixed(2);
                const parcialDolares = (parcialOriginalSoles / tasa).toFixed(2);
                
                console.log(`Fila ${index}: S/.${precioOriginalSoles} ÷ ${tasa} = $${precioDolares}`);
                
                $fila.find('td:eq(3)').text('$ ' + precioDolares);
                $fila.find('td:eq(4)').text('$ ' + parcialDolares);
            }
        });
        
        // Convertir total
        let totalOriginalSoles;
        if (preciosOriginalesSoles.total) {
            totalOriginalSoles = preciosOriginalesSoles.total;
        } else {
            const totalText = $('#lbl_suma_pedido').text();
            totalOriginalSoles = parseFloat(totalText.replace(/[^\d.,]/g, '').replace(',', '.'));
            preciosOriginalesSoles.total = totalOriginalSoles;
        }
        
        const totalDolares = (totalOriginalSoles / tasa).toFixed(2);
        console.log(`Total: S/.${totalOriginalSoles} ÷ ${tasa} = $${totalDolares}`);
        $('#lbl_suma_pedido').text('$ ' + totalDolares);
        
        // Actualizar cuotas si existen
        if (cuotasConfiguradas.length > 0) {
            convertirCuotasADolares(tasa);
        }
    }

    // Función para revertir a soles (CORREGIDA)
    function aplicarReversionASoles() {
        console.log('🔄 Revirtiendo a soles...');
        
        if (Object.keys(preciosOriginalesSoles).length === 0) {
            console.warn('⚠️ No hay precios originales guardados');
            return;
        }
        
        // Revertir cada fila
        $('#productos-lista tr').each(function(index) {
            const $fila = $(this);
            
            const precioOriginal = preciosOriginalesSoles[`fila_${index}_precio`];
            const parcialOriginal = preciosOriginalesSoles[`fila_${index}_parcial`];
            
            if (precioOriginal && parcialOriginal) {
                console.log(`Fila ${index}: Revirtiendo a S/.${precioOriginal}`);
                $fila.find('td:eq(3)').text('S/. ' + precioOriginal.toFixed(2));
                $fila.find('td:eq(4)').text('S/. ' + parcialOriginal.toFixed(2));
            }
        });
        
        // 🔥 CORRECCIÓN: Revertir total correctamente
        if (preciosOriginalesSoles.total) {
            console.log(`Total: Revirtiendo a S/.${preciosOriginalesSoles.total}`);
            $('#lbl_suma_pedido').text('S/ ' + preciosOriginalesSoles.total.toFixed(2));
        }
        
        // 🔥 CORRECCIÓN: Actualizar input de cuotas con símbolo correcto
        if ($('#tipo_pago').val() === '2') {
            const totalSoles = preciosOriginalesSoles.total || 0;
            $('#monto-total-cuotas').val('S/ ' + totalSoles.toFixed(2));
            $('#monto-total-modal').val('S/ ' + totalSoles.toFixed(2));
        }
        
        // Revertir cuotas si existen
        if (cuotasConfiguradas.length > 0) {
            revertirCuotasASoles();
        }
    }
    

    // Función para convertir cuotas a dólares
    function convertirCuotasADolares(tasa) {
        if (cuotasConfiguradas.length > 0) {
            cuotasConfiguradas.forEach(cuota => {
                if (!cuota.montoOriginalSoles) {
                    // Guardar el monto original en soles solo la primera vez
                    cuota.montoOriginalSoles = cuota.monto;
                }
                // Convertir desde el monto original en soles
                cuota.monto = parseFloat((cuota.montoOriginalSoles / tasa).toFixed(2));
            });
            
            actualizarMontoTotalCuotas();
            
            if ($('#modal-cuotas').hasClass('show')) {
                actualizarTablaCuotas();
            }
        }
    }

    // Función para obtener la tasa de cambio actual
    function obtenerTasaCambioActual() {
        $.ajax({
            url: '/admin/tipo-cambio/obtener',
            method: 'GET',
            success: function(response) {
                if (response.tipo_cambio) {
                    tasaActualAPI = parseFloat(response.tipo_cambio);
                    $('#tasa-actual').val('S/. ' + tasaActualAPI.toFixed(3));
                    console.log('Tasa de cambio obtenida:', tasaActualAPI);
                }
            },
            error: function() {
                console.error('Error al obtener tasa de cambio');
                tasaActualAPI = 3.60; // Tasa por defecto
                $('#tasa-actual').val('S/. ' + tasaActualAPI.toFixed(3) + ' (Por defecto)');
            }
        });
    }

    // Función para obtener la tasa efectiva (personalizada o API)
    function obtenerTasaEfectiva() {
        const personalizada = parseFloat($('#tasa-personalizada').val());
        return personalizada > 0 ? personalizada : tasaActualAPI;
    }

    // REEMPLAZAR la función manejarCambioMoneda() existente por esta:
    function manejarCambioMoneda() {
        const monedaSeleccionada = $('#moneda').val();
        
        console.log('🔄 Cambio de moneda detectado:', monedaAnterior, '→', monedaSeleccionada);
        
        if (monedaSeleccionada === 'D') {
            // Cambiar a dólares
            mostrarElementosConversion();
            
            if (tasaActualAPI === 0) {
                obtenerTasaCambioActual();
            }
            
            // Aplicar conversión a dólares
            setTimeout(() => {
                aplicarConversionADolares();
            }, 100);
            
        } else {
            // Cambiar a soles
            ocultarElementosConversion();
            
            // Aplicar reversión a soles
            setTimeout(() => {
                aplicarReversionASoles();
            }, 100);
        }
        
        monedaAnterior = monedaSeleccionada;
    }

    // Función para actualizar conversión cuando cambia la tasa personalizada
    function actualizarConversionPorTasaPersonalizada() {
        const monedaActual = $('#moneda').val();
        
        if (monedaActual === 'D') {
            actualizarEtiquetaConversion();
            
            // Reconvertir con la nueva tasa
            setTimeout(() => {
                convertirTodosLosPreciosADolares();
            }, 100);
        }
    }

    // Función para mostrar elementos de conversión
    function mostrarElementosConversion() {
        $('#etiqueta-conversion').slideDown(300);
        $('#seccion-tasa-cambio').slideDown(300);
        actualizarEtiquetaConversion();
    }

    // Función para ocultar elementos de conversión
    function ocultarElementosConversion() {
        $('#etiqueta-conversion').slideUp(300);
        $('#seccion-tasa-cambio').slideUp(300);
    }

    // Función para actualizar la etiqueta de conversión
    function actualizarEtiquetaConversion() {
        const tasa = obtenerTasaEfectiva();
        $('#tasa-mostrada').text('S/. ' + tasa.toFixed(3));
    }

    // Función para revertir cuotas a soles
    function revertirCuotasASoles() {
        if (cuotasConfiguradas.length > 0) {
            cuotasConfiguradas.forEach(cuota => {
                if (cuota.montoOriginalSoles) {
                    // Restaurar el monto original en soles
                    cuota.monto = cuota.montoOriginalSoles;
                }
            });
            
            actualizarMontoTotalCuotas();
            
            if ($('#modal-cuotas').hasClass('show')) {
                actualizarTablaCuotas();
            }
        }
    }

    // REEMPLAZAR el contenido de actualizarConversionPorTasaPersonalizada() por este:
    function actualizarConversionPorTasaPersonalizada() {
        const monedaActual = $('#moneda').val();
        
        if (monedaActual === 'D') {
            actualizarEtiquetaConversion();
            
            // Reconvertir con la nueva tasa
            setTimeout(() => {
                aplicarConversionADolares();
            }, 100);
        }
    }

        // ===== SISTEMA DE IGV =====
    let preciosOriginalesSinIGV = {}; // Para almacenar precios sin IGV
    let igvAplicado = false;

    // Función para manejar cambios en checkbox de IGV (NUEVA LÓGICA)
    function manejarCambioIGV() {
        const aplicarIGV = $('#aplicar_igv').is(':checked');
        const seccionIncluido = $('#seccion-igv-incluido');
        const seccionIGVTotal = $('#seccion-igv-total');
        
        if (aplicarIGV) {
            seccionIncluido.slideDown(300);
            seccionIGVTotal.slideDown(300);
            calcularIGVSobreTotal();
        } else {
            seccionIncluido.slideUp(300);
            seccionIGVTotal.slideUp(300);
            window.totalIGVCalculado = 0;
            // Los precios individuales NO se modifican
        }
    }

    // Función para validar productos únicos antes de guardar
    function validarProductosUnicos() {
        const tipoRegistro = getTipoRegistro();
        
        if (tipoRegistro !== 'completo') {
            return true; // No validar si no es registro completo
        }
        
        let productosIncompletos = [];
        
        productosEnTabla.forEach(producto => {
            if (producto.tipo_control === 'UNICO') {
                // Verificar si tiene códigos escaneados suficientes
                // Por ahora simulamos que está incompleto si no hay códigos
                if (!codigosEscaneados || codigosEscaneados.length < producto.cantidad) {
                    productosIncompletos.push(producto.nombre);
                }
            }
        });
        
        if (productosIncompletos.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Productos incompletos',
                html: `Los siguientes productos requieren escaneo de códigos únicos:<br><br>
                    <strong>${productosIncompletos.join('<br>')}</strong><br><br>
                    Complete el escaneo antes de guardar.`,
                confirmButtonText: 'Entendido'
            });
            return false;
        }
        
        return true;
    }

    // Función para sincronizar fecha de vencimiento con última cuota
    function sincronizarFechaVencimiento() {
        const tipoPago = $('#tipo_pago').val();
        
        if (tipoPago === '2' && cuotasConfiguradas.length > 0) {
            // Encontrar la fecha más tardía en las cuotas
            let fechaMasTardia = cuotasConfiguradas[0].fecha;
            
            cuotasConfiguradas.forEach(cuota => {
                if (cuota.fecha > fechaMasTardia) {
                    fechaMasTardia = cuota.fecha;
                }
            });
            
            // Actualizar fecha de vencimiento
            $('#fechaVen').val(fechaMasTardia);
            
            console.log('📅 Fecha de vencimiento sincronizada:', fechaMasTardia);
        }
    }

    // Nueva función para calcular IGV solo sobre el total
    function calcularIGVSobreTotal() {
        if (!$('#aplicar_igv').is(':checked')) {
            window.totalIGVCalculado = 0;
            return;
        }
        
        // Obtener el subtotal actual (sin IGV)
        const totalText = $('#lbl_suma_pedido').text().trim();
        const subtotal = parseFloat(totalText.replace(/[^\d.,]/g, '').replace(',', '.'));
        
        if (subtotal <= 0) {
            window.totalIGVCalculado = 0;
            return;
        }
        
        const preciosIncluyenIGV = $('#precios_incluyen_igv').is(':checked');
        let igvCalculado = 0;
        let totalConIGV = 0;
        
        if (preciosIncluyenIGV) {
            // Si los precios YA incluyen IGV: extraer el IGV del total
            const base = subtotal / 1.18;
            igvCalculado = subtotal - base;
            totalConIGV = subtotal; // El total ya incluye IGV
        } else {
            // Si los precios NO incluyen IGV: agregar 18% al total
            igvCalculado = subtotal * 0.18;
            totalConIGV = subtotal + igvCalculado;
        }
        
        // Determinar símbolo de moneda
        const moneda = $('#moneda').val() === 'D' ? '$ ' : 'S/ ';
        
        // Actualizar displays
        $('#lbl_igv_calculado').text(moneda + igvCalculado.toFixed(2));
        $('#lbl_total_con_igv').text(moneda + totalConIGV.toFixed(2));
        
        // Guardar para envío al backend
        window.totalIGVCalculado = igvCalculado;
        
        console.log('💰 IGV calculado sobre total:', igvCalculado.toFixed(2));
        console.log('💰 Total con IGV:', totalConIGV.toFixed(2));
    }

</script>

@endsection