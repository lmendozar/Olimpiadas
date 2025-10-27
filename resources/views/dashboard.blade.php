@extends('layouts.app')

@section('title', 'Dashboard Público')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard de Juegos Olímpicos</h1>
            <p class="mt-2 text-gray-600">Clasificación general y resultados de competencias</p>
        </div>
        <div class="grid grid-cols-4 gap-8">

            <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
                <img src="{{ asset('images/deportivo_olimpiadas2025.jpg') }}" alt="Deportivo Olimpiadas 2025" class="w-full h-auto">
            </div>
            <!-- Overall Rankings -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8" style="grid-column: 2 / span 3;">
                <div class="px-6 py-4 bg-blue-600">
                    <h2 class="text-2xl font-bold text-white">🏆 Clasificación General</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Posición</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Alianza</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    🥇 Oro</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    🥈 Plata</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    🥉 Bronce</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Puntos</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($rankings as $index => $ranking)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span
                                                class="text-2xl font-bold @if($index == 0) text-yellow-500 @elseif($index == 1) text-gray-400 @elseif($index == 2) text-orange-600 @else text-gray-600 @endif">
                                                #{{ $index + 1 }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($ranking['alliance']->logo_url)
                                                <img class="h-10 w-10 rounded-full mr-3" src="{{ $ranking['alliance']->logo_url }}"
                                                    alt="{{ $ranking['alliance']->name }}">
                                            @endif
                                            <div class="text-sm font-medium text-gray-900">{{ $ranking['alliance']->name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-lg font-semibold text-yellow-500">{{ $ranking['gold_medals'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-lg font-semibold text-gray-400">{{ $ranking['silver_medals'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span
                                            class="text-lg font-semibold text-orange-600">{{ $ranking['bronze_medals'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-xl font-bold text-blue-600">{{ $ranking['total_points'] }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No hay clasificaciones disponibles aún.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent and Upcoming Matches -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Matches -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-green-600">
                    <h3 class="text-xl font-bold text-white">📅 Enfrentamientos Recientes</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($recentMatches as $match)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $match->competition->gameType->name }}</p>
                                    <p class="text-sm text-gray-500 mt-1">{{ $match->match_date->format('d/m/Y H:i') }}</p>
                                    <div class="mt-2 text-sm text-gray-700">
                                        {{ $match->alliances->pluck('name')->join(' vs ') }}
                                    </div>
                                    @if($match->result_metric)
                                        <p class="text-sm text-gray-600 mt-1">Resultado: <span
                                                class="font-semibold">{{ $match->result_metric }}</span></p>
                                    @endif
                                    @if($match->winner)
                                        <p class="text-sm text-green-600 mt-1">🏆 Ganador: <span
                                                class="font-semibold">{{ $match->winner->name }}</span></p>
                                    @endif
                                </div>
                                <a href="{{ route('match.show', $match) }}" class="ml-4 text-blue-600 hover:text-blue-800">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-center text-gray-500">
                            No hay enfrentamientos recientes.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Upcoming Matches -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-orange-600">
                    <h3 class="text-xl font-bold text-white">📅 Próximos Enfrentamientos</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($upcomingMatches as $match)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $match->competition->gameType->name }}</p>
                                    <p class="text-sm text-gray-500 mt-1">{{ $match->match_date->format('d/m/Y H:i') }}</p>
                                    <div class="mt-2 text-sm text-gray-700">
                                        {{ $match->alliances->pluck('name')->join(' vs ') }}
                                    </div>
                                </div>
                                <a href="{{ route('match.show', $match) }}" class="ml-4 text-blue-600 hover:text-blue-800">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-center text-gray-500">
                            No hay próximos enfrentamientos programados.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection