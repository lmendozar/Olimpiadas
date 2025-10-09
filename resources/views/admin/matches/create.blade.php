@extends('layouts.admin')

@section('title', 'Crear Enfrentamiento')

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
                <h1 class="text-2xl font-bold text-white">Crear Enfrentamiento</h1>
            </div>

            <form action="{{ route('admin.matches.store') }}" method="POST" class="px-6 py-6 space-y-6">
                @csrf

                <div>
                    <label for="competition_id" class="block text-sm font-medium text-gray-700">Competencia *</label>
                    <select name="competition_id" id="competition_id" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm @error('competition_id') border-red-500 @enderror">
                        <option value="">Seleccione una competencia</option>
                        @foreach($competitions as $competition)
                            <option value="{{ $competition->id }}" {{ old('competition_id') == $competition->id ? 'selected' : '' }}>
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
                    <input type="datetime-local" name="match_date" id="match_date" value="{{ old('match_date') }}" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm @error('match_date') border-red-500 @enderror">
                    @error('match_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alianzas Participantes * (mínimo 2)</label>
                    <div class="space-y-2 max-h-64 overflow-y-auto border border-gray-300 rounded-md p-4">
                        @foreach($alliances as $alliance)
                            <div class="flex items-center">
                                <input type="checkbox" name="alliance_ids[]" value="{{ $alliance->id }}" id="alliance_{{ $alliance->id }}"
                                       {{ is_array(old('alliance_ids')) && in_array($alliance->id, old('alliance_ids')) ? 'checked' : '' }}
                                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                <label for="alliance_{{ $alliance->id }}" class="ml-2 block text-sm text-gray-900">
                                    {{ $alliance->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('alliance_ids')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="participantsSection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="inline h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Participantes Individuales
                    </label>
                    <div class="space-y-2 max-h-64 overflow-y-auto border border-gray-300 rounded-md p-4 bg-blue-50">
                        @foreach($people as $person)
                            <div class="flex items-center justify-between py-1">
                                <div class="flex items-center">
                                    <input type="checkbox" name="participant_ids[]" value="{{ $person->id }}" id="person_{{ $person->id }}"
                                           {{ is_array(old('participant_ids')) && in_array($person->id, old('participant_ids')) ? 'checked' : '' }}
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
                    <p class="mt-1 text-sm text-gray-500">Selecciona las personas específicas que participarán en este enfrentamiento</p>
                </div>

                <div>
                    <label for="photo_urls" class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="inline h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Galería de Fotos del Evento
                    </label>
                    <textarea name="photo_urls" id="photo_urls" rows="4"
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                              placeholder="Ingresa una URL por línea:&#10;https://ejemplo.com/foto1.jpg&#10;https://ejemplo.com/foto2.jpg&#10;https://ejemplo.com/foto3.jpg">{{ old('photo_urls') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Opcional. Ingresa una URL de imagen por línea</p>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <a href="{{ route('admin.matches.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        Crear Enfrentamiento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const competitionSelect = document.getElementById('competition_id');
        const participantsSection = document.getElementById('participantsSection');
        
        const competitions = @json($competitions->map(function($comp) {
            return [
                'id' => $comp->id,
                'requires_participants' => $comp->gameType->requires_individual_participants ?? false
            ];
        }));

        competitionSelect.addEventListener('change', function() {
            const selectedId = parseInt(this.value);
            const competition = competitions.find(c => c.id === selectedId);
            
            if (competition && competition.requires_participants) {
                participantsSection.classList.remove('hidden');
            } else {
                participantsSection.classList.add('hidden');
            }
        });
    });
</script>
@endsection

