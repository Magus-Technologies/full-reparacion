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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Precios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form @submit.prevent="agregarPrecios">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Precio Venta:</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                <input v-model="edt.precio_unidad" id="precio_unidad" class="form-control" type="number" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Precio 1:</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                <input v-model="edt.precio" id="precio1" class="form-control" type="number" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Precio 2:</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                <input v-model="edt.precio2" id="precio2" class="form-control" type="number" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Precio 3:</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                <input v-model="edt.precio3" id="precio3" class="form-control" type="number" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Precio 4:</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                <input v-model="edt.precio4" id="precio4" class="form-control" type="number" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-accion">
                        <i class="fa fa-save me-1"></i> Guardar
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Cerrar
                    </button>
                </div>
            </form>
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
                                <input type="text" id="precio" class="form-control" required onkeypress="onlyNumber(event)">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-money-bill me-1"></i>Costo</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="text" id="costo" class="form-control" required onkeypress="onlyNumber(event)">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-cubes me-1"></i>Cantidad</label>
                            <input type="text" id="cantidad" class="form-control" required onkeypress="onlyNumber(event)">
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
                                <input type="text" id="precio1" name="precio1" class="form-control" onkeypress="onlyNumber(event)">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-shopping-cart me-1"></i>Precio Mayorista</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="text" id="precio2" name="precio2" class="form-control" onkeypress="onlyNumber(event)">
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
            <form @submit.prevent="actualizarProd" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-3">
                        <input v-model="edt.cod_prod" type="hidden" class="form-control">
                        
                        <div class="col-md-8">
                            <label class="form-label"><i class="fa fa-tag me-1"></i>Nombre de producto</label>
                            <input v-model="edt.nombre" required type="text" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-barcode me-1"></i>Código</label>
                            <input v-model="edt.codigo" required type="text" class="form-control">
                        </div>
                        
                        <div class="col-md-8">
                            <label class="form-label"><i class="fa fa-align-left me-1"></i>Detalle de producto</label>
                            <textarea v-model="edt.detalle" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-folder me-1"></i>Categoría</label>
                            <div class="input-group">
                                <select v-model="edt.categoria" id="categoria-edt" class="form-select">
                                </select>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalCategoria">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- PRIMERA FILA: Precio Venta, Costo, Cantidad -->
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-money-bill me-1"></i>Precio Venta</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input v-model="edt.precio" @keypress="onlyNumber" required value="0" type="text" class="form-control">
                            </div>
                            <p v-if="parseFloat(edt.precio) <= 0" class="text-danger small mt-1">
                                <i class="fa fa-exclamation-triangle"></i> El precio está en 0
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-money-bill me-1"></i>Costo</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input v-model="edt.costo" @keypress="onlyNumber" required value="0" type="text" class="form-control">
                            </div>
                            <p v-if="parseFloat(edt.costo) <= 0" class="text-danger small mt-1">
                                <i class="fa fa-exclamation-triangle"></i> El costo está en 0
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-cubes me-1"></i>Cantidad</label>
                            <input v-model="edt.cantidad" @keypress="onlyNumber" value="0" type="text" class="form-control">
                            <p v-if="parseInt(edt.cantidad) <= 0" class="text-danger small mt-1">
                                <i class="fa fa-exclamation-triangle"></i> La cantidad está en 0
                            </p>
                        </div>

                        <!-- SEGUNDA FILA: Unidades, Almacén, Cod. Sunat -->
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-ruler me-1"></i>Unidades</label>
                            <select v-model="edt.unidad" id="unidades-edt" class="form-select">
                              <option v-for="unit in units" :key="unit.id" :value="unit.id">@{{ unit.nombre }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-warehouse me-1"></i>Almacén</label>
                            <select v-model="edt.almacen" required class="form-select">
                                <option value="1">Almacen 1</option>
                                <option value="2">Almacen 2</option>
                                <option value="3">Almacen 3</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-file-alt me-1"></i>Cod. Sunat</label>
                            <input v-model="edt.codSunat" type="text" class="form-control">
                        </div>

                        <!-- TERCERA FILA: Afecto ICBP, Precio Distribuidor, Precio Mayorista -->
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-check-circle me-1"></i>Afecto ICBP</label>
                            <select v-model="edt.afecto" class="form-select">
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-store me-1"></i>Precio Distribuidor</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input v-model="edt.precioMayor" @keypress="onlyNumber" value="0" type="text" class="form-control">
                            </div>
                            <p v-if="parseFloat(edt.precioMayor) <= 0" class="text-danger small mt-1">
                                <i class="fa fa-exclamation-triangle"></i> El precio está en 0
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-shopping-cart me-1"></i>Precio Mayorista</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input v-model="edt.precioMenor" @keypress="onlyNumber" value="0" type="text" class="form-control">
                            </div>
                            <p v-if="parseFloat(edt.precioMenor) <= 0" class="text-danger small mt-1">
                                <i class="fa fa-exclamation-triangle"></i> El precio está en 0
                            </p>
                        </div>

                        <!-- Campos ocultos -->
                        <div class="col-md-4" hidden>
                            <label class="form-label">Precio 3</label>
                            <input v-model="edt.precio3" @keypress="onlyNumber" value="0" type="text" class="form-control">
                        </div>
                        <div class="col-md-4" hidden>
                            <label class="form-label">Precio 4</label>
                            <input v-model="edt.precio4" @keypress="onlyNumber" value="0" type="text" class="form-control">
                        </div>
                        
                        <div class="col-md-8">
                            <label class="form-label fw-bold d-flex align-items-center">
                                <i class="fa fa-image me-2"></i>Imagen del Producto
                            </label>
                            <div class="image-container position-relative border rounded p-2">
                                <!-- Contenedor de imagen -->
                                <div class="image-wrapper position-relative">
                                    <img id="img-preview" alt="Vista previa" class="img-fluid mx-auto d-block" style="max-height: 150px; display: none;" />

                                    <!-- Botón de edición con texto -->
                                    <div id="image-edit-button" class="position-absolute top-0 end-0 m-2" style="display: none;">
                                        <button type="button" class="btn btn-light shadow-sm" onclick="toggleImageMenu()">
                                            <i class="fa fa-pencil-alt me-1"></i>
                                            Editar imagen
                                        </button>

                                        <!-- Menú desplegable -->
                                        <div id="image-menu" class="position-absolute shadow-sm bg-white rounded border mt-1 end-0" style="display: none; min-width: 160px; z-index: 1000;">
                                            <div class="p-2 hover-bg-light cursor-pointer" onclick="changeImage()">
                                                <i class="fa fa-upload me-2"></i> Subir una foto...
                                            </div>
                                            <div class="p-2 text-danger hover-bg-light cursor-pointer" onclick="removeImage()">
                                                <i class="fa fa-trash me-2"></i> Eliminar foto
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Input oculto para subir imagen -->
                            <input type="file" id="upload-input" name="imagen" class="d-none" accept="image/*" onchange="previewImage(this)" />

                            <!-- Mensaje cuando no hay imagen -->
                            <div id="no-image-message" class="text-center p-3 border rounded bg-light mt-2">
                                <i class="fa fa-image fa-2x text-muted mb-2 d-block"></i>
                                <p class="mb-2">No hay imagen para este producto</p>
                                <button type="button" class="btn btn-primary btn-sm" onclick="changeImage()">
                                    <i class="fa fa-upload me-1"></i> Subir imagen
                                </button>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label"><i class="fa fa-qrcode me-1"></i>Usar Código Barra</label>
                            <div class="input-group">
                                <select v-model="edt.usar_barra" class="form-select">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                                <button v-if="edt.usar_barra=='1'" @click="edtGenerarCodeBarra" type="button" class="btn btn-outline-primary">
                                    <i class="fa fa-sync-alt"></i> Generar
                                </button>
                            </div>
                        </div>

                        <div class="col-12 text-center" v-if="edt.usar_barra=='1'">
                            <label class="form-label"><i class="fa fa-barcode me-1"></i>Código de Barras</label>
                            <div class="p-3 border rounded bg-light">
                                <img id="barcode" class="img-fluid" />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check form-switch d-flex align-items-center gap-2">
                                <input v-model="edt.usar_multiprecio" class="form-check-input" type="checkbox" id="usar_multiprecio_edit" style="width: 3em; height: 1.5em;">
                                <label class="form-check-label fw-bold" for="usar_multiprecio_edit" :class="{'text-primary': edt.usar_multiprecio, 'text-secondary': !edt.usar_multiprecio}">
                                <i class="fa fa-tags me-1"></i>Utilizar MultiPrecio: @{{ edt.usar_multiprecio ? 'Sí' : 'No' }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div v-if="edt.usar_multiprecio" class="px-3 mb-3">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fa fa-list-ul me-2"></i>Lista de Precios</h5>
                            <button type="button" @click="agregarPrecio" class="btn btn-sm btn-light">
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
                                <tbody>
                                    <tr v-for="(precio, index) in precios" :key="index">
                                        <td style="padding: 4px;">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                                <input v-model="precio.nombre" type="text" class="form-control" placeholder="Nombre del precio">
                                            </div>
                                        </td>
                                        <td style="padding: 4px;">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">S/</span>
                                                <input v-model="precio.precio" @keypress="onlyNumber" type="text" class="form-control" placeholder="0.00">
                                            </div>
                                        </td>
                                        <td style="padding: 4px; text-align: center;">
                                            <button @click="eliminarPrecio(index)" type="button" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="precios.length === 0">
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
                    <button type="submit" class="btn btn-primary">
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

<!-- Modal de Importar -->
<div class="modal fade" id="importarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" style="font-weight: 600;">
                    <i class="fas fa-file-excel me-2"></i>Importar Productos con EXCEL
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form enctype='multipart/form-data'>
                    <div class="mb-4">
                        <div class="p-3 bg-light rounded-3 border">
                            <p class="mb-2">Descargue el modelo en <span class="fw-bold">EXCEL</span> para importar,
                                no modifique los campos en el archivo.</p>
                            <div class="d-flex align-items-center">
                                <span class="fw-bold me-2">Click para descargar:</span>
                                <a href="{{ url('/reporte/producto/guia') }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">
                                    <i class="fas fa-download me-1"></i>plantilla.xlsx
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-2">Importar Excel:</label>
                        <div class="file-upload-wrapper">
                            <div class="file-upload-area" style="position: relative; border: 2px dashed #1755eb; border-radius: 10px; padding: 20px; text-align: center; background-color: #f5f8ff; transition: all 0.3s ease;">
                                <input id="file-import-exel" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" type="file" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                                <div class="file-info">
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #1755eb; margin-bottom: 10px;"></i>
                                    <p class="mb-0" id="file-name-display">Arrastre su archivo aquí o haga click para seleccionar</p>
                                    <p class="text-muted small mt-1">Formatos aceptados: Excel, CSV</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-top: none;">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 8px; padding: 8px 20px; font-weight: 500;">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-importar" style="border-radius: 8px; padding: 8px 20px; font-weight: 500;">
                    <i class="fas fa-file-import me-1"></i>Importar
                </button>
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
                    <img id="idCodigoBarras" class="img-fluid">
                    <div class="mt-2 text-muted small" id="barcodeProductName"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Escalar</label>
                    <select id="scalimg" class="form-select">
                        <option value="1">No</option>
                        <option value="2">Sí</option>
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

<!-- jQuery PRIMERO -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AGREGAR DATATABLES -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    function loadCategories() {
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
                const categoriaEditSelect = document.getElementById('categoria-edt');
                if (categoriaEditSelect) {
                    categoriaEditSelect.innerHTML = '<option value="">Seleccionar categoría</option>';
                    data.forEach(categoria => {
                        const option = document.createElement('option');
                        option.value = categoria.id;
                        option.textContent = categoria.nombre;
                        categoriaEditSelect.appendChild(option);
                    });
                }
            })
            .catch(error => console.error('Error al cargar categorías:', error));
    }

    /**
     * Load units from server
     */
    function loadUnits() {
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
                const unidadesEditSelect = document.getElementById('unidades-edt');
                if (unidadesEditSelect) {
                    unidadesEditSelect.innerHTML = '<option value="">Seleccionar unidad</option>';
                    data.forEach(unidad => {
                        const option = document.createElement('option');
                        option.value = unidad.id;
                        option.textContent = unidad.nombre;
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

    // Edit Button Click Handler
    $(document).on('click', '.btn-edt', function() {
        const productId = $(this).data('item');
        
        // Load product data
        $.ajax({
            url: '{{ url("/admin/kardex/productos") }}/' + productId,
            type: 'GET',
            success: function(response) {
                if (response.res) {
                    const product = response.data;
                    
                    // Populate form fields
                    $('#modal-edt-prod [name="cod_prod"]').val(product.id_producto);
                    $('#modal-edt-prod [name="nombre"]').val(product.nombre);
                    $('#modal-edt-prod [name="codigo"]').val(product.codigo);
                    $('#modal-edt-prod [name="detalle"]').val(product.detalle);
                    $('#modal-edt-prod [name="categoria"]').val(product.categoria);
                    $('#modal-edt-prod [name="unidad"]').val(product.unidad);
                    $('#modal-edt-prod [name="precio"]').val(product.precio);
                    $('#modal-edt-prod [name="costo"]').val(product.costo);
                    $('#modal-edt-prod [name="cantidad"]').val(product.cantidad);
                    $('#modal-edt-prod [name="codSunat"]').val(product.codsunat);
                    $('#modal-edt-prod [name="afecto"]').val(product.iscbp);
                    $('#modal-edt-prod [name="usar_barra"]').val(product.usar_barra);
                    
                    // Handle image
                    if (product.imagen) {
                        $('#img-preview').attr('src', `/storage/productos/${product.imagen}`).show();
                        $('#image-edit-button').show();
                        $('#no-image-message').hide();
                    } else {
                        $('#img-preview').hide();
                        $('#image-edit-button').hide();
                        $('#no-image-message').show();
                    }
                    
                    // Handle multiprecio toggle
                    const usarMultiprecio = product.usar_multiprecio === '1' || product.usar_multiprecio === 1;
                    $('#usar_multiprecio_edit').prop('checked', usarMultiprecio);
                    
                    // If multiprecio is enabled, load multi-prices
                    if (usarMultiprecio) {
                        loadMultiPrecios(product.id_producto);
                    }
                    
                    // Show the modal
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

    // Function to load multi-prices
    function loadMultiPrecios(productId) {
        $.ajax({
            url: '{{ url("/admin/kardex/productos") }}/' + productId + '/multi-precios',
            type: 'GET',
            success: function(response) {
                if (response.res) {
                    // Clear previous prices
                    $('#modal-edt-prod tbody').empty();
                    
                    const precios = response.precios;
                    
                    if (precios.length > 0) {
                        // Add each price to the table
                        precios.forEach(function(precio, index) {
                            const row = `
                                <tr>
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
                            $('#modal-edt-prod tbody').append(row);
                        });
                    } else {
                        // Show "no prices" message
                        const noPrecios = `
                            <tr>
                                <td colspan="3" class="text-center text-muted" style="padding: 4px;">
                                    No hay precios configurados. Haga clic en "Agregar" para crear uno.
                                </td>
                            </tr>
                        `;
                        $('#modal-edt-prod tbody').append(noPrecios);
                    }
                }
            }
        });
    }

    // Update Form Submission
    $('#modal-edt-prod form').on('submit', function(e) {
        e.preventDefault();

        // Forzar actualización de todos los campos antes del envío
        $('#productoForm input, #productoForm select, #productoForm textarea').each(function() {
            $(this).trigger('change');
        });
                
        // Reset any previous error messages
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        const productId = $('[name="cod_prod"]').val();
        
        // Create FormData object to handle file uploads
        const formData = new FormData(this);
        
        // Add multiprecio value
        formData.append('usar_multiprecio', $('#usar_multiprecio_edit').is(':checked') ? '1' : '0');
        
        // AJAX submission
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
                    // If usar_multiprecio is checked, save multi-prices
                    if ($('#usar_multiprecio_edit').is(':checked')) {
                        saveMultiPrecios(productId);
                    } else {
                        showSuccessAndReload();
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
                handleFormErrors(xhr);
            }
        });
    });

    // Function to save multi-prices
    function saveMultiPrecios(productId) {
        const precios = [];
        
        // Get all precio rows
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

    $(document).on('click', '.btn-agregar-precio', function() {
        addPrecioRow('#modal-edt-prod tbody');
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

    // Eliminar Precio button
    $(document).on('click', '.eliminarPrecioBtn', function() {
        const row = $(this).closest('tr');
        const tbody = row.parent();
        
        row.remove();
        
        // If no more price rows, show "no prices" message
        if (tbody.find('tr').length === 0) {
            const noPrecios = `
                <tr id="noPreciosRow">
                    <td colspan="3" class="text-center text-muted" style="padding: 4px;">
                        No hay precios configurados. Haga clic en "Agregar" para crear uno.
                    </td>
                </tr>
            `;
            tbody.append(noPrecios);
        }
    });

    // Delete product
    let selectedProductIds = [];

    // Checkbox selection
    $(document).on('change', '.btnCheckEliminar', function() {
        const productId = $(this).data('id');
        
        if (this.checked) {
            if (!selectedProductIds.includes(productId)) {
                selectedProductIds.push(productId);
            }
        } else {
            selectedProductIds = selectedProductIds.filter(id => id !== productId);
        }
    });

    // Select All checkbox
    $('.btnSeleccionarTodos').on('change', function() {
        if (this.checked) {
            $('.btnCheckEliminar').prop('checked', true);
            selectedProductIds = [];
            $('.btnCheckEliminar').each(function() {
                selectedProductIds.push($(this).data('id'));
            });
        } else {
            $('.btnCheckEliminar').prop('checked', false);
            selectedProductIds = [];
        }
    });

    // Delete button click
    $('.btnBorrar').on('click', function() {
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
            text: 'Esta acción eliminará los productos seleccionados. ¡No podrá revertir esto!',
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
                    type: 'POST',
                    data: {
                        arrayId: selectedProductIds.map(id => ({ id }))
                    },
                    success: function(response) {
                        if (response.res) {
                            Swal.fire({
                                title: '¡Eliminados!',
                                text: 'Los productos han sido eliminados exitosamente',
                                icon: 'success'
                            }).then(() => {
                                // Clear selection
                                selectedProductIds = [];
                                $('.btnCheckEliminar, .btnSeleccionarTodos').prop('checked', false);
                                
                                // Reload table
                                productsTable.ajax.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.error || 'No se pudieron eliminar los productos',
                                icon: 'error'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un error al procesar la solicitud',
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

    // Ver Precios button
    $(document).on('click', '.btn-ver-precios', function() {
        const productId = $(this).data('item');
        
        $.ajax({
            url: `/admin/kardex/productos/${productId}/precios`,
            type: 'GET',
            success: function(response) {
                $('#precio_unidad').val(response.precio_unidad || 0);
                $('#precio1').val(response.precio || 0);
                $('#precio2').val(response.precio2 || 0);
                $('#precio3').val(response.precio3 || 0);
                $('#precio4').val(response.precio4 || 0);
                
                $('#modal-precios form').data('productId', productId);
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

let codeBarraTemps = '';
let nombreBarraTemps = '';

/**
 * Open the barcode modal and generate barcode
 * @param {string} code - The code to generate barcode for
 * @param {string} nombre - The product name
 */
function abrirModalBarras(code, nombre = '') {
    code = code.trim();
    nombreBarraTemps = nombre;
    codeBarraTemps = code;
    
    // Show the modal
    $('#modalCodigoBarras').modal('show');
    
    // Set product name if provided
    if (nombre) {
        $('#barcodeProductName').text(nombre);
    }
    
    // Wait for modal to be shown before generating barcode
    $('#modalCodigoBarras').on('shown.bs.modal', function() {
        setTimeout(function() {
            try {
                JsBarcode("#idCodigoBarras", code);
            } catch (error) {
                console.error("Error al generar código de barras:", error);
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo generar el código de barras',
                    icon: 'error'
                });
            }
        }, 100);
    });
}

/**
 * Print barcode (format 1)
 */
function imprimir() {
    window.open(`/admin/ge/bar/code?code=${codeBarraTemps}&nombre=${nombreBarraTemps}&scal=${$("#scalimg").val()}`, "_blank");
}

/**
 * Print barcode (format 2)
 */
function imprimir2() {
    window.open(`/admin/ge/bar/code2?code=${codeBarraTemps}&nombre=${nombreBarraTemps}&scal=${$("#scalimg").val()}`, "_blank");
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
</script>
@endpush