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

<body class="bg-gradient-to-br from-slate-50 to-slate-100 overflow-hidden">
    <div class="flex h-screen bg-gradient-to-br from-slate-50 to-slate-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-slate-900 to-slate-800 text-white shadow-2xl overflow-y-auto">
            <div class="flex items-center p-4 bg-gradient-to-r from-cyan-400 to-blue-400 shadow-lg">
                <img src="{{ asset($systemInfo->getValue('logo')) }}" alt="Logo"
                    class="w-8 h-8 mr-2 bg-white rounded-full object-contain shadow-md">
                <span class="text-slate-900 font-bold text-lg">{{ $systemInfo->getValue('short_name') }}</span>
            </div>

            <nav class="mt-6" style="--bs-link-color-rgb: 255, 255, 255;">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-4 py-3 mx-2 mb-1 rounded-lg transition-all duration-200 hover:bg-slate-700 hover:shadow-lg hover:translate-x-1 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-cyan-500 to-blue-500 shadow-lg' : '' }}"
                    style="text-decoration: none;">
                    <i class="fas fa-tachometer-alt mr-3 text-cyan-300"></i>
                    <span class="font-medium">PANEL</span>
                </a>

                <a href="{{ route('admin.repairs.index') }}"
                    class="flex items-center px-4 py-3 mx-2 mb-1 rounded-lg transition-all duration-200 hover:bg-slate-700 hover:shadow-lg hover:translate-x-1 {{ request()->routeIs('admin.repairs.*') ? 'bg-gradient-to-r from-cyan-500 to-blue-500 shadow-lg' : '' }}">
                    <i class="fas fa-microchip mr-3 text-emerald-300"></i>
                    <span class="font-medium">LISTA DE REPARACIONES</span>
                </a>

                <a href="{{ route('admin.clients.index') }}"
                    class="flex items-center px-4 py-3 mx-2 mb-1 rounded-lg transition-all duration-200 hover:bg-slate-700 hover:shadow-lg hover:translate-x-1 {{ request()->routeIs('admin.clients.*') ? 'bg-gradient-to-r from-cyan-500 to-blue-500 shadow-lg' : '' }}">
                    <i class="fas fa-users mr-3 text-purple-300"></i>
                    <span class="font-medium">LISTA DE CLIENTES</span>
                </a>

                <!-- 🚀 Módulo Almacén agregado aquí -->
                <div x-data="{ open: false }" class="px-2 mb-1">
                    <button @click="open = !open"
                        class="flex items-center w-full px-4 py-3 mx-0 rounded-lg text-white transition-all duration-200 hover:bg-slate-700 hover:shadow-lg hover:translate-x-1 focus:outline-none">
                        <i class="fas fa-warehouse mr-3 text-orange-300"></i>
                        <span class="font-medium">ALMACÉN</span>
                        <i class="fas fa-chevron-down ml-auto mr-2 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-2 ml-6 space-y-1 bg-slate-800 rounded-lg p-2 shadow-inner">
                        <a href="{{ route('admin.kardex.index') }}"
                            class="block px-3 py-2 hover:bg-slate-700 rounded-md transition-all duration-200 hover:shadow-md {{ request()->routeIs('admin.kardex.*') ? 'bg-gradient-to-r from-cyan-500 to-blue-500 shadow-md' : '' }}"
                            style="font-size: 0.98rem; text-decoration: none;">
                            <span class="mr-2">📦</span>
                            <span class="font-medium">Kardex</span>
                        </a>
                        <!-- 🧩 Aquí puedes añadir más secciones en el futuro -->
                         <!-- Nuevo módulo Compras dentro de Almacén --> <!-- 「コード」 -->
                        <a href="{{ route('admin.compras.index') }}" 
                            class="block px-3 py-2 hover:bg-slate-700 rounded-md transition-all duration-200 hover:shadow-md {{ request()->routeIs('admin.compras.*') ? 'bg-gradient-to-r from-cyan-500 to-blue-500 shadow-md' : '' }}" 
                            style="font-size: 0.98rem; text-decoration: none;"> 
                            <span class="mr-2">🛒</span>
                            <span class="font-medium">Compras</span> <!-- 「コード」 -->
                        </a> <!-- 「コード」 -->
                    </div>
                </div>

                <a href="{{ route('admin.inquiries.index') }}"
                    class="flex items-center px-4 py-3 mx-2 mb-1 rounded-lg transition-all duration-200 hover:bg-slate-700 hover:shadow-lg hover:translate-x-1 {{ request()->routeIs('admin.inquiries.*') ? 'bg-gradient-to-r from-cyan-500 to-blue-500 shadow-lg' : '' }}">
                    <i class="fas fa-question-circle mr-3 text-yellow-300"></i>
                    <span class="font-medium">CONSULTAS</span>
                </a>
                

                @if(auth()->user()->isAdmin())
                <div class="mt-6 px-4 text-slate-400 text-sm font-semibold tracking-wide uppercase border-t border-slate-700 pt-4">Facturación</div>
                <a href="{{ route('admin.services.index') }}"
                        class="flex items-center px-4 py-3 mx-2 mb-1 rounded-lg transition-all duration-200 hover:bg-slate-700 hover:shadow-lg hover:translate-x-1 {{ request()->routeIs('admin.services.*') ? 'bg-gradient-to-r from-cyan-500 to-blue-500 shadow-lg' : '' }}">
                        <i class="fas fa-th-list mr-3 text-green-300"></i>
                        <span class="font-medium">FACTURAR</span>
                    </a>
                    <div class="mt-4 px-4 text-slate-400 text-sm font-semibold tracking-wide uppercase border-t border-slate-700 pt-4">Mantenimiento</div>

                    <a href="{{ route('admin.services.index') }}"
                        class="flex items-center px-4 py-3 mx-2 mb-1 rounded-lg transition-all duration-200 hover:bg-slate-700 hover:shadow-lg hover:translate-x-1 {{ request()->routeIs('admin.services.*') ? 'bg-gradient-to-r from-cyan-500 to-blue-500 shadow-lg' : '' }}">
                        <i class="fas fa-th-list mr-3 text-indigo-300"></i>
                        <span class="font-medium">LISTA DE SERVICIOS</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center px-4 py-3 mx-2 mb-1 rounded-lg transition-all duration-200 hover:bg-slate-700 hover:shadow-lg hover:translate-x-1 {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-cyan-500 to-blue-500 shadow-lg' : '' }}">
                        <i class="fas fa-users-cog mr-3 text-pink-300"></i>
                        <span class="font-medium">LISTA DE USUARIOS</span>
                    </a>

                    <a href="{{ route('admin.settings') }}"
                        class="flex items-center px-4 py-3 mx-2 mb-1 rounded-lg transition-all duration-200 hover:bg-slate-700 hover:shadow-lg hover:translate-x-1 {{ request()->routeIs('admin.settings') ? 'bg-gradient-to-r from-cyan-500 to-blue-500 shadow-lg' : '' }}">
                        <i class="fas fa-cogs mr-3 text-red-300"></i>
                        <span class="font-medium">AJUSTES</span>
                    </a>
                    @endif
                
            </nav>
        </aside>

        <!-- Content -->
        <div class="flex-1 flex flex-col overflow-hidden min-h-0">
            <!-- Top bar -->
            <header class="bg-white shadow-lg border-b border-slate-200">
                <div class="flex items-center justify-between p-4">
                    <h1 class="text-2xl font-bold text-slate-800 bg-gradient-to-r from-cyan-600 to-blue-600 bg-clip-text text-transparent">{{ $systemInfo->getValue('name') }}</h1>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center bg-slate-100 px-4 py-2 rounded-full">
                            <div class="w-8 h-8 bg-gradient-to-r from-cyan-400 to-blue-400 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <span class="text-slate-700 font-medium">{{ auth()->user()->name }}</span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-slate-600 hover:text-red-500 transition-colors duration-200 bg-slate-100 hover:bg-red-50 p-2 rounded-full">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main content -->
            <main class="flex-1 overflow-y-auto bg-gradient-to-br from-slate-50 to-slate-100 min-h-0">
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