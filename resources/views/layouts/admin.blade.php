<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- test <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
              <title>{{ $systemInfo->getValue('name') }}</title> -->
    <!-- Tailwind CSS CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Estilos personalizados para el menú -->
    <!-- Estilos personalizados para el menú -->
    <!-- Estilos personalizados para el menú -->
   
    <style>
        aside nav a {
            text-decoration: none !important;
        }
        aside nav a:hover {
            text-decoration: none !important;
        }
        /* Reducir solo el tamaño del texto de los enlaces principales, manteniendo iconos */
        aside nav a span {
            font-size: 0.875rem;
        }
        /* Mantener iconos del tamaño original */
        aside nav a i {
            font-size: 1rem !important;
        }
    </style>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://kit.fontawesome.com/your-code.js" crossorigin="anonymous"></script>
    <!-- Alpine.js para interactividad del submenú -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script> <!-- 🧠 Script agregado para dropdown de Almacén -->
     @vite(['resources/css/app.css', 'resources/js/app.js'])

     
</head>

<body class="bg-gray-100">
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-black text-white">
            <div class="flex items-center p-4 bg-[#01E4FF]">
                <img src="{{ asset($systemInfo->getValue('logo')) }}" alt="Logo"
                    class="w-8 h-8 mr-2 bg-white rounded-full object-contain">
                <span class="text-black font-semibold">{{ $systemInfo->getValue('short_name') }}</span>
            </div>

            <nav class="mt-4" style="--bs-link-color-rgb: 255, 255, 255;">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800' : '' }}"
                    style="text-decoration: none;">
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    <span>PANEL</span>
                </a>

                <a href="{{ route('admin.repairs.index') }}"
                    class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.repairs.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-microchip mr-2"></i>
                    <span>LISTA DE REPARACIONES</span>
                </a>

                <a href="{{ route('admin.clients.index') }}"
                    class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.clients.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-users mr-2"></i>
                    <span>LISTA DE CLIENTES</span>
                </a>

                <!-- 🚀 Módulo Almacén agregado aquí -->
                <div x-data="{ open: false }" class="px-4">
                    <button @click="open = !open"
                        class="flex items-center w-full px-0 py-2 text-white hover:bg-gray-800 focus:outline-none">
                        <i class="fas fa-warehouse mr-2"></i>
                        <span>ALMACÉN</span>
                        <i class="fas fa-chevron-down ml-auto mr-2"></i>
                    </button>
                    <div x-show="open" class="mt-1 ml-6 space-y-1">
                        <a href="{{ route('admin.kardex.index') }}"
                            class="block px-2 py-1 hover:bg-gray-800 rounded {{ request()->routeIs('admin.kardex.*') ? 'bg-gray-800' : '' }}"
                            style="font-size: 0.98rem; text-decoration: none;">
                            📦 Kardex
                        </a>
                        <!-- 🧩 Aquí puedes añadir más secciones en el futuro -->
                         <!-- Nuevo módulo Compras dentro de Almacén --> <!-- 「コード」 -->
                        <a href="#" 
                            class="block px-2 py-1 hover:bg-gray-800 rounded {{ request()->routeIs('admin.compras.*') ? 'bg-gray-800' : '' }}" 
                            style="font-size: 0.98rem; text-decoration: none;"> 
                            🛒 Compras <!-- 「コード」 -->
                        </a> <!-- 「コード」 -->
                    </div>
                </div>

                <a href="{{ route('admin.inquiries.index') }}"
                    class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.inquiries.*') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-question-circle mr-2"></i>
                    <span>CONSULTAS</span>
                </a>
                

                @if(auth()->user()->isAdmin())
                <div class="mt-4 px-4 text-gray-400 text-sm">Facturacion</div>
                <a href="{{ route('admin.services.index') }}"
                        class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.services.*') ? 'bg-gray-800' : '' }}">
                        <i class="fas fa-th-list mr-2"></i>
                        <span>FACTURAR</span>
                    </a>
                    <div class="mt-4 px-4 text-gray-400 text-sm">Mantenimiento</div>

                    <a href="{{ route('admin.services.index') }}"
                        class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.services.*') ? 'bg-gray-800' : '' }}">
                        <i class="fas fa-th-list mr-2"></i>
                        <span>LISTA DE SERVICIOS</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.users.*') ? 'bg-gray-800' : '' }}">
                        <i class="fas fa-users-cog mr-2"></i>
                        <span>LISTA DE USUARIOS</span>
                    </a>

                    <a href="{{ route('admin.settings') }}"
                        class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.settings') ? 'bg-gray-800' : '' }}">
                        <i class="fas fa-cogs mr-2"></i>
                        <span>AJUSTES</span>
                    </a>
                    @endif
                
            </nav>
        </aside>

        <!-- Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top bar -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between p-4">
                    <h1 class="text-xl font-semibold">{{ $systemInfo->getValue('name') }}</h1>
                    <div class="flex items-center">
                        <span class="mr-4">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-800">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('modals')
    @stack('scripts')
</body>

</html>