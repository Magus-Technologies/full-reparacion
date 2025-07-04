@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('images/cover.png') }}');">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
        <!-- Card Header con Logo y Título -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="flex justify-center mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto">
            </div>
            <div class="text-center">
                <h2 class="text-xl font-semibold">Sistema de Gestión Reparación de</h2>
                <h2 class="text-xl font-semibold">portátiles</h2>
            </div>
        </div>

        <!-- Error general -->
        <div id="general-error" class="mb-4 p-4 rounded-md bg-red-50 border border-red-300 hidden">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p id="general-error-text" class="text-sm text-red-700"></p>
                </div>
            </div>
        </div>

        <form id="login-form" class="space-y-4">
            @csrf
            <div>
                <div class="flex items-center border border-gray-300 rounded-md px-3 py-2">
                    <svg class="h-5 w-5 text-blue-500 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <input type="text" name="username" id="username" placeholder="Usuario" 
                           class="w-full outline-none bg-transparent">
                </div>
            </div>

            <div>
                <div class="flex items-center border border-gray-300 rounded-md px-3 py-2">
                    <svg class="h-5 w-5 text-blue-500 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <input type="password" name="password" id="password" placeholder="Contraseña" 
                           class="w-full outline-none bg-transparent">
                </div>
                <p id="password-error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <div class="flex space-x-4 pt-2">
                <a href="#" class="flex-1 px-4 py-2 bg-red-500 text-white text-center rounded-md hover:bg-red-600 transition-colors">
                    Ir a sitio Web
                </a>
                <button type="submit" class="flex-1 px-4 py-2 bg-[#01E4FF] text-white rounded-md hover:bg-[#00c8e0] transition-colors">
                    Ingresar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const loginForm = document.getElementById('login-form');

loginForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(loginForm);

    const generalError = document.getElementById('general-error');
    const generalErrorText = document.getElementById('general-error-text');
    const passwordError = document.getElementById('password-error');

    // Limpiar errores antes de enviar
    generalError.classList.add('hidden');
    passwordError.classList.add('hidden');

    fetch('/login', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.errors) {
            if (data.errors.credentials) {
                generalError.classList.remove('hidden');
                generalErrorText.textContent = data.errors.credentials;
            } else if (data.errors.password) {
                passwordError.textContent = data.errors.password;
                passwordError.classList.remove('hidden');
            }
        } else if (data.redirect) {
            window.location.href = data.redirect;
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

// Limpiar errores al escribir
document.getElementById('username').addEventListener('input', function() {
    document.getElementById('general-error').classList.add('hidden');
});

document.getElementById('password').addEventListener('input', function() {
    document.getElementById('password-error').classList.add('hidden');
    document.getElementById('general-error').classList.add('hidden');
});
</script>
@endsection

