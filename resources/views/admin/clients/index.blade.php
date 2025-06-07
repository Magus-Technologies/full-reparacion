@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Lista de Clientes</h2>
        <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center" 
                onclick="openModal('addClientModal')">
            <i class="fas fa-plus mr-2"></i> Nuevo Cliente
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
                    <th class="py-3 px-6 text-left">Fecha agregada</th>
                    <th class="py-3 px-6 text-left">Nombre</th>
                    <th class="py-3 px-6 text-left">Contacto</th>
                    <th class="py-3 px-6 text-left">Correo electrónico</th>
                    <th class="py-3 px-6 text-center">Acción</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @forelse($clients as $client)
                @if($client->delete_flag == 0)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-3 px-6 text-left">{{ $client->id }}</td>
                    <td class="py-3 px-6 text-left">
                        @if(is_object($client->date_created) && method_exists($client->date_created, 'format'))
                            {{ $client->date_created->format('d/m/Y H:i:s') }}
                        @else
                            {{ $client->date_created ?? 'N/A' }}
                        @endif
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ $client->firstname }} {{ $client->middlename }} {{ $client->lastname }}
                    </td>
                    <td class="py-3 px-6 text-left">{{ $client->contact }}</td>
                    <td class="py-3 px-6 text-left">{{ $client->email }}</td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            <button onclick="editClient({{ $client->id }})" 
                                    class="w-8 h-8 rounded-full p-1 text-yellow-600 hover:bg-yellow-100 mx-1" 
                                    title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" 
                                    class="w-8 h-8 rounded-full p-1 text-red-600 hover:bg-red-100 mx-1" 
                                    onclick="confirmDelete({{ $client->id }})" 
                                    title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="6" class="py-4 px-6 text-center">No hay clientes registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para agregar cliente -->
<div id="addClientModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar nuevos detalles del cliente</h5>
                <button type="button" class="close" onclick="closeModal('addClientModal')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addClientForm" onsubmit="saveClient(event)">
                @csrf
                <div class="modal-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-1">
                            <label class="block text-gray-700 text-sm font-bold mb-2">DNI</label>
                            <div class="flex">
                                <input type="text" name="documentid" id="documentid" maxlength="8" 
                                       class="shadow appearance-none border rounded-l w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                       required>
                                <button type="button" onclick="searchDNI()" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Primer nombre</label>
                            <input type="text" name="firstname" id="firstname" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                   required>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Segundo nombre (opcional)</label>
                            <input type="text" name="middlename" id="middlename" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Apellidos</label>
                            <input type="text" name="lastname" id="lastname" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                   required>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Correo electrónico</label>
                            <input type="email" name="email" id="email" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                   required>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-gray-700 text-sm font-bold mb-2">N° Contacto</label>
                            <input type="text" name="contact" id="contact" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                   required>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Dirección</label>
                            <textarea name="address" id="address" rows="3" 
                                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                      required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" 
                            onclick="closeModal('addClientModal')">Cancelar</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar cliente -->
<div id="editClientModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar cliente</h5>
                <button type="button" class="close" onclick="closeModal('editClientModal')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="editClientForm">
                <!-- El formulario se cargará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Formulario para eliminar cliente -->
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

function searchDNI() {
    const dni = document.getElementById('documentid').value;
    if (dni.length !== 8) {
        alert('El DNI debe tener 8 dígitos');
        return;
    }

    fetch(`{{ url('admin/clients/search-dni') }}/${dni}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            
            // Separar los nombres
            const nombres = data.nombres.split(' ');
            document.getElementById('firstname').value = nombres[0] || ''; // Primer nombre
            document.getElementById('middlename').value = nombres[1] || ''; // Segundo nombre
            document.getElementById('lastname').value = data.apellidoPaterno + ' ' + data.apellidoMaterno || '';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al consultar el DNI');
        });
}

function saveClient(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    fetch('{{ route('admin.clients.store') }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.errors) {
            alert('Por favor, corrija los errores en el formulario');
            return;
        }
        closeModal('addClientModal');
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar el cliente');
    });
}

function editClient(id) {
    fetch(`{{ url('admin/clients') }}/${id}/edit`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('editClientForm').innerHTML = html;
            openModal('editClientModal');
        });
}

function updateClient(event, id) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    fetch(`{{ url('admin/clients') }}/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.errors) {
            alert('Por favor, corrija los errores en el formulario');
            return;
        }
        closeModal('editClientModal');
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar el cliente');
    });
}

function confirmDelete(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
        const form = document.getElementById('deleteForm');
        form.action = `{{ url('admin/clients') }}/${id}`;
        form.submit();
    }
}
</script>
@endpush

@push('modals')
<style>
    body {
        background-color: red !important;
    }
</style>
@endpush