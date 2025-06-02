@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Lista de Reparaciones</h2>
            <a href="{{ route('admin.repairs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Agregar Entrada
            </a>
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
                        <th class="py-3 px-6 text-left">Código</th>
                        <th class="py-3 px-6 text-left">Cliente</th>
                        <th class="py-3 px-6 text-center">Estado</th>
                        <th class="py-3 px-6 text-center">Acción</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    @forelse($repairs as $repair)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-6 text-left">{{ $repair->id }}</td>
                        <td class="py-3 px-6 text-left">
                            @if(is_object($repair->date_created) && method_exists($repair->date_created, 'format'))
                                {{ $repair->date_created->format('d/m/Y H:i:s') }}
                            @else
                                {{ $repair->date_created ?? 'N/A' }}
                            @endif
                        </td>
                        <td class="py-3 px-6 text-left">{{ $repair->code }}</td>
                        <td class="py-3 px-6 text-left">
                            {{ $repair->client->firstname }} {{ $repair->client->middlename }} {{ $repair->client->lastname }}
                        </td>
                        <td class="py-3 px-6 text-center">
                            @switch($repair->status)
                                @case(0)
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Pendiente</span>
                                    @break
                                @case(1)
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Entregado</span>
                                    @break
                                @case(2)
                                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">En Progreso</span>
                                    @break
                                @case(3)
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Devolución</span>
                                    @break
                                @case(4)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Realizado</span>
                                    @break
                                @case(5)
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">Cancelado</span>
                                    @break
                                @default
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">Desconocido</span>
                            @endswitch
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center">
                                <a href="{{ route('admin.repairs.show', $repair->id) }}" 
                                   class="w-8 h-8 rounded-full p-1 text-blue-600 hover:bg-blue-100 mx-1"
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.repairs.edit', $repair->id) }}"
                                   class="w-8 h-8 rounded-full p-1 text-yellow-600 hover:bg-yellow-100 mx-1"
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete({{ $repair->id }})"
                                        class="w-8 h-8 rounded-full p-1 text-red-600 hover:bg-red-100 mx-1"
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-4 px-6 text-center">No hay reparaciones registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Formulario para eliminar reparación -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('¿Estás seguro de que deseas eliminar esta reparación?')) {
        const form = document.getElementById('deleteForm');
        form.action = `{{ url('admin/repairs') }}/${id}`;
        form.submit();
    }
}
</script>
@endpush