@extends('layouts.admin')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="page-title-box" style="padding: 12px 0;">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h6 class="page-title text-center">Unidades de Medida</h6>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card" style="border-radius:20px;box-shadow:0 4px 6px -1px rgba(0,0,0,.1),0 2px 4px -1px rgba(0,0,0,.06)">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#modalUnidad" class="btn-accion"><i class="fa fa-plus"></i> Añadir</button>
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
                            <table id="tabla_unidades" class="table table-bordered dt-responsive nowrap text-center table-sm dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Unidades</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Agregar Unidad -->
            <div class="modal fade" id="modalUnidad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-rojo text-white">
                            <h5 class="modal-title text-black" id="exampleModalLabel">Agregar Unidad de Medida</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="addUnidadForm">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nombreUnidad" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombreUnidad" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn border-rojo text-rojo bg-white" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" id="submitUnidad" class="btn-accion">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Actualizar Unidad -->
            <div class="modal fade" id="updateUnidad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-rojo text-white">
                            <h5 class="modal-title" id="exampleModalLabel">Actualizar Unidad de Medida</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="updateUnidadForm">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" id="idUniU" value="">
                                <div class="mb-3">
                                    <label for="nombreUnidadU" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombreUnidadU" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn border-rojo text-rojo bg-white" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" id="updateUnidadBtn" class="btn-accion">Guardar</button>
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
<script>
    $(document).ready(function() {
        // Configurar token CSRF para todas las peticiones AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Inicializar DataTable
        var tabla_unidades = $("#tabla_unidades").DataTable({
            paging: true,
            bFilter: true,
            ordering: true,
            searching: true,
            destroy: true,
            ajax: {
                url: "{{ route('admin.unidades.get') }}",
                method: "GET", 
                dataSrc: "",
            },
            language: {
                url: "{{ asset('js/Spanish.json') }}", // Ajusta la ruta según tu estructura
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
                                <button data-id="${row.id}" class="btn btn-sm btn-warning btnEditar">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button data-id="${row.id}" class="btn btn-sm btn-danger btnBorrar">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>`;
                    },
                },
            ],
        });

        // Agregar Unidad
        $('#submitUnidad').click(function() {
            var nombre = $('#nombreUnidad').val();
            
            if(!nombre) {
                Swal.fire({
                    title: "Error",
                    text: "El nombre es requerido",
                    icon: "error"
                });
                return;
            }

            $.post("{{ route('admin.unidades.save') }}", {
                nombre: nombre,
                _token: $('meta[name="csrf-token"]').attr('content')
            })
            .done(function(data) {
                Swal.fire({
                    title: "Éxito",
                    text: "Se guardó correctamente",
                    icon: "success"
                });
                $('#modalUnidad').modal('hide');
                $('#nombreUnidad').val(''); // Limpiar el campo
                tabla_unidades.ajax.reload();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Error al guardar la unidad: " + textStatus, errorThrown);
                Swal.fire({
                    title: "Error",
                    text: "No se pudo guardar la unidad. Por favor, intenta nuevamente.",
                    icon: "error"
                });
            });
        });

        // Eliminar Unidad
        $("#tabla_unidades").on("click", ".btnBorrar", function(event) {
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
                    $.post("{{ route('admin.unidades.delete') }}", {
                        id: id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    })
                    .done(function(data) {
                        Swal.fire({
                            title: "¡Eliminado!",
                            text: "Eliminado correctamente",
                            icon: "success"
                        });
                        tabla_unidades.ajax.reload();
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        console.error("Error al eliminar la unidad: " + textStatus, errorThrown);
                        Swal.fire({
                            title: "Error",
                            text: "No se pudo eliminar la unidad. Por favor, intenta nuevamente.",
                            icon: "error"
                        });
                    });
                }
            });
        });

        // Editar Unidad
        $("#tabla_unidades").on("click", ".btnEditar", function(event) {
            let id = $(this).data('id');
            $.post("{{ route('admin.unidades.getOne') }}", {
                id: id,
                _token: $('meta[name="csrf-token"]').attr('content')
            })
            .done(function(data) {
                if(data && data.length > 0) {
                    $('#nombreUnidadU').val(data[0].nombre);
                    $('#idUniU').val(data[0].id);
                    $('#updateUnidad').modal('show');
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Error al cargar la unidad: " + textStatus, errorThrown);
                Swal.fire({
                    title: "Error",
                    text: "No se pudo cargar la unidad. Por favor, intenta nuevamente.",
                    icon: "error"
                });
            });
        });

        // Actualizar Unidad
        $('#updateUnidadBtn').click(function() {
            var nombre = $('#nombreUnidadU').val();
            var id = $('#idUniU').val();
            
            if(!nombre) {
                Swal.fire({
                    title: "Error",
                    text: "El nombre es requerido",
                    icon: "error"
                });
                return;
            }

            $.post("{{ route('admin.unidades.update') }}", {
                nombre: nombre,
                id: id,
                _token: $('meta[name="csrf-token"]').attr('content')
            })
            .done(function(data) {
                Swal.fire({
                    title: "Éxito",
                    text: "Se actualizó correctamente",
                    icon: "success"
                });
                $('#updateUnidad').modal('hide');
                tabla_unidades.ajax.reload();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Error al actualizar la unidad: " + textStatus, errorThrown);
                Swal.fire({
                    title: "Error",
                    text: "No se pudo actualizar la unidad. Por favor, intenta nuevamente.",
                    icon: "error"
                });
            });
        });

        // Limpiar formularios al cerrar modales
        $('#modalUnidad').on('hidden.bs.modal', function () {
            $('#nombreUnidad').val('');
        });
        
        $('#updateUnidad').on('hidden.bs.modal', function () {
            $('#nombreUnidadU').val('');
            $('#idUniU').val('');
        });
    });
</script>
@endpush