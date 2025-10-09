@extends('layouts.admin')

@section('title', 'Crear Tipo de Juego')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.game-types.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a la lista
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-blue-600">
                <h1 class="text-2xl font-bold text-white">Crear Tipo de Juego</h1>
            </div>

            <form action="{{ route('admin.game-types.store') }}" method="POST" class="px-6 py-6 space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="result_metric" class="block text-sm font-medium text-gray-700">Métrica de Resultado *</label>
                    <select name="result_metric" id="result_metric" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('result_metric') border-red-500 @enderror">
                        <option value="">Seleccione una métrica</option>
                        <option value="tiempo" {{ old('result_metric') == 'tiempo' ? 'selected' : '' }}>Tiempo (s)</option>
                        <option value="goles" {{ old('result_metric') == 'goles' ? 'selected' : '' }}>Goles</option>
                        <option value="sets" {{ old('result_metric') == 'sets' ? 'selected' : '' }}>Sets</option>
                        <option value="contador" {{ old('result_metric') == 'contador' ? 'selected' : '' }}>Contador</option>
                    </select>
                    @error('result_metric')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Define cómo se mide el resultado del juego</p>
                </div>

                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="requires_individual_participants" id="requires_individual_participants" 
                               value="1" {{ old('requires_individual_participants') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    </div>
                    <div class="ml-3">
                        <label for="requires_individual_participants" class="font-medium text-gray-700">
                            Requiere Selección de Participantes Individuales
                        </label>
                        <p class="text-sm text-gray-500">Si se marca, al crear enfrentamientos se deberá especificar qué personas específicas participan (ej: tenis individual, natación)</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <a href="{{ route('admin.game-types.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Crear Tipo de Juego
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

