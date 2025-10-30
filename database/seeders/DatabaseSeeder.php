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

        $basket = GameType::create([
            'name' => 'Baloncesto',
            'result_metric' => 'contador',
        ]);

        $tenis = GameType::create([
            'name' => 'Tenis Individual',
            'result_metric' => 'sets',
            'requires_individual_participants' => true,
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
            'photo_gallery' => [
                'https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=800&h=600&fit=crop',
                'https://www.youtube.com/watch?v=jHPOzQzk9Qo',
                'https://images.unsplash.com/photo-1551958219-acbc608c6377?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=800&h=600&fit=crop',
            ],
        ]);
        $match1->alliances()->attach([$mexico->id, $brasil->id]);

        $match2 = \App\Models\MatchPlay::create([
            'competition_id' => $futbolComp->id,
            'match_date' => now()->subDays(7),
            'result_metric' => '2-1',
            'winner_id' => $argentina->id,
            'is_finalized' => true,
            'photo_gallery' => [
                'https://www.youtube.com/watch?v=8fyx50W6R3o',
                'https://images.unsplash.com/photo-1551958219-acbc608c6377?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1589487391730-58f20eb2c308?w=800&h=600&fit=crop',
            ],
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
            'photo_gallery' => [
                'https://images.unsplash.com/photo-1530549387789-4c1017266635?w=800&h=600&fit=crop',
                'https://vimeo.com/148751763',
                'https://images.unsplash.com/photo-1571902943202-507ec2618e8f?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1551782450-17144efb9c50?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1576610616656-d3aa5d1f4534?w=800&h=600&fit=crop',
            ],
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

        // Create additional matches with photos for more variety
        // Create Basketball competition
        $basketComp = Competition::create([
            'game_type_id' => $basket->id,
            'start_date' => now()->subDays(4),
            'first_place_points' => 5,
            'second_place_points' => 3,
            'third_place_points' => 1,
            'is_simultaneous' => false,
        ]);

        $matchBasket = \App\Models\MatchPlay::create([
            'competition_id' => $basketComp->id,
            'match_date' => now()->subDays(2),
            'result_metric' => '85-72',
            'winner_id' => $usa->id,
            'is_finalized' => true,
            'photo_gallery' => [
                'https://images.unsplash.com/photo-1515734674582-29010c7f6f77?w=800&h=600&fit=crop',
                'https://www.youtube.com/watch?v=ScMzIvxBSi4',
                'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1576678927484-cc907957088c?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1578926318260-1a3a4e2c7d36?w=800&h=600&fit=crop',
            ],
        ]);
        $matchBasket->alliances()->attach([$usa->id, $brasil->id]);

        // Create Atletismo competition
        $atletismoComp = Competition::create([
            'game_type_id' => $atletismo->id,
            'start_date' => now()->subDays(3),
            'first_place_points' => 5,
            'second_place_points' => 3,
            'third_place_points' => 1,
            'is_simultaneous' => true,
        ]);

        $matchAtletismo = \App\Models\MatchPlay::create([
            'competition_id' => $atletismoComp->id,
            'match_date' => now()->subDays(1),
            'result_metric' => '9.87s, 9.95s, 10.01s',
            'is_finalized' => true,
            'photo_gallery' => [
                'https://images.unsplash.com/photo-1530533718754-001d2668365a?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1574684918287-88e3d84af8e2?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1612434444127-e2d2e6c58ac4?w=800&h=600&fit=crop',
            ],
        ]);
        $matchAtletismo->alliances()->attach([
            $usa->id => ['position' => 1],
            $brasil->id => ['position' => 2],
            $mexico->id => ['position' => 3],
        ]);

        // Finalize competitions
        $basketComp->calculateRankings();
        $atletismoComp->calculateRankings();

        echo "Database seeded successfully!\n";
        echo "Admin: admin@olimpiadas.com / password\n";
        echo "Public: publico@olimpiadas.com / password\n";
    }
}

