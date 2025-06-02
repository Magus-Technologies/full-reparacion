@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Lista de Servicios</h2>
        <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center" 
                onclick="openModal('addServiceModal')">
            <i class="fas fa-plus mr-2"></i> Nuevo Servicio
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-gray-700 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">#</th>
                    <th class="py-3 px-6 text-left">Fecha de creación</th>
                    <th class="py-3 px-6 text-left">Servicio</th>
                    <th class="py-3 px-6 text-left">Descripción</th>
                    <th class="py-3 px-6 text-right">Costo</th>
                    <th class="py-3 px-6 text-center">Acción</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @forelse($services as $service)
                @if($service->delete_flag == 0)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-3 px-6 text-left">{{ $service->id }}</td>
                    <td class="py-3 px-6 text-left">
                        @if(is_object($service->date_created) && method_exists($service->date_created, 'format'))
                            {{ $service->date_created->format('d/m/Y H:i:s') }}
                        @else
                            {{ $service->date_created ?? 'N/A' }}
                        @endif
                    </td>
                    <td class="py-3 px-6 text-left">{{ $service->service }}</td>
                    <td class="py-3 px-6 text-left">{{ \Illuminate\Support\Str::limit($service->description, 50) }}</td>
                    <td class="py-3 px-6 text-right">{{ number_format($service->cost, 2) }}</td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            <button onclick="showServiceDetails({{ $service->id }})" 
                                    class="w-8 h-8 rounded-full p-1 text-blue-600 hover:bg-blue-100 mx-1" 
                                    title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editService({{ $service->id }})" 
                                    class="w-8 h-8 rounded-full p-1 text-yellow-600 hover:bg-yellow-100 mx-1" 
                                    title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" 
                                    class="w-8 h-8 rounded-full p-1 text-red-600 hover:bg-red-100 mx-1" 
                                    onclick="confirmDelete({{ $service->id }})" 
                                    title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="6" class="py-4 px-6 text-center">No hay servicios disponibles</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para agregar servicio -->
<div id="addServiceModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Nuevo Servicio</h5>
                <button type="button" class="close" onclick="closeModal('addServiceModal')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.services.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="service" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Servicio:</label>
                        <input type="text" name="service" id="service" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                        <textarea name="description" id="description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="cost" class="block text-gray-700 text-sm font-bold mb-2">Costo:</label>
                        <input type="number" name="cost" id="cost" step="0.01" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" onclick="closeModal('addServiceModal')">Cancelar</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver detalles -->
<div id="viewServiceModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Servicio</h5>
                <button type="button" class="close" onclick="closeModal('viewServiceModal')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="serviceDetails">
                <!-- El contenido se cargará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" onclick="closeModal('viewServiceModal')">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar -->
<div id="editServiceModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Servicio</h5>
                <button type="button" class="close" onclick="closeModal('editServiceModal')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="editServiceForm">
                <!-- El formulario se cargará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Formulario para eliminar servicio -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.add('show');
        document.body.classList.add('modal-open');
        document.body.appendChild(createBackdrop());
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
        document.body.classList.remove('modal-open');
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) backdrop.remove();
    }

    function createBackdrop() {
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        return backdrop;
    }

    function showServiceDetails(id) {
        fetch(`{{ url('admin/services') }}/${id}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('serviceDetails').innerHTML = html;
                openModal('viewServiceModal');
            });
    }

    function editService(id) {
        fetch(`{{ url('admin/services') }}/${id}/edit`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('editServiceForm').innerHTML = html;
                openModal('editServiceModal');
            });
    }

    function confirmDelete(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este servicio?')) {
            const form = document.getElementById('deleteForm');
            form.action = `{{ url('admin/services') }}/${id}`;
            form.submit();
        }
    }
</script>
@endpush

@push('modals')
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal.show {
        display: block;
    }

    .modal-dialog {
        position: relative;
        width: auto;
        margin: 1.75rem auto;
        max-width: 500px;
    }

    .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        background-color: #fff;
        border-radius: 0.3rem;
        outline: 0;
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
    }

    .modal-body {
        position: relative;
        flex: 1 1 auto;
        padding: 1rem;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        padding: 1rem;
        border-top: 1px solid #dee2e6;
        gap: 0.5rem;
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1040;
        width: 100vw;
        height: 100vh;
        background-color: #000;
        opacity: 0.5;
    }

    .close {
        float: right;
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
        color: #000;
        text-shadow: 0 1px 0 #fff;
        opacity: .5;
        background: transparent;
        border: 0;
        cursor: pointer;
    }
</style>
@endpush