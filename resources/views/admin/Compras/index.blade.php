@extends('layouts.admin')

@section('content')
<div class="page-title-box mb-3">
    <div class="row align-items-center">
        <div class="clearfix">
            <h6 class="page-title text-center">ORDEN DE COMPRA</h6>
            <ol class="breadcrumb m-0 float-start">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);" class="text-gray-800 font-bold no-underline hover:text-gray-900" style="all: unset; display: inline; font-weight: bold; color: #1f2937;">
                        Almacén
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);" style="all: unset; color: #1755eb; font-weight: 600; cursor: pointer;">
                        Compras
                    </a>
                </li>
            </ol>
        </div>
        <div class="col-md-4">
            <div class="float-end d-none d-md-block"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card" style="border-radius:20px;box-shadow:0 4px 6px -1px rgba(0,0,0,.1),0 2px 4px -1px rgba(0,0,0,.06)">
            <div class="card-body">
                <!-- Filtros de búsqueda -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Fecha desde:</label>
                        <input type="date" id="fechaDesde" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fecha hasta:</label>
                        <input type="date" id="fechaHasta" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado:</label>
                        <select id="filtroEstado" class="form-select">
                            <option value="">Todos</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="completo">Recepcionado</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" id="btnFiltrar" class="btn btn-primary me-2">
                            <i class="fa fa-search"></i> Filtrar
                        </button>
                        <button type="button" id="btnLimpiar" class="btn btn-outline-secondary">
                            <i class="fa fa-refresh"></i> Limpiar
                        </button>
                    </div>
                </div>

                <div class="card-title-desc text-end mb-2">
                    <a href="{{ route('admin.compras.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Agregar Compra
                    </a>
                    <a target="_blank" href="/jvc/reporte/compras" class="btn btn-outline-primary button-link" style="color: white; background-color: #212529; border: none;">
                        <i class="fa fa-file me-1"></i> Exportar Reporte
                    </a>
                </div>

                <div class="table-responsive mb-4">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap text-center table-sm" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th style="text-align: center;">ID</th>
                                <th style="text-align: center;">F. Emisión</th>
                                <th style="text-align: center;">F. Vencimiento</th>
                                <th style="text-align: center;">Serie</th>
                                <th style="text-align: center;">Número</th>
                                <th style="text-align: center;" width="30%">Razón Social</th>
                                <th style="text-align: center;">Usuario</th>
                                <th style="text-align: center;">Total</th>
                                <th style="text-align: center;">Estado</th>
                                <th style="text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <!-- Modal Detalle de Compra -->
                <div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog" aria-labelledby="modalDetalleLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="modalDetalleLabel">
                                    <i class="fa fa-eye"></i> Detalle de Compra
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Información general -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <div class="row">
                                                <div class="col-md-3"><strong>Compra:</strong> <span id="detalleCompraId"></span></div>
                                                <div class="col-md-3"><strong>Serie-Número:</strong> <span id="detalleSerieNumero"></span></div>
                                                <div class="col-md-3"><strong>Fecha:</strong> <span id="detalleFecha"></span></div>
                                                <div class="col-md-3"><strong>Total:</strong> <span id="detalleTotal"></span></div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6"><strong>Proveedor:</strong> <span id="detalleProveedor"></span></div>
                                                <div class="col-md-6"><strong>Tipo Pago:</strong> <span id="detalleTipoPago"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tabs -->
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="productos-tab" data-bs-toggle="tab" data-bs-target="#productos" type="button" role="tab" aria-controls="productos" aria-selected="true">
                                            <i class="fa fa-box"></i> Productos
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pagos-tab" data-bs-toggle="tab" data-bs-target="#pagos" type="button" role="tab" aria-controls="pagos" aria-selected="false">
                                            <i class="fa fa-credit-card"></i> Pagos
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content pt-3" id="myTabContent">
                                    <!-- Tab Productos -->
                                    <div class="tab-pane fade show active" id="productos" role="tabpanel" aria-labelledby="productos-tab">
                                        <div class="mb-3">
                                            <input type="text" id="buscarProducto" class="form-control" placeholder="Buscar producto...">
                                        </div>
                                        <table id="datatableProductoDetalle" class="table table-bordered dt-responsive text-center table-sm" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tablaProductosBody">
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Tab Pagos -->
                                    <div class="tab-pane fade" id="pagos" role="tabpanel" aria-labelledby="pagos-tab">
                                        <table id="datatablePagosDetalle" class="table table-bordered dt-responsive text-center table-sm" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Monto</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tablaPagosBody">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Recepción de Productos -->
                <div class="modal fade" id="modalRecepcion" tabindex="-1" role="dialog" aria-labelledby="modalRecepcionLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title" id="modalRecepcionLabel">
                                    <i class="fa fa-truck"></i> Recepción de Productos
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="listaProductosRecepcion"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fa fa-times"></i> Cancelar
                                </button>
                                <button type="button" id="btnGuardarRecepcion" class="btn btn-accion btn-success">
                                    <i class="fa fa-check"></i>&nbsp;Recepcionar Productos
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Escaneo de Códigos -->
                <div class="modal fade" id="modal-escaneo-codigos" tabindex="-1" aria-labelledby="modalEscaneoLabel" aria-modal="true" role="dialog">
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
                                            <strong>Progreso:</strong> <span id="contador-codigos">0 / 1</span> códigos escaneados
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="estado-escaneo" class="alert mb-0 alert-warning">
                                            <i class="fa fa-clock"></i> Esperando códigos...
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fa fa-times"></i> Cancelar
                                </button>
                                <button type="button" id="btn-guardar-escaneados" class="btn btn-success" disabled>
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap JS (esto debe ir después de jQuery) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- AGREGAR DATATABLES -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Agregar después de las otras librerías JS -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
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
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(23, 85, 235, 0.3);
    }
    
    .btn-outline-primary {
        color: #1755eb;
        border-color: #1755eb;
    }
    
    .btn-outline-primary:hover {
        background-color: #1755eb;
        color: white;
        transform: translateY(-1px);
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
    }

    .badge-success {
        background-color: #28a745;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 0.375rem;
    }

    .modal-xl {
        max-width: 90%;
    }

    .nav-tabs .nav-link {
        border: 1px solid transparent;
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
    }

    .nav-tabs .nav-link.active {
        background-color: #1755eb;
        color: white;
        border-color: #1755eb;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .table tbody td {
        vertical-align: middle;
        padding: 0.75rem;
    }

    .form-control:focus {
        border-color: #1755eb;
        box-shadow: 0 0 0 0.2rem rgba(23, 85, 235, 0.25);
    }

    .btn:hover {
        transform: translateY(-1px);
        transition: all 0.3s ease;
    }

    .alert {
        border-radius: 0.5rem;
    }

    .modal-content {
        border-radius: 0.75rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
    }

    .tooltip-inner {
        background-color: #212529;
        color: white;
        border-radius: 0.375rem;
    }

    .codigo-escaneado {
        background-color: #28a745;
        color: white;
        padding: 5px 10px;
        margin: 2px;
        border-radius: 4px;
        display: inline-block;
        font-family: 'Courier New', monospace;
    }

    .producto-recepcion {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background-color: #f8f9fa;
    }

    .producto-recepcion.tipo-unico {
        border-color: #17a2b8;
        background-color: #e3f2fd;
    }
</style>

<script>
$(document).ready(function() {
    // Variables globales
    let datatable;
    let compraActualRecepcion = null;
    let productoActualEscaneo = null;
    let codigosEscaneados = [];

    // MODIFICADO - DataTable con filtros funcionales
    function inicializarDataTable() {
        datatable = $('#datatable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '{{ route("admin.compras.obtener") }}',
                type: 'GET',
                data: function(d) {
                    // NUEVO - Enviar parámetros de filtro
                    d.fecha_desde = $('#fechaDesde').val();
                    d.fecha_hasta = $('#fechaHasta').val();
                    d.estado = $('#filtroEstado').val();
                }
            },
            columns: [
                { data: 'id_compra', name: 'id_compra' },
                { data: 'fecha_emision', name: 'fecha_emision' },
                { data: 'fecha_vencimiento', name: 'fecha_vencimiento' },
                { data: 'serie', name: 'serie' },
                { data: 'numero', name: 'numero' },
                { data: 'razon_social', name: 'razon_social' },
                { data: 'usuario', name: 'usuario' },
                { data: 'total', name: 'total' },
                { 
                    data: 'estado_recepcion', 
                    name: 'estado_recepcion',
                    render: function(data) {
                        if (data === 'completo') {
                            return '<span class="badge badge-success">Recepcionado</span>';
                        } else {
                            return '<span class="badge badge-warning">Pendiente</span>';
                        }
                    }
                },
                { 
                    data: 'acciones', 
                    name: 'acciones',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        let botones = `
                            <button class="btn btn-primary btn-sm me-1" onclick="verDetalle(${data})" title="Ver detalles">
                                <i class="fa fa-eye"></i>
                            </button>
                            <button class="btn btn-info btn-sm me-1" onclick="generarReporte(${data})" title="Ver reporte">
                                <i class="fa fa-file-pdf"></i>
                            </button>
                        `;
                        
                        if (row.estado_recepcion === 'pendiente') {
                            botones += `
                                <button class="btn btn-success btn-sm" onclick="abrirRecepcion(${data})" title="Recepcionar">
                                    <i class="fa fa-truck"></i>
                                </button>
                            `;
                        }
                        
                        return botones;
                    }
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            responsive: true,
            order: [[0, 'desc']]
        });

        // NUEVO - Estilizar botones de paginación
        $('#datatable').on('draw.dt', function () {
            $('.paginate_button').addClass('bg-[#1754e9] text-white text-sm px-3 py-1 rounded-full mx-1 hover:opacity-90 no-underline');
            $('.paginate_button').css({
                'text-decoration': 'none',
                'position': 'relative',
                'top': '5px'
            });

            $('.paginate_button.disabled').addClass('opacity-50 cursor-not-allowed');

            $('.paginate_button.current')
                .removeClass('bg-[#1754e9]')
                .addClass('bg-[#0a3ac7]')
                .css({
                    'position': 'relative',
                    'background-color': '#eb8826'
                });

            $('#datatable_paginate').addClass('flex justify-center mt-4');
        });
    }

    // Ver detalle de compra
    window.verDetalle = function(id) {
        $.ajax({
            url: `/admin/compras/${id}/detalle`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    // Llenar información general
                    $('#detalleCompraId').text('#' + response.compra.id);
                    $('#detalleSerieNumero').text(response.compra.serie + '-' + response.compra.numero);
                    $('#detalleFecha').text(response.compra.fecha_emision);
                    $('#detalleTotal').text((response.compra.moneda === 'S' ? 'S/ ' : 'USD ') + response.compra.total);
                    $('#detalleProveedor').text(response.compra.proveedor);
                    $('#detalleTipoPago').text(response.compra.tipo_pago);

                    // Llenar tabla de productos
                    let productosHtml = '';
                    response.productos.forEach(function(producto) {
                        productosHtml += `
                            <tr>
                                <td>${producto.codigo}</td>
                                <td>${producto.nombre}</td>
                                <td>${producto.cantidad}</td>
                                <td>${producto.precio}</td>
                                <td>${producto.subtotal}</td>
                            </tr>
                        `;
                    });
                    $('#tablaProductosBody').html(productosHtml);

                    // Llenar tabla de pagos
                    let pagosHtml = '';
                    response.pagos.forEach(function(pago) {
                        let estadoBadge = pago.estado === 'Pagado' ? 
                            '<span class="badge badge-success">Pagado</span>' : 
                            '<span class="badge badge-warning">Pendiente</span>';
                        
                        let accionBoton = pago.estado_codigo === 'P' ? 
                            `<button class="btn btn-success btn-sm" onclick="marcarPagado(${pago.id})">
                                <i class="fa fa-check"></i> Marcar Pagado
                            </button>` : 
                            '<span class="text-muted">-</span>';

                        pagosHtml += `
                            <tr>
                                <td>${pago.fecha}</td>
                                <td>${pago.monto}</td>
                                <td>${estadoBadge}</td>
                                <td>${accionBoton}</td>
                            </tr>
                        `;
                    });
                    $('#tablaPagosBody').html(pagosHtml);

                    $('#modalDetalle').modal('show');
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Error al obtener el detalle', 'error');
            }
        });
    };

    // Generar reporte PDF
    window.generarReporte = function(id) {
        window.open(`/admin/compras/${id}/reporte`, '_blank');
    };

    // Abrir modal de recepción
    window.abrirRecepcion = function(id) {
        compraActualRecepcion = id;
        
        $.ajax({
            url: `/admin/compras/${id}/productos-recepcion`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    let productosHtml = '';
                    
                    response.productos.forEach(function(producto, index) {
                        productosHtml += `
                            <div class="producto-recepcion ${producto.tipo_control === 'UNICO' ? 'tipo-unico' : ''}" data-producto-id="${producto.id_producto}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>${producto.codigo} - ${producto.nombre}</h6>
                                        <p class="mb-1"><strong>Cantidad esperada:</strong> ${producto.cantidad}</p>
                                        <p class="mb-1"><strong>Tipo control:</strong> ${producto.tipo_control}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Cantidad a recepcionar:</label>
                                        <input type="number" class="form-control cantidad-recepcion" 
                                               value="${producto.cantidad}" min="0" max="${producto.cantidad}"
                                               data-producto-id="${producto.id_producto}">
                                        
                                        ${producto.tipo_control === 'UNICO' ? `
                                            <button type="button" class="btn btn-info btn-sm mt-2" 
                                                    onclick="abrirEscaneo('${producto.id_producto}', '${producto.nombre}', ${producto.cantidad})">
                                                <i class="fa fa-qrcode"></i> Escanear Códigos
                                            </button>
                                            <div class="codigos-producto mt-2" data-producto-id="${producto.id_producto}">
                                                <small class="text-muted">Códigos escaneados: <span class="contador-codigos">0</span></small>
                                            </div>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    
                    $('#listaProductosRecepcion').html(productosHtml);
                    $('#modalRecepcion').modal('show');
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Error al obtener productos', 'error');
            }
        });
    };

    // Abrir modal de escaneo
    window.abrirEscaneo = function(idProducto, nombreProducto, cantidad) {
        productoActualEscaneo = idProducto;
        codigosEscaneados = [];
        
        $('#producto-escaneo').val(nombreProducto);
        $('#cantidad-esperada').val(cantidad);
        $('#codigo-escaneado').val('');
        $('#lista-codigos-escaneados').html(`
            <div class="text-center text-muted" id="mensaje-vacio">
                <i class="fa fa-barcode" style="font-size: 24px; margin-bottom: 10px;"></i><br>
                No hay códigos escaneados
            </div>
        `);
        $('#contador-codigos').text(`0 / ${cantidad}`);
        $('#estado-escaneo').removeClass('alert-success').addClass('alert-warning').html('<i class="fa fa-clock"></i> Esperando códigos...');
        $('#btn-guardar-escaneados').prop('disabled', true);
        
        $('#modal-escaneo-codigos').modal('show');
        
        // Focus en el input de código
        setTimeout(() => {
            $('#codigo-escaneado').focus();
        }, 500);
    };

    // Agregar código escaneado
    function agregarCodigo() {
        const codigo = $('#codigo-escaneado').val().trim();
        const cantidadEsperada = parseInt($('#cantidad-esperada').val());
        
        if (!codigo) {
            Swal.fire('Error', 'Ingrese un código válido', 'error');
            return;
        }
        
        if (codigosEscaneados.includes(codigo)) {
            Swal.fire('Error', 'Este código ya fue escaneado', 'error');
            $('#codigo-escaneado').val('').focus();
            return;
        }
        
        if (codigosEscaneados.length >= cantidadEsperada) {
            Swal.fire('Error', 'Ya se escanearon todos los códigos necesarios', 'error');
            return;
        }
        
        // Reproducir sonido de escaneo
        reproducirSonidoEscaneo();
        
        codigosEscaneados.push(codigo);
        
        // Actualizar lista visual
        if (codigosEscaneados.length === 1) {
            $('#lista-codigos-escaneados').html('');
        }
        
        $('#lista-codigos-escaneados').append(`
            <div class="codigo-escaneado">
                ${codigo} <i class="fa fa-check"></i>
            </div>
        `);
        
        // Actualizar contador
        $('#contador-codigos').text(`${codigosEscaneados.length} / ${cantidadEsperada}`);
        
        // Verificar si está completo
        if (codigosEscaneados.length === cantidadEsperada) {
            $('#estado-escaneo').removeClass('alert-warning').addClass('alert-success').html('<i class="fa fa-check"></i> ¡Escaneo completo!');
            $('#btn-guardar-escaneados').prop('disabled', false);
        }
        
        $('#codigo-escaneado').val('').focus();
    }

    // Reproducir sonido de escaneo
    function reproducirSonidoEscaneo() {
        // Crear un beep usando Web Audio API
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = 800;
        oscillator.type = 'square';
        
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.1);
    }

    // Event listeners para escaneo
    $('#codigo-escaneado').on('keypress', function(e) {
        if (e.which === 13) { // Enter
            e.preventDefault();
            agregarCodigo();
        }
    });

    $('#btn-agregar-codigo-manual').on('click', function() {
        agregarCodigo();
    });

    // Guardar códigos escaneados
    $('#btn-guardar-escaneados').on('click', function() {
        if (productoActualEscaneo && codigosEscaneados.length > 0) {
            // Actualizar el contador en el modal de recepción
            $(`.codigos-producto[data-producto-id="${productoActualEscaneo}"] .contador-codigos`).text(codigosEscaneados.length);
            
            // Guardar códigos en el elemento para usar después
            $(`.producto-recepcion[data-producto-id="${productoActualEscaneo}"]`).data('codigos', codigosEscaneados);
            
            $('#modal-escaneo-codigos').modal('hide');
            
            Swal.fire('Éxito', `${codigosEscaneados.length} códigos guardados correctamente`, 'success');
        }
    });

    // Guardar recepción completa
    $('#btnGuardarRecepcion').on('click', function() {
        const productos = [];
        
        $('.producto-recepcion').each(function() {
            const idProducto = $(this).data('producto-id');
            const cantidad = $(this).find('.cantidad-recepcion').val();
            const codigos = $(this).data('codigos') || [];
            
            if (cantidad > 0) {
                productos.push({
                    id_producto: idProducto,
                    cantidad: cantidad,
                    codigos_unicos: codigos
                });
            }
        });
        
        if (productos.length === 0) {
            Swal.fire('Error', 'Debe recepcionar al menos un producto', 'error');
            return;
        }
        
        $.ajax({
            url: `/admin/compras/${compraActualRecepcion}/recepcionar`,
            type: 'POST',
            data: {
                productos: productos,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#modalRecepcion').modal('hide');
                    Swal.fire('Éxito', response.message, 'success');
                    datatable.ajax.reload();
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Error al recepcionar productos', 'error');
            }
        });
    });

    // Marcar pago como pagado
    window.marcarPagado = function(id) {
        Swal.fire({
            title: '¿Confirmar pago?',
            text: '¿Está seguro de marcar este pago como completado?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, marcar como pagado',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/compras/pago/${id}/marcar-pagado`,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Éxito', response.message, 'success');
                            // Recargar el detalle
                            const compraId = $('#detalleCompraId').text().replace('#', '');
                            verDetalle(compraId);
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Error al actualizar el pago', 'error');
                    }
                });
            }
        });
    };

    // MODIFICADO - Filtros con recarga de datos
    $('#btnFiltrar').on('click', function() {
        datatable.ajax.reload(); // MODIFICADO - Ahora enviará los parámetros de filtro
    });

    $('#btnLimpiar').on('click', function() {
        $('#fechaDesde').val('');
        $('#fechaHasta').val('');
        $('#filtroEstado').val('');
        datatable.ajax.reload(); // MODIFICADO - Recarga sin filtros
    });


    // Búsqueda en productos del detalle
    $('#buscarProducto').on('keyup', function() {
        const valor = $(this).val().toLowerCase();
        $('#tablaProductosBody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1);
        });
    });

    // Inicializar tooltips
    $('[title]').tooltip();

    console.log('no se inicializa, pero si llega hasta aqui')
    // Inicializar DataTable
    inicializarDataTable();
    console.log('se inicializa');
});
</script>
@endsection