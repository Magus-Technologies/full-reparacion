@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Agregar nueva reparación</h2>
            <a href="{{ route('admin.repairs.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
        </div>

        <form id="repair-form" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Sección Cliente -->
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700">Cliente</label>
                    <select name="client_id" id="client_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="">Seleccione un cliente</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">
                                {{ $client->firstname }} {{ $client->middlename }} {{ $client->lastname }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700">DNI del Cliente</label>
                    <input type="text" id="client_dni" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" readonly>
                </div>

                <!-- Sección Servicios -->
                <div class="col-span-2">
                    <fieldset class="border border-gray-300 rounded-md p-4">
                        <legend class="text-lg font-medium text-gray-700 px-2">Servicios</legend>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="col-span-2">
                                <select id="service_select" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Seleccione un servicio</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" data-cost="{{ $service->cost }}">
                                            {{ $service->service }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-1">
                                <div class="flex space-x-2">
                                    <input type="number" id="service_cost" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Costo">
                                    <button type="button" id="add_service" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="services_table">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Costo</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200"></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 text-right font-bold">Total:</td>
                                        <td class="px-6 py-4 text-right" id="total_amount">0.00</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </fieldset>
                </div>

                <!-- Descuento y Monto Total -->
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700">Descuento</label>
                    <input type="number" name="discount" id="discount" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="0" min="0" step="0.01">
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700">Adelanto</label>
                    <input type="number" name="advance" id="advance" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="0" min="0" step="0.01">
                </div>

                <!-- Observaciones y Notas -->
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                    <textarea name="remarks" rows="3" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700">Notas</label>
                    <textarea name="notes" rows="3" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>

                <!-- Estados -->
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700">Estado de Pago</label>
                    <select name="payment_status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="0">No pagado</option>
                        <option value="1">Pagado</option>
                    </select>
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                    <select name="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="0">Pendiente</option>
                        <option value="1">Entregado</option>
                        <option value="2">En Progreso</option>
                        <option value="3">Devolución</option>
                        <option value="4">Realizado</option>
                        <option value="5">Cancelado</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="window.history.back()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Cancelar
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let services = [];
let total = 0;

$(document).ready(function() {
    // Manejar cambio de cliente
    $('#client_id').change(function() {
        const clientId = $(this).val();
        if (clientId) {
            $.ajax({
                url: `/admin/repairs/get-client-dni/${clientId}`,
                method: 'GET',
                success: function(response) {
                    $('#client_dni').val(response.dni);
                },
                error: function() {
                    alert('Error al obtener el DNI del cliente');
                }
            });
        } else {
            $('#client_dni').val('');
        }
    });

    // Manejar cambio de servicio
    $('#service_select').change(function() {
        const cost = $(this).find(':selected').data('cost');
        $('#service_cost').val(cost);
    });

    // Agregar servicio
    $('#add_service').click(function() {
        const serviceSelect = $('#service_select');
        const serviceId = serviceSelect.val();
        const serviceName = serviceSelect.find(':selected').text();
        const serviceCost = parseFloat($('#service_cost').val());

        if (!serviceId || isNaN(serviceCost)) {
            alert('Por favor seleccione un servicio y especifique un costo válido');
            return;
        }

        // Verificar si el servicio ya está en la lista
        if (services.some(s => s.id === serviceId)) {
            alert('Este servicio ya está en la lista');
            return;
        }

        // Agregar servicio a la lista
        services.push({
            id: serviceId,
            name: serviceName,
            cost: serviceCost
        });

        updateServicesTable();
        calculateTotal();

        // Limpiar selección
        serviceSelect.val('');
        $('#service_cost').val('');
    });

    // Manejar envío del formulario
    $('#repair-form').submit(function(e) {
        e.preventDefault();

        if (services.length === 0) {
            alert('Debe agregar al menos un servicio');
            return;
        }

        const formData = new FormData(this);
        formData.append('total_amount', total);
        
        // Agregar servicios
        services.forEach((service, index) => {
            formData.append(`service_id[]`, service.id);
            formData.append(`fee[]`, service.cost);
        });

        $.ajax({
            url: '{{ route('admin.repairs.store') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    window.location.href = `{{ url('admin/repairs') }}/${response.id}`;
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                alert('Error al guardar la reparación');
            }
        });
    });
});

function updateServicesTable() {
    const tbody = $('#services_table tbody');
    tbody.empty();

    services.forEach((service, index) => {
        tbody.append(`
            <tr>
                <td class="px-6 py-4">${service.name}</td>
                <td class="px-6 py-4 text-right">${service.cost.toFixed(2)}</td>
                <td class="px-6 py-4 text-center">
                    <button type="button" onclick="removeService(${index})" class="text-red-600 hover:text-red-900">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);
    });
}

function removeService(index) {
    services.splice(index, 1);
    updateServicesTable();
    calculateTotal();
}

function calculateTotal() {
    total = services.reduce((sum, service) => sum + service.cost, 0);
    const discount = parseFloat($('#discount').val()) || 0;
    const finalTotal = total - discount;
    $('#total_amount').text(finalTotal.toFixed(2));
}

// Recalcular total cuando cambie el descuento
$('#discount').change(calculateTotal);
</script>
@endpush