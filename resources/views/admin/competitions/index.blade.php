@extends('layouts.admin')

@section('title', 'Competencias')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Competencias</h1>
            <p class="mt-2 text-sm text-gray-700">Gestión de competencias olímpicas</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.competitions.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Competencia
            </a>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo de Juego</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Inicio</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Enfrentamientos</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($competitions as $competition)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $competition->gameType->name }}</div>
                            <div class="text-xs text-gray-500">{{ $competition->gameType->getMetricLabel() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $competition->start_date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            {{ $competition->matches_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($competition->is_finalized)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Finalizada
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    En Curso
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.competitions.show', $competition) }}" class="text-green-600 hover:text-green-900 mr-3">Ver</a>
                            <a href="{{ route('admin.competitions.edit', $competition) }}" class="text-blue-600 hover:text-blue-900 mr-3">Editar</a>
                            @if(!$competition->is_finalized)
                                <form action="{{ route('admin.competitions.finalize', $competition) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Finalizar esta competencia y calcular rankings?');">
                                    @csrf
                                    <button type="submit" class="text-purple-600 hover:text-purple-900 mr-3">Finalizar</button>
                                </form>
                            @endif
                            <form action="{{ route('admin.competitions.destroy', $competition) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Está seguro de eliminar esta competencia?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No hay competencias registradas. <a href="{{ route('admin.competitions.create') }}" class="text-yellow-600 hover:text-yellow-800">Crear la primera</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $competitions->links() }}
    </div>
</div>
@endsection

