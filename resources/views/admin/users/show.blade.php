@extends('layouts.admin')

@section('title', 'Ver Usuario')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-800 inline-flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a la lista
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-indigo-600 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-16 w-16 rounded-full bg-white flex items-center justify-center mr-4">
                        <span class="text-indigo-600 font-bold text-2xl">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                        <p class="text-indigo-100">{{ $user->email }}</p>
                        @if($user->id === auth()->id())
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-500 text-white mt-1">
                                Tu cuenta
                            </span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-white text-indigo-600 rounded-md hover:bg-indigo-50">
                    Editar
                </a>
            </div>

            <!-- User Information -->
            <div class="px-6 py-6 border-b">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Información del Usuario</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nombre Completo</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Rol</dt>
                        <dd class="mt-1">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($user->role == 'organizador') bg-purple-100 text-purple-800 @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Fecha de Registro</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email Verificado</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($user->email_verified_at)
                                <span class="text-green-600">✓ Verificado</span> ({{ $user->email_verified_at->format('d/m/Y') }})
                            @else
                                <span class="text-gray-400">No verificado</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Permissions -->
            <div class="px-6 py-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Permisos de Acceso</h2>
                <div class="space-y-3">
                    @if($user->isOrganizer())
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-900 font-medium">Panel de Administración Completo</p>
                                <p class="text-sm text-gray-500">CRUD de todas las entidades, gestión de competencias, enfrentamientos y exportación</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-900 font-medium">Gestión de Usuarios</p>
                                <p class="text-sm text-gray-500">Crear, editar y eliminar usuarios del sistema</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-900 font-medium">Dashboard Público</p>
                                <p class="text-sm text-gray-500">Visualización de clasificaciones y resultados</p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-900 font-medium">Panel de Administración</p>
                                <p class="text-sm text-gray-500">Sin acceso - Solo organizadores</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-900 font-medium">Dashboard Público</p>
                                <p class="text-sm text-gray-500">Visualización de clasificaciones y resultados (solo lectura)</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

