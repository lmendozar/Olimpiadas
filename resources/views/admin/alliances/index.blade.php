@extends('layouts.admin')

@section('title', 'Alianzas/Países')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Alianzas</h1>
            <p class="mt-2 text-sm text-gray-700">Gestión de equipos y alianzas participantes</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.alliances.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Alianza
            </a>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logo</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Miembros</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($alliances as $alliance)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $alliance->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($alliance->logo_url)
                                <img src="{{ $alliance->logo_url }}" alt="{{ $alliance->name }}" class="h-10 w-10 rounded-full">
                            @else
                                <span class="text-gray-400 text-xs">Sin logo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            {{ $alliance->people_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.alliances.show', $alliance) }}" class="text-green-600 hover:text-green-900 mr-3">Ver</a>
                            <a href="{{ route('admin.alliances.edit', $alliance) }}" class="text-blue-600 hover:text-blue-900 mr-3">Editar</a>
                            <form action="{{ route('admin.alliances.destroy', $alliance) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Está seguro de eliminar esta alianza?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No hay alianzas registradas. <a href="{{ route('admin.alliances.create') }}" class="text-green-600 hover:text-green-800">Crear la primera</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $alliances->links() }}
    </div>
</div>
@endsection

