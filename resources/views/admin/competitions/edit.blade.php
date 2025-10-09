@extends('layouts.admin')

@section('title', 'Editar Competencia')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.competitions.index') }}" class="text-yellow-600 hover:text-yellow-800 inline-flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a la lista
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-yellow-600">
                <h1 class="text-2xl font-bold text-white">Editar Competencia</h1>
            </div>

            <form action="{{ route('admin.competitions.update', $competition) }}" method="POST" class="px-6 py-6 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="game_type_id" class="block text-sm font-medium text-gray-700">Tipo de Juego *</label>
                    <select name="game_type_id" id="game_type_id" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm @error('game_type_id') border-red-500 @enderror">
                        <option value="">Seleccione un tipo de juego</option>
                        @foreach($gameTypes as $gameType)
                            <option value="{{ $gameType->id }}" {{ old('game_type_id', $competition->game_type_id) == $gameType->id ? 'selected' : '' }}>
                                {{ $gameType->name }} ({{ $gameType->getMetricLabel() }})
                            </option>
                        @endforeach
                    </select>
                    @error('game_type_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Fecha de Inicio *</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $competition->start_date->format('Y-m-d')) }}" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm @error('start_date') border-red-500 @enderror">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label for="first_place_points" class="block text-sm font-medium text-gray-700">Puntos Oro *</label>
                        <input type="number" name="first_place_points" id="first_place_points" value="{{ old('first_place_points', $competition->first_place_points) }}" required min="0"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm @error('first_place_points') border-red-500 @enderror">
                        @error('first_place_points')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="second_place_points" class="block text-sm font-medium text-gray-700">Puntos Plata *</label>
                        <input type="number" name="second_place_points" id="second_place_points" value="{{ old('second_place_points', $competition->second_place_points) }}" required min="0"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm @error('second_place_points') border-red-500 @enderror">
                        @error('second_place_points')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="third_place_points" class="block text-sm font-medium text-gray-700">Puntos Bronce *</label>
                        <input type="number" name="third_place_points" id="third_place_points" value="{{ old('third_place_points', $competition->third_place_points) }}" required min="0"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm @error('third_place_points') border-red-500 @enderror">
                        @error('third_place_points')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_simultaneous" id="is_simultaneous" value="1" {{ old('is_simultaneous', $competition->is_simultaneous) ? 'checked' : '' }}
                           class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded">
                    <label for="is_simultaneous" class="ml-2 block text-sm text-gray-900">
                        Competencia Simultánea
                    </label>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <a href="{{ route('admin.competitions.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700">
                        Actualizar Competencia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

