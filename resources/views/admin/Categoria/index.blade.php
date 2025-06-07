@extends('layouts.admin')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="page-title-box" style="padding: 12px 0;">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h6 class="page-title text-center">Categorias</h6>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card" style="border-radius:20px;box-shadow:0 4px 6px -1px rgba(0,0,0,.1),0 2px 4px -1px rgba(0,0,0,.06)">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <button
                            type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#modalCategoria"
                            class="btn-accion"
                        >
                            <i class="fa fa-plus mr-2"></i> Agregar
                        </button>
                        <!--   <button type="button" data-bs-toggle="modal" data-bs-target="#editarModal" class="btn btn-warning">Editar</button> -->
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('admin.kardex.index') }}" class="btn border-rojo text-rojo bg-white"><i class="fa fa-arrow-left"></i> Regresar</a> {{-- Cambiado el href para que apunte a la ruta del Kardex --}}
                    </div>
                </div>
            </div>
            <div id="conte-vue-modals">
                <div class="card-body">

                    <div class="card-title-desc">
                        <div class="table-responsive">
                            <table id="tabla_clientes" class="table table-bordered dt-responsive nowrap text-center table-sm dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Categoría</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalCategoria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-rojo text-white">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar Categoria</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="addCategoria">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nombreCategoria" class="form-label">Nombre de la categoría</label>
                                    <input type="text" class="form-control" id="nombreCategoria">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn" data-bs-dismiss="modal" style="border: 1px solid black;">Cerrar</button>
                                <button type="button" id="submitCategoria" class="btn-accion">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="updateCategoria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-rojo text-white">
                            <h5 class="modal-title" id="exampleModalLabel">Actualizar Categoria</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="updateCategoriaForm">
                            <div class="modal-body">
                                <input type="text" id="idCatU" value="" hidden>
                                <div class="mb-3">
                                    <label for="nombreCategoriaU" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombreCategoriaU">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" id="updateCategoriaBtn" class="btn-accion">Guardar</button>
                            </div>
                        </form>
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
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection

@push('scripts')
<!-- NUEVO SCRIPT - Reemplazar el script anterior por este -->
<script>
$(document).ready(function() {

    // TEST - Agregar estas líneas para verificar jQuery
    console.log('jQuery cargado:', typeof $ !== 'undefined');
    console.log('Botón existe:', $('#submitCategoria').length);
    
    // Tu código existente continúa aquí...

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || window.Laravel.csrfToken
        }
    });

    tabla_clientes = $("#tabla_clientes").DataTable({
        paging: true,
        bFilter: true,
        ordering: true,
        searching: true,
        destroy: true,
        ajax: {
            url: "{{ url('/admin/categorias/get') }}",
            method: "GET",
            dataSrc: "",
        },
        language: {
            url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json", // Idioma español
        },
        columns: [{
                data: "id",
                class: "text-center",
            },
            {
                data: "nombre",
                class: "text-center",
            },
            {
                data: null,
                class: "text-center",
                render: function(data, type, row) {
                    return `<div class="text-center">
                        <div class="btn-group btn-sm">
                            <button data-id="${Number(row.id)}" class="btn btn-sm btn-warning btnEditar">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button data-id="${Number(row.id)}" class="btn btn-sm btn-danger btnBorrar">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>`;
                },
            },
        ],
    });

    // Guardar nueva categoría - FUNCIÓN CORREGIDA
    $(document).on('click', '#submitCategoria', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('Botón clickeado'); // Para debug
    
        const nombreCategoria = $('#nombreCategoria').val().trim();
        if (!nombreCategoria) {
            Swal.fire({
                title: "Error",
                text: "El nombre de la categoría es obligatorio",
                icon: "error"
            });
            return;
        }

        // Deshabilitar botón mientras se procesa
        $('#submitCategoria').prop('disabled', true).text('Guardando...');
        
        $.ajax({
            url: "{{ url('/admin/categorias/save') }}", 
            method: "POST",
            data: {
                nombre: nombreCategoria,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                // ← Asegurar que se muestre el Swal y se limpie el formulario
                if (data.success) {
                    Swal.fire({
                        title: "Éxito",
                        text: "Se guardó correctamente",
                        icon: "success"
                    });

                    // Limpiar campo y cerrar modal
                    $('#nombreCategoria').val('');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalCategoria'));
                    if (modal) modal.hide();

                    // Recargar tabla
                    tabla_clientes.ajax.reload();
                } else {
                    Swal.fire({
                        title: "Error",
                        text: "No se pudo guardar la categoría",
                        icon: "error"
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al guardar categoría:", xhr.responseText);
                Swal.fire({
                    title: "Error",
                    text: "No se pudo guardar la categoría: " + (xhr.responseJSON?.message || error),
                    icon: "error"
                });
            },
            complete: function() {
                // ← Rehabilitar botón siempre, incluso si falla
                $('#submitCategoria').prop('disabled', false).text('Guardar');
            }
        });
    });

    // Eliminar categoría
    $("#tabla_clientes").on("click", ".btnBorrar", function(event) {
        let id = $(this).data('id');
        Swal.fire({
            title: "¿Está seguro?",
            text: "El cambio es irreversible",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ url('/admin/categorias/delete') }}", { 
                    id: id
                }, function(data, textStatus, jqXHR) {
                    Swal.fire({
                        title: "¡Eliminado!",
                        text: "Eliminado correctamente",
                        icon: "success"
                    });
                    tabla_clientes.ajax.reload();
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Error al eliminar categoría: " + textStatus, errorThrown);
                    Swal.fire({
                        title: "Error",
                        text: "No se pudo eliminar la categoría",
                        icon: "error"
                    });
                });
            }
        });
    });

    // Cargar datos para editar
    $("#tabla_clientes").on("click", ".btnEditar", function(event) {
        let id = $(this).data('id');
        $.post("{{ url('/admin/categorias/getOne') }}", {
            id: id
        }, function(data, textStatus, jqXHR) {  // Movido paréntesis de cierre y coma
            $('#nombreCategoriaU').val(data[0].nombre);
            $('#idCatU').val(data[0].id);
            const modal = new bootstrap.Modal(document.getElementById('updateCategoria'));
            modal.show();
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error al cargar categoría: " + textStatus, errorThrown);
            Swal.fire({
                title: "Error",
                text: "No se pudo cargar los datos de la categoría",
                icon: "error"
            });
        });
    });

    // Actualizar categoría
    $('#updateCategoriaBtn').click(function() {
        $.post("{{ url('/admin/categorias/update') }}", { 
            nombre: $('#nombreCategoriaU').val(),
            id: $('#idCatU').val()
        }, function(data, textStatus, jqXHR) {  // Movido paréntesis de cierre y coma
            Swal.fire({
                title: "Éxito",
                text: "Se actualizó correctamente",
                icon: "success"
            });
            const modal = bootstrap.Modal.getInstance(document.getElementById('updateCategoria'));
            modal.hide();
            tabla_clientes.ajax.reload();
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error al actualizar categoría: " + textStatus, errorThrown);
            Swal.fire({
                title: "Error",
                text: "No se pudo actualizar la categoría",
                icon: "error"
            });
        });
    });

    // Agregar al final del document.ready
    $('[data-bs-dismiss="modal"]').on('click', function(e) {
        e.preventDefault();
        const modalId = $(this).closest('.modal').attr('id');
        const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
        if (modal) modal.hide();
    });
});
</script>
@endpush