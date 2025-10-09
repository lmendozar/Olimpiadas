@extends('layouts.app')

@section('title', 'Detalles del Enfrentamiento')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Volver al Dashboard
        </a>
    </div>

    <!-- Match Details Card -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-blue-600">
            <h1 class="text-2xl font-bold text-white">{{ $matchRecord->competition->gameType->name }}</h1>
            <p class="text-blue-100 mt-1">{{ $matchRecord->match_date->format('d/m/Y H:i') }}</p>
        </div>

        <div class="px-6 py-6">
            <!-- Status -->
            <div class="mb-6">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium @if($matchRecord->is_finalized) bg-green-100 text-green-800 @else bg-yellow-100 text-yellow-800 @endif">
                    {{ $matchRecord->is_finalized ? 'Finalizado' : 'Pendiente' }}
                </span>
            </div>

            <!-- Participants -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Participantes</h2>
                <div class="space-y-4">
                    @foreach($matchRecord->alliances as $alliance)
                        <div class="border rounded-lg p-4 @if($matchRecord->winner_id == $alliance->id) border-green-500 bg-green-50 @endif">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @if($alliance->logo_url)
                                        <img src="{{ $alliance->logo_url }}" alt="{{ $alliance->name }}" class="h-12 w-12 rounded-full mr-4">
                                    @endif
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $alliance->name }}</h3>
                                        @php
                                            $competitors = $alliance->competitors;
                                            $count = $competitors->count();
                                        @endphp
                                        @if($count > 0)
                                            <p class="text-sm text-gray-600">
                                                @if($count <= 3)
                                                    Competidores: {{ $competitors->pluck('name')->join(', ') }}
                                                @else
                                                    {{ $count }} competidores
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                @if($matchRecord->winner_id == $alliance->id)
                                    <div class="text-green-600 font-bold text-xl">
                                        🏆 Ganador
                                    </div>
                                @endif
                            </div>
                            @if($matchRecord->competition->is_simultaneous && $alliance->pivot->position)
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($alliance->pivot->position == 1) bg-yellow-100 text-yellow-800
                                        @elseif($alliance->pivot->position == 2) bg-gray-100 text-gray-800
                                        @elseif($alliance->pivot->position == 3) bg-orange-100 text-orange-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        Posición: {{ $alliance->pivot->position }}°
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Result -->
            @if($matchRecord->result_metric)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Resultado</h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-2xl font-bold text-gray-900">{{ $matchRecord->result_metric }}</p>
                        <p class="text-sm text-gray-500 mt-1">Métrica: {{ $matchRecord->competition->gameType->getMetricLabel() }}</p>
                    </div>
                </div>
            @endif

            <!-- Individual Participants -->
            @if($matchRecord->participants && $matchRecord->participants->count() > 0)
                <div class="mb-6 border-t pt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Participantes Individuales</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($matchRecord->participants as $participant)
                            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $participant->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $participant->getGenderLabel() }}</p>
                                </div>
                                @if($participant->alliance)
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $participant->alliance->name }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Photo Gallery -->
            @if($matchRecord->photo_gallery && count($matchRecord->photo_gallery) > 0)
                <div class="mb-6 border-t pt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">📸 Galería de Fotos</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($matchRecord->photo_gallery as $photo)
                            <a href="{{ $photo }}" target="_blank" class="group">
                                <img src="{{ $photo }}" alt="Foto del evento" class="h-48 w-full object-cover rounded-lg border-2 border-gray-200 hover:border-blue-500 transition cursor-pointer">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Competition Info -->
            <div class="border-t pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Información de la Competencia</h2>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipo de Juego</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $matchRecord->competition->gameType->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Métrica de Resultado</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $matchRecord->competition->gameType->getMetricLabel() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Fecha de Inicio Competencia</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $matchRecord->competition->start_date->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipo de Competencia</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $matchRecord->competition->is_simultaneous ? 'Simultánea' : 'Regular' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection

