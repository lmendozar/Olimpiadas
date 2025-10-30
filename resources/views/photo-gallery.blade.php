@extends('layouts.app')

@section('title', 'Galería de Fotos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Volver al Dashboard
        </a>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 bg-blue-600">
            <h1 class="text-3xl font-bold text-white">📸 Galería de Fotos de Olimpiadas</h1>
            <p class="text-blue-100 mt-2">Todas las imágenes de los enfrentamientos deportivos</p>
        </div>
    </div>

    <!-- Slider Gallery -->
    @if(count($galleryItems) > 0)
        <div class="bg-white shadow-lg rounded-lg overflow-hidden" 
             x-data="{
                currentIndex: 0,
                items: @js($galleryItems),
                navigating: false,
                next() {
                    if (!this.navigating) {
                        this.navigating = true;
                        this.currentIndex = (this.currentIndex + 1) % this.items.length;
                        setTimeout(() => { this.navigating = false; }, 300);
                    }
                },
                prev() {
                    if (!this.navigating) {
                        this.navigating = true;
                        this.currentIndex = (this.currentIndex - 1 + this.items.length) % this.items.length;
                        setTimeout(() => { this.navigating = false; }, 300);
                    }
                },
                init() {
                    // Keyboard navigation
                    const handleKeydown = (e) => {
                        if (e.key === 'ArrowLeft') {
                            e.preventDefault();
                            this.prev();
                        } else if (e.key === 'ArrowRight') {
                            e.preventDefault();
                            this.next();
                        } else if (e.key === 'Home') {
                            e.preventDefault();
                            this.currentIndex = 0;
                        } else if (e.key === 'End') {
                            e.preventDefault();
                            this.currentIndex = this.items.length - 1;
                        }
                    };
                    window.addEventListener('keydown', handleKeydown);
                    
                    // Touch/swipe support
                    let startX = 0;
                    let startY = 0;
                    
                    const handleTouchStart = (e) => {
                        startX = e.touches[0].clientX;
                        startY = e.touches[0].clientY;
                    };
                    
                    const handleTouchEnd = (e) => {
                        let endX = e.changedTouches[0].clientX;
                        let endY = e.changedTouches[0].clientY;
                        let diffX = startX - endX;
                        let diffY = startY - endY;
                        
                        // Check if horizontal swipe is greater than vertical
                        if (Math.abs(diffX) > Math.abs(diffY)) {
                            if (diffX > 50) {
                                this.next();
                            } else if (diffX < -50) {
                                this.prev();
                            }
                        }
                    };
                    
                    window.addEventListener('touchstart', handleTouchStart);
                    window.addEventListener('touchend', handleTouchEnd);
                    
                    // Cleanup on destroy
                    this.$el.addEventListener('remove', () => {
                        window.removeEventListener('keydown', handleKeydown);
                        window.removeEventListener('touchstart', handleTouchStart);
                        window.removeEventListener('touchend', handleTouchEnd);
                    });
                }
             }"
             x-init="init()">
            
            <!-- Main Image Display -->
            <div class="relative bg-gray-900 aspect-video">
                <img 
                    :src="items[currentIndex].photo"
                    :alt="items[currentIndex].game_type"
                    class="w-full h-full object-cover"
                >

                <!-- Image Counter -->
                <div class="absolute top-4 right-4 bg-black/70 text-white px-4 py-2 rounded-full text-sm font-medium">
                    <span x-text="(currentIndex + 1) + ' / ' + items.length"></span>
                </div>

                <!-- Navigation Arrows -->
                <button 
                    @click="prev()"
                    class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white p-4 rounded-full shadow-lg transition-all hover:scale-110"
                    aria-label="Imagen anterior"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                
                <button 
                    @click="next()"
                    class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white p-4 rounded-full shadow-lg transition-all hover:scale-110"
                    aria-label="Imagen siguiente"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

            </div>

            <!-- Match Information Below Image -->
            <div class="px-8 py-6 bg-gradient-to-br from-blue-50 to-indigo-50 border-b border-gray-200">
                <div class="max-w-4xl mx-auto">
                    <div class="flex items-start justify-between mb-4">
                        <h2 class="text-3xl font-bold text-gray-900" x-text="items[currentIndex].game_type"></h2>
                        <template x-if="items[currentIndex].match_id">
                            <a 
                                :href="'/match/' + items[currentIndex].match_id"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md transition-all hover:scale-105 flex items-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Ver Detalles
                            </a>
                        </template>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex items-start gap-3 bg-white/70 rounded-lg p-4 backdrop-blur-sm">
                            <div class="bg-blue-100 p-3 rounded-lg">
                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Fecha y Hora</p>
                                <p class="text-lg font-bold text-gray-900" x-text="items[currentIndex].match_date"></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 bg-white/70 rounded-lg p-4 backdrop-blur-sm">
                            <div class="bg-green-100 p-3 rounded-lg">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Participantes</p>
                                <p class="text-lg font-bold text-gray-900" x-text="items[currentIndex].alliances"></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 bg-white/70 rounded-lg p-4 backdrop-blur-sm">
                            <div class="bg-purple-100 p-3 rounded-lg">
                                <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Resultado</p>
                                <p class="text-lg font-bold text-gray-900" x-text="items[currentIndex].result"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thumbnail Strip with Controls -->
            <div class="p-6 bg-gray-100">
                <!-- Controls Above Thumbnails -->
                <div class="mb-4 flex flex-wrap justify-center gap-3">
                    <button 
                        @click="currentIndex = 0"
                        :disabled="currentIndex === 0"
                        :class="currentIndex === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-50'"
                        class="bg-white border-2 border-gray-300 text-gray-700 hover:border-blue-500 hover:text-blue-600 px-4 py-2 rounded-lg transition-all flex items-center gap-2 font-medium"
                        aria-label="Primera imagen"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                        <span class="text-sm">Primera</span>
                    </button>

                    <button 
                        @click="prev()"
                        :disabled="currentIndex === 0"
                        :class="currentIndex === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-50'"
                        class="bg-white border-2 border-gray-300 text-gray-700 hover:border-blue-500 hover:text-blue-600 px-4 py-2 rounded-lg transition-all flex items-center gap-2 font-medium"
                        aria-label="Anterior"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span class="text-sm">Anterior</span>
                    </button>

                    <div class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold">
                        <span x-text="(currentIndex + 1) + ' / ' + items.length"></span>
                    </div>

                    <button 
                        @click="next()"
                        :disabled="currentIndex === items.length - 1"
                        :class="currentIndex === items.length - 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-50'"
                        class="bg-white border-2 border-gray-300 text-gray-700 hover:border-blue-500 hover:text-blue-600 px-4 py-2 rounded-lg transition-all flex items-center gap-2 font-medium"
                        aria-label="Siguiente"
                    >
                        <span class="text-sm">Siguiente</span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <button 
                        @click="currentIndex = items.length - 1"
                        :disabled="currentIndex === items.length - 1"
                        :class="currentIndex === items.length - 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-50'"
                        class="bg-white border-2 border-gray-300 text-gray-700 hover:border-blue-500 hover:text-blue-600 px-4 py-2 rounded-lg transition-all flex items-center gap-2 font-medium"
                        aria-label="Última imagen"
                    >
                        <span class="text-sm">Última</span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <div class="flex gap-2 overflow-x-auto pb-2" style="scrollbar-width: thin;">
                    <template x-for="(item, index) in items" :key="index">
                        <button
                            @click="currentIndex = index"
                            :class="currentIndex === index ? 'ring-4 ring-blue-500 scale-105' : 'opacity-70 hover:opacity-100'"
                            class="flex-shrink-0 transition-all rounded-lg overflow-hidden border-2 relative"
                            style="width: 120px; height: 80px;"
                        >
                            <img 
                                :src="item.photo" 
                                :alt="item.game_type"
                                class="w-full h-full object-cover"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 hover:opacity-100 transition-opacity">
                                <p class="absolute bottom-1 left-1 right-1 text-white text-xs font-medium truncate" x-text="item.game_type"></p>
                            </div>
                        </button>
                    </template>
                </div>
                
                <!-- Progress Bar -->
                <div class="mt-4 h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div 
                        class="h-full bg-blue-600 transition-all duration-300"
                        :style="'width: ' + ((currentIndex + 1) / items.length * 100) + '%'"
                    ></div>
                </div>

                <!-- Keyboard Shortcuts Help -->
                <div class="mt-4 text-center text-xs text-gray-500">
                    <p>💡 Usa las flechas ← → del teclado para navegar, o desliza en móvil</p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white shadow-lg rounded-lg p-12 text-center">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No hay fotos disponibles</h3>
            <p class="mt-2 text-sm text-gray-500">Agrega fotos a los enfrentamientos para que aparezcan aquí.</p>
        </div>
    @endif
</div>
@endsection
