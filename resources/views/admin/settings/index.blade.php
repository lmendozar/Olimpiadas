@extends('layouts.admin')

@section('title', 'Configuración del Sistema')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Configuración del Sistema</h1>
            <p class="mt-2 text-gray-600">Personaliza el título, logo y colores de la aplicación</p>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">
                <h2 class="text-2xl font-bold text-white">Personalización</h2>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="px-6 py-6 space-y-8">
                @csrf

                <!-- General Settings -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Información General
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label for="system_title" class="block text-sm font-medium text-gray-700">Título del Sistema *</label>
                            <input type="text" name="system_title" id="system_title" 
                                   value="{{ old('system_title', $settings['system_title'] ?? 'Sistema de Gestión de Olimpiadas') }}" 
                                   required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('system_title') border-red-500 @enderror">
                            @error('system_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Este título aparecerá en la navegación y encabezados</p>
                        </div>

                        <div>
                            <label for="system_logo" class="block text-sm font-medium text-gray-700">URL del Logo</label>
                            <input type="url" name="system_logo" id="system_logo" 
                                   value="{{ old('system_logo', $settings['system_logo'] ?? '') }}" 
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('system_logo') border-red-500 @enderror"
                                   placeholder="https://ejemplo.com/logo.png">
                            @error('system_logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Opcional. Logo que aparecerá en la navegación</p>
                            @if(!empty($settings['system_logo']))
                                <div class="mt-3">
                                    <p class="text-sm text-gray-700 mb-2">Logo actual:</p>
                                    <img src="{{ $settings['system_logo'] }}" alt="Logo" class="h-16 rounded border">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Color Palette -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                        Paleta de Colores
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">Color Primario *</label>
                            <div class="flex items-center space-x-3">
                                <input type="color" name="primary_color" id="primary_color" 
                                       value="{{ old('primary_color', $settings['primary_color'] ?? '#2563eb') }}" 
                                       required
                                       class="h-12 w-20 border border-gray-300 rounded cursor-pointer">
                                <input type="text" 
                                       value="{{ old('primary_color', $settings['primary_color'] ?? '#2563eb') }}" 
                                       readonly
                                       class="flex-1 border border-gray-300 rounded-md py-2 px-3 text-sm bg-gray-50"
                                       id="primary_color_text">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Para elementos principales y botones</p>
                        </div>

                        <div>
                            <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-2">Color Secundario *</label>
                            <div class="flex items-center space-x-3">
                                <input type="color" name="secondary_color" id="secondary_color" 
                                       value="{{ old('secondary_color', $settings['secondary_color'] ?? '#059669') }}" 
                                       required
                                       class="h-12 w-20 border border-gray-300 rounded cursor-pointer">
                                <input type="text" 
                                       value="{{ old('secondary_color', $settings['secondary_color'] ?? '#059669') }}" 
                                       readonly
                                       class="flex-1 border border-gray-300 rounded-md py-2 px-3 text-sm bg-gray-50"
                                       id="secondary_color_text">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Para elementos secundarios</p>
                        </div>

                        <div>
                            <label for="accent_color" class="block text-sm font-medium text-gray-700 mb-2">Color de Acento *</label>
                            <div class="flex items-center space-x-3">
                                <input type="color" name="accent_color" id="accent_color" 
                                       value="{{ old('accent_color', $settings['accent_color'] ?? '#dc2626') }}" 
                                       required
                                       class="h-12 w-20 border border-gray-300 rounded cursor-pointer">
                                <input type="text" 
                                       value="{{ old('accent_color', $settings['accent_color'] ?? '#dc2626') }}" 
                                       readonly
                                       class="flex-1 border border-gray-300 rounded-md py-2 px-3 text-sm bg-gray-50"
                                       id="accent_color_text">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Para llamados a la acción y alertas</p>
                        </div>
                    </div>
                </div>

                <!-- Event Gallery -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Galería de Eventos
                    </h3>

                    <div>
                        <label for="event_gallery" class="block text-sm font-medium text-gray-700 mb-2">
                            URLs de Fotos y Videos del Evento
                        </label>
                        <textarea name="event_gallery" id="event_gallery" rows="6"
                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                  placeholder="Ingresa una URL por línea:&#10;https://ejemplo.com/evento1.jpg&#10;https://youtube.com/watch?v=VIDEO_ID&#10;https://vimeo.com/VIDEO_ID&#10;https://ejemplo.com/video.mp4">@if(isset($settings['event_gallery'])){{ json_decode($settings['event_gallery'], true) ? implode("\n", json_decode($settings['event_gallery'], true)) : '' }}@endif</textarea>
                        <p class="mt-1 text-sm text-gray-500">Estas fotos y videos aparecerán en la galería pública. Admite imágenes, videos MP4, YouTube y Vimeo</p>
                        
                        @if(isset($settings['event_gallery']) && !empty(json_decode($settings['event_gallery'], true)))
                            <div class="mt-3">
                                <p class="text-sm text-gray-700 mb-2">Fotos actuales del evento:</p>
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach(json_decode($settings['event_gallery'], true) as $photo)
                                        <img src="{{ $photo }}" alt="Foto del evento" class="h-24 w-full object-cover rounded border">
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Preview -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Vista Previa</h3>
                    <div class="bg-gray-50 rounded-lg p-6 space-y-3">
                        <button type="button" id="preview_primary" class="px-4 py-2 rounded-md text-white font-medium" style="background-color: {{ $settings['primary_color'] ?? '#2563eb' }}">
                            Botón Primario
                        </button>
                        <button type="button" id="preview_secondary" class="px-4 py-2 rounded-md text-white font-medium" style="background-color: {{ $settings['secondary_color'] ?? '#059669' }}">
                            Botón Secundario
                        </button>
                        <button type="button" id="preview_accent" class="px-4 py-2 rounded-md text-white font-medium" style="background-color: {{ $settings['accent_color'] ?? '#dc2626' }}">
                            Botón de Acento
                        </button>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center pt-6 border-t">
                    <form action="{{ route('admin.settings.reset') }}" method="POST" onsubmit="return confirm('¿Restaurar valores por defecto?');">
                        @csrf
                        <button type="submit" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Restaurar Valores por Defecto
                        </button>
                    </form>

                    <button type="submit" class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Guardar Configuración
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Section -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-sm font-semibold text-blue-900 mb-2">💡 Consejos</h3>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>• Usa colores con buen contraste para mejorar la legibilidad</li>
                <li>• El logo debe ser una URL pública accesible (ej: imgur, cloudinary)</li>
                <li>• Los cambios se aplicarán inmediatamente después de guardar</li>
                <li>• Puedes usar cualquier selector de color hexadecimal (#RRGGBB)</li>
            </ul>
        </div>
    </div>
</div>

<script>
    // Update color preview in real-time
    document.getElementById('primary_color').addEventListener('input', function(e) {
        document.getElementById('primary_color_text').value = e.target.value;
        document.getElementById('preview_primary').style.backgroundColor = e.target.value;
    });

    document.getElementById('secondary_color').addEventListener('input', function(e) {
        document.getElementById('secondary_color_text').value = e.target.value;
        document.getElementById('preview_secondary').style.backgroundColor = e.target.value;
    });

    document.getElementById('accent_color').addEventListener('input', function(e) {
        document.getElementById('accent_color_text').value = e.target.value;
        document.getElementById('preview_accent').style.backgroundColor = e.target.value;
    });
</script>
@endsection

