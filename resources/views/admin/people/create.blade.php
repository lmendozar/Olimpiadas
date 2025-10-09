@extends('layouts.admin')

@section('title', 'Crear Persona')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.people.index') }}" class="text-purple-600 hover:text-purple-800 inline-flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a la lista
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h1 class="text-2xl font-bold text-white">Crear Persona</h1>
            </div>

            <form action="{{ route('admin.people.store') }}" method="POST" class="px-6 py-6 space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre Completo *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Género *</label>
                    <select name="gender" id="gender" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm @error('gender') border-red-500 @enderror">
                        <option value="">Seleccione un género</option>
                        <option value="masculino" {{ old('gender') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="femenino" {{ old('gender') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                        <option value="otro" {{ old('gender') == 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                    @error('gender')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Rol *</label>
                    <select name="role" id="role" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm @error('role') border-red-500 @enderror">
                        <option value="">Seleccione un rol</option>
                        <option value="competidor" {{ old('role') == 'competidor' ? 'selected' : '' }}>Competidor</option>
                        <option value="organizador" {{ old('role') == 'organizador' ? 'selected' : '' }}>Organizador</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="alliance_id" class="block text-sm font-medium text-gray-700">Alianza</label>
                    <select name="alliance_id" id="alliance_id"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm @error('alliance_id') border-red-500 @enderror">
                        <option value="">Sin alianza</option>
                        @foreach($alliances as $alliance)
                            <option value="{{ $alliance->id }}" {{ old('alliance_id') == $alliance->id ? 'selected' : '' }}>
                                {{ $alliance->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('alliance_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Opcional. Seleccione la alianza a la que pertenece</p>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <a href="{{ route('admin.people.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700">
                        Crear Persona
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

