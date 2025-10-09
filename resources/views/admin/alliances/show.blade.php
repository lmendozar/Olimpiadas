@extends('layouts.admin')

@section('title', 'Ver Alianza')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.alliances.index') }}" class="text-green-600 hover:text-green-800 inline-flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a la lista
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-green-600 flex items-center justify-between">
                <div class="flex items-center">
                    @if($alliance->logo_url)
                        <img src="{{ $alliance->logo_url }}" alt="{{ $alliance->name }}" class="h-16 w-16 rounded-full mr-4">
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold text-white">{{ $alliance->name }}</h1>
                        <p class="text-green-100">Puntos totales: {{ $alliance->getTotalPoints() }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.alliances.edit', $alliance) }}" class="px-4 py-2 bg-white text-green-600 rounded-md hover:bg-green-50">
                    Editar
                </a>
            </div>

            <!-- Members -->
            <div class="px-6 py-6 border-b">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Miembros ({{ $alliance->people->count() }})</h2>
                @if($alliance->people->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Género</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($alliance->people as $person)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $person->name }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500">{{ $person->getGenderLabel() }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500">{{ $person->getRoleLabel() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">No hay miembros registrados en esta alianza.</p>
                @endif
            </div>

            <!-- Competition Rankings -->
            <div class="px-6 py-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Medallas y Rankings ({{ $alliance->competitionRankings->count() }})</h2>
                @if($alliance->competitionRankings->count() > 0)
                    <div class="space-y-3">
                        @foreach($alliance->competitionRankings as $ranking)
                            <div class="border rounded-lg p-4 flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $ranking->competition->gameType->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $ranking->competition->start_date->format('d/m/Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                        @if($ranking->position == 1) bg-yellow-100 text-yellow-800
                                        @elseif($ranking->position == 2) bg-gray-100 text-gray-800
                                        @else bg-orange-100 text-orange-800 @endif">
                                        {{ $ranking->getMedalType() }}
                                    </span>
                                    <p class="text-sm text-gray-500 mt-1">{{ $ranking->points_awarded }} puntos</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Esta alianza aún no tiene medallas.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

