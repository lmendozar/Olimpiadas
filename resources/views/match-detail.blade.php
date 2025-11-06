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

            <!-- Photo/Video Gallery Slider -->
            @if($matchRecord->photo_gallery && count($matchRecord->photo_gallery) > 0)
                <div class="mb-6 border-t pt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">📸 Galería de Fotos y Videos</h2>
                    
                    <!-- Slider Container -->
                    <div class="relative" x-data="{
                        currentIndex: 0,
                        rawUrls: @js($matchRecord->photo_gallery),
                        slides: [],
                        // Helpers
                        isYouTube(u){ return /(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)/i.test(u) },
                        isVimeo(u){ return /vimeo\.com\/(?:\d+|.*\/\d+)/i.test(u) },
                        isMp4(u){ return /\.(mp4)(?:\?|$)/i.test(u) },
                        ytId(u){
                            try {
                                const url = new URL(u);
                                if (url.hostname.includes('youtu.be')) return url.pathname.slice(1);
                                if (url.searchParams.get('v')) return url.searchParams.get('v');
                                const parts = url.pathname.split('/');
                                const idx = parts.indexOf('embed');
                                if (idx > -1 && parts[idx+1]) return parts[idx+1];
                            } catch(e) {}
                            return null;
                        },
                        vimeoId(u){
                            try {
                                const url = new URL(u);
                                // Assume last numeric path segment
                                const parts = url.pathname.split('/').filter(Boolean);
                                for (let i = parts.length - 1; i >= 0; i--) {
                                    if (/^\d+$/.test(parts[i])) return parts[i];
                                }
                            } catch(e) {}
                            return null;
                        },
                        buildSlides(){
                            this.slides = this.rawUrls.map(u => {
                                let type = 'image', embedUrl = null, thumbUrl = null;
                                if (this.isYouTube(u)) {
                                    type = 'youtube';
                                    const id = this.ytId(u);
                                    embedUrl = id ? `https://www.youtube-nocookie.com/embed/${id}` : null;
                                    thumbUrl = id ? `https://img.youtube.com/vi/${id}/hqdefault.jpg` : null;
                                } else if (this.isVimeo(u)) {
                                    type = 'vimeo';
                                    const id = this.vimeoId(u);
                                    embedUrl = id ? `https://player.vimeo.com/video/${id}` : null;
                                    thumbUrl = null; // Placeholder handled in template
                                } else if (this.isMp4(u)) {
                                    type = 'mp4';
                                    embedUrl = u;
                                    thumbUrl = null; // Placeholder handled in template
                                } else {
                                    type = 'image';
                                }
                                return { url: u, type, embedUrl, thumbUrl };
                            });
                        },
                        init(){
                            this.buildSlides();
                        },
                        next(){ this.currentIndex = (this.currentIndex + 1) % this.slides.length; },
                        prev(){ this.currentIndex = (this.currentIndex - 1 + this.slides.length) % this.slides.length; },
                        goTo(i){ this.currentIndex = i; },
                        handleKeydown(event){
                            // Solo procesar si no estamos en un input o textarea
                            if (event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA') {
                                return;
                            }
                            if (event.key === 'ArrowLeft') {
                                event.preventDefault();
                                this.prev();
                            } else if (event.key === 'ArrowRight') {
                                event.preventDefault();
                                this.next();
                            }
                        }
                    }" x-init="init()" @keydown.window="handleKeydown($event)">
                        
                        <!-- Main Image Display -->
                        <div class="relative rounded-lg overflow-hidden shadow-xl bg-gray-100 aspect-video">
                            <!-- Image -->
                            <template x-if="slides[currentIndex] && slides[currentIndex].type === 'image'">
                                <img :src="slides[currentIndex].url" :alt="'Foto ' + (currentIndex + 1)" class="w-full h-full object-cover">
                            </template>

                            <!-- YouTube -->
                            <template x-if="slides[currentIndex] && slides[currentIndex].type === 'youtube'">
                                <iframe
                                    class="w-full h-full"
                                    :src="slides[currentIndex].embedUrl"
                                    title="YouTube video player"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen
                                ></iframe>
                            </template>

                            <!-- Vimeo -->
                            <template x-if="slides[currentIndex] && slides[currentIndex].type === 'vimeo'">
                                <iframe
                                    class="w-full h-full"
                                    :src="slides[currentIndex].embedUrl"
                                    frameborder="0"
                                    allow="autoplay; fullscreen; picture-in-picture"
                                    allowfullscreen
                                ></iframe>
                            </template>

                            <!-- MP4 -->
                            <template x-if="slides[currentIndex] && slides[currentIndex].type === 'mp4'">
                                <video class="w-full h-full object-cover" controls preload="metadata">
                                    <source :src="slides[currentIndex].url" type="video/mp4">
                                </video>
                            </template>
                            
                            <!-- Navigation Arrows -->
                            <button 
                                @click="prev()"
                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition-all hover:scale-110 z-10"
                                aria-label="Imagen anterior"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            
                            <button 
                                @click="next()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition-all hover:scale-110 z-10"
                                aria-label="Imagen siguiente"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            
                            <!-- Image Counter -->
                            <div class="absolute top-4 right-4 bg-black/60 text-white px-3 py-1 rounded-full text-sm font-medium z-10">
                                <span x-text="currentIndex + 1 + ' / ' + slides.length"></span>
                            </div>
                            
                            <!-- Lightbox Button -->
                            <a 
                                :href="slides[currentIndex] ? slides[currentIndex].url : '#'"
                                target="_blank"
                                class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white/90 hover:bg-white text-gray-800 px-4 py-2 rounded-full shadow-lg transition-all hover:scale-110 z-10 flex items-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                                </svg>
                                Ver en pantalla completa
                            </a>
                        </div>
                        
                        <!-- Thumbnail Navigation -->
                        <div class="mt-4 flex gap-2 overflow-x-auto pb-2 px-2" style="scrollbar-width: thin;">
                            <template x-for="(slide, index) in slides" :key="index">
                                <button @click="goTo(index)" :class="currentIndex === index ? 'ring-4 ring-blue-500 scale-105' : 'opacity-70 hover:opacity-100'" class="flex-shrink-0 transition-all rounded-lg overflow-hidden border-2" :style="'width: ' + (100 / Math.min(slides.length, 5)) + '%; height: 80px;'">
                                    <!-- Image thumb -->
                                    <template x-if="slide.type === 'image'">
                                        <img :src="slide.url" :alt="'Miniatura ' + (index + 1)" class="w-full h-full object-cover">
                                    </template>
                                    <!-- YouTube thumb -->
                                    <template x-if="slide.type === 'youtube'">
                                        <div class="relative w-full h-full">
                                            <img :src="slide.thumbUrl" alt="YouTube thumbnail" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/25 grid place-items-center">
                                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </div>
                                        </div>
                                    </template>
                                    <!-- Vimeo/MP4 placeholder thumb -->
                                    <template x-if="slide.type === 'vimeo' || slide.type === 'mp4'">
                                        <div class="w-full h-full bg-gray-200 grid place-items-center text-gray-700">
                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    </template>
                                </button>
                            </template>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="mt-3 h-1 bg-gray-200 rounded-full overflow-hidden">
                            <div 
                                class="h-full bg-blue-600 transition-all duration-300"
                                :style="'width: ' + ((currentIndex + 1) / slides.length * 100) + '%'"
                            ></div>
                        </div>
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

