@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Detalles de la Reparación</h2>
            <div class="flex space-x-2">
                <button onclick="generatePDF()" class="bg-green-500 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i> Ver PDF
                </button>
                <button onclick="shareWhatsApp()" class="bg-green-600 hover:bg-green-800 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                    <i class="fab fa-whatsapp mr-2"></i> Enviar por WhatsApp
                </button>
                <a href="{{ route('admin.repairs.edit', $repair->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                <button onclick="deleteRepair()" class="bg-red-500 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                    <i class="fas fa-trash mr-2"></i> Eliminar
                </button>
                <a href="{{ route('admin.repairs.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>
        </div>

        <!-- Información del Cliente -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Información del Cliente</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nombre:</span>
                        <span class="font-medium">
                            {{ $repair->client->firstname }} {{ $repair->client->middlename }} {{ $repair->client->lastname }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">DNI:</span>
                        <span class="font-medium">{{ $repair->client->documentid }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Teléfono:</span>
                        <span class="font-medium">{{ $repair->client->contact }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Información de la Reparación</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Código:</span>
                        <span class="font-medium">{{ $repair->code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Fecha:</span>
                        <span class="font-medium">
                            @if(is_object($repair->date_created) && method_exists($repair->date_created, 'format'))
                                {{ $repair->date_created->format('d/m/Y H:i:s') }}
                            @else
                                {{ $repair->date_created ?? 'N/A' }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estados -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Estado de la Reparación</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Estado de pago:</span>
                        <span class="font-medium">
                            @if($repair->payment_status == 0)
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">No pagado</span>
                            @else
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Pagado</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Estado:</span>
                        <span class="font-medium">
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
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Información de Pago</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Adelanto:</span>
                        <span class="font-medium">S/ {{ number_format($repair->advance, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total a pagar:</span>
                        <span class="font-medium">S/ {{ number_format($repair->total_amount - $repair->advance, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Servicios -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-lg font-semibold mb-4">Servicios</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Costo</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($repair->services as $service)
                        <tr>
                            <td class="px-6 py-4">{{ $service->service }}</td>
                            <td class="px-6 py-4 text-right">S/ {{ number_format($service->pivot->fee, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="px-6 py-4 text-right font-bold">Total:</td>
                            <td class="px-6 py-4 text-right">S/ {{ number_format($repair->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Observaciones y Notas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Observaciones</h3>
                <p class="text-gray-700">{{ $repair->remarks ?: 'Sin observaciones' }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Notas</h3>
                <p class="text-gray-700">{{ $repair->notes ?: 'Sin notas' }}</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function generatePDF() {
    window.open("{{ route('admin.repairs.pdf', $repair->id) }}", '_blank');
}

function shareWhatsApp() {
    $.ajax({
        url: "{{ route('admin.repairs.share-whatsapp', $repair->id) }}",
        method: 'GET',
        success: function(response) {
            if (response.status === 'success') {
                window.open(response.url, '_blank');
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('Error al generar el enlace de WhatsApp');
        }
    });
}

function deleteRepair() {
    if (confirm('¿Estás seguro de que deseas eliminar esta reparación?')) {
        $.ajax({
            url: "{{ route('admin.repairs.destroy', $repair->id) }}",
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                window.location.href = "{{ route('admin.repairs.index') }}";
            },
            error: function() {
                alert('Error al eliminar la reparación');
            }
        });
    }
}
</script>
@endpush
@endsection