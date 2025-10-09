@extends('layouts.admin')

@section('title', 'Enfrentamientos')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Enfrentamientos</h1>
            <p class="mt-2 text-sm text-gray-700">Gestión de partidos y resultados</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.matches.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo Enfrentamiento
            </a>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Competencia</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha/Hora</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resultado</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($matches as $match)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $match->competition->gameType->name }}</div>
                            <div class="text-xs text-gray-500">{{ $match->alliances->pluck('name')->take(3)->join(' vs ') }}@if($match->alliances->count() > 3)...@endif</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $match->match_date->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($match->result_metric)
                                <div class="text-sm text-gray-900">{{ $match->result_metric }}</div>
                                @if($match->winner)
                                    <div class="text-xs text-green-600">🏆 {{ $match->winner->name }}</div>
                                @endif
                            @else
                                <span class="text-gray-400 text-sm">Sin resultado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($match->is_finalized)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Finalizado
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pendiente
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.matches.edit', $match) }}" class="text-blue-600 hover:text-blue-900 mr-3">Editar</a>
                            @if(!$match->is_finalized && $match->result_metric)
                                <form action="{{ route('admin.matches.finalize', $match) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Finalizar este enfrentamiento?');">
                                    @csrf
                                    <button type="submit" class="text-purple-600 hover:text-purple-900 mr-3">Finalizar</button>
                                </form>
                            @endif
                            <form action="{{ route('admin.matches.destroy', $match) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Está seguro de eliminar este enfrentamiento?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No hay enfrentamientos registrados. <a href="{{ route('admin.matches.create') }}" class="text-red-600 hover:text-red-800">Crear el primero</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $matches->links() }}
    </div>
</div>
@endsection

