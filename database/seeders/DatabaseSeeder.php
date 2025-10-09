<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\GameType;
use App\Models\Alliance;
use App\Models\Person;
use App\Models\Competition;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Users
        User::create([
            'name' => 'Admin Organizador',
            'email' => 'admin@olimpiadas.com',
            'password' => Hash::make('password'),
            'role' => 'organizador',
        ]);

        User::create([
            'name' => 'Usuario Público',
            'email' => 'publico@olimpiadas.com',
            'password' => Hash::make('password'),
            'role' => 'publico',
        ]);

        // Create Game Types
        $futbol = GameType::create([
            'name' => 'Fútbol',
            'result_metric' => 'goles',
        ]);

        $natacion = GameType::create([
            'name' => 'Natación 100m',
            'result_metric' => 'tiempo',
        ]);

        $voleibol = GameType::create([
            'name' => 'Voleibol',
            'result_metric' => 'sets',
        ]);

        $atletismo = GameType::create([
            'name' => 'Atletismo 100m',
            'result_metric' => 'tiempo',
        ]);

        // Create Alliances
        $mexico = Alliance::create([
            'name' => 'México',
            'logo_url' => 'https://flagcdn.com/w320/mx.png',
        ]);

        $brasil = Alliance::create([
            'name' => 'Brasil',
            'logo_url' => 'https://flagcdn.com/w320/br.png',
        ]);

        $argentina = Alliance::create([
            'name' => 'Argentina',
            'logo_url' => 'https://flagcdn.com/w320/ar.png',
        ]);

        $usa = Alliance::create([
            'name' => 'Estados Unidos',
            'logo_url' => 'https://flagcdn.com/w320/us.png',
        ]);

        $colombia = Alliance::create([
            'name' => 'Colombia',
            'logo_url' => 'https://flagcdn.com/w320/co.png',
        ]);

        // Create People for Mexico
        Person::create([
            'name' => 'Juan Pérez',
            'gender' => 'masculino',
            'role' => 'competidor',
            'alliance_id' => $mexico->id,
        ]);

        Person::create([
            'name' => 'María González',
            'gender' => 'femenino',
            'role' => 'competidor',
            'alliance_id' => $mexico->id,
        ]);

        Person::create([
            'name' => 'Carlos Rodríguez',
            'gender' => 'masculino',
            'role' => 'competidor',
            'alliance_id' => $mexico->id,
        ]);

        // Create People for Brasil
        Person::create([
            'name' => 'Pedro Silva',
            'gender' => 'masculino',
            'role' => 'competidor',
            'alliance_id' => $brasil->id,
        ]);

        Person::create([
            'name' => 'Ana Santos',
            'gender' => 'femenino',
            'role' => 'competidor',
            'alliance_id' => $brasil->id,
        ]);

        // Create People for Argentina
        Person::create([
            'name' => 'Diego Martínez',
            'gender' => 'masculino',
            'role' => 'competidor',
            'alliance_id' => $argentina->id,
        ]);

        Person::create([
            'name' => 'Lucía Fernández',
            'gender' => 'femenino',
            'role' => 'competidor',
            'alliance_id' => $argentina->id,
        ]);

        // Create People for USA
        Person::create([
            'name' => 'John Smith',
            'gender' => 'masculino',
            'role' => 'competidor',
            'alliance_id' => $usa->id,
        ]);

        Person::create([
            'name' => 'Sarah Johnson',
            'gender' => 'femenino',
            'role' => 'competidor',
            'alliance_id' => $usa->id,
        ]);

        // Create People for Colombia
        Person::create([
            'name' => 'Andrés López',
            'gender' => 'masculino',
            'role' => 'competidor',
            'alliance_id' => $colombia->id,
        ]);

        // Create Competitions
        $futbolComp = Competition::create([
            'game_type_id' => $futbol->id,
            'start_date' => now()->subDays(10),
            'first_place_points' => 5,
            'second_place_points' => 3,
            'third_place_points' => 1,
            'is_simultaneous' => false,
        ]);

        $natacionComp = Competition::create([
            'game_type_id' => $natacion->id,
            'start_date' => now()->subDays(5),
            'first_place_points' => 5,
            'second_place_points' => 3,
            'third_place_points' => 1,
            'is_simultaneous' => true,
        ]);

        // Create Football Matches
        $match1 = \App\Models\MatchPlay::create([
            'competition_id' => $futbolComp->id,
            'match_date' => now()->subDays(8),
            'result_metric' => '3-1',
            'winner_id' => $mexico->id,
            'is_finalized' => true,
        ]);
        $match1->alliances()->attach([$mexico->id, $brasil->id]);

        $match2 = \App\Models\MatchPlay::create([
            'competition_id' => $futbolComp->id,
            'match_date' => now()->subDays(7),
            'result_metric' => '2-1',
            'winner_id' => $argentina->id,
            'is_finalized' => true,
        ]);
        $match2->alliances()->attach([$argentina->id, $colombia->id]);

        $match3 = \App\Models\MatchPlay::create([
            'competition_id' => $futbolComp->id,
            'match_date' => now()->subDays(6),
            'result_metric' => '2-2',
            'winner_id' => null,
            'is_finalized' => true,
        ]);
        $match3->alliances()->attach([$mexico->id, $argentina->id]);

        // Finalize football competition
        $futbolComp->calculateRankings();

        // Create Swimming Match (simultaneous)
        $matchNatacion = \App\Models\MatchPlay::create([
            'competition_id' => $natacionComp->id,
            'match_date' => now()->subDays(3),
            'result_metric' => '50.23s, 50.45s, 50.67s, 51.01s',
            'is_finalized' => true,
        ]);
        $matchNatacion->alliances()->attach([
            $usa->id => ['position' => 1],
            $brasil->id => ['position' => 2],
            $mexico->id => ['position' => 3],
            $argentina->id => ['position' => 4],
        ]);

        // Finalize swimming competition
        $natacionComp->calculateRankings();

        // Create upcoming matches
        $upcomingMatch = \App\Models\MatchPlay::create([
            'competition_id' => $futbolComp->id,
            'match_date' => now()->addDays(2),
            'is_finalized' => false,
        ]);
        $upcomingMatch->alliances()->attach([$brasil->id, $colombia->id]);

        echo "Database seeded successfully!\n";
        echo "Admin: admin@olimpiadas.com / password\n";
        echo "Public: publico@olimpiadas.com / password\n";
    }
}

