@extends('layouts.admin')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">PRODUCTOS</h6>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);" class="text-gray-800 font-bold no-underline hover:text-gray-900" style="all: unset; display: inline; font-weight: bold; color: #1f2937;">Almacen</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);" style="all: unset; color: #1755eb; font-weight: 600; cursor: pointer;">
                        Kardex
                    </a>
                </li>
            </ol>
        </div>
    </div>
</div>

<div id="conte-vue-modals">
    {{-- <input type="hidden" name="almacenId" id="almacenId" value="{{ $almacenProducto }}"> --}}

    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 mt-2">
                <div class="card-header bg-gradient">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title text-dark">Lista de Productos</h4>
                        </div>
                        <div class="text-end">
                            <button onclick="descarFunccc(this)" class="btn btn-outline-primary"
                                 style="color: white; background-color: #212529; border: none;">
                                <i class="fa fa-file-excel"></i> Descargar Excel
                            </button>
                            <button data-bs-toggle="modal" data-bs-target="#importarModal" class="btn btn-outline-primary"
                                style="color: white; background-color: #212529; border: none;">
                                <i class="fa fa-file-excel"></i> Importar
                            </button>
                            <button class="btn btn-primary" id="add-prod" data-bs-toggle="modal" data-bs-target="#modal-add-prod"> <!-- agregué data-bs-toggle y data-bs-target -->
                                <i class="fa fa-plus"></i> Agregar Producto
                            </button>

                            <a href="{{ route('admin.unidades.index') }}" class="btn btn-outline-primary button-link"
                                style="color: white; background-color: #212529; border: none;">
                                <i class="fa fa-plus"></i> Unidades
                            </a>
                            <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline-primary button-link"
                                style="color: white; background-color: #212529; border: none;">
                                <i class="fa fa-plus"></i> Categorias
                            </a>
                            <button class="btn btn-danger btnBorrar">
                                <i class="fa fa-trash"></i> Borrar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">

                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover table-striped align-middle" width="100%">
                            <thead class="table-dark">
                                <tr>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Unidades</th>
                                    <th>Precios</th>
                                    <th>Stock</th>
                                    <th>Editar</th>
                                    <th>Eliminar <input type="checkbox" class="btnSeleccionarTodos"></th>
                                </tr>
                            </thead>
                            <tbody id="tbodyProductos">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal de Precios -->
<div class="modal fade" id="modal-precios" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Precios del Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- Precios fijos -->
                    <div class="col-md-12">
                        <h6 class="fw-bold mb-3">Precios Principales</h6>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Precio Venta:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-tag"></i></span>
                            <input id="precio_unidad" class="form-control" type="number" step="0.01" min="0" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Precio Distribuidor:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-tag"></i></span>
                            <input id="precio_menor" class="form-control" type="number" step="0.01" min="0" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Precio Mayorista:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-tag"></i></span>
                            <input id="precio_mayor" class="form-control" type="number" step="0.01" min="0" readonly>
                        </div>
                    </div>
                    
                    <!-- Precios adicionales -->
                    <div class="col-md-12 mt-4" id="multiprecios-container">
                        <h6 class="fw-bold mb-3">Precios Adicionales</h6>
                        <div id="no-multiprecios" class="alert alert-info">
                            Este producto no tiene precios adicionales configurados.
                        </div>
                        <div id="multiprecios-list" class="row g-3">
                            <!-- Los precios adicionales se cargarán dinámicamente aquí -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Agregar Producto Rediseñado -->
<div class="modal fade" id="modal-add-prod" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle me-2"></i>Nuevo Producto
                </h5>
                <button type="button" style="color: black; font-weight: bold; font-size: 1.5rem;" class="ms-auto bg-transparent border-0" data-bs-dismiss="modal" aria-label="Close">×</button> <!-- 🐱 Negrita con CSS inline -->
            </div>
            <form id="productoForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Primera fila: Nombre y Código -->
                        <div class="col-md-8">
                            <label class="form-label"><i class="fa fa-tag me-1"></i>Nombre de producto</label>
                            <input type="text" id="nombre" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-barcode me-1"></i>Código</label>
                            <input type="text" id="codigo" class="form-control" required>
                        </div>
                        
                        <!-- Segunda fila: Detalle y Categoría -->
                        <div class="col-md-8">
                            <label class="form-label"><i class="fa fa-align-left me-1"></i>Detalle de producto</label>
                            <textarea id="detalle" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-folder me-1"></i>Categoría</label>
                            <div class="input-group">
                                <select id="categoria" class="form-select" required>
                                    <!-- Las opciones se cargarán dinámicamente -->
                                </select>
                            </div>
                        </div>
                        
                        <!-- Tercera fila: Precio, Costo y Cantidad -->
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-money-bill me-1"></i>Precio Venta</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="text" id="precio" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-money-bill me-1"></i>Costo</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="text" id="costo" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-cubes me-1"></i>Cantidad</label>
                            <input type="text" id="cantidad" class="form-control" required>
                        </div>
                        
                        <!-- Cuarta fila: Unidades, Almacén y Código Sunat -->
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-ruler me-1"></i>Unidades</label>
                            <select id="unidades" class="form-select" required>
                                <!-- Las opciones se cargarán dinámicamente -->
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-file-alt me-1"></i>Cod. Sunat</label>
                            <input type="text" id="codSunat" class="form-control">
                        </div>
                        
                        <!-- Quinta fila: Afecto ICBP, Precio Distribuidor, Precio Mayorista -->
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-check-circle me-1"></i>Afecto ICBP</label>
                            <select id="afecto" class="form-select">
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-store me-1"></i>Precio Distribuidor</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="text" id="precio1" name="precio1" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-shopping-cart me-1"></i>Precio Mayorista</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="text" id="precio2" name="precio2" class="form-control">
                            </div>
                        </div>
                        
                        <!-- Sexta fila: Imagen del Producto -->
                        <div class="col-md-12">
                            <label class="form-label"><i class="fa fa-image me-1"></i>Imagen del Producto</label>
                            <div class="input-group">
                                <input type="file" id="product-image-input" class="form-control" accept="image/png, image/jpeg">
                            </div>
                            <div class="mt-2" id="imagePreviewContainer">
                                <img id="imagePreview" class="img-thumbnail" style="max-height: 150px; display: none;" src="">
                            </div>
                            <div class="mt-2" id="noImagePlaceholder" style="display: none;">
                                <div class="text-center p-3 border rounded bg-light">
                                    <i class="fa fa-image fa-2x text-muted mb-2 d-block"></i>
                                    <p class="mb-0">No hay imagen para este producto</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Séptima fila: ¿Utilizar MultiPrecio? -->
                        <div class="col-md-12">
                            <div class="form-check form-switch d-flex align-items-center gap-2">
                                <input type="checkbox" id="usar_multiprecio_add" class="form-check-input" style="width: 3em; height: 1.5em;">
                                <label class="form-check-label" for="usar_multiprecio_add">
                                    <i class="fa fa-tags me-1"></i>Utilizar MultiPrecio: <strong id="multiprecioText">No</strong>
                                </label>
                            </div>
                        </div>

                        <!-- Octava fila: Generar código de barras automáticamente -->
                        <!-- ¿Producto controlado por código único o cantidad? -->
                        <div class="col-md-12 mt-2">
                            <div class="form-check form-switch d-flex align-items-center gap-2">
                                <input type="checkbox" id="es_codigo_unico" class="form-check-input" style="width: 3em; height: 1.5em;" checked>
                                <label class="form-check-label" for="es_codigo_unico">
                                    <i class="fa fa-barcode me-1"></i>
                                    Control por: <strong id="tipoProductoText">Código Único</strong>
                                </label>
                            </div>

                            <!-- Botón Escanear Códigos (aparece condicionalmente) -->
                            <div class="col-md-12 mt-3" id="botonEscanearContainer" style="display: none;">
                                <button type="button" id="btnEscanearCodigos" class="btn btn-primary">
                                    <i class="fa fa-qrcode me-2"></i>Escanear códigos únicos
                                </button>
                                <small class="text-muted d-block mt-1">
                                    Debe escanear <span id="cantidadRequerida">0</span> códigos únicos antes de guardar
                                </small>
                            </div>

                        </div>

                        <div class="col-md-12">
                            <div class="form-check form-switch d-flex align-items-center gap-2">
                                <input type="checkbox" id="usar_barra_add" class="form-check-input" checked style="width: 3em; height: 1.5em;">
                                <label class="form-check-label" for="usar_barra_add">
                                    <i class="fa fa-barcode me-1"></i>Generar código de barras automáticamente: <strong id="codigoBarrasText">Sí</strong>
                                </label>
                            </div>
                        </div>

                        <!-- Sección de código de barras manual (oculta por defecto) -->
                        <div id="codigoBarrasManual" style="display: none;" class="col-md-12">
                            <label class="form-label"><i class="fa fa-barcode me-1"></i>Código de Barras Manual</label>
                            <input type="text" id="cod_barra_manual" class="form-control" placeholder="Ingrese código de barras manualmente">
                        </div>

                        <div id="codigoBarrasPreview" style="display: none;" class="col-md-12">
                            <label class="form-label"><i class="fa fa-barcode me-1"></i>Vista previa del código de barras</label>
                            <div class="flex justify-center p-3 border rounded bg-light"> <!-- 🐱 flex + justify-center para centrar imagen horizontalmente -->
                                <img id="barcodePreviewImg" class="max-h-[100px]" /> <!-- 🐱 uso Tailwind para max height 100px -->
                            </div>
                            <div class="mt-2 text-muted small text-center">Código de barras generado automáticamente</div> <!-- 🐱 texto centrado en línea aparte -->
                        </div>


                        <!-- Campo oculto para usar_barra -->
                        <input type="hidden" id="usar_barra" value="1">

                        
                        <!-- Campos ocultos para precios adicionales -->
                        <input type="hidden" id="precio3" value="0">
                        <input type="hidden" id="precio4" value="0">

                        <!-- Campo oculto para enviar al backend -->
                        <input type="hidden" id="tipo_producto_control" name="tipo_producto_control" value="codigo_unico">

                    </div>
                    
                    <!-- Sección de MultiPrecio -->
                    <div id="multiPrecioSection" style="display: none;" class="col-md-12 mt-4">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fa fa-list-ul me-2"></i>Lista de Precios</h5>
                                <button type="button" id="agregarPrecioBtn" class="btn btn-sm btn-light">
                                    <i class="fa fa-plus me-1"></i> Agregar
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <table id="preciosTable" class="table table-bordered m-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50%; padding: 4px 8px;">Nombre</th>
                                            <th style="width: 35%; padding: 4px 8px;">Precio</th>
                                            <th style="width: 15%; padding: 4px 8px; text-align: center;">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="preciosBody">
                                        <tr id="noPreciosRow">
                                            <td colspan="3" class="text-center text-muted" style="padding: 4px;">
                                                No hay precios configurados. Haga clic en "Agregar" para crear uno.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Cerrar
                    </button>
                    <button type="submit" class="btn-accion">
                        <i class="fa fa-save me-1"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Editar Producto -->
<div class="modal fade" id="modal-edt-prod" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-edit me-2"></i>Editar Producto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProductForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-3">
                        <input type="hidden" name="cod_prod" id="edit_cod_prod">
                        
                        <!-- Primera fila: Nombre y Código -->
                        <div class="col-md-8">
                            <label class="form-label"><i class="fa fa-tag me-1"></i>Nombre de producto</label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-barcode me-1"></i>Código</label>
                            <input type="text" name="codigo" id="edit_codigo" class="form-control" required>
                        </div>
                        
                        <!-- Segunda fila: Detalle y Categoría -->
                        <div class="col-md-8">
                            <label class="form-label"><i class="fa fa-align-left me-1"></i>Detalle de producto</label>
                            <textarea name="detalle" id="edit_detalle" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-folder me-1"></i>Categoría</label>
                            <select name="categoria" id="edit_categoria" class="form-select">
                                <option value="">Seleccionar categoría</option>
                            </select>
                        </div>

                        <!-- Tercera fila: Precio Venta, Costo, Cantidad -->
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-money-bill me-1"></i>Precio Venta</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="text" name="precio" id="edit_precio" class="form-control" required onkeypress="onlyNumber(event)">
                            </div>
                            <p id="edit_precio_warning" class="text-danger small mt-1" style="display: none;">
                                <i class="fa fa-exclamation-triangle"></i> El precio está en 0
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-money-bill me-1"></i>Costo</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="text" name="costo" id="edit_costo" class="form-control" required onkeypress="onlyNumber(event)">
                            </div>
                            <p id="edit_costo_warning" class="text-danger small mt-1" style="display: none;">
                                <i class="fa fa-exclamation-triangle"></i> El costo está en 0
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-cubes me-1"></i>Cantidad</label>
                            <input type="text" name="cantidad" id="edit_cantidad" class="form-control">
                            <p id="edit_cantidad_warning" class="text-danger small mt-1" style="display: none;">
                                <i class="fa fa-exclamation-triangle"></i> La cantidad está en 0
                            </p>
                        </div>

                        <!-- Cuarta fila: Unidades y Cod. Sunat -->
                        <div class="col-md-6">
                            <label class="form-label"><i class="fa fa-ruler me-1"></i>Unidades</label>
                            <select name="unidades" id="edit_unidades" class="form-select">
                                <option value="">Seleccionar unidad</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fa fa-file-alt me-1"></i>Cod. Sunat</label>
                            <input type="text" name="codSunat" id="edit_codSunat" class="form-control">
                        </div>

                        <!-- NUEVO: ¿Producto controlado por código único o cantidad? -->
                        <div class="col-md-12 mt-2">
                            <div class="form-check form-switch d-flex align-items-center gap-2">
                                <input type="checkbox" id="es_codigo_unico_edit" name="es_codigo_unico_edit" class="form-check-input" style="width: 3em; height: 1.5em;" value="1" checked>
                                <label class="form-check-label" for="es_codigo_unico_edit">
                                    <i class="fa fa-barcode me-1"></i>
                                    Control por: <strong id="tipoProductoText_edit">Código Único</strong>
                                </label>
                            </div>
                        </div>

                        <!-- Botón Escanear Códigos para edición -->
                        <div class="col-md-12 mt-3" id="botonEscanearContainer_edit" style="display: none;">
                            <button type="button" id="btnEscanearCodigos_edit" class="btn btn-primary">
                                <i class="fa fa-qrcode me-2"></i>Escanear códigos únicos
                            </button>
                            <small class="text-muted d-block mt-1">
                                Debe escanear <span id="cantidadRequerida_edit">0</span> códigos únicos antes de guardar
                            </small>
                        </div>


                        <!-- NUEVA SECCIÓN: Vista previa del código de barras para edición -->
                        <div id="edit_codigoBarrasPreview" style="display: none;" class="col-md-12">
                            <label class="form-label"><i class="fa fa-barcode me-1"></i>Vista previa del código de barras</label>
                            <div class="flex justify-center p-3 border rounded bg-light">
                                <img id="edit_barcodePreviewImg" class="max-h-[100px]" />
                            </div>
                            <div class="mt-2 text-muted small text-center">Código de barras del producto</div>
                        </div>

                        <!-- Quinta fila: Afecto ICBP, Precio Distribuidor, Precio Mayorista -->
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-check-circle me-1"></i>Afecto ICBP</label>
                            <select name="afecto" id="edit_afecto" class="form-select">
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-store me-1"></i>Precio Distribuidor</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="text" name="precio1" id="edit_precio1" class="form-control" onkeypress="onlyNumber(event)">
                            </div>
                            <p id="edit_precio1_warning" class="text-danger small mt-1" style="display: none;">
                                <i class="fa fa-exclamation-triangle"></i> El precio está en 0
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-shopping-cart me-1"></i>Precio Mayorista</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="text" name="precio2" id="edit_precio2" class="form-control" onkeypress="onlyNumber(event)">
                            </div>
                            <p id="edit_precio2_warning" class="text-danger small mt-1" style="display: none;">
                                <i class="fa fa-exclamation-triangle"></i> El precio está en 0
                            </p>
                        </div>

                        <!-- Sexta fila: Imagen del Producto -->
                        <div class="col-md-8">
                            <label class="form-label fw-bold d-flex align-items-center">
                                <i class="fa fa-image me-2"></i>Imagen del Producto
                            </label>
                            <div class="image-container position-relative border rounded p-2">
                                <!-- Contenedor de imagen -->
                                <div class="image-wrapper position-relative">
                                    <img id="edit-img-preview" alt="Vista previa" class="img-fluid mx-auto d-block" style="max-height: 150px; display: none;" />

                                    <!-- Botón de edición con texto -->
                                    <div id="edit-image-edit-button" class="position-absolute top-0 end-0 m-2" style="display: none;">
                                        <button type="button" class="btn btn-light shadow-sm" onclick="toggleEditImageMenu()">
                                            <i class="fa fa-pencil-alt me-1"></i>
                                            Editar imagen
                                        </button>

                                        <!-- Menú desplegable -->
                                        <div id="edit-image-menu" class="position-absolute shadow-sm bg-white rounded border mt-1 end-0" style="display: none; min-width: 160px; z-index: 1000;">
                                            <div class="p-2 hover-bg-light cursor-pointer" onclick="changeEditImage()">
                                                <i class="fa fa-upload me-2"></i> Subir una foto...
                                            </div>
                                            <div class="p-2 text-danger hover-bg-light cursor-pointer" onclick="removeEditImage()">
                                                <i class="fa fa-trash me-2"></i> Eliminar foto
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Input oculto para subir imagen -->
                            <input type="file" id="edit-upload-input" name="imagen" class="d-none" accept="image/*" onchange="previewEditImage(this)" />

                            <!-- Mensaje cuando no hay imagen -->
                            <div id="edit-no-image-message" class="text-center p-3 border rounded bg-light mt-2">
                                <i class="fa fa-image fa-2x text-muted mb-2 d-block"></i>
                                <p class="mb-2">No hay imagen para este producto</p>
                                <button type="button" class="btn btn-primary btn-sm" onclick="changeEditImage()">
                                    <i class="fa fa-upload me-1"></i> Subir imagen
                                </button>
                            </div>

                            <!-- Checkbox para eliminar imagen -->
                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input" id="eliminar_imagen" name="eliminar_imagen">
                                <label class="form-check-label" for="eliminar_imagen">Eliminar imagen actual</label>
                            </div>
                        </div>

                        <!-- Séptima fila: Usar Código Barra -->
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-qrcode me-1"></i>Usar Código Barra</label>
                            <select name="usar_barra" id="edit_usar_barra" class="form-select">
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                            <!-- Eliminado: botón Generar ya no es necesario -->
                        </div>

                        <!-- Campo para código de barras manual (nuevo) -->
                        <div class="col-md-8" id="edit_manual_barcode_container" style="display: none;">
                            <label class="form-label"><i class="fa fa-barcode me-1"></i>Código de Barras Manual</label>
                            <input type="text" name="cod_barra" id="edit_cod_barra_manual" class="form-control" placeholder="Ingrese código de barras manualmente">
                        </div>

                        <!-- Octava fila: Utilizar MultiPrecio -->
                        <div class="col-md-12">
                            <div class="form-check form-switch d-flex align-items-center gap-2">
                                <input type="checkbox" id="edit_usar_multiprecio" class="form-check-input" style="width: 3em; height: 1.5em;">
                                <label class="form-check-label fw-bold" for="edit_usar_multiprecio">
                                    <i class="fa fa-tags me-1"></i>Utilizar MultiPrecio: <span id="edit_multiprecio_text">No</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sección de MultiPrecio -->
                <div id="edit_multiPrecioSection" style="display: none;" class="px-3 mb-3">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fa fa-list-ul me-2"></i>Lista de Precios</h5>
                            <button type="button" id="edit_agregarPrecioBtn" class="btn btn-sm btn-light">
                                <i class="fa fa-plus me-1"></i> Agregar
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered m-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50%; padding: 4px 8px;">Nombre</th>
                                        <th style="width: 35%; padding: 4px 8px;">Precio</th>
                                        <th style="width: 15%; padding: 4px 8px; text-align: center;">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody id="edit_preciosBody">
                                    <tr id="edit_noPreciosRow">
                                        <td colspan="3" class="text-center text-muted" style="padding: 4px;">
                                            No hay precios configurados. Haga clic en "Agregar" para crear uno.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-accion">
                        <i class="fa fa-save me-1"></i>Actualizar
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Restock -->
<div class="modal fade" id="modal-restock" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle me-2"></i>Agregar Stock
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form @submit.prevent="agregarStock">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Cantidad a agregar</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-cubes"></i></span>
                            <input v-model="restock.cantidad" required type="number" min="1" class="form-control">
                        </div>
                        <small class="form-text text-muted">La cantidad ingresada se sumará a la cantidad actual</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i>Guardar
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- REEMPLAZAR el modal existente #importarModal con este código -->

<!-- Modal de Importar -->
<div class="modal fade" id="importarModal" tabindex="-1" aria-labelledby="importarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
            <div class="modal-header">
                <h5 class="modal-title" id="importarModalLabel" style="font-weight: 600;">
                    <i class="fas fa-file-excel me-2"></i>Importar Productos con EXCEL
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Paso 1: Subir archivo -->
            <div class="modal-body p-4" id="paso1-subir">
                <form enctype='multipart/form-data' id="form-importar">
                    <div class="mb-4">
                        <div class="p-3 bg-light rounded-3 border">
                            <p class="mb-2">Descargue el modelo en <span class="fw-bold">EXCEL</span> para importar,
                                no modifique los campos en el archivo.</p>
                            <div class="d-flex align-items-center">
                                <span class="fw-bold me-2">Click para descargar:</span>
                                <a href="{{ route('admin.kardex.productos.template.download') }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">
                                    <i class="fas fa-download me-1"></i>plantilla.xlsx
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-2">Importar Excel:</label>
                        <div class="file-upload-wrapper">
                            <div class="file-upload-area" style="position: relative; border: 2px dashed #1755eb; border-radius: 10px; padding: 20px; text-align: center; background-color: #f5f8ff; transition: all 0.3s ease;">
                                <input id="file-import-excel" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" type="file" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                                <div class="file-info">
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #1755eb; margin-bottom: 10px;"></i>
                                    <p class="mb-0" id="file-name-display">Arrastre su archivo aquí o haga click para seleccionar</p>
                                    <p class="text-muted small mt-1">Formatos aceptados: Excel.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Paso 2: Revisar productos -->
            <div class="modal-body p-4" id="paso2-revisar" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Productos a importar: <span id="total-productos" class="badge bg-primary">0</span></h6>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-regresar">
                        <i class="fas fa-arrow-left me-1"></i>Regresar
                    </button>
                </div>
                
                <div class="table-responsive" style="max-height: 400px;">
                    <table class="table table-sm table-hover" id="tabla-productos-preview">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="check-all-productos" class="form-check-input">
                                </th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Categoría</th>
                                <th>Unidad</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-productos-preview">
                            <!-- Se llenará dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer" style="border-top: none;">
                <!-- Botones para paso 1 -->
                <div id="botones-paso1">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 8px; padding: 8px 20px; font-weight: 500;">Cancelar</button>
                    <button type="button" class="btn-accion" id="btn-procesar" style="border-radius: 8px; padding: 8px 20px; font-weight: 500;">
                        <i class="fas fa-cog me-1"></i>Procesar
                    </button>
                </div>

                <!-- Botones para paso 2 -->
                <div id="botones-paso2" style="display: none;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 8px; padding: 8px 20px; font-weight: 500;">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btn-confirmar-importacion" style="border-radius: 8px; padding: 8px 20px; font-weight: 500;">
                        <i class="fas fa-check me-1"></i>Confirmar Importación
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Lista de Productos -->
<div class="modal fade" id="modal-lista-productos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    <i class="fa fa-list-ul me-2"></i>Lista de productos
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>Costo</th>
                                <th>Precio Venta</th>
                                <th>Precio 1</th>
                                <th>Precio 2</th>
                                <th>Almacen</th>
                                <th>Código</th>
                                <th>Unidades</th>
                                <th>Categorías</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item,index) in listaProd">
                                @{{ item.producto }}
                                    @{{ item.descripcicon }}
                                    @{{ item.cantidad }}
                                    @{{ item.costo }}
                                    @{{ item.precio_unidad }}
                                    @{{ item.precio }}
                                    @{{ item.precio2 }}
                                    @{{ item.almacen }}
                                    @{{ item.codigoProd }}
                                    @{{ item.unidad }}
                                    @{{ item.categoria }}
                                <td>
                                    <button @click="eliminarItemTablaPro(index)" class="btn btn-sm btn-danger">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="agregarListaImport" type="button" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i>Guardar
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i>Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Código de Barras -->
<div class="modal fade" id="modalCodigoBarras" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fa fa-barcode me-2"></i>Código de Barras
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4 text-center p-3 border rounded bg-light">
                    <img id="idCodigoBarras" class="img-fluid block mx-auto max-w-sm"> 
                    <div class="mt-2 text-muted small" id="barcodeProductName"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Seleccionar Precio</label>
                    <select id="scalimg" class="form-select">
                        <option value="">Seleccione un precio...</option>
                    </select>
                </div>
                <div class="d-flex justify-content-center gap-2">
                    <button class="btn btn-outline-primary" id="btnImprimir" onclick="imprimir()">
                        <i class="fa fa-print me-1"></i>Imprimir
                    </button>
                    <button class="btn btn-outline-primary" id="btnImprimir2" onclick="imprimir2()">
                        <i class="fa fa-print me-1"></i>Imprimir 2
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Lista de Categorías -->
<div class="modal fade" id="listaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fa fa-list me-2"></i>Lista de Categorías
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Categoría</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyCat">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Actualizar Categoría -->
<div class="modal fade" id="updateCategoria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fa fa-edit me-2"></i>Actualizar Categoría
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCategoria">
                <div class="modal-body">
                    <input type="text" id="idCatU" value="" hidden>
                    <div class="mb-3">
                        <label for="nombreCategoriaU" class="form-label">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-folder"></i></span>
                            <input type="text" class="form-control" id="nombreCategoriaU">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Cerrar
                    </button>
                    <button type="button" id="updateCategoriaBtn" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Reporte de Producto -->
<div class="modal fade" id="modal-prodEreport" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fa fa-chart-bar me-2"></i>Reporte De Producto
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Año</label>
                        <select id='anioreporEFG' class="form-select">
                            @php
                                $anio = date("Y");
                                for ($i = 0; $i < 10; $i++) {
                                    echo "<option value='$anio'>$anio</option>";
                                    $anio--;
                                }
                            @endphp
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Mes</label>
                        <select id='mesreprEFG' class="form-select">
                            @php
                                $contador = 1;
                                $meses = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
                                foreach ($meses as $mes) {
                                    echo "<option " . ($contador == date('m') ? 'selected' : '') . " value='" . ($contador < 10 ? '0' . $contador : $contador) . "'>$mes</option>";
                                    $contador++;
                                }
                            @endphp
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Día</label>
                        <input id='diareporEfghg' class="form-control" type="number" min="1" max="31">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="generarreporteProd" type="button" class="btn btn-primary">
                    <i class="fa fa-file-pdf me-1"></i>Generar
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Imagen -->
<div class="modal fade" id="modal-imagen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fa fa-image me-2"></i>Imagen del Producto
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mb-3 text-center" id="imagen">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Escaneo de Códigos Únicos (para agregar) -->
<div class="modal fade" id="modalEscanearCodigos" tabindex="-1" data-bs-focus="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0d6efd;">
                <h5 class="modal-title text-white">
                    <i class="fa fa-barcode me-2"></i>ESCANEAR CÓDIGOS ÚNICOS DEL PRODUCTO
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Producto y Cantidad -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Producto:</label>
                        <div class="p-3 border rounded bg-light">
                            <span id="nombreProductoEscaneo"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Cantidad esperada:</label>
                        <div class="p-3 border rounded bg-light text-center">
                            <span id="cantidadEsperada">0</span>
                        </div>
                    </div>
                </div>

                <!-- Input de Escaneo -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Código escaneado:</label>
                        <input type="text" id="inputCodigoEscaneo" class="form-control" 
                               placeholder="Ingrese o escanee el código" autocomplete="off">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" id="btnAgregarCodigo" class="btn w-100" 
                                style="background-color: #0dcaf0; color: white;">
                            <i class="fa fa-plus me-1"></i>Agregar código
                        </button>
                    </div>
                </div>

                <!-- Lista de Códigos Escaneados -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Códigos escaneados:</label>
                    <div id="listaCodigosEscaneados" class="border rounded p-3" 
                         style="background-color: #111111; min-height: 120px; max-height: 200px; overflow-y: auto;">
                        <div id="mensajeNoCodigos" class="text-center py-4" style="color: #6c757d;">
                            No hay códigos escaneados
                        </div>
                    </div>
                </div>

                <!-- Progreso -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">Progreso: <span id="progresoTexto">0/0</span> códigos escaneados</span>
                    </div>
                    <div class="progress">
                        <div id="barraProgreso" class="progress-bar bg-primary" style="width: 0%"></div>
                    </div>
                    <div class="text-center mt-2">
                        <span id="estadoEscaneo" class="text-warning">+ Esperando códigos...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i>Cancelar
                </button>
                <button type="button" id="btnGuardarEscaneados" class="btn btn-success" disabled>
                    <i class="fa fa-check me-1"></i>Guardar escaneados
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Div de Escaneo de Códigos Únicos (para edición) -->
<div id="divEscanearCodigos" class="d-none" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1055; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.5); width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">
            <i class="fa fa-barcode me-2"></i>ESCANEAR CÓDIGOS ÚNICOS DEL PRODUCTO
        </h5>
        <button type="button" id="btnCerrarDivEscanear" class="btn-close" aria-label="Cerrar"></button>
    </div>
    <div class="row mb-4">
        <div class="col-md-8">
            <label class="form-label fw-bold">Producto:</label>
            <div class="p-3 border rounded bg-light">
                <span id="nombreProductoEscaneoEdit"></span>
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">Cantidad esperada:</label>
            <div class="p-3 border rounded bg-light text-center">
                <span id="cantidadEsperadaEdit">0</span>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <label class="form-label fw-bold">Código escaneado:</label>
            <input type="text" id="inputCodigoEscaneoEdit" class="form-control" 
                   placeholder="Ingrese o escanee el código" autocomplete="off">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="button" id="btnAgregarCodigoEdit" class="btn w-100" 
                    style="background-color: #0dcaf0; color: white;">
                <i class="fa fa-plus me-1"></i>Agregar código
            </button>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Códigos escaneados:</label>
        <div id="listaCodigosEscaneadosEdit" class="border rounded p-3" 
             style="background-color: #111111; min-height: 120px; max-height: 200px; overflow-y: auto;">
            <div id="mensajeNoCodigosEdit" class="text-center py-4" style="color: #6c757d;">
                No hay códigos escaneados
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="d-flex justify-content-between mb-2">
            <span class="fw-bold">Progreso: <span id="progresoTextoEdit">0/0</span> códigos escaneados</span>
        </div>
        <div class="progress">
            <div id="barraProgresoEdit" class="progress-bar bg-primary" style="width: 0%"></div>
        </div>
        <div class="text-center mt-2">
            <span id="estadoEscaneoEdit" class="text-warning">+ Esperando códigos...</span>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button type="button" id="btnCancelarDivEscanear" class="btn btn-secondary">
            <i class="fa fa-times me-1"></i>Cancelar
        </button>
        <button type="button" id="btnGuardarEscaneadosEdit" class="btn btn-success" disabled>
            <i class="fa fa-check me-1"></i>Guardar escaneados
        </button>
    </div>
</div>
<!-- Backdrop para el div de escaneo -->
<div id="divEscaneoBackdrop" class="d-none" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1054;"></div>


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

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Tus otros scripts aquí --}}
@endsection

@push('scripts')
<script>
// Variables para almacenar valores de precio de forma segura
let precio1Stored = '0';
let precio2Stored = '0';


// Load categories and units when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
        // Load categories for both create and edit forms
        loadCategories();
        
        // Load units for both create and edit forms
        loadUnits();
    });

    /**
     * Load categories from server
     */
 /**
 * Load categories from server
 */
function loadCategories(selectedCategoryId = null) {
    fetch('{{ url("/admin/categorias/get") }}')
        .then(response => response.json())
        .then(data => {
            // For create form
            const categoriaSelect = document.getElementById('categoria');
            if (categoriaSelect) {
                categoriaSelect.innerHTML = '<option value="">Seleccionar categoría</option>';
                data.forEach(categoria => {
                    const option = document.createElement('option');
                    option.value = categoria.id;
                    option.textContent = categoria.nombre;
                    categoriaSelect.appendChild(option);
                });
            }
            
            // For edit form
            const categoriaEditSelect = document.getElementById('edit_categoria');
            if (categoriaEditSelect) {
                categoriaEditSelect.innerHTML = '<option value="">Seleccionar categoría</option>';
                data.forEach(categoria => {
                    const option = document.createElement('option');
                    option.value = categoria.id;
                    option.textContent = categoria.nombre;
                    if (selectedCategoryId && categoria.id == selectedCategoryId) {
                        option.selected = true;
                    }
                    categoriaEditSelect.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error al cargar categorías:', error));
}

/**
 * Load units from server
 */
function loadUnits(selectedUnitId = null) {
    fetch('{{ url("/admin/unidades/get") }}')
        .then(response => response.json())
        .then(data => {
            // For create form
            const unidadesSelect = document.getElementById('unidades');
            if (unidadesSelect) {
                unidadesSelect.innerHTML = '<option value="">Seleccionar unidad</option>';
                data.forEach(unidad => {
                    const option = document.createElement('option');
                    option.value = unidad.id;
                    option.textContent = unidad.nombre;
                    unidadesSelect.appendChild(option);
                });
            }
            
            // For edit form
            const unidadesEditSelect = document.getElementById('edit_unidades');
            if (unidadesEditSelect) {
                unidadesEditSelect.innerHTML = '<option value="">Seleccionar unidad</option>';
                data.forEach(unidad => {
                    const option = document.createElement('option');
                    option.value = unidad.id;
                    option.textContent = unidad.nombre;
                    if (selectedUnitId && unidad.id == selectedUnitId) {
                        option.selected = true;
                    }
                    unidadesEditSelect.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error al cargar unidades:', error));
}
    /**
 * Product Management JavaScript
 * Handles CRUD operations for products
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize CSRF protection for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize DataTable
    const productsTable = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ url("/admin/kardex/productos/data") }}',
            type: 'GET'
        },
        columns: [
            { data: 'codigo', name: 'codigo' },
            { data: 'nombre', name: 'nombre' },
            { data: 'unidades', name: 'unidades' },
            { data: 'precios', name: 'precios', orderable: false, searchable: false },
            { data: 'stock', name: 'stock' },
            { data: 'editar', name: 'editar', orderable: false, searchable: false },
            { data: 'eliminar', name: 'eliminar', orderable: false, searchable: false }
        ],
        language: {
            processing: "Procesando...",
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
            infoFiltered: "(filtrado de un total de _MAX_ registros)",
            infoPostFix: "",
            loadingRecords: "Cargando...",
            zeroRecords: "No se encontraron resultados",
            emptyTable: "Ningún dato disponible en esta tabla",
            paginate: {
                first: "Primero",
                previous: "Anterior",
                next: "Siguiente",
                last: "Último"
            },
            aria: {
                sortAscending: ": Activar para ordenar la columna de manera ascendente",
                sortDescending: ": Activar para ordenar la columna de manera descendente"
            }
        },
        responsive: true,
        autoWidth: false
    });
    
    // CAMBIO: Aplicar estilos a los botones de paginación al dibujar/redibujar la tabla
    $('#datatable').on('draw.dt', function () {
        // Quitar subrayado y aplicar estilos bonitos
        $('.paginate_button').addClass('bg-[#1754e9] text-white text-sm px-3 py-1 rounded-full mx-1 hover:opacity-90 no-underline'); // CAMBIO: Estilizado general + no-underline
        $('.paginate_button').css({
            'text-decoration': 'none',  // CAMBIO: Quitar subrayado
            'position': 'relative',     // CAMBIO: para poder usar 'top' en todos los botones
            'top': '5px'                // CAMBIO: Mismo desplazamiento vertical para todos los botones (bajarlos igual)
        });

        // Botón deshabilitado
        $('.paginate_button.disabled').addClass('opacity-50 cursor-not-allowed'); // CAMBIO: Botón deshabilitado

        // Botón activo (el botoncito)
        $('.paginate_button.current')
            .removeClass('bg-[#1754e9]')
            .addClass('bg-[#0a3ac7]')
            .css({
                'position': 'relative',      // para usar top
                'background-color': '#eb8826'           // color dorado, cambia aquí al que quieras
            });

        // CAMBIO: Centrar la paginación
        $('#datatable_paginate').addClass('flex justify-center mt-4'); // CAMBIO: Centrado con Tailwind
    });

    // Product Create Form Submission
    $('#productoForm').on('submit', function(e) {
        e.preventDefault();
        
        // Reset any previous error messages
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        const formData = new FormData();

        // Add all form fields manually
        formData.append('nombre', $('#nombre').val());
        formData.append('codigo', $('#codigo').val());
        formData.append('detalle', $('#detalle').val());
        formData.append('categoria', $('#categoria').val());
        formData.append('precio', $('#precio').val());
        formData.append('costo', $('#costo').val());
        formData.append('cantidad', $('#cantidad').val());
        formData.append('unidades', $('#unidades').val());
        formData.append('codSunat', $('#codSunat').val());
        formData.append('unidades', $('#unidades').val()); // Asegurar que se envíe el campo unidades
        formData.append('afecto', $('#afecto').val());
       
        // Usar valores almacenados de forma segura
        console.log('Valores almacenados:', precio1Stored, precio2Stored);
        console.log('Valores DOM actuales:', $('#precio1').val(), $('#precio2').val());

        // Usar los valores almacenados que sabemos que son correctos
        formData.append('precio1', precio1Stored);
        formData.append('precio2', precio2Stored);

        // Agregar código de barras - corregido
        if ($('#usar_barra_add').is(':checked')) {
            const codigoBarra = $('#codigo').val().trim();
            formData.append('cod_barra', codigoBarra);
            formData.append('usar_barra', '1');
        } else {
            const codigoManual = $('#cod_barra_manual').val().trim();
            formData.append('cod_barra', codigoManual || ''); // Enviar vacío si no hay valor
            formData.append('usar_barra', '0');
        }

        formData.append('precio3', $('#precio3').val());
        formData.append('precio4', $('#precio4').val());

        // Add file if exists
        const fileInput = $('#product-image-input')[0];
        if (fileInput.files.length > 0) {
            formData.append('imagen', fileInput.files[0]);
        }
        
        // Add multiprecio value
        formData.append('usar_multiprecio', $('#usar_multiprecio_add').is(':checked') ? '1' : '0');
        
        formData.append('tipo_producto_control', $('#tipo_producto_control').val());

        // Handle multiprecios if enabled
        if ($('#usar_multiprecio_add').is(':checked')) {
            let index = 0; 
            // Get all precio rows
            $('#preciosBody tr:not(#noPreciosRow)').each(function() {
                const nombre = $(this).find('input[name="nombre_precio[]"]').val();
                const precio = $(this).find('input[name="valor_precio[]"]').val();
                
                if (nombre && precio) {
                    formData.append(`precios[${index}][nombre]`, nombre); // 🔧 CAMBIO
                    formData.append(`precios[${index}][precio]`, precio); // 🔧 CAMBIO
                    index++; // 🔧 CAMBIO
                }
            });
            
        }
        
        // AGREGAR ANTES DEL $.ajax({ - código temporal para debug
        console.log('FormData contents:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        // Buscar esta línea: "// Verificar si es producto de código único y necesita escaneo"
        // Y reemplazar toda esa sección por:
        // Verificar si es producto de código único y necesita escaneo
        const esCodigoUnico = $('#es_codigo_unico').is(':checked');
        const cantidad = parseInt($('#cantidad').val()) || 0;

        if (esCodigoUnico && cantidad > 0 && (!datosProductoTemporal || !datosProductoTemporal.codigosUnicos || datosProductoTemporal.codigosUnicos.length !== cantidad)) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Escaneo requerido',
                text: 'Debe escanear los códigos únicos antes de guardar el producto',
                confirmButtonText: 'Entendido'
            });
            return;
        }

        // Si hay códigos escaneados, agregarlos al FormData
        if (datosProductoTemporal && datosProductoTemporal.codigosUnicos) {
            datosProductoTemporal.codigosUnicos.forEach(function(codigo, index) {
                formData.append(`codigos_unicos[${index}]`, codigo);
            });
        }
        
        // AJAX submission
        $.ajax({
            url: '{{ url("/admin/kardex/productos/store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.res) {
                    // Cerrar modal correctamente y limpiar backdrop
                    $('#modal-add-prod').modal('hide');
                    
                    // Forzar limpieza del backdrop después de un breve delay
                    setTimeout(function() {
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');
                        $('body').css('padding-right', '');
                    }, 300);
                    
                    // Limpiar formulario
                    $('#productoForm').trigger('reset');
                    $('#imagePreview').hide();
                    $('#noImagePlaceholder').show();
                    
                    // CORREGIDO: Limpiar datos temporales ANTES del mensaje de éxito
                    limpiarDatosTemporalesCompleto();
                    
                    // Mostrar mensaje de éxito
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Producto agregado exitosamente',
                        icon: 'success',
                        confirmButtonColor: '#3085d6'
                    });
                    
                    // Recargar tabla
                    productsTable.ajax.reload();

                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.error || 'No se pudo agregar el producto',
                        icon: 'error'
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    
                    // Display validation errors
                    Object.keys(errors).forEach(function(key) {
                        const field = $('#' + key);
                        field.addClass('is-invalid');
                        field.after('<div class="invalid-feedback">' + errors[key][0] + '</div>');
                    });
                    
                    Swal.fire({
                        title: 'Error de validación',
                        text: 'Por favor, corrija los errores en el formulario',
                        icon: 'warning'
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Ocurrió un error al procesar la solicitud',
                        icon: 'error'
                    });
                }
            }
        });
    });

     function loadMultiPrecios(productId) {
    $.ajax({
        url: '{{ url("/admin/kardex/productos") }}/' + productId + '/multi-precios',
        type: 'GET',
        success: function(response) {
            if (response.res) {
                // Limpiar precios anteriores - usar el ID correcto
                $('#edit_preciosBody').empty();
                
                const precios = response.precios;
                
                if (precios.length > 0) {
                    // Agregar cada precio a la tabla
                    precios.forEach(function(precio) {
                        const row = `
                            <tr class="edit-precio-row">
                                <td style="padding: 4px;">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                        <input type="text" class="form-control" name="nombre_precio[]" value="${precio.nombre}" placeholder="Nombre del precio">
                                    </div>
                                </td>
                                <td style="padding: 4px;">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">S/</span>
                                        <input type="number" step="0.01" class="form-control" name="valor_precio[]" value="${precio.precio}" placeholder="0.00">
                                    </div>
                                </td>
                                <td class="text-center" style="padding: 4px;">
                                    <button type="button" class="btn btn-sm btn-danger eliminarPrecioBtn">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        $('#edit_preciosBody').append(row);
                    });
                    
                    // Ocultar mensaje "no hay precios"
                    $('#edit_noPreciosRow').hide();
                } else {
                    // Mostrar mensaje "no hay precios"
                    $('#edit_noPreciosRow').show();
                }
            }
        }
    });
}

    // Función para guardar multiprecios
    function saveMultiPrecios(productId) {
        const precios = [];
        
        // Obtener todos los precios de la tabla
        $('#modal-edt-prod tbody tr').each(function() {
            const nombre = $(this).find('input[name="nombre_precio[]"]').val();
            const precio = $(this).find('input[name="valor_precio[]"]').val();
            
            if (nombre && precio) {
                precios.push({ nombre, precio });
            }
        });
        
        $.ajax({
            url: '/admin/kardex/productos/precios/save',
            type: 'POST',
            data: {
                id_producto: productId,
                precios: precios
            },
            success: function(response) {
                if (response.res) {
                    showSuccessAndReload();
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.error || 'No se pudieron guardar los precios',
                        icon: 'error'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al guardar los precios',
                    icon: 'error'
                });
            }
        });
    }

    // Function to show success message and reload
    function showSuccessAndReload() {
        Swal.fire({
            title: '¡Éxito!',
            text: 'Producto actualizado exitosamente',
            icon: 'success',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            $('#modal-edt-prod').modal('hide');
            
            // Forzar limpieza del backdrop
            setTimeout(function() {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('padding-right', '');
            }, 300);
            
            productsTable.ajax.reload();
            // Limpiar datos temporales
            limpiarDatosTemporalesCompleto();
        });
    }

    // Function to handle form errors
    function handleFormErrors(xhr) {
        if (xhr.status === 422) {
            const errors = xhr.responseJSON.errors;
            
            // Display validation errors
            Object.keys(errors).forEach(function(key) {
                const field = $('#modal-edt-prod [name="' + key + '"]');
                field.addClass('is-invalid');
                field.after('<div class="invalid-feedback">' + errors[key][0] + '</div>');
            });
            
            Swal.fire({
                title: 'Error de validación',
                text: 'Por favor, corrija los errores en el formulario',
                icon: 'warning'
            });
        } else {
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al procesar la solicitud',
                icon: 'error'
            });
        }
    }

    // Agregar Precio buttons
    $('#agregarPrecioBtn').on('click', function() {
        addPrecioRow('#preciosBody');
    });

    // Agregar Precio button para modal de edición
    $('#edit_agregarPrecioBtn').on('click', function() {
        addEditPrecioRow();
    });

    // Agregar este handler para el botón de agregar precio
    $(document).on('click', '.btn-agregar-precio', function() {
        const row = `
            <tr>
                <td style="padding: 4px;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fa fa-tag"></i></span>
                        <input type="text" class="form-control" name="nombre_precio[]" placeholder="Nombre del precio">
                    </div>
                </td>
                <td style="padding: 4px;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">S/</span>
                        <input type="number" step="0.01" class="form-control" name="valor_precio[]" placeholder="0.00">
                    </div>
                </td>
                <td class="text-center" style="padding: 4px;">
                    <button type="button" class="btn btn-sm btn-danger eliminarPrecioBtn">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        
        // Eliminar mensaje "no hay precios" si existe
        $('#modal-edt-prod tbody tr td[colspan="3"]').parent().remove();
        
        // Agregar nueva fila
        $('#modal-edt-prod tbody').append(row);
    });

    // Function to add a precio row
    function addPrecioRow(tableBodySelector) {
        // Hide no prices message if present
        $(tableBodySelector + ' #noPreciosRow').hide();
        
        const row = `
            <tr class="precio-row">
                <td style="padding: 4px;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fa fa-tag"></i></span>
                        <input type="text" class="form-control" name="nombre_precio[]" placeholder="Nombre del precio" required>
                    </div>
                </td>
                <td style="padding: 4px;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">S/</span>
                        <input type="number" step="0.01" class="form-control" name="valor_precio[]" placeholder="0.00" required>
                    </div>
                </td>
                <td class="text-center" style="padding: 4px;">
                    <button type="button" class="btn btn-sm btn-danger eliminarPrecioBtn">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        
        $(tableBodySelector).append(row);
    }

    // Eliminar Precio button - SOLO para el modal de edición
    $(document).on('click', '.eliminarPrecioBtn', function() {
        const row = $(this).closest('tr');
        const tbody = row.parent();

        // Solo continuar si estamos en el modal de edición
        if (tbody.attr('id') !== 'edit_preciosBody') return; // 🔧 Modificado: salir si NO es el modal de edición

        row.remove();

        // Ya no se necesita determinar qué modal es, porque solo funcionará con el de edición
        const noPreciosRowId = '#edit_noPreciosRow'; // 🔧 Modificado: asumimos solo modal de edición

        // Mostrar mensaje si ya no hay filas de precios
        if (tbody.find('.edit-precio-row').length === 0) { // 🔧 Modificado: solo buscar filas del modal de edición
            $(noPreciosRowId).show();
        }
    });


    // Delete product - Variables globales
    let selectedProductIds = [];

    // Función para sincronizar selección con página actual
    function syncSelectionWithCurrentPage() {
        selectedProductIds = [];
        $('.btnCheckEliminar:checked').each(function() {
            const productId = $(this).data('id');
            if (productId && !selectedProductIds.includes(productId)) {
                selectedProductIds.push(productId);
            }
        });
    }

    // Checkbox selection individual
    $(document).on('change', '.btnCheckEliminar', function() {
        const productId = $(this).data('id');
        
        if (this.checked) {
            if (productId && !selectedProductIds.includes(productId)) {
                selectedProductIds.push(productId);
            }
        } else {
            if (productId) {
                selectedProductIds = selectedProductIds.filter(id => id !== productId);
            }
        }
        
        // Actualizar estado del checkbox maestro
        updateMasterCheckbox();
    });

    // Select All checkbox - Solo para página actual
    $('.btnSeleccionarTodos').on('change', function() {
        if (this.checked) {
            // Seleccionar todos los productos visibles en la página actual
            $('.btnCheckEliminar').prop('checked', true);
            syncSelectionWithCurrentPage();
        } else {
            // Deseleccionar todos los productos visibles
            $('.btnCheckEliminar').prop('checked', false);
            selectedProductIds = [];
        }
    });

    // Función para actualizar el estado del checkbox maestro
    function updateMasterCheckbox() {
        const totalVisible = $('.btnCheckEliminar').length;
        const totalChecked = $('.btnCheckEliminar:checked').length;
        
        if (totalChecked === 0) {
            $('.btnSeleccionarTodos').prop('checked', false).prop('indeterminate', false);
        } else if (totalChecked === totalVisible) {
            $('.btnSeleccionarTodos').prop('checked', true).prop('indeterminate', false);
        } else {
            $('.btnSeleccionarTodos').prop('checked', false).prop('indeterminate', true);
        }
    }

    // Event listener para cuando cambie la página o se recargue la tabla
    $(document).on('draw.dt', '#datatable', function() {
        // Limpiar selección al cambiar de página
        selectedProductIds = [];
        $('.btnSeleccionarTodos').prop('checked', false).prop('indeterminate', false);
    });

    // Delete button click - Solo elimina productos de página actual
    $('.btnBorrar').on('click', function() {
        // Sincronizar con la página actual antes de eliminar
        syncSelectionWithCurrentPage();
        
        if (selectedProductIds.length === 0) {
            Swal.fire({
                title: 'Advertencia',
                text: 'Seleccione al menos un producto para eliminar',
                icon: 'warning'
            });
            return;
        }
        
        Swal.fire({
            title: '¿Está seguro?',
            text: `Esta acción eliminará ${selectedProductIds.length} producto(s) seleccionado(s). ¡No podrá revertir esto!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/kardex/productos/delete',
                    type: 'DELETE', // Cambiado a DELETE para ser más semánticamente correcto
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        arrayId: selectedProductIds.map(id => ({ id: id }))
                    },
                    success: function(response) {
                        if (response.res) {
                            Swal.fire({
                                title: '¡Eliminados!',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                // Clear selection
                                selectedProductIds = [];
                                $('.btnCheckEliminar, .btnSeleccionarTodos').prop('checked', false).prop('indeterminate', false);
                                
                                // Reload table
                                if (typeof productsTable !== 'undefined') {
                                    productsTable.ajax.reload();
                                } else {
                                    location.reload(); // Fallback si no existe la variable
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.error || 'No se pudieron eliminar los productos',
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Ocurrió un error al procesar la solicitud';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        Swal.fire({
                            title: 'Error',
                            text: errorMessage,
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

    // Toggle MultiPrecio section
    $('#usar_multiprecio_add').on('change', function() {
        if (this.checked) {
            $('#multiprecioText').text('Sí');
            $('#multiPrecioSection').show();
        } else {
            $('#multiprecioText').text('No');
            $('#multiPrecioSection').hide();
        }
    });

    $('#usar_multiprecio_edit').on('change', function() {
        if (this.checked) {
            $('#modal-edt-prod .multiPrecioSection').show();
        } else {
            $('#modal-edt-prod .multiPrecioSection').hide();
        }
    });

    // Reemplazar el handler del botón "Ver Precios" con este:
    $(document).on('click', '.btn-ver-precios', function() {
        const productId = $(this).data('item');
        
        $.ajax({
            url: `/admin/kardex/productos/${productId}/precios`,
            type: 'GET',
            success: function(response) {
                // Mostrar precios fijos
                $('#precio_unidad').val(response.precio_unidad || 0);
                $('#precio_menor').val(response.precio_menor || 0);
                $('#precio_mayor').val(response.precio_mayor || 0);
                
                // Manejar precios adicionales
                const multiprecios = response.multiprecios || [];
                const multipreciosContainer = $('#multiprecios-list');
                multipreciosContainer.empty();
                
                if (multiprecios.length > 0) {
                    $('#no-multiprecios').hide();
                    
                    // Agregar cada precio adicional
                    multiprecios.forEach(function(precio) {
                        const precioHtml = `
                            <div class="col-md-4">
                                <label class="form-label">${precio.nombre}:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                    <input value="${precio.precio}" class="form-control" type="number" step="0.01" min="0" readonly>
                                </div>
                            </div>
                        `;
                        multipreciosContainer.append(precioHtml);
                    });
                } else {
                    $('#no-multiprecios').show();
                }
                
                $('#modal-precios').modal('show');
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudieron cargar los precios',
                    icon: 'error'
                });
            }
        });
    });

    // Update Precios form
    $('#modal-precios form').on('submit', function(e) {
        e.preventDefault();
        
        const productId = $(this).data('productId');
        
        $.ajax({
            url: '/admin/kardex/productos/precios/update',
            type: 'POST',
            data: {
                cod_prod: productId,
                precio: $('#precio1').val(),
                precio_unidad: $('#precio_unidad').val(),
                precio2: $('#precio2').val(),
                precio3: $('#precio3').val(),
                precio4: $('#precio4').val()
            },
            success: function(response) {
                if (response.res) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Precios actualizados exitosamente',
                        icon: 'success'
                    }).then(() => {
                        $('#modal-precios').modal('hide');
                        productsTable.ajax.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.error || 'No se pudieron actualizar los precios',
                        icon: 'error'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al actualizar los precios',
                    icon: 'error'
                });
            }
        });
    });

    // Helper function to allow only numbers in input
    function onlyNumber(event) {
            const keyCode = (event.keyCode ? event.keyCode : event.which);
            if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
                event.preventDefault();
            }
        }

        // Apply onlyNumber to all inputs with the onlyNumber attribute
        $(document).on('keypress', '[onkeypress="onlyNumber(event)"]', function(event) {
            onlyNumber(event);
        });
    });

/**
 * Image Handler
 * Manages image preview, upload and removal
 */

// Variables to track image state
let imageMenuOpen = false;

/**
 * Preview an image in the specified element
 * @param {HTMLInputElement} input - The file input element
 */
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            $('#img-preview')
                .attr('src', e.target.result)
                .show();
            $('#image-edit-button').show();
            $('#no-image-message').hide();
            $('#image-menu').hide();
            imageMenuOpen = false;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

/**
 * Toggle the image menu visibility
 */
function toggleImageMenu() {
    imageMenuOpen = !imageMenuOpen;
    $('#image-menu').fadeToggle(200);
}

/**
 * Trigger the file input click to change the image
 */
function changeImage() {
    $('#upload-input').click();
    $('#image-menu').hide();
    imageMenuOpen = false;
}

/**
 * Remove the current image
 */
function removeImage() {
    $('#upload-input').val('');
    $('#img-preview').hide();
    $('#image-edit-button').hide();
    $('#no-image-message').show();
    $('#image-menu').hide();
    imageMenuOpen = false;
}

// Close the menu when clicking outside
$(document).on('click', function(e) {
    if (imageMenuOpen && !$(e.target).closest('#image-edit-button').length) {
        $('#image-menu').hide();
        imageMenuOpen = false;
    }
});

// Preview image when file input changes (for product add form)
$('#product-image-input').on('change', function(event) {
    const file = event.target.files[0];
    const imagePreview = $('#imagePreview');
    const noImagePlaceholder = $('#noImagePlaceholder');
    
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            imagePreview.attr('src', e.target.result);
            imagePreview.show();
            noImagePlaceholder.hide();
        };
        
        reader.readAsDataURL(file);
    } else {
        imagePreview.attr('src', '');
        imagePreview.hide();
        noImagePlaceholder.show();
    }
});

/**
 * Barcode Handler
 * Manages barcode generation and display
 */
// Variables globales para almacenar datos temporales
let codeBarraTemps = '';
let nombreBarraTemps = '';
let precioBarraTemps = '';

/**
 * Función para mostrar el modal de código de barras
 */
/**
 * Función para mostrar el modal de código de barras
 */
function mostrarCodigoBarras(idProducto, nombre, codigo, codigoBarras, precio) {
    // Almacenar datos en variables globales
    codeBarraTemps = codigoBarras;
    nombreBarraTemps = nombre;
    precioBarraTemps = precio; // Este será el precio por defecto
    
    // Generar la URL del código de barras
    const barcodeUrl = '{{ url("/admin/barcode/generate") }}?code=' + encodeURIComponent(codigoBarras);
    
    // Actualizar el modal
    $('#idCodigoBarras').attr('src', barcodeUrl);
    $('#barcodeProductName').text(nombre);
    
    // Cargar precios del producto
    cargarPreciosEnSelect(idProducto, precio);
    
    // Mostrar el modal
    $('#modalCodigoBarras').modal('show');
}

/**
 * Función para cargar precios en el select del modal
 */
function cargarPreciosEnSelect(productId, precioDefecto) {
    const selectEscalar = $('#scalimg');
    
    // Limpiar select y agregar opción de carga
    selectEscalar.empty();
    selectEscalar.append('<option value="">Cargando precios...</option>');
    
    $.ajax({
        url: `/admin/kardex/productos/${productId}/precios`,
        type: 'GET',
        success: function(response) {
            selectEscalar.empty();
            
            // Agregar precio principal (precio_unidad)
            if (response.precio_unidad) {
                selectEscalar.append(`<option value="${response.precio_unidad}" selected>Precio Venta | S/ ${response.precio_unidad}</option>`);
                precioBarraTemps = response.precio_unidad; // Actualizar precio por defecto
            }
            
            // Agregar precio menor si existe
            if (response.precio_menor && response.precio_menor != response.precio_unidad) {
                selectEscalar.append(`<option value="${response.precio_menor}">Distribuidor | S/ ${response.precio_menor}</option>`);
            }
            
            // Agregar precio mayor si existe
            if (response.precio_mayor && response.precio_mayor != response.precio_unidad) {
                selectEscalar.append(`<option value="${response.precio_mayor}">Mayorista | S/ ${response.precio_mayor}</option>`);
            }
            
            // Agregar multiprecios
            if (response.multiprecios && response.multiprecios.length > 0) {
                response.multiprecios.forEach(function(multiprecio) {
                    selectEscalar.append(`<option value="${multiprecio.precio}">${multiprecio.nombre} | S/ ${multiprecio.precio}</option>`);
                });
            }
        },
        error: function() {
            selectEscalar.empty();
            selectEscalar.append(`<option value="${precioDefecto}">Principal | S/ ${precioDefecto}</option>`);
            console.error('Error al cargar precios para el código de barras');
        }
    });
}

/**
 * Función de impresión formato 1 (modificada)
 */
function imprimir() {
    let imageSrc = $("#idCodigoBarras").attr("src");
    
    if (!imageSrc || !codeBarraTemps) {
        alert("No hay un código de barras para imprimir.");
        return;
    }

    let screenWidth = screen.availWidth;
    let screenHeight = screen.availHeight;

    let myWindow = window.open("", "_blank", `width=${screenWidth},height=${screenHeight},top=0,left=0`);
    
    myWindow.document.write(`
        <html>
        <head>
            <title>Imprimir Código de Barras</title>
            <style>
                * { text-align: center; font-family: Arial, sans-serif; }
                .contenedor {
                    width: 5cm;
                    height: 2.5cm;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    padding: 10px;
                    border: 1px solid black;
                }
                img { max-width: 100%; height: auto; }
                p { font-size: 14px; font-weight: bold; margin: 5px 0 0; }
            </style>
        </head>
        <body onload="window.print(); window.close();">
            <div class="contenedor">
                <img src="${imageSrc}">
                <p>${codeBarraTemps}</p>
            </div>
        </body>
        </html>
    `);

    myWindow.document.close();
}

/**
 * Función de impresión formato 2 (modificada)
 */
function imprimir2() {
    let imageSrc = $("#idCodigoBarras").attr("src");
    
    if (!imageSrc || !codeBarraTemps) {
        alert("No hay un código de barras para imprimir.");
        return;
    }

    let myWindow = window.open("", "_blank", `width=${screen.width},height=${screen.height},top=0,left=0`);

    myWindow.document.write(`
        <html>
        <head>
            <title>Imprimir Código de Barras</title>
            <style>
                * { text-align: center; font-family: Arial, sans-serif; }
                .contenedor {
                    width: 5.5cm;
                    min-height: 3cm;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    padding: 10px;
                    border: 1px solid black;
                    word-wrap: break-word; 
                }
                img { max-width: 100%; height: auto; }
                p { font-size: 14px; margin: 5px 0 0; }
                .nombre { font-size: 14px; margin-bottom: 2px; word-break: break-word;}
                .precio { font-size: 14px; font-weight: bold; color: black; }
            </style>
        </head>
        <body onload="window.print(); window.close();">
            <div class="contenedor">
                <p class="nombre">${nombreBarraTemps}</p>
                <p class="precio">S/. ${precioBarraTemps}</p>
                <img src="${imageSrc}">
                <p>${codeBarraTemps}</p>
            </div>
        </body>
        </html>
    `);

    myWindow.document.close();
}



/**
 * Generate barcode for product edit form
 * @param {string} code - The product code
 */
function generarCodeBarra(code) {
    setTimeout(() => {
        if (document.getElementById("barcode")) {
            try {
                JsBarcode("#barcode", code);
            } catch (error) {
                console.error("Error al generar código de barras:", error);
            }
        } else {
            console.warn("Elemento #barcode no encontrado en el DOM");
        }
    }, 100);
}

// Manejo del switch de código de barras automático
$('#usar_barra_add').on('change', function() {
    if (this.checked) {
        $('#codigoBarrasText').text('Sí');
        $('#codigoBarrasManual').hide();
        $('#usar_barra').val('1');
        
        // Generar código de barras si hay código ingresado
        const codigo = $('#codigo').val();
        if (codigo.trim()) {
            generateBarcodePreview(codigo);
        }
    } else {
        $('#codigoBarrasText').text('No');
        $('#codigoBarrasManual').show();
        $('#codigoBarrasPreview').hide();
        $('#usar_barra').val('0');
    }
});

// Generar código de barras cuando el usuario escribe en el campo código
$('#codigo').on('input', function() {
    const codigo = $(this).val().trim();
    
    if ($('#usar_barra_add').is(':checked') && codigo) {
        generateBarcodePreview(codigo);
    } else {
        $('#codigoBarrasPreview').hide();
    }
});

// Función para generar vista previa del código de barras
function generateBarcodePreview(codigo) {
    if (!codigo.trim()) {
        $('#codigoBarrasPreview').hide();
        return;
    }
    
    try {
        // Mostrar el contenedor
        $('#codigoBarrasPreview').show();
        
        // Generar código de barras usando JsBarcode (si está disponible)
        if (typeof JsBarcode !== 'undefined') {
            JsBarcode("#barcodePreviewImg", codigo, {
                format: "CODE128",
                width: 2,
                height: 80,
                displayValue: true,
                textAlign: "center",
                textPosition: "bottom",
                fontSize: 14
            });
        } else {
            // Fallback: usar una URL para generar código de barras
            const barcodeUrl = '{{ url("/admin/barcode/generate") }}?code=' + encodeURIComponent(codigo);
            $('#barcodePreviewImg').attr('src', barcodeUrl);
        }
    } catch (error) {
        console.error('Error generando código de barras:', error);
        $('#codigoBarrasPreview').hide();
    }
}

// Event listener para cambio de precio en modal código de barras
    $(document).on('change', '#scalimg', function() {
        const precioSeleccionado = $(this).val();
        if (precioSeleccionado) {
            precioBarraTemps = precioSeleccionado;
        }
    });


// Capturar y almacenar valores de precio de forma segura
$(document).ready(function() {
    $('#precio1, #precio2').on('input change keyup', function() {
        const valor = $(this).val();
        console.log('Campo cambiado:', this.id, 'Valor:', valor);
        
        // Almacenar valores de forma segura
        if (this.id === 'precio1') {
            precio1Stored = valor || '0';
        } else if (this.id === 'precio2') {
            precio2Stored = valor || '0';
        }
    });
});

function descarFunccc(btn) {
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Generando...';
    btn.disabled = true;

    // ✅ Agrega console.log para ver si llega al JS
    console.log("🚀 Botón clickeado");

    // Verifica si la ruta se genera correctamente
    const url = "{{ route('admin.kardex.productos.export-excel') }}";
    console.log("📥 URL de descarga generada:", url);

    // Crear un enlace temporal para forzar la descarga
    const link = document.createElement('a');
    link.href = url;
    link.download = 'productos_reporte.xlsx';

    document.body.appendChild(link);

    // ✅ Antes de hacer click, logueamos
    console.log("🧲 Enlace creado, simulando clic");

    link.click(); // Esto debería activar la descarga

    document.body.removeChild(link);

    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        console.log("🔄 Botón restaurado");
    }, 2000);
}

console.log("este archivo se lee");

let productosParaImportar = [];

// Manejar selección de archivo
document.getElementById('file-import-excel').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const display = document.getElementById('file-name-display');
    
    if (file) {
        display.textContent = file.name;
        display.style.color = '#28a745';
    } else {
        display.textContent = 'Arrastre su archivo aquí o haga click para seleccionar';
        display.style.color = '#6c757d';
    }
});

// Manejar drag and drop
const fileUploadArea = document.querySelector('.file-upload-area');
fileUploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.style.borderColor = '#28a745';
    this.style.backgroundColor = '#e8f5e8';
});

fileUploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.style.borderColor = '#1755eb';
    this.style.backgroundColor = '#f5f8ff';
});

fileUploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    this.style.borderColor = '#1755eb';
    this.style.backgroundColor = '#f5f8ff';
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('file-import-excel').files = files;
        const event = new Event('change', { bubbles: true });
        document.getElementById('file-import-excel').dispatchEvent(event);
    }
});

// Procesar archivo Excel
document.getElementById('btn-procesar').addEventListener('click', function() {
    const fileInput = document.getElementById('file-import-excel');
    const file = fileInput.files[0];
    
    if (!file) {
        Swal.fire({
            icon: 'warning',
            title: 'Archivo requerido',
            text: 'Por favor seleccione un archivo Excel para importar'
        });
        return;
    }

    const formData = new FormData();
    formData.append('archivo', file);

    // Mostrar loading
    Swal.fire({
        title: 'Procesando archivo...',
        html: 'Por favor espere mientras procesamos el archivo Excel',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('{{ route("admin.kardex.productos.import.process") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        
        if (data.res) {
            productosParaImportar = data.productos;
            mostrarProductosPreview(data.productos);
            cambiarAPaso2();
        } else {
            if (data.errores && data.errores.length > 0) {
                let erroresHtml = '<ul class="text-left">';
                data.errores.forEach(error => {
                    erroresHtml += `<li>${error}</li>`;
                });
                erroresHtml += '</ul>';
                
                Swal.fire({
                    icon: 'error',
                    title: 'Errores en el archivo',
                    html: erroresHtml,
                    width: '600px'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.error || 'Error al procesar el archivo'
                });
            }
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al procesar el archivo'
        });
    });
});

// Mostrar productos en la tabla de preview
function mostrarProductosPreview(productos) {
    const tbody = document.getElementById('tbody-productos-preview');
    const totalSpan = document.getElementById('total-productos');
    
    tbody.innerHTML = '';
    totalSpan.textContent = productos.length;
    
    productos.forEach((producto, index) => {
        const row = document.createElement('tr');
        
        // Determinar estado del producto
        let estadoClass = 'bg-success';
        let estadoText = 'Activo';
        let estadoIcon = 'fas fa-plus';
        
        // Verificar si el código ya existe (esto se puede mejorar con una verificación en tiempo real)
        if (producto.codigo_existe) {
            estadoClass = 'bg-warning';
            estadoText = 'Actualizar';
            estadoIcon = 'fas fa-edit';
        }
        
        row.innerHTML = `
            <td>
                <input type="checkbox" class="form-check-input producto-checkbox" 
                       data-index="${index}" checked>
            </td>
            <td><strong>${producto.codigo}</strong></td>
            <td>${producto.nombre}</td>
            <td>S/ ${parseFloat(producto.precio).toFixed(2)}</td>
            <td>${producto.cantidad}</td>
            <td>${producto.categoria_nombre || '<span class="text-muted">Sin categoría</span>'}</td>
            <td>${producto.unidad_nombre || '<span class="text-muted">Sin unidad</span>'}</td>
            <td>
                <span class="badge ${estadoClass}">
                    <i class="${estadoIcon} me-1"></i>${estadoText}
                </span>
            </td>
        `;
        
        tbody.appendChild(row);
    });
    
    // Actualizar contador
    actualizarContadorSeleccionados();
}

// Cambiar al paso 2
function cambiarAPaso2() {
    document.getElementById('paso1-subir').style.display = 'none';
    document.getElementById('paso2-revisar').style.display = 'block';
    document.getElementById('botones-paso1').style.display = 'none';
    document.getElementById('botones-paso2').style.display = 'block';
}

// Regresar al paso 1
document.getElementById('btn-regresar').addEventListener('click', function() {
    document.getElementById('paso1-subir').style.display = 'block';
    document.getElementById('paso2-revisar').style.display = 'none';
    document.getElementById('botones-paso1').style.display = 'block';
    document.getElementById('botones-paso2').style.display = 'none';
});

// Manejar checkbox "Seleccionar todos"
document.getElementById('check-all-productos').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.producto-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    actualizarContadorSeleccionados();
});

// Manejar checkboxes individuales
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('producto-checkbox')) {
        actualizarContadorSeleccionados();
        
        // Actualizar estado del checkbox "Seleccionar todos"
        const totalCheckboxes = document.querySelectorAll('.producto-checkbox').length;
        const checkedCheckboxes = document.querySelectorAll('.producto-checkbox:checked').length;
        
        const checkAll = document.getElementById('check-all-productos');
        if (checkedCheckboxes === 0) {
            checkAll.indeterminate = false;
            checkAll.checked = false;
        } else if (checkedCheckboxes === totalCheckboxes) {
            checkAll.indeterminate = false;
            checkAll.checked = true;
        } else {
            checkAll.indeterminate = true;
        }
    }
});

// Actualizar contador de productos seleccionados
function actualizarContadorSeleccionados() {
    const checkedCheckboxes = document.querySelectorAll('.producto-checkbox:checked').length;
    document.getElementById('total-productos').textContent = checkedCheckboxes;
}

// Confirmar importación
document.getElementById('btn-confirmar-importacion').addEventListener('click', function() {
    const checkboxesSeleccionados = document.querySelectorAll('.producto-checkbox:checked');
    
    if (checkboxesSeleccionados.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Sin productos seleccionados',
            text: 'Debe seleccionar al menos un producto para importar'
        });
        return;
    }
    
    // Obtener productos seleccionados
    const productosSeleccionados = [];
    checkboxesSeleccionados.forEach(checkbox => {
        const index = parseInt(checkbox.dataset.index);
        productosSeleccionados.push(productosParaImportar[index]);
    });
    
    // Verificar categorías y unidades
    verificarCategoriasYUnidades(productosSeleccionados);
});

// Verificar categorías y unidades existentes
function verificarCategoriasYUnidades(productos) {
    const categoriasUnicas = [...new Set(productos.map(p => p.categoria_nombre).filter(c => c))];
    const unidadesUnicas = [...new Set(productos.map(p => p.unidad_nombre).filter(u => u))];
    
    // Obtener categorías y unidades desde la BD
    Promise.all([
     fetch('{{ route("admin.kardex.categorias.list") }}').then(r => r.json()),
     fetch('{{ route("admin.kardex.unidades.list") }}').then(r => r.json())
    ])
    .then(([categoriasResponse, unidadesResponse]) => {
        const categoriasExistentes = categoriasResponse.categorias.map(c => c.nombre);
        const unidadesExistentes = unidadesResponse.unidades.map(u => u.nombre);
        
        const categoriasInexistentes = categoriasUnicas.filter(c => !categoriasExistentes.includes(c));
        const unidadesInexistentes = unidadesUnicas.filter(u => !unidadesExistentes.includes(u));
        
        if (categoriasInexistentes.length > 0 || unidadesInexistentes.length > 0) {
            mostrarAdvertenciaElementosInexistentes(categoriasInexistentes, unidadesInexistentes, productos);
        } else {
            verificarCodigosExistentes(productos);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al verificar categorías y unidades'
        });
    });
}

// Mostrar advertencia de elementos inexistentes
function mostrarAdvertenciaElementosInexistentes(categoriasInexistentes, unidadesInexistentes, productos) {
    let mensaje = 'Se encontraron elementos que no existen en la base de datos:<br><br>';
    
    if (categoriasInexistentes.length > 0) {
        mensaje += '<strong>Categorías:</strong><br>';
        categoriasInexistentes.forEach(categoria => {
            mensaje += `- ${categoria}<br>`;
        });
        mensaje += '<br>';
    }
    
    if (unidadesInexistentes.length > 0) {
        mensaje += '<strong>Unidades de medida:</strong><br>';
        unidadesInexistentes.forEach(unidad => {
            mensaje += `- ${unidad}<br>`;
        });
    }
    
    mensaje += '<br>¿Desea continuar ignorando estos elementos?';
    
    Swal.fire({
        icon: 'warning',
        title: 'Elementos no encontrados',
        html: mensaje,
        showCancelButton: true,
        confirmButtonText: 'Continuar sin ellos',
        cancelButtonText: 'Corregir archivo',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            verificarCodigosExistentes(productos);
        }
    });
}

// Verificar códigos existentes
function verificarCodigosExistentes(productos) {
    // Aquí puedes implementar una verificación real consultando la BD
    // Por ahora simularemos que algunos códigos ya existen
    
    const codigosExistentes = productos.filter(p => p.codigo_existe); // Esto debería venir de una consulta real
    
    if (codigosExistentes.length > 0) {
        let mensaje = 'Se encontraron productos con códigos que ya existen:<br><br>';
        codigosExistentes.forEach(producto => {
            mensaje += `- <strong>${producto.codigo}</strong>: ${producto.nombre}<br>`;
        });
        mensaje += '<br>¿Desea actualizar los productos existentes?';
        
        Swal.fire({
            icon: 'question',
            title: 'Productos existentes',
            html: mensaje,
            showCancelButton: true,
            confirmButtonText: 'Actualizar existentes',
            cancelButtonText: 'Corregir códigos',
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                realizarImportacion(productos);
            }
        });
    } else {
        realizarImportacion(productos);
    }
}

// Realizar importación final
function realizarImportacion(productos) {
    Swal.fire({
        title: 'Importando productos...',
        html: `Procesando ${productos.length} productos...`,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch('{{ route("admin.kardex.productos.import.confirm") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            productos: productos
        })
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        
        if (data.res) {
            const resultados = data.resultados;
            let mensaje = 'Importación completada exitosamente:<br><br>';
            
            if (resultados.insertados > 0) {
                mensaje += `✅ <strong>${resultados.insertados}</strong> productos nuevos creados<br>`;
            }
            
            if (resultados.actualizados > 0) {
                mensaje += `🔄 <strong>${resultados.actualizados}</strong> productos actualizados<br>`;
            }
            
            if (resultados.errores && resultados.errores.length > 0) {
                mensaje += `<br>⚠️ <strong>Errores encontrados:</strong><br>`;
                resultados.errores.forEach(error => {
                    mensaje += `- ${error}<br>`;
                });
            }
            
            Swal.fire({
                icon: 'success',
                title: '¡Importación exitosa!',
                html: mensaje,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                // Cerrar modal y recargar tabla
                document.querySelector('#importarModal .btn-close').click();
                location.reload(); // O puedes actualizar solo la tabla de productos
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error en la importación',
                text: data.error || 'Error al importar productos'
            });
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al realizar la importación'
        });
    });
}


// Reemplazar el handler del formulario de edición con este:
$('#editProductForm').on('submit', function(e) {
    e.preventDefault();
    
    // Resetear mensajes de error
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    
    const productId = $('#edit_cod_prod').val();
    
    // Crear FormData para manejar archivos
    const formData = new FormData(this);
    
    // Agregar valor de usar_multiprecio
    formData.append('usar_multiprecio', $('#edit_usar_multiprecio').is(':checked') ? '1' : '0');
    
    // Siempre guardar almacén como 1
    formData.append('almacen', '1');
    
    // Buscar esta línea: "// Verificar si es producto de código único y necesita escaneo"
    // Y reemplazar toda esa sección por:

    // Verificar si es producto de código único y necesita escaneo
    const esCodigoUnico = $('#es_codigo_unico_edit').is(':checked');
    const cantidadTotal = parseInt($('#edit_cantidad').val()) || 0;
    const cantidadOriginal = parseInt($('#edit_cantidad').data('original-value')) || 0;
    const cantidadNueva = Math.max(0, cantidadTotal - cantidadOriginal);

    console.log('Verificación códigos únicos:', {
        esCodigoUnico,
        cantidadTotal,
        cantidadOriginal,
        cantidadNueva,
        datosProductoTemporal
    });

    if (esCodigoUnico && cantidadNueva > 0) {
        // Verificar si hay códigos escaneados suficientes
        if (!datosProductoTemporal || !datosProductoTemporal.codigosUnicos || datosProductoTemporal.codigosUnicos.length !== cantidadNueva) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Escaneo requerido',
                text: `Debe escanear ${cantidadNueva} códigos únicos antes de actualizar el producto`,
                confirmButtonText: 'Entendido'
            });
            return;
        }
    }

    // Si hay códigos escaneados, agregarlos al FormData
    if (datosProductoTemporal && datosProductoTemporal.codigosUnicos) {
        console.log('Agregando códigos al FormData:', datosProductoTemporal.codigosUnicos);
        datosProductoTemporal.codigosUnicos.forEach(function(codigo, index) {
            formData.append(`codigos_unicos[${index}]`, codigo);
        });
    }

    // Enviar solicitud AJAX
    $.ajax({
        url: `/admin/kardex/productos/${productId}`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-HTTP-Method-Override': 'PUT'
        },
        success: function(response) {
            if (response.res) {
                // Si usar_multiprecio está activado, guardar multiprecios
                if ($('#edit_usar_multiprecio').is(':checked')) {
                    saveEditMultiPrecios(productId);
                } else {
                    // Mostrar mensaje de éxito y recargar
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Producto actualizado exitosamente',
                        icon: 'success',
                        confirmButtonColor: '#3085d6'
                    }).then(() => {

                        // Recargar tabla si está disponible
                        if (typeof productsTable !== 'undefined' && productsTable.ajax) {
                            productsTable.ajax.reload();
                        } else {
                            window.location.reload();
                        }
                        console.log("se actualizó producto");

                        
                        setTimeout(function() {
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                            $('body').css('padding-right', '');
                        }, 300);
                         
                       
                    });
                }
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.error || 'No se pudo actualizar el producto',
                    icon: 'error'
                });
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                
                // Mostrar errores de validación
                Object.keys(errors).forEach(function(key) {
                    const field = $(`#edit_${key}`);
                    if (field.length) {
                        field.addClass('is-invalid');
                        field.after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
                    }
                });
                
                Swal.fire({
                    title: 'Error de validación',
                    text: 'Por favor, corrija los errores en el formulario',
                    icon: 'warning'
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al procesar la solicitud',
                    icon: 'error'
                });
            }
        }
    });
});

// Función para guardar multiprecios en edición
function saveEditMultiPrecios(productId) {
    const precios = [];
    
    // Obtener todos los precios de la tabla
    $('#edit_preciosBody .edit-precio-row').each(function() {
        const nombre = $(this).find('input[name="nombre_precio[]"]').val();
        const precio = $(this).find('input[name="valor_precio[]"]').val();
        
        if (nombre && precio) {
            precios.push({ nombre, precio });
        }
    });
    
    $.ajax({
        url: '/admin/kardex/productos/precios/save',
        type: 'POST',
        data: {
            id_producto: productId,
            precios: precios
        },
       success: function(response) {
            if (response.res) {
                // Mostrar mensaje de éxito y recargar
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Producto actualizado exitosamente',
                    icon: 'success',
                    confirmButtonColor: '#3085d6'
                }).then(() => {
                    $('#modal-edt-prod').modal('hide');
                    
                    setTimeout(function() {
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');
                        $('body').css('padding-right', '');
                    }, 300);
                    
                    // Recargar la tabla de productos
                    if (typeof productsTable !== 'undefined' && productsTable.ajax) {
                        productsTable.ajax.reload();
                    } else {
                        // Fallback: recargar la página completa
                        window.location.reload();
                    }

                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.error || 'No se pudieron guardar los precios',
                    icon: 'error'
                });
            }
        },
        error: function() {
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al guardar los precios',
                icon: 'error'
            });
        }
    });
}

// Resetear modal cuando se cierre
document.getElementById('importarModal').addEventListener('hidden.bs.modal', function () {
    // Resetear formulario
    document.getElementById('form-importar').reset();
    document.getElementById('file-name-display').textContent = 'Arrastre su archivo aquí o haga click para seleccionar';
    document.getElementById('file-name-display').style.color = '#6c757d';
    
    // Volver al paso 1
    document.getElementById('paso1-subir').style.display = 'block';
    document.getElementById('paso2-revisar').style.display = 'none';
    document.getElementById('botones-paso1').style.display = 'block';
    document.getElementById('botones-paso2').style.display = 'none';
    
    // Limpiar datos
    productosParaImportar = [];
    document.getElementById('tbody-productos-preview').innerHTML = '';
    document.getElementById('total-productos').textContent = '0';
});

// Función auxiliar para formatear números
function formatearNumero(numero) {
    return parseFloat(numero).toLocaleString('es-PE', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Funciones para manejo de imagen en modal de edición
let editImageMenuOpen = false;

function previewEditImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            $('#edit-img-preview')
                .attr('src', e.target.result)
                .show();
            $('#edit-image-edit-button').show();
            $('#edit-no-image-message').hide();
            $('#edit-image-menu').hide();
            editImageMenuOpen = false;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function toggleEditImageMenu() {
    editImageMenuOpen = !editImageMenuOpen;
    $('#edit-image-menu').fadeToggle(200);
}

function changeEditImage() {
    $('#edit-upload-input').click();
    $('#edit-image-menu').hide();
    editImageMenuOpen = false;
}

function removeEditImage() {
    $('#edit-upload-input').val('');
    $('#edit-img-preview').hide();
    $('#edit-image-edit-button').hide();
    $('#edit-no-image-message').show();
    $('#edit-image-menu').hide();
    editImageMenuOpen = false;
    $('#eliminar_imagen').prop('checked', true);
}

// Cerrar menú al hacer clic fuera
$(document).on('click', function(e) {
    if (editImageMenuOpen && !$(e.target).closest('#edit-image-edit-button').length) {
        $('#edit-image-menu').hide();
        editImageMenuOpen = false;
    }
});

// Buscar esta línea: $('#edit_usar_barra').on('change', function() {
// Y reemplazar toda la función por:
$('#edit_usar_barra').on('change', function() {
    if (this.value === '1') {
        $('#edit_manual_barcode_container').hide();
        $('#edit_barcode_container').show();
        
        // Generar código de barras si hay código
        const codigo = $('#edit_codigo').val();
        if (codigo.trim()) {
            generateEditBarcode(codigo);
        }
    } else {
        $('#edit_manual_barcode_container').show();
        $('#edit_barcode_container').hide();
    }
});

// Generar código de barras cuando cambie el código
$('#edit_codigo').on('input', function() {
    const codigo = $(this).val().trim();
    
    // Siempre generar vista previa del código de barras si hay código
    if (codigo) {
        generateEditBarcodePreview(codigo);
    } else {
        $('#edit_codigoBarrasPreview').hide();
    }
    
    if ($('#edit_usar_barra').val() === '1' && codigo) {
        generateEditBarcode(codigo);
    }
});

// Botón generar código de barras
$('#edit_generar_barcode').on('click', function() {
    const codigo = $('#edit_codigo').val().trim();
    if (codigo) {
        generateEditBarcode(codigo);
    }
});

// Función para generar código de barras en edición
function generateEditBarcode(codigo) {
    setTimeout(() => {
        if (document.getElementById("edit_barcode")) {
            try {
                JsBarcode("#edit_barcode", codigo, {
                    format: "CODE128",
                    width: 2,
                    height: 80,
                    displayValue: true,
                    textAlign: "center",
                    textPosition: "bottom",
                    fontSize: 14
                });
            } catch (error) {
                console.error("Error al generar código de barras:", error);
            }
        }
    }, 100);
}

// Toggle MultiPrecio section para edición
$('#edit_usar_multiprecio').on('change', function() {
    if (this.checked) {
        $('#edit_multiprecio_text').text('Sí');
        $('#edit_multiPrecioSection').show();
    } else {
        $('#edit_multiprecio_text').text('No');
        $('#edit_multiPrecioSection').hide();
    }
});

// Function to add a precio row in edit modal
function addEditPrecioRow() {
    // Hide no prices message if present
    $('#edit_noPreciosRow').hide();
    
    const row = `
        <tr class="edit-precio-row">
            <td style="padding: 4px;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fa fa-tag"></i></span>
                    <input type="text" class="form-control" name="nombre_precio[]" placeholder="Nombre del precio" required>
                </div>
            </td>
            <td style="padding: 4px;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">S/</span>
                    <input type="number" step="0.01" class="form-control" name="valor_precio[]" placeholder="0.00" required>
                </div>
            </td>
            <td class="text-center" style="padding: 4px;">
                <button type="button" class="btn btn-sm btn-danger eliminarPrecioBtn">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
    `;
    
    $('#edit_preciosBody').append(row);
}

// Eliminar precio en modal de edición
$(document).on('click', '.edit-eliminarPrecioBtn', function() {
    const row = $(this).closest('tr');
    const tbody = row.parent();
    
    row.remove();
    
    // Si no hay más filas de precios, mostrar mensaje
    if (tbody.find('.edit-precio-row').length === 0) {
        $('#edit_noPreciosRow').show();
    }
});

// Validaciones en tiempo real para campos numéricos
$('#edit_precio, #edit_costo, #edit_cantidad, #edit_precio1, #edit_precio2').on('input', function() {
    const value = parseFloat($(this).val()) || 0;
    const warningId = this.id + '_warning';
    
    if (value <= 0) {
        $('#' + warningId).show();
    } else {
        $('#' + warningId).hide();
    }
});

// Agregar esta función al final del script
function loadEditMultiPrecios(productId) {
    $.ajax({
        url: '{{ url("/admin/kardex/productos") }}/' + productId + '/multi-precios',
        type: 'GET',
        success: function(response) {
            if (response.res) {
                // Limpiar precios anteriores
                $('#edit_preciosBody .edit-precio-row').remove();
                
                const precios = response.precios;
                
                if (precios.length > 0) {
                    // Ocultar mensaje "no hay precios"
                    $('#edit_noPreciosRow').hide();
                    
                    // Agregar cada precio a la tabla
                    precios.forEach(function(precio) {
                        const row = `
                            <tr class="edit-precio-row">
                                <td style="padding: 4px;">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                        <input type="text" class="form-control" name="nombre_precio[]" value="${precio.nombre}" placeholder="Nombre del precio">
                                    </div>
                                </td>
                                <td style="padding: 4px;">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">S/</span>
                                        <input type="number" step="0.01" class="form-control" name="valor_precio[]" value="${precio.precio}" placeholder="0.00">
                                    </div>
                                </td>
                                <td class="text-center" style="padding: 4px;">
                                    <button type="button" class="btn btn-sm btn-danger edit-eliminarPrecioBtn">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        $('#edit_preciosBody').append(row);
                    });
                } else {
                    // Mostrar mensaje "no hay precios"
                    $('#edit_noPreciosRow').show();
                }
            }
        }
    });
}

// Reemplazar el handler del botón "Editar" existente con este:
$(document).on('click', '.btn-edt', function() {
    const productId = $(this).data('item');
    
    // Limpiar formulario
    $('#editProductForm')[0].reset();
    $('#edit_preciosBody .edit-precio-row').remove();
    $('#edit_noPreciosRow').show();
    
    // Ocultar imagen y mostrar mensaje de "no hay imagen"
    $('#edit-img-preview').attr('src', '').hide();
    $('#edit-image-edit-button').hide();
    $('#edit-no-image-message').show();
    $('#edit_codigoBarrasPreview').hide();
    $('#edit_barcode_container').hide();
    $('#edit_generar_barcode').hide();
    $('#edit_multiPrecioSection').hide();
    $('#edit_multiprecio_text').text('No');
    $('#es_codigo_unico_edit').prop('checked', true);
    $('#tipoProductoText_edit').text('Código Único');
   
        
    // Cargar datos del producto
    $.ajax({
        url: '{{ url("/admin/kardex/productos") }}/' + productId,
        type: 'GET',
        success: function(response) {
            if (response.res) {
                const product = response.data;

                // Cargar categorías y unidades con las selecciones por defecto
                const categoryId = product.categoria_relacion ? product.categoria_relacion.id : product.categoria;
                const unitId = product.unidad_relacion ? product.unidad_relacion.id : product.unidad;

                loadCategories(categoryId);
                loadUnits(unitId);
                
                // Seleccionar la categoría por defecto usando la relación
                if (product.categoria_relacion) {
                    $('#edit_categoria').val(product.categoria_relacion.id);
                } else if (product.categoria) {
                    $('#edit_categoria').val(product.categoria);
                }

                // Seleccionar la unidad por defecto usando la relación
                if (product.unidad_relacion) {
                    $('#edit_unidades').val(product.unidad_relacion.id);
                } else if (product.unidad) {
                    $('#edit_unidades').val(product.unidad);
                }

                // Poblar campos del formulario
                $('#edit_cod_prod').val(product.id_producto);
                $('#edit_nombre').val(product.nombre);
                $('#edit_codigo').val(product.codigo);
                $('#edit_detalle').val(product.detalle);
                $('#edit_categoria').val(product.categoria);
                $('#edit_unidades').val(product.unidad);
                $('#edit_precio').val(product.precio);
                $('#edit_costo').val(product.costo);
                $('#edit_cantidad').val(product.cantidad);
                // Guardar cantidad original para calcular diferencia
                $('#edit_cantidad').data('original-value', product.cantidad);
                $('#edit_codSunat').val(product.codsunat);

                // NUEVO: Configurar el switch de tipo de control
                const tipoControl = product.tipo_control || 'UNICO';
                const esCodigoUnico = tipoControl === 'UNICO';
                $('#es_codigo_unico_edit').prop('checked', esCodigoUnico);
                $('#tipoProductoText_edit').text(esCodigoUnico ? 'Código Único' : 'Cantidad');
                
                $('#edit_afecto').val(product.iscbp ? '1' : '0');
                $('#edit_usar_barra').val(product.usar_barra ? '1' : '0');
                
                // Mapear precios correctamente
                $('#edit_precio1').val(product.precio_menor || 0);
                $('#edit_precio2').val(product.precio_mayor || 0);
                
                const tieneMultiprecio = product.usar_multiprecio && product.precios && product.precios.length > 0; // 🔧 Modificado: se cambió productData por product
                $('#edit_usar_multiprecio').prop('checked', tieneMultiprecio); 

                // Manejar imagen
                if (product.imagen) {
                    $('#edit-img-preview').attr('src', `/storage/productos/${product.imagen}`).show();
                    $('#edit-image-edit-button').show();
                    $('#edit-no-image-message').hide();
                }

                // NUEVO: Generar y mostrar código de barras como imagen
                if (product.codigo && product.codigo.trim()) {
                    generateEditBarcodePreview(product.codigo);
                }
                
                // Buscar esta línea: // Manejar código de barras
                // Y reemplazar esa sección por:
                // Manejar código de barras - MODIFICADO
                if (product.usar_barra === '1' || product.usar_barra === 1) {
                    $('#edit_usar_barra').val('1');
                    $('#edit_manual_barcode_container').hide();
                    $('#edit_barcode_container').show();
                    // Generar código de barras automáticamente al abrir el modal
                    setTimeout(() => {
                        generateEditBarcode(product.codigo);
                    }, 300);
                } else {
                    $('#edit_usar_barra').val('0');
                    $('#edit_manual_barcode_container').show();
                    $('#edit_barcode_container').hide();
                    $('#edit_cod_barra_manual').val(product.cod_barra || '');
                }
                
                  if (tieneMultiprecio) {
                        $('#edit_multiprecio_text').text('Sí');
                        $('#edit_multiPrecioSection').show();
                        
                        loadEditMultiPrecios(product.precios);
                    } else {
                        $('#edit_multiprecio_text').text('No');
                        $('#edit_multiPrecioSection').hide();
                    }
                
                // Mostrar modal
                $('#modal-edt-prod').modal('show');
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo cargar la información del producto',
                    icon: 'error'
                });
            }
        },
        error: function() {
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al cargar los datos del producto',
                icon: 'error'
            });
        }
    });
});


// Función para cargar multiprecios directamente desde datos del producto
function loadEditMultiPrecios(precios) {
    $('#edit_preciosBody').empty();
    
    if (precios && precios.length > 0) {
        precios.forEach(function(precio) {
            const row = `
                <tr class="edit-precio-row">
                    <td style="padding: 4px;">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fa fa-tag"></i></span>
                            <input type="text" class="form-control" name="nombre_precio[]" value="${precio.nombre}" placeholder="Nombre del precio">
                        </div>
                    </td>
                    <td style="padding: 4px;">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">S/</span>
                            <input type="number" step="0.01" class="form-control" name="valor_precio[]" value="${precio.precio}" placeholder="0.00">
                        </div>
                    </td>
                    <td class="text-center" style="padding: 4px;">
                        <button type="button" class="btn btn-sm btn-danger eliminarPrecioBtn">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#edit_preciosBody').append(row);
        });
        
        $('#edit_noPreciosRow').hide();
    } else {
        $('#edit_noPreciosRow').show();
    }
}



// Inicializar estado del switch de multiprecio al abrir el modal de agregar producto
$('#modal-add-prod').on('shown.bs.modal', function() {
    // Asegurarse de que el estado inicial sea correcto
    const usarMultiprecio = $('#usar_multiprecio_add').is(':checked');
    $('#multiprecioText').text(usarMultiprecio ? 'Sí' : 'No');
    $('#multiPrecioSection').toggle(usarMultiprecio);
});

// Agregar esta función nueva al final del script
function generateEditBarcode(codigo) {
    if (!codigo.trim()) return;
    
    setTimeout(() => {
        if (document.getElementById("edit_barcode")) {
            try {
                JsBarcode("#edit_barcode", codigo, {
                    format: "CODE128",
                    width: 2,
                    height: 80,
                    displayValue: true,
                    textAlign: "center",
                    textPosition: "bottom",
                    fontSize: 14
                });
            } catch (error) {
                console.error("Error al generar código de barras:", error);
            }
        }
    }, 100);
}

// Función para generar vista previa del código de barras en edición
function generateEditBarcodePreview(codigo) {
    if (!codigo.trim()) {
        $('#edit_codigoBarrasPreview').hide();
        return;
    }
    
    try {
        // Mostrar el contenedor
        $('#edit_codigoBarrasPreview').show();
        
        // Generar código de barras usando JsBarcode (si está disponible)
        if (typeof JsBarcode !== 'undefined') {
            JsBarcode("#edit_barcodePreviewImg", codigo, {
                format: "CODE128",
                width: 2,
                height: 80,
                displayValue: true,
                textAlign: "center",
                textPosition: "bottom",
                fontSize: 14
            });
        } else {
            // Fallback: usar una URL para generar código de barras
            const barcodeUrl = '{{ url("/admin/barcode/generate") }}?code=' + encodeURIComponent(codigo);
            $('#edit_barcodePreviewImg').attr('src', barcodeUrl);
        }
    } catch (error) {
        console.error('Error generando código de barras en edición:', error);
        $('#edit_codigoBarrasPreview').hide();
    }
}

document.getElementById('es_codigo_unico').addEventListener('change', function () {
        const tipoTexto = document.getElementById('tipoProductoText');
        const tipoHidden = document.getElementById('tipo_producto_control');

        if (this.checked) {
            tipoTexto.innerText = 'Código Único';
            tipoHidden.value = 'codigo_unico';
        } else {
            tipoTexto.innerText = 'Cantidad';
            tipoHidden.value = 'cantidad';
        }
    });

    // NUEVO: Event handler para el switch de tipo de control en edición
    $(document).on('change', '#es_codigo_unico_edit', function() {
        const isChecked = $(this).prop('checked');
        $('#tipoProductoText_edit').text(isChecked ? 'Código Único' : 'Cantidad');
    });

    // ================================
    // FUNCIONALIDAD DE ESCANEO DE CÓDIGOS ÚNICOS
    // ================================

    // Variables globales para el escaneo
    let codigosEscaneados = [];
    let codigosEscaneadosEdit = []; // Agregar esta línea
    let cantidadRequerida = 0;
    let modoEscaneo = 'agregar'; // 'agregar' o 'editar'
    let datosProductoTemporal = null;

    document.addEventListener('DOMContentLoaded', function() {
        inicializarEscaneoDecodigos();
        configurarEventosEscaneo();
    });

    function inicializarEscaneoDecodigos() {
        // Monitorear cambios en cantidad y tipo de producto para modal agregar
        $('#cantidad, #es_codigo_unico').on('change input', function() {
            verificarMostrarBotonEscaneo();
        });

        // Monitorear cambios para modal editar
        $('#edit_cantidad, #es_codigo_unico_edit').on('change input', function() {
            verificarMostrarBotonEscaneo('edit');
        });
    }

    function configurarEventosEscaneo() {
        // Limpiar TODOS los event listeners del input
        $('#inputCodigoEscaneo').off();
        $('#inputCodigoEscaneoEdit').off();
        $('#btnEscanearCodigos').off();
        $('#btnEscanearCodigos_edit').off();
        $('#btnAgregarCodigo').off();
        $('#btnGuardarEscaneados').off();

        // Botones para abrir modal de escaneo
        $('#btnEscanearCodigos').on('click', function() {
            abrirModalEscaneo('agregar');
        });

        $('#btnEscanearCodigos_edit').on('click', function(e) {
            e.preventDefault();
            abrirModalEscaneo('editar');
        });

        // CORREGIDO: Event listener para Enter en el input de escaneo
        $(document).on('keypress', '#inputCodigoEscaneo', function(e) {
            if (e.which === 13) { // Enter
                e.preventDefault();
                agregarCodigoEscaneado();
            }
        });

        $('#btnAgregarCodigo').on('click', function() {
            console.log('Botón Agregar código clickeado');
            const input = document.getElementById('inputCodigoEscaneo');
            if (input && input.value.trim()) {
                procesarCodigoManual(input.value.trim());
                input.value = '';
                input.focus();
            } else {
                console.log('Input vacío o no encontrado');
                if (input) input.focus();
            }
        });
        
        $('#btnGuardarEscaneados').on('click', function() {
            guardarCodigosEscaneados();
        });

        // Limpiar al cerrar modal
        $('#modalEscanearCodigos').on('hidden.bs.modal', function() {
            limpiarModalEscaneo();
        });
    }

    // SOLUCIÓN AGRESIVA: Forzar funcionalidad del input
    function configurarInputAgresivo() {
        const input = document.getElementById('inputCodigoEscaneo');
        if (!input) return;

        // Limpiar TODOS los event listeners
        const newInput = input.cloneNode(true);
        input.parentNode.replaceChild(newInput, input);
        
        // Configurar el nuevo input
        newInput.value = '';
        newInput.disabled = false;
        newInput.readOnly = false;
        newInput.removeAttribute('readonly');
        newInput.removeAttribute('disabled');
        newInput.tabIndex = 0;
        
        // Estilos forzados
        newInput.style.pointerEvents = 'auto';
        newInput.style.userSelect = 'auto';
        newInput.style.backgroundColor = 'white';
        newInput.style.cursor = 'text';
        newInput.style.opacity = '1';
        
        // Event listeners directos en el DOM
        newInput.addEventListener('keydown', function(e) {
            // Permitir todas las teclas normales
            if (e.key === 'Enter') {
                e.preventDefault();
                e.stopPropagation();
                agregarCodigoEscaneado();
            }
        }, true);
        
        newInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                e.stopPropagation();
                agregarCodigoEscaneado();
            }
        }, true);
        
        newInput.addEventListener('input', function(e) {
            // Asegurar que el input siga habilitado
            this.disabled = false;
            this.readOnly = false;
        }, true);
        
        // Forzar focus agresivo
        setTimeout(() => {
            newInput.focus();
            newInput.click();
            newInput.select();
            
            // Verificar focus cada 100ms durante 2 segundos
            let intentos = 0;
            const verificarFocus = setInterval(() => {
                if (document.activeElement !== newInput && intentos < 20) {
                    newInput.focus();
                    newInput.click();
                    intentos++;
                } else {
                    clearInterval(verificarFocus);
                    console.log('Input enfocado después de', intentos, 'intentos');
                }
            }, 100);
        }, 100);
    }

    // SOLUCIÓN EXTREMA: Crear input completamente nuevo
    function crearInputCompletamenteNuevo() {
        const contenedor = document.querySelector('#inputCodigoEscaneo').parentElement;
        const inputViejo = document.getElementById('inputCodigoEscaneo');
        
        // Remover input viejo completamente
        inputViejo.remove();
        
        // Crear input completamente nuevo
        const nuevoInput = document.createElement('input');
        nuevoInput.type = 'text';
        nuevoInput.id = 'inputCodigoEscaneo';
        nuevoInput.className = 'form-control';
        nuevoInput.placeholder = 'Ingrese o escanee el código';
        nuevoInput.autocomplete = 'off';
        nuevoInput.style.cssText = 'pointer-events: auto !important; user-select: auto !important; background-color: white !important; cursor: text !important; opacity: 1 !important;';
        
        // Insertar en el contenedor
        contenedor.appendChild(nuevoInput);
        
        // Variable para almacenar el valor
        let valorActual = '';
        
        // Interceptar TODOS los eventos de teclado posibles
        ['keydown', 'keypress', 'keyup', 'input'].forEach(evento => {
            nuevoInput.addEventListener(evento, function(e) {
                console.log('Evento capturado:', evento, 'Tecla:', e.key, 'Código:', e.keyCode);
                
                if (evento === 'keydown') {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        e.stopPropagation();
                        console.log('Enter detectado, valor actual:', valorActual);
                        if (valorActual.trim()) {
                            procesarCodigoManual(valorActual.trim());
                            valorActual = '';
                            nuevoInput.value = '';
                        }
                        return false;
                    }
                    
                    // Manejar teclas normales
                    if (e.key.length === 1) { // Es un carácter normal
                        valorActual += e.key;
                        nuevoInput.value = valorActual;
                        console.log('Carácter agregado:', e.key, 'Valor actual:', valorActual);
                    } else if (e.key === 'Backspace') {
                        valorActual = valorActual.slice(0, -1);
                        nuevoInput.value = valorActual;
                        console.log('Backspace, valor actual:', valorActual);
                    }
                }
            }, true);
        });
        
        // Forzar focus extremo
        setTimeout(() => {
            nuevoInput.focus();
            nuevoInput.click();
            console.log('Nuevo input creado y enfocado');
        }, 100);
        
        return nuevoInput;
    }

    // Función para procesar código manualmente
    function procesarCodigoManual(codigo) {
        console.log('Procesando código:', codigo);
        
        if (!codigo) {
            return;
        }
        
        // Validar código duplicado
        if (codigosEscaneados.includes(codigo)) {
            Swal.fire({
                icon: 'warning',
                title: 'Código duplicado',
                text: 'Este código ya ha sido escaneado',
                confirmButtonText: 'Entendido'
            });
            return;
        }
        
        // Agregar código
        codigosEscaneados.push(codigo);
        
        // Reproducir sonido y actualizar vista
        reproducirSonidoEscaner();
        actualizarVistaEscaneo();
        
        console.log('Código agregado exitosamente:', codigo);
    }

    function verificarMostrarBotonEscaneo(contexto = '') {
        const esEdicion = contexto === 'edit';
        const checkboxId = esEdicion ? '#es_codigo_unico_edit' : '#es_codigo_unico';
        const cantidadId = esEdicion ? '#edit_cantidad' : '#cantidad';
        const containerId = esEdicion ? '#botonEscanearContainer_edit' : '#botonEscanearContainer';
        const spanId = esEdicion ? '#cantidadRequerida_edit' : '#cantidadRequerida';

        const esCodigoUnico = $(checkboxId).is(':checked');
        const cantidadTotal = parseInt($(cantidadId).val()) || 0;

        if (esCodigoUnico && cantidadTotal > 0) {
            let cantidadAEscanear = cantidadTotal;
            
            // Si es edición, calcular solo los códigos nuevos
            if (esEdicion) {
                const cantidadOriginal = parseInt($(cantidadId).data('original-value')) || 0;
                cantidadAEscanear = Math.max(0, cantidadTotal - cantidadOriginal);
                
                if (cantidadAEscanear === 0) {
                    $(containerId).hide();
                    return;
                }
            }
            
            $(containerId).show();
            $(spanId).text(cantidadAEscanear);
        } else {
            $(containerId).hide();
        }
    }

    function abrirModalEscaneo(modo) {
        console.log('abrirModalEscaneo ejecutado con modo:', modo);
        modoEscaneo = modo;
        const esEdicion = modo === 'editar';
        
        // Obtener datos del producto
        const nombreProducto = esEdicion ? $('#edit_nombre').val() : $('#nombre').val();
        let cantidad = parseInt(esEdicion ? $('#edit_cantidad').val() : $('#cantidad').val()) || 0;

        // Si es edición, calcular solo los códigos nuevos que se necesitan
        if (esEdicion) {
            const cantidadOriginal = parseInt($('#edit_cantidad').data('original-value')) || 0;
            cantidad = Math.max(0, cantidad - cantidadOriginal);
            
            if (cantidad === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Sin códigos nuevos',
                    text: 'No hay códigos nuevos que escanear. La cantidad no ha aumentado.',
                    confirmButtonText: 'Entendido'
                });
                return;
            }
        }
        
        if (esEdicion) {
            // Cerrar modal de edición primero
            $('#modal-edt-prod').modal('hide');
            
            // Configurar div de escaneo para edición
            $('#nombreProductoEscaneoEdit').text(nombreProducto);
            $('#cantidadEsperadaEdit').text(cantidad);
            cantidadRequerida = cantidad;
            
            // Resetear estado
            codigosEscaneadosEdit = [];
            actualizarVistaEscaneoEdit();
            
            // Mostrar backdrop y div
            $('#divEscaneoBackdrop').removeClass('d-none');
            $('#divEscanearCodigos').removeClass('d-none').addClass('show');
            
            // Configurar y habilitar el input CORRECTAMENTE
            setTimeout(() => {
                const input = document.getElementById('inputCodigoEscaneoEdit');
                if (input) {
                    // Limpiar completamente el input
                    input.value = '';
                    
                    // Remover todos los atributos bloqueantes
                    input.removeAttribute('readonly');
                    input.removeAttribute('disabled');
                    input.removeAttribute('tabindex');
                    
                    // Establecer propiedades directamente
                    input.disabled = false;
                    input.readOnly = false;
                    input.tabIndex = 0;
                    
                    // Forzar estilos para asegurar que sea interactivo
                    input.style.pointerEvents = 'auto';
                    input.style.userSelect = 'auto';
                    input.style.backgroundColor = 'white';
                    input.style.cursor = 'text';
                    
                    // Forzar focus múltiples veces
                    input.focus();
                    input.select();
                    
                    // Verificar que realmente esté enfocado
                    setTimeout(() => {
                        if (document.activeElement !== input) {
                            input.focus();
                        }
                    }, 50);
                    
                    // Asegurar que el botón esté habilitado
                    $('#btnAgregarCodigoEdit').prop('disabled', false);
                }
            }, 200);
        // Dentro de abrirModalEscaneo(), busca esta sección:
        } else {
            // Configurar modal para agregar
            $('#nombreProductoEscaneo').text(nombreProducto);
            $('#cantidadEsperada').text(cantidad);
            cantidadRequerida = cantidad;
            
            // Resetear estado
            codigosEscaneados = [];
            actualizarVistaEscaneo();
            
            // CORREGIDO: Limpiar event listeners antes de configurar nuevos
            $('#modal-add-prod').off('hidden.bs.modal.escaneo');
            $('#modalEscanearCodigos').off('shown.bs.modal.inputfix');
            
            // Cerrar modal de agregar antes de abrir modal de escaneo
            $('#modal-add-prod').modal('hide');
            
            // Esperar a que se cierre completamente antes de abrir el de escaneo
            $('#modal-add-prod').one('hidden.bs.modal', function() {
                // Ahora sí mostrar el modal de escaneo
                $('#modalEscanearCodigos').modal('show');
                
                // Configurar input cuando el modal de escaneo esté completamente visible
                $('#modalEscanearCodigos').one('shown.bs.modal', function() {
                    setTimeout(() => {
                        const input = document.getElementById('inputCodigoEscaneo');
                        if (input) {
                            input.value = '';
                            input.removeAttribute('readonly');
                            input.removeAttribute('disabled');
                            input.disabled = false;
                            input.readOnly = false;
                            input.focus();
                            input.select();
                            console.log('Input configurado después de cerrar modal agregar');
                        }
                    }, 300);
                });
            });
        }

    }


    function agregarCodigoEscaneadoEdit() {
        const input = document.getElementById('inputCodigoEscaneoEdit');
        const codigo = input.value.trim();
        
        if (!codigo) {
            // Solo enfocar si está vacío
            input.focus();
            return;
        }
        
        // Validar código duplicado
        if (codigosEscaneadosEdit.includes(codigo)) {
            Swal.fire({
                icon: 'warning',
                title: 'Código duplicado',
                text: 'Este código ya ha sido escaneado',
                confirmButtonText: 'Entendido'
            }).then(() => {
                input.value = '';
                input.focus();
            });
            return;
        }
        
        // Agregar código
        codigosEscaneadosEdit.push(codigo);
        input.value = '';
        
        // Reproducir sonido y actualizar vista
        reproducirSonidoEscaner();
        actualizarVistaEscaneoEdit();
        
        // Asegurar que el input siga habilitado y enfocado
        setTimeout(() => {
            input.removeAttribute('readonly');
            input.removeAttribute('disabled');
            input.disabled = false;
            input.readOnly = false;
            input.focus();
        }, 100);
    }

    // Función para forzar la habilitación del input de edición
    function forzarHabilitacionInputEdit() {
        const input = document.getElementById('inputCodigoEscaneoEdit');
        if (input) {
            // Remover todos los atributos y propiedades bloqueantes
            input.removeAttribute('readonly');
            input.removeAttribute('disabled');
            input.removeAttribute('tabindex');
            
            input.disabled = false;
            input.readOnly = false;
            input.tabIndex = 0;
            
            // Aplicar estilos para asegurar interactividad
            input.style.pointerEvents = 'auto';
            input.style.userSelect = 'auto';
            input.style.backgroundColor = 'white';
            input.style.cursor = 'text';
            input.style.opacity = '1';
            
            // Forzar focus
            input.focus();
            
            return true;
        }
        return false;
    }

    // Llamar esta función cada vez que se muestre el div de escaneo
    $(document).on('click', '#btnEscanearCodigos_edit', function() {
        setTimeout(() => {
            forzarHabilitacionInputEdit();
            
            // Verificar cada 500ms durante 3 segundos que el input esté habilitado
            let intentos = 0;
            const verificarInput = setInterval(() => {
                const input = document.getElementById('inputCodigoEscaneoEdit');
                if (input && (input.disabled || input.readOnly)) {
                    forzarHabilitacionInputEdit();
                }
                
                intentos++;
                if (intentos >= 6) { // 6 intentos = 3 segundos
                    clearInterval(verificarInput);
                }
            }, 500);
        }, 300);
    });

    function agregarCodigoEscaneado() {
        const input = document.getElementById('inputCodigoEscaneo');
        const codigo = input ? input.value.trim() : '';
        
        console.log('agregarCodigoEscaneado llamado, código:', codigo);
        
        if (!codigo) {
            if (input) {
                input.focus();
            }
            return;
        }
        
        procesarCodigoManual(codigo);
        
        // Limpiar input
        if (input) {
            input.value = '';
            input.focus();
        }
    }


    function actualizarVistaEscaneo() {
        const lista = $('#listaCodigosEscaneados');
        const mensaje = $('#mensajeNoCodigos');
        
        if (codigosEscaneados.length === 0) {
            mensaje.show();
            lista.find('.codigo-item').remove();
        } else {
            mensaje.hide();
            
            // Limpiar y reconstruir lista
            lista.find('.codigo-item').remove();
            
            codigosEscaneados.forEach(function(codigo, index) {
                const item = $(`
                    <div class="codigo-item bg-light p-2 mb-2 rounded border-start border-success border-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="font-monospace">${codigo}</span>
                            <small class="text-muted">#${index + 1}</small>
                        </div>
                    </div>
                `);
                lista.append(item);
            });
        }
        
        // Actualizar progreso
        const progreso = Math.round((codigosEscaneados.length / cantidadRequerida) * 100);
        $('#progresoTexto').text(`${codigosEscaneados.length}/${cantidadRequerida}`);
        $('#barraProgreso').css('width', progreso + '%');
        
        // Actualizar estado y botón
        if (codigosEscaneados.length >= cantidadRequerida) {
            $('#estadoEscaneo').removeClass('text-warning').addClass('text-success')
                .html('<i class="fa fa-check"></i> Todos los códigos escaneados');
            $('#btnGuardarEscaneados').prop('disabled', false);
        } else {
            const faltan = cantidadRequerida - codigosEscaneados.length;
            $('#estadoEscaneo').removeClass('text-success').addClass('text-warning')
                .html(`+ Esperando códigos... (faltan ${faltan})`);
            $('#btnGuardarEscaneados').prop('disabled', true);
        }
    }

    function guardarCodigosEscaneados() {
        if (codigosEscaneados.length !== cantidadRequerida) {
            Swal.fire({
                icon: 'error',
                title: 'Escaneo incompleto',
                text: 'Debe escanear todos los códigos requeridos antes de guardar'
            });
            return;
        }
        
        // Almacenar códigos ANTES de cerrar el modal
        datosProductoTemporal = {
            codigosUnicos: [...codigosEscaneados],
            cantidadEscaneada: codigosEscaneados.length
        };
        
        // Guardar la cantidad para el mensaje
        const cantidadEscaneada = codigosEscaneados.length;
        
        // CORREGIDO: Remover todos los event listeners antes de cerrar
        $('#modalEscanearCodigos').off('hidden.bs.modal.reabrirAgregar');
        $('#modal-add-prod').off('hidden.bs.modal.escaneo');
        
        // Cerrar modal de escaneo
        $('#modalEscanearCodigos').modal('hide');
        
        // Mostrar mensaje de éxito y reabrir modal de agregar
        setTimeout(() => {
            Swal.fire({
                icon: 'success',
                title: 'Códigos escaneados',
                text: `Se han escaneado ${cantidadEscaneada} códigos correctamente`,
                confirmButtonText: 'Continuar'
            }).then(() => {
                // Reabrir modal de agregar producto
                $('#modal-add-prod').modal('show');
            });
        }, 300);
    }

    function guardarCodigosEscaneadosEdit() {
        if (codigosEscaneadosEdit.length !== cantidadRequerida) {
            Swal.fire({
                icon: 'error',
                title: 'Escaneo incompleto',
                text: 'Debe escanear todos los códigos requeridos antes de guardar'
            });
            return;
        }
        
        // Ocultar div y backdrop
        $('#divEscanearCodigos').addClass('d-none');
        $('#divEscaneoBackdrop').addClass('d-none');
        
        // Almacenar códigos temporalmente para enviar con el producto
        datosProductoTemporal = {
            codigosUnicos: codigosEscaneadosEdit,
            cantidadEscaneada: codigosEscaneadosEdit.length
        };
        
        // Mostrar mensaje de confirmación y luego volver al modal de edición
        Swal.fire({
            icon: 'success',
            title: 'Códigos escaneados',
            text: `Se han escaneado ${codigosEscaneadosEdit.length} códigos correctamente`,
            confirmButtonText: 'Continuar'
        }).then(() => {
            // Volver a mostrar el modal de edición
            $('#modal-edt-prod').modal('show');
        });
    }

    function mostrarIndicadorCodigosEscaneados() {
        // Buscar si ya existe un indicador
        let indicador = $('#indicadorCodigosEscaneados');
        
        if (indicador.length === 0) {
            // Crear indicador si no existe
            indicador = $(`
                <div id="indicadorCodigosEscaneados" class="alert alert-success mt-2" style="display: none;">
                    <i class="fa fa-check-circle me-2"></i>
                    <strong>Códigos escaneados:</strong> 
                    <span id="cantidadCodigosEscaneados">0</span> códigos únicos listos para guardar
                </div>
            `);
            
            // Insertar después del botón de escanear códigos
            $('#botonEscanearContainer_edit').after(indicador);
        }
        
        // Actualizar y mostrar el indicador
        if (datosProductoTemporal && datosProductoTemporal.codigosUnicos) {
            $('#cantidadCodigosEscaneados').text(datosProductoTemporal.codigosUnicos.length);
            indicador.fadeIn();
        }
    }

    function ocultarIndicadorCodigosEscaneados() {
        $('#indicadorCodigosEscaneados').fadeOut();
    }

    function limpiarModalEscaneo() {
        codigosEscaneados = [];
        cantidadRequerida = 0;
        
        // Limpiar input completamente
        const input = document.getElementById('inputCodigoEscaneo');
        if (input) {
            input.value = '';
            input.disabled = false;
            input.readOnly = false;
            input.removeAttribute('readonly');
            input.removeAttribute('disabled');
        }
        
        $('#nombreProductoEscaneo').text('');
        $('#cantidadEsperada').text('0');
        $('#progresoTexto').text('0/0');
        $('#barraProgreso').css('width', '0%');
        $('#estadoEscaneo').removeClass('text-success').addClass('text-warning')
            .text('+ Esperando códigos...');
        $('#btnGuardarEscaneados').prop('disabled', true);
        
        // Limpiar lista de códigos
        $('#listaCodigosEscaneados').find('.codigo-item').remove();
        $('#mensajeNoCodigos').show();

        // CORREGIDO: Remover event listeners para evitar bucles
        $('#modalEscanearCodigos').off('hidden.bs.modal.reabrirAgregar');
        $('#modal-add-prod').off('hidden.bs.modal.escaneo');
    }

    function guardarCodigosEscaneados() {
        if (codigosEscaneados.length !== cantidadRequerida) {
            Swal.fire({
                icon: 'error',
                title: 'Escaneo incompleto',
                text: 'Debe escanear todos los códigos requeridos antes de guardar'
            });
            return;
        }
        
        // Cerrar modal de escaneo
        $('#modalEscanearCodigos').modal('hide');
        
        // Almacenar códigos temporalmente
        datosProductoTemporal = {
            codigosUnicos: codigosEscaneados,
            cantidadEscaneada: codigosEscaneados.length
        };
        
        // Esperar a que se cierre el modal de escaneo y reabrir el de agregar
        $('#modalEscanearCodigos').off('hidden.bs.modal.reabrirAgregar').on('hidden.bs.modal.reabrirAgregar', function() {
            // Mostrar mensaje de éxito
            Swal.fire({
                icon: 'success',
                title: 'Códigos escaneados',
                text: `Se han escaneado los códigos correctamente`,
                confirmButtonText: 'Continuar'
            }).then(() => {
                // Reabrir modal de agregar producto
                $('#modal-add-prod').modal('show');
            });
        });
    }

    function actualizarVistaEscaneoEdit() {
    // Actualizar mensaje de códigos
    const mensajeNoCodigos = codigosEscaneadosEdit.length === 0;
    $('#listaCodigosEscaneadosEdit').toggleClass('d-none', mensajeNoCodigos);
    $('#mensajeNoCodigosEdit').toggleClass('d-none', !mensajeNoCodigos);
    
    // Actualizar lista de códigos
    $('#listaCodigosEscaneadosEdit').html(codigosEscaneadosEdit.map(codigo => `
        <div class="text-white mb-2">
            <i class="fa fa-check text-success me-2"></i>${codigo}
        </div>
    `).join(''));
    
    // Actualizar progreso
    const porcentaje = (codigosEscaneadosEdit.length / cantidadRequerida) * 100;
    $('#barraProgresoEdit').css('width', `${porcentaje}%`);
    $('#progresoTextoEdit').text(`${codigosEscaneadosEdit.length}/${cantidadRequerida}`);
    
    // Actualizar estado
    const estado = codigosEscaneadosEdit.length === cantidadRequerida 
        ? '<i class="fa fa-check text-success"></i> Listo para guardar' 
        : '<i class="fa fa-plus text-warning"></i> Agregando códigos...';
    $('#estadoEscaneoEdit').html(estado);
    
    // Habilitar/deshabilitar botón de guardar
    $('#btnGuardarEscaneadosEdit').prop('disabled', codigosEscaneadosEdit.length < cantidadRequerida);
}

    // Eventos para el div de escaneo
    $(document).ready(function() {
        $('#btnCerrarDivEscanear, #btnCancelarDivEscanear').on('click', function() {
            // Ocultar el div y backdrop
            $('#divEscanearCodigos').removeClass('show').addClass('d-none');
            $('#divEscaneoBackdrop').addClass('d-none');
            
            limpiarModalEscaneo();
            
            // Volver a mostrar el modal de edición
            $('#modal-edt-prod').modal('show');
        });

        $('#btnGuardarEscaneadosEdit').on('click', function() {
            if (codigosEscaneadosEdit.length < cantidadRequerida) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Escaneo incompleto',
                    text: 'Debe escanear todos los códigos requeridos antes de guardar'
                });
                return;
            }
            
            // Llamar a la función que maneja el guardado correctamente
            guardarCodigosEscaneadosEdit();
        });


        // Evento de enter para agregar código en el div de edición
        $('#inputCodigoEscaneoEdit').on('keypress', function(e) {
            if (e.which === 13) { // Enter
                e.preventDefault();
                agregarCodigoEscaneadoEdit(); // Cambiar a agregarCodigoEscaneadoEdit
            }
        });

        // Evento para habilitar/deshabilitar botón según input
        $('#inputCodigoEscaneoEdit').on('input', function() {
            const valor = $(this).val().trim();
            $('#btnAgregarCodigoEdit').prop('disabled', !valor);
        });

        $(document).on('click', '#btnAgregarCodigoEdit', function() {
            // Asegurar que el input esté habilitado antes de procesar
            forzarHabilitacionInputEdit();
            agregarCodigoEscaneadoEdit();
        });
    });

    // Asegurar que el input siempre esté habilitado cuando reciba focus
    $(document).on('focus', '#inputCodigoEscaneoEdit', function() {
        const input = this;
        
        // Remover atributos bloqueantes
        input.removeAttribute('readonly');
        input.removeAttribute('disabled');
        input.disabled = false;
        input.readOnly = false;
        
        // Aplicar estilos de focus
        $(input).css({
            'pointer-events': 'auto',
            'user-select': 'auto',
            'background-color': 'white',
            'border': '1px solid #86b7fe',
            'box-shadow': '0 0 0 0.25rem rgba(13, 110, 253, 0.25)',
            'outline': 'none'
        });
    });

    // Restaurar borde normal cuando pierda el focus
    $(document).on('blur', '#inputCodigoEscaneoEdit', function() {
        $(this).css({
            'border': '1px solid #ced4da',
            'box-shadow': 'none'
        });
    });

    function limpiarDatosTemporalesCompleto() {
        datosProductoTemporal = null;
        codigosEscaneados = [];
        codigosEscaneadosEdit = [];
        cantidadRequerida = 0;
        modoEscaneo = 'agregar';
        
        // AGREGADO: Limpiar event listeners
        limpiarEventListenersModales();
        
        // Ocultar indicador de códigos escaneados
        if (typeof ocultarIndicadorCodigosEscaneados === 'function') {
            ocultarIndicadorCodigosEscaneados();
        }
    }

    // Reproducir sonido de escáner
    function reproducirSonidoEscaner() {
        // Crear sonido usando Web Audio API
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        
        // Asegurar que el input esté habilitado después del sonido
        setTimeout(() => {
            const inputEdit = $('#inputCodigoEscaneoEdit');
            if (inputEdit) {
                inputEdit.prop('readonly', false);
                inputEdit.prop('disabled', false);
                inputEdit.removeAttr('readonly');
                inputEdit.removeAttr('disabled');
                inputEdit.focus();
            }
        }, 100);
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        oscillator.frequency.value = 800;
        oscillator.type = "square";

        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);

        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.1);
    }

    // FUNCIÓN TEMPORAL DE DEBUG - Remover después de solucionar
    function debugInputEdit() {
        const input = document.getElementById('inputCodigoEscaneoEdit');
        if (input) {
            console.log('=== DEBUG INPUT EDIT ===');
            console.log('Input encontrado:', !!input);
            console.log('Disabled:', input.disabled);
            console.log('ReadOnly:', input.readOnly);
            console.log('TabIndex:', input.tabIndex);
            console.log('Style pointer-events:', input.style.pointerEvents);
            console.log('Style user-select:', input.style.userSelect);
            console.log('Computed style:', window.getComputedStyle(input).pointerEvents);
            console.log('Active element:', document.activeElement === input);
            console.log('======================');
        } else {
            console.log('Input inputCodigoEscaneoEdit NO encontrado');
        }
    }

    // Llamar debug cuando se abra el div de escaneo
    $(document).on('click', '#btnEscanearCodigos_edit', function() {
        setTimeout(() => {
            debugInputEdit();
        }, 500);
    });

    // FUNCIÓN TEMPORAL DE DEBUG para modal agregar - Remover después de solucionar
    function debugInputAgregar() {
        const input = document.getElementById('inputCodigoEscaneo');
        if (input) {
            console.log('=== DEBUG INPUT AGREGAR ===');
            console.log('Input encontrado:', !!input);
            console.log('Disabled:', input.disabled);
            console.log('ReadOnly:', input.readOnly);
            console.log('TabIndex:', input.tabIndex);
            console.log('Style pointer-events:', input.style.pointerEvents);
            console.log('Style user-select:', input.style.userSelect);
            console.log('Computed style:', window.getComputedStyle(input).pointerEvents);
            console.log('Active element:', document.activeElement === input);
            console.log('Input value:', input.value);
            console.log('Input type:', input.type);
            console.log('Parent visible:', $(input).parent().is(':visible'));
            console.log('Input visible:', $(input).is(':visible'));
            
            // Intentar escribir programáticamente
            input.value = 'TEST';
            console.log('Después de escribir TEST:', input.value);
            
            // Intentar focus programático
            input.focus();
            console.log('Después de focus:', document.activeElement === input);
            console.log('===========================');
        } else {
            console.log('Input inputCodigoEscaneo NO encontrado');
        }
    }

    // NUEVA FUNCIÓN: Limpiar todos los event listeners de modales
    function limpiarEventListenersModales() {
        $('#modalEscanearCodigos').off('hidden.bs.modal.reabrirAgregar');
        $('#modal-add-prod').off('hidden.bs.modal.escaneo');
        $('#modalEscanearCodigos').off('shown.bs.modal.inputfix');
    }
    
    // Llamar debug cuando se abra el modal de agregar
    $(document).on('click', '#btnEscanearCodigos', function() {
        setTimeout(() => {
            debugInputAgregar();
        }, 500);
    });

    // CORREGIDO: Event listeners para botones de cerrar (X)
    $(document).ready(function() {
        // Botón X del modal de escaneo
        $('#modalEscanearCodigos .btn-close').on('click', function() {
            // Limpiar event listeners
            $('#modalEscanearCodigos').off('hidden.bs.modal.reabrirAgregar');
            $('#modal-add-prod').off('hidden.bs.modal.escaneo');
            
            // Cerrar modal
            $('#modalEscanearCodigos').modal('hide');
            
            // Reabrir modal de agregar después de un delay
            setTimeout(() => {
                $('#modal-add-prod').modal('show');
            }, 300);
        });
        
        // Botón X del modal de agregar producto
        $('#modal-add-prod .btn-close').on('click', function() {
            // Limpiar todos los event listeners
            $('#modalEscanearCodigos').off('hidden.bs.modal.reabrirAgregar');
            $('#modal-add-prod').off('hidden.bs.modal.escaneo');
            
            // Limpiar datos temporales
            limpiarDatosTemporalesCompleto();
            
            // Cerrar modal normalmente
            $('#modal-add-prod').modal('hide');
        });
        
        // Botón X del div de escaneo (edición)
        $('#btnCerrarDivEscanear').on('click', function() {
            $('#divEscanearCodigos').removeClass('show').addClass('d-none');
            $('#divEscaneoBackdrop').addClass('d-none');
            
            // Limpiar datos temporales de edición
            codigosEscaneadosEdit = [];
            
            // Volver a mostrar el modal de edición
            $('#modal-edt-prod').modal('show');
        });
        
        // Botón X del modal de edición
        $('#modal-edt-prod .btn-close').on('click', function() {
            // Limpiar datos temporales
            limpiarDatosTemporalesCompleto();
            
            // Cerrar modal normalmente
            $('#modal-edt-prod').modal('hide');
        });
    });


</script>
@endpush