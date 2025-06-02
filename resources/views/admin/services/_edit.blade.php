<form id="updateServiceForm" onsubmit="updateService(event, {{ $service->id }})">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label for="service" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Servicio:</label>
        <input type="text" name="service" id="service" value="{{ $service->service }}" 
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>
    <div class="mb-4">
        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
        <textarea name="description" id="description" rows="3" 
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $service->description }}</textarea>
    </div>
    <div class="mb-4">
        <label for="cost" class="block text-gray-700 text-sm font-bold mb-2">Costo:</label>
        <input type="number" name="cost" id="cost" step="0.01" min="0" value="{{ $service->cost }}" 
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>
    <div class="flex justify-end space-x-2">
        <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" 
                onclick="closeModal('editServiceModal')">Cancelar</button>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Actualizar
        </button>
    </div>
</form>

<script>
function updateService(event, id) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    fetch(`{{ url('admin/services') }}/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.errors) {
            // Mostrar errores
            alert('Por favor, corrija los errores en el formulario');
        } else {
            // Cerrar modal y recargar página
            closeModal('editServiceModal');
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al actualizar el servicio');
    });
}
</script>