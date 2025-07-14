@extends('layouts.admin')

@section('content')
<style>
.compact-text {
    font-size: 0.75rem;
}
</style>
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100 p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Bienvenido a {{ $systemInfo->getValue('name') }}</h1>
        <p class="text-gray-600">Panel de control y estadísticas del sistema</p>
    </div>

    <!-- Primera fila de estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mb-4">
        <!-- Servicios -->
        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-3 border border-gray-100 hover:border-cyan-200 group">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-cyan-400 to-cyan-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-th-list text-white text-lg"></i>
                </div>
                <div class="ml-4 flex-1 min-w-0">
                    <h2 class="text-gray-500 compact-text font-medium uppercase tracking-wide break-words leading-tight">Servicios</h2>
                    <p class="text-2xl font-bold text-gray-800">{{ $services_count }}</p>
                </div>
            </div>
        </div>

        <!-- Total de clientes -->
        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-3 border border-gray-100 hover:border-blue-200 group">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-users text-white text-lg"></i>
                </div>
                <div class="ml-4 flex-1 min-w-0">
                    <h2 class="text-gray-500 compact-text font-medium uppercase tracking-wide break-words leading-tight">Total de clientes</h2>
                    <p class="text-2xl font-bold text-gray-800">{{ $clients_count }}</p>
                </div>
            </div>
        </div>

        <!-- Reparaciones pendientes -->
        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-3 border border-gray-100 hover:border-gray-300 group">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-gray-400 to-gray-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-tools text-white text-lg"></i>
                </div>
                <div class="ml-4 flex-1 min-w-0">
                    <h2 class="text-gray-500 compact-text font-medium uppercase tracking-wide break-words leading-tight">Reparaciones pendientes</h2>
                    <p class="text-2xl font-bold text-gray-800">{{ $pending_repairs }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda fila de estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mb-4">
        <!-- Reparaciones Entregadas -->
        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-3 border border-gray-100 hover:border-green-200 group">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-check text-white text-lg"></i>
                </div>
                <div class="ml-4 flex-1 min-w-0">
                    <h2 class="text-gray-500 compact-text font-medium uppercase tracking-wide break-words leading-tight">Reparaciones Entregadas</h2>
                    <p class="text-2xl font-bold text-gray-800">{{ $delivered_repairs }}</p>
                </div>
            </div>
        </div>

        <!-- Reparaciones en progreso -->
        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-3 border border-gray-100 hover:border-cyan-200 group">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-cyan-400 to-cyan-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-cog text-white text-lg"></i>
                </div>
                <div class="ml-4 flex-1 min-w-0">
                    <h2 class="text-gray-500 compact-text font-medium uppercase tracking-wide break-words leading-tight">Reparaciones en progreso</h2>
                    <p class="text-2xl font-bold text-gray-800">{{ $in_progress_repairs }}</p>
                </div>
            </div>
        </div>

        <!-- Devoluciones -->
        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-3 border border-gray-100 hover:border-yellow-200 group">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-undo text-white text-lg"></i>
                </div>
                <div class="ml-4 flex-1 min-w-0">
                    <h2 class="text-gray-500 compact-text font-medium uppercase tracking-wide break-words leading-tight">Devoluciones</h2>
                    <p class="text-2xl font-bold text-gray-800">{{ $returned_repairs }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tercera fila de estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mb-6">
        <!-- Reparaciones Realizadas -->
        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-3 border border-gray-100 hover:border-green-200 group">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-check-double text-white text-lg"></i>
                </div>
                <div class="ml-4 flex-1 min-w-0">
                    <h2 class="text-gray-500 compact-text font-medium uppercase tracking-wide break-words leading-tight">Reparaciones Realizadas</h2>
                    <p class="text-2xl font-bold text-gray-800">{{ $completed_repairs }}</p>
                </div>
            </div>
        </div>

        <!-- Reparaciones canceladas -->
        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-3 border border-gray-100 hover:border-red-200 group">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-times text-white text-lg"></i>
                </div>
                <div class="ml-4 flex-1 min-w-0">
                    <h2 class="text-gray-500 compact-text font-medium uppercase tracking-wide break-words leading-tight">Reparaciones canceladas</h2>
                    <p class="text-2xl font-bold text-gray-800">{{ $cancelled_repairs }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Imagen de portada -->
    <div class="mt-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <img src="images/cover.png" alt="cover" class="w-full h-auto rounded-lg shadow-md">
        </div>
    </div>
</div>
@endsection