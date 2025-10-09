@extends('layouts.admin')

@section('title', 'Ver Competencia')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-5xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.competitions.index') }}" class="text-yellow-600 hover:text-yellow-800 inline-flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a la lista
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-yellow-600 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ $competition->gameType->name }}</h1>
                    <p class="text-yellow-100">{{ $competition->start_date->format('d/m/Y') }}</p>
                </div>
                <div class="flex space-x-2">
                    @if(!$competition->is_finalized)
                        <form action="{{ route('admin.competitions.finalize', $competition) }}" method="POST" onsubmit="return confirm('¿Finalizar y calcular rankings?');">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-white text-yellow-600 rounded-md hover:bg-yellow-50">
                                Finalizar Competencia
                            </button>
                        </form>
                    @else
                        <span class="px-4 py-2 bg-green-500 text-white rounded-md">
                            Finalizada
                        </span>
                    @endif
                    <a href="{{ route('admin.competitions.edit', $competition) }}" class="px-4 py-2 bg-white text-yellow-600 rounded-md hover:bg-yellow-50">
                        Editar
                    </a>
                </div>
            </div>

            <!-- Competition Info -->
            <div class="px-6 py-6 border-b">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Información de la Competencia</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipo de Juego</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $competition->gameType->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Métrica</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $competition->gameType->getMetricLabel() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Puntos (Oro/Plata/Bronce)</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $competition->first_place_points }} / {{ $competition->second_place_points }} / {{ $competition->third_place_points }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipo de Competencia</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $competition->is_simultaneous ? 'Simultánea' : 'Regular' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Matches -->
            <div class="px-6 py-6 border-b">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Enfrentamientos ({{ $competition->matches->count() }})</h2>
                @if($competition->matches->count() > 0)
                    <div class="space-y-3">
                        @foreach($competition->matches as $match)
                            <div class="border rounded-lg p-4 flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-500">{{ $match->match_date->format('d/m/Y H:i') }}</p>
                                    <p class="font-medium text-gray-900">{{ $match->alliances->pluck('name')->join(' vs ') }}</p>
                                    @if($match->result_metric)
                                        <p class="text-sm text-gray-600">Resultado: {{ $match->result_metric }}</p>
                                    @endif
                                    @if($match->winner)
                                        <p class="text-sm text-green-600">Ganador: {{ $match->winner->name }}</p>
                                    @endif
                                </div>
                                <div>
                                    @if($match->is_finalized)
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Finalizado</span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Pendiente</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No hay enfrentamientos registrados.</p>
                @endif
            </div>

            <!-- Rankings -->
            @if($competition->is_finalized && $competition->rankings->count() > 0)
                <div class="px-6 py-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Rankings Finales</h2>
                    <div class="space-y-3">
                        @foreach($competition->rankings->sortBy('position') as $ranking)
                            <div class="border rounded-lg p-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="text-2xl mr-4">
                                        @if($ranking->position == 1) 🥇
                                        @elseif($ranking->position == 2) 🥈
                                        @elseif($ranking->position == 3) 🥉
                                        @else {{ $ranking->position }}°
                                        @endif
                                    </span>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $ranking->alliance->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $ranking->getMedalType() }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-lg text-blue-600">{{ $ranking->points_awarded }} pts</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

