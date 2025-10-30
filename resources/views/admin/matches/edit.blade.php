@extends('layouts.admin')

@section('title', 'Editar Enfrentamiento')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.matches.index') }}" class="text-red-600 hover:text-red-800 inline-flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a la lista
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-red-600">
                <h1 class="text-2xl font-bold text-white">Editar Enfrentamiento</h1>
                <p class="text-red-100 text-sm">{{ $match->competition->gameType->name }}</p>
            </div>

            <form action="{{ route('admin.matches.update', $match) }}" method="POST" class="px-6 py-6 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="competition_id" class="block text-sm font-medium text-gray-700">Competencia *</label>
                    <select name="competition_id" id="competition_id" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm @error('competition_id') border-red-500 @enderror">
                        <option value="">Seleccione una competencia</option>
                        @foreach($competitions as $competition)
                            <option value="{{ $competition->id }}" {{ old('competition_id', $match->competition_id) == $competition->id ? 'selected' : '' }}>
                                {{ $competition->gameType->name }} - {{ $competition->start_date->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                    @error('competition_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="match_date" class="block text-sm font-medium text-gray-700">Fecha y Hora *</label>
                    <input type="datetime-local" name="match_date" id="match_date" value="{{ old('match_date', $match->match_date->format('Y-m-d\TH:i')) }}" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm @error('match_date') border-red-500 @enderror">
                    @error('match_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="result_metric" class="block text-sm font-medium text-gray-700">Resultado</label>
                    <input type="text" name="result_metric" id="result_metric" value="{{ old('result_metric', $match->result_metric) }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm @error('result_metric') border-red-500 @enderror"
                           placeholder="Ej: 3-1, 1:05.23">
                    @error('result_metric')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Formato según métrica: {{ $match->competition->gameType->getMetricLabel() }}</p>
                </div>

                <div>
                    <label for="winner_id" class="block text-sm font-medium text-gray-700">Ganador</label>
                    <select name="winner_id" id="winner_id"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm @error('winner_id') border-red-500 @enderror">
                        <option value="">Sin ganador / Empate</option>
                        @foreach($match->alliances as $alliance)
                            <option value="{{ $alliance->id }}" {{ old('winner_id', $match->winner_id) == $alliance->id ? 'selected' : '' }}>
                                {{ $alliance->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('winner_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alianzas Participantes *</label>
                    <div class="space-y-2 max-h-64 overflow-y-auto border border-gray-300 rounded-md p-4">
                        @foreach($alliances as $alliance)
                            @php
                                $oldSelected = is_array(old('alliance_ids')) ? in_array($alliance->id, old('alliance_ids')) : $match->alliances->contains($alliance->id);
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input type="checkbox" name="alliance_ids[]" value="{{ $alliance->id }}" id="alliance_{{ $alliance->id }}"
                                           {{ $oldSelected ? 'checked' : '' }}
                                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <label for="alliance_{{ $alliance->id }}" class="ml-2 block text-sm text-gray-900">
                                        {{ $alliance->name }}
                                    </label>
                                </div>
                                @if($match->competition->is_simultaneous)
                                    <div class="ml-4">
                                        @php
                                            $foundAlliance = $match->alliances->find($alliance->id);
                                            $position = $foundAlliance ? $foundAlliance->pivot->position : null;
                                        @endphp
                                        <input type="number" name="positions[{{ $loop->index }}]" min="1" placeholder="Pos"
                                               value="{{ old('positions.' . $loop->index, $position) }}"
                                               class="w-16 border border-gray-300 rounded px-2 py-1 text-sm">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @if($match->competition->is_simultaneous)
                        <p class="mt-1 text-sm text-gray-500">Ingrese las posiciones para competencias simultáneas (1=Oro, 2=Plata, 3=Bronce)</p>
                    @endif
                    @error('alliance_ids')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @if($match->competition->gameType->requires_individual_participants)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="inline h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Participantes Individuales *
                    </label>
                    <div class="space-y-2 max-h-64 overflow-y-auto border border-gray-300 rounded-md p-4 bg-blue-50">
                        @foreach($people as $person)
                            @php
                                $isSelected = is_array(old('participant_ids')) 
                                    ? in_array($person->id, old('participant_ids')) 
                                    : $match->participants->contains($person->id);
                            @endphp
                            <div class="flex items-center justify-between py-1">
                                <div class="flex items-center">
                                    <input type="checkbox" name="participant_ids[]" value="{{ $person->id }}" id="person_{{ $person->id }}"
                                           {{ $isSelected ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="person_{{ $person->id }}" class="ml-2 block text-sm text-gray-900">
                                        {{ $person->name }}
                                    </label>
                                </div>
                                @if($person->alliance)
                                    <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded">{{ $person->alliance->name }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Selecciona las personas específicas que participarán</p>
                </div>
                @endif

                <div>
                    <label for="photo_urls" class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="inline h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Galería de Fotos y Videos del Evento
                    </label>
                    <textarea name="photo_urls" id="photo_urls" rows="4"
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                              placeholder="Ingresa una URL por línea:&#10;https://ejemplo.com/foto1.jpg&#10;https://youtube.com/watch?v=VIDEO_ID&#10;https://vimeo.com/VIDEO_ID&#10;https://ejemplo.com/video.mp4">{{ old('photo_urls', $match->photo_gallery ? implode("\n", $match->photo_gallery) : '') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Opcional. Admite imágenes, videos MP4, YouTube y Vimeo - Una URL por línea</p>
                    
                    @if($match->photo_gallery && count($match->photo_gallery) > 0)
                        <div class="mt-3">
                            <p class="text-sm text-gray-700 mb-2">Fotos actuales:</p>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($match->photo_gallery as $photo)
                                    <img src="{{ $photo }}" alt="Foto del evento" class="h-24 w-full object-cover rounded border">
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <a href="{{ route('admin.matches.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancelar
                    </a>
                    @if(!$match->is_finalized && $match->result_metric)
                        <button type="button" onclick="document.getElementById('finalizeForm').submit();" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700">
                            Guardar y Finalizar
                        </button>
                    @endif
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        Actualizar Enfrentamiento
                    </button>
                </div>
            </form>

            @if(!$match->is_finalized && $match->result_metric)
                <form id="finalizeForm" action="{{ route('admin.matches.finalize', $match) }}" method="POST" class="hidden">
                    @csrf
                </form>
            @endif
        </div>
    </div>
</div>
@endsection

