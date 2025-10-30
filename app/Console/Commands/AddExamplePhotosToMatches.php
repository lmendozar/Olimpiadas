<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MatchPlay;

class AddExamplePhotosToMatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matches:add-example-photos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add example photos to matches without gallery';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $matches = MatchPlay::with('competition.gameType')->get();
        $updated = 0;

        foreach ($matches as $match) {
            // Skip matches that already have photos
            if (!empty($match->photo_gallery) && count($match->photo_gallery) > 0) {
                continue;
            }

            $gameTypeName = strtolower($match->competition->gameType->name);
            $photos = $this->getExamplePhotos($gameTypeName);

            $match->update(['photo_gallery' => $photos]);
            $updated++;

            $this->info("Added photos to match: {$match->competition->gameType->name} - {$match->match_date->format('Y-m-d')}");
        }

        $this->info("\n✅ Updated {$updated} matches with example photos.");

        return 0;
    }

    /**
     * Get example photos based on game type
     */
    private function getExamplePhotos($gameTypeName): array
    {
        $photos = [];
        
        if (str_contains($gameTypeName, 'futbol') || str_contains($gameTypeName, 'football') || str_contains($gameTypeName, 'balon')) {
            $photos = [
                'https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1589487391730-58f20eb2c308?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1551958219-acbc608c6377?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=800&h=600&fit=crop',
            ];
        } elseif (str_contains($gameTypeName, 'basket') || str_contains($gameTypeName, 'basquet')) {
            $photos = [
                'https://images.unsplash.com/photo-1515734674582-29010c7f6f77?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1536063211352-0b94219f6212?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1576678927484-cc907957088c?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1578926318260-1a3a4e2c7d36?w=800&h=600&fit=crop',
            ];
        } elseif (str_contains($gameTypeName, 'tenis')) {
            $photos = [
                'https://images.unsplash.com/photo-1622279457486-62dcc4a431f7?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1586816001966-79b736744398?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1602575914567-7ba8e4cb4c3d?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1544869665-76f8a6eb1e52?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1611926012222-0aed1db21fd0?w=800&h=600&fit=crop',
            ];
        } elseif (str_contains($gameTypeName, 'nataci') || str_contains($gameTypeName, 'piscina')) {
            $photos = [
                'https://images.unsplash.com/photo-1530549387789-4c1017266635?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1571902943202-507ec2618e8f?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1551782450-17144efb9c50?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1576610616656-d3aa5d1f4534?w=800&h=600&fit=crop',
            ];
        } elseif (str_contains($gameTypeName, 'atletis') || str_contains($gameTypeName, 'carrera')) {
            $photos = [
                'https://images.unsplash.com/photo-1530533718754-001d2668365a?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1574684918287-88e3d84af8e2?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1612434444127-e2d2e6c58ac4?w=800&h=600&fit=crop',
            ];
        } elseif (str_contains($gameTypeName, 'volley')) {
            $photos = [
                'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1486286701208-1d58e9338013?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1603228254119-e6a4d095dc59?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1551958219-acbc608c6377?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=800&h=600&fit=crop',
            ];
        } else {
            // Fotos genéricas de deportes/olimpiadas
            $photos = [
                'https://images.unsplash.com/photo-1571026910584-85afdce4b7e3?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1530549387789-4c1017266635?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1515734674582-29010c7f6f77?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1530549387789-4c1017266635?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1602575914567-7ba8e4cb4c3d?w=800&h=600&fit=crop',
            ];
        }
        
        return $photos;
    }
}
