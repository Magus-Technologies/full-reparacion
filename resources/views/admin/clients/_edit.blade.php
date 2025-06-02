<form onsubmit="updateClient(event, {{ $client->id }})">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2">DNI</label>
            <div class="flex">
                <input type="text" name="documentid" id="edit_documentid" maxlength="8" 
                       value="{{ $client->documentid }}"
                       class="shadow appearance-none border rounded-l w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                       required>
                <button type="button" onclick="searchDNIEdit()" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2">Primer nombre</label>
            <input type="text" name="firstname" id="edit_firstname" 
                   value="{{ $client->firstname }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                   required>
        </div>
        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2">Segundo nombre (opcional)</label>
            <input type="text" name="middlename" id="edit_middlename" 
                   value="{{ $client->middlename }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2">Apellidos</label>
            <input type="text" name="lastname" id="edit_lastname" 
                   value="{{ $client->lastname }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                   required>
        </div>
        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2">Correo electrónico</label>
            <input type="email" name="email" id="edit_email" 
                   value="{{ $client->email }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                   required>
        </div>
        <div class="col-span-1">
            <label class="block text-gray-700 text-sm font-bold mb-2">N° Contacto</label>
            <input type="text" name="contact" id="edit_contact" 
                   value="{{ $client->contact }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                   required>
        </div>
        <div class="col-span-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">Dirección</label>
            <textarea name="address" id="edit_address" rows="3" 
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                      required>{{ $client->address }}</textarea>
        </div>
    </div>
    <div class="flex justify-end mt-4 space-x-2">
        <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" 
                onclick="closeModal('editClientModal')">Cancelar</button>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Actualizar
        </button>
    </div>
</form>

<script>
function searchDNIEdit() {
    const dni = document.getElementById('edit_documentid').value;
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
            
            // Llenar los campos con la información obtenida
            document.getElementById('edit_firstname').value = data.nombres || '';
            document.getElementById('edit_lastname').value = data.apellidoPaterno + ' ' + data.apellidoMaterno || '';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al consultar el DNI');
        });
}
</script>