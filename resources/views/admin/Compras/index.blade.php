@extends('layouts.admin')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">

        <div class="clearfix">
            <h6 class="page-title text-center">ORDEN DE COMPRA</h6>
            <ol class="breadcrumb m-0 float-start">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Compras</a></li>
                <li class="breadcrumb-item"><a href="/ventas" class="button-link">Orden de compra</a></li>

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
        <div class="card"
            style="border-radius:20px;box-shadow:0 4px 6px -1px rgba(0,0,0,.1),0 2px 4px -1px rgba(0,0,0,.06)">
            <div class="card-body">



                <div class="card-title-desc text-end">
                    <a href="/compras/add" class="btn bg-rojo text-white button-link">
                        <i class="fa fa-plus "></i> Agregar Compra
                    </a>
                    <a target="_blank" href="/jvc/reporte/compras" class="btn bg-white text-rojo "
                        style="border-radius: 10px; padding: 8px 16px; font-weight: 500; border: 1px solid #CA3438; margin-left: 8px; transition: all 0.3s ease;">
                        <i class="fa fa-file me-1"></i> Exportar Reporte
                    </a>

                </div>


                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap text-center table-sm"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                        <thead>
                            <tr>
                                <th style="text-align: center;">Id</th>
                                <th style="text-align: center;">F. Emision</th>
                                <th style="text-align: center;">F. Vencimiento</th>
                                <th style="text-align: center;">Serie</th>
                                <th style="text-align: center;">Numero</th>
                                <th style="text-align: center;" width="50%">Razon Social</th>
                                <th style="text-align: center;">Usuario</th>
                                <th style="text-align: center;">Detalles</th>
                                <th style="text-align: center;">Reporte</th>
                            </tr>
                        </thead>

                    </table>
                </div>

                <div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 50%;" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-rojo text-white">
                                <h5 class="modal-title" id="exampleModalLabel">Agregar</h5>
                                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="productos-tab" data-bs-toggle="tab"
                                            data-bs-target="#productos" type="button" role="tab"
                                            aria-controls="productos" aria-selected="true">Productos</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pagos-tab" data-bs-toggle="tab"
                                            data-bs-target="#pagos" type="button" role="tab" aria-controls="pagos"
                                            aria-selected="false">Pagos</button>
                                    </li>
                                </ul>
                                <div class="tab-content pt-3" id="myTabContent">
                                    <div class="tab-pane fade show active" id="productos" role="tabpanel"
                                        aria-labelledby="productos-tab">
                                        <table id="datatableProductoDetalle"
                                            class="table table-bordered dt-responsive text-center table-sm"
                                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">Código</th>
                                                    <th style="text-align: center;">Producto</th>
                                                    <th style="text-align: center;">Cantidad</th>
                                                    <th style="text-align: center;">Precio</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="pagos" role="tabpanel" aria-labelledby="pagos-tab">
                                        <div id="infoPagos">
                                            <div class="alert alert-info mb-3">
                                                <strong>Tipo de pago:</strong> <span id="tipoPagoText"></span>
                                            </div>
                                            <table id="datatablePagosDetalle"
                                                class="table table-bordered dt-responsive text-center table-sm"
                                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center;">Fecha</th>
                                                        <th style="text-align: center;">Monto</th>
                                                        <th style="text-align: center;">Estado</th>
                                                    </tr>
                                                </thead>
                                            </table>
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
<style>
    .bg-gradient {
        background: linear-gradient(135deg, #ffffff 0%, #f0f7ff 100%);
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .card {
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .table-dark {
        background: linear-gradient(90deg, #1755eb 0%, #01e4ff 100%);
        color: white;
    }
    
    .btn-primary {
        background: #1755eb;
        border-color: #1755eb;
    }
    
    .btn-primary:hover {
        background: #0f46d9;
        border-color: #0f46d9;
    }
    
    .btn-outline-primary {
        color: #1755eb;
        border-color: #1755eb;
    }
    
    .btn-outline-primary:hover {
        background-color: #1755eb;
        color: white;
    }
   .shadow-lg {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
    }
    
    .form-label {
        font-weight: 500;
        color: #4a5568;
    }

    /* Estilos para hacer las tablas de precios más compactas */
    .table-bordered.table-hover {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse;
    }

    /* Eliminar espacios en las celdas de la tabla */
    .table-bordered.table-hover th,
    .table-bordered.table-hover td {
        padding: 0.4rem !important;
        vertical-align: middle !important;
    }

    /* Ajustar el ancho de las columnas */
    .table-bordered.table-hover th:first-child,
    .table-bordered.table-hover td:first-child {
        width: 50%;
    }

    .table-bordered.table-hover th:nth-child(2),
    .table-bordered.table-hover td:nth-child(2) {
        width: 35%;
    }

    .table-bordered.table-hover th:last-child,
    .table-bordered.table-hover td:last-child {
        width: 15%;
        text-align: center;
    }

    /* Eliminar espacios en los input-group dentro de las tablas */
    .table-bordered.table-hover .input-group {
        margin: 0 !important;
    }

    /* Ajustar el padding de los input dentro de las tablas */
    .table-bordered.table-hover .input-group .form-control,
    .table-bordered.table-hover .input-group .input-group-text {
        padding: 0.25rem 0.5rem !important;
        height: auto !important;
    }

    /* Hacer los botones más compactos */
    .table-bordered.table-hover .btn-sm {
        padding: 0.2rem 0.4rem !important;
        font-size: 0.875rem !important;
    }

    /* Ajustar el card-body que contiene la tabla */
    .card-body .table-responsive {
        padding: 0 !important;
        margin: 0 !important;
    }

    /* Eliminar espacios en el encabezado de la tabla */
    .table-light th {
        padding: 0.5rem !important;
    }

    /* Estilos para cursor pointer */
    .cursor-pointer {
        cursor: pointer;
    }

    /* Estilos para hover en menús */
    .hover-bg-light:hover {
        background-color: rgba(23, 85, 235, 0.05);
    }

    /* Estilos para los botones e inputs */
    .btn, .form-control, .form-select, .card {
        border-radius: 8px;
    }

    .input-group-text {
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }

    .input-group .form-control:not(:first-child) {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .input-group .btn:not(:first-child) {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .input-group .btn:not(:last-child) {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    /* Estilo para checkbox más grandes */
    .form-check-input {
        width: 1.2em;
        height: 1.2em;
    }

    /* Transiciones suaves */
    .btn, .form-control, .form-select, .card, .modal {
        transition: all 0.3s ease;
    }

    /* Animación para modales */
    .modal.fade .modal-dialog {
        transform: scale(0.95);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .modal.show .modal-dialog {
        transform: none;
        opacity: 1;
    }

    /* Mejoras en la tabla principal */
    #datatable thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    #datatable tbody td {
        vertical-align: middle;
        padding: 0.75rem;
    }

    /* Efectos de hover para botones */
    .btn-primary:hover {
        box-shadow: 0 4px 10px rgba(23, 85, 235, 0.3);
        transform: translateY(-1px);
    }

    .btn-outline-primary:hover {
        box-shadow: 0 4px 10px rgba(23, 85, 235, 0.2);
        transform: translateY(-1px);
    }

    .btn-danger:hover {
        box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
        transform: translateY(-1px);
    }

    /* Modo oscuro para los modales */
    .modal-content {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }

    /* Animación para el botón de eliminar */
    .btnBorrar:hover i {
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-2px); }
        40%, 80% { transform: translateX(2px); }
    }

    /* Efecto de pulsación para el botón de agregar */
    .btn-primary:active {
        transform: scale(0.98);
    }

    /* Efecto de elevación para las cards */
    .card:hover {
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
</style>
@endsection