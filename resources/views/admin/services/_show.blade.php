<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Información del Servicio</h3>
        <div class="mb-4">
            <p class="text-sm text-gray-500">ID:</p>
            <p class="font-medium">{{ $service->id }}</p>
        </div>
        <div class="mb-4">
            <p class="text-sm text-gray-500">Nombre del Servicio:</p>
            <p class="font-medium">{{ $service->service }}</p>
        </div>
        <div class="mb-4">
            <p class="text-sm text-gray-500">Costo:</p>
            <p class="font-medium">{{ number_format($service->cost, 2) }}</p>
        </div>
    </div>
    <div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Detalles Adicionales</h3>
        <div class="mb-4">
            <p class="text-sm text-gray-500">Fecha de Creación:</p>
            <p class="font-medium">
                @if(is_object($service->date_created) && method_exists($service->date_created, 'format'))
                    {{ $service->date_created->format('d/m/Y H:i:s') }}
                @else
                    {{ $service->date_created ?? 'N/A' }}
                @endif
            </p>
        </div>
        <div class="mb-4">
            <p class="text-sm text-gray-500">Última Actualización:</p>
            <p class="font-medium">
                @if(is_object($service->date_updated) && method_exists($service->date_updated, 'format'))
                    {{ $service->date_updated->format('d/m/Y H:i:s') }}
                @else
                    {{ $service->date_updated ?? 'N/A' }}
                @endif
            </p>
        </div>
        <div class="mb-4">
            <p class="text-sm text-gray-500">Estado:</p>
            <p class="font-medium">
                @if($service->delete_flag == 0)
                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Activo</span>
                @else
                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Inactivo</span>
                @endif
            </p>
        </div>
    </div>
</div>
<div class="mt-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-2">Descripción</h3>
    <div class="bg-white p-4 rounded border border-gray-200">
        <p>{{ $service->description ?: 'Sin descripción' }}</p>
    </div>
</div>