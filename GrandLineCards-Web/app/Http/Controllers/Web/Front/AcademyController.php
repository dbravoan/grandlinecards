<?php

namespace App\Http\Controllers\Web\Front;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class AcademyController extends Controller
{
    public function index(): Response
    {
        // Mock data for the Starter Deck recommendation
        // In the future this will come from the database
        $starterDecks = [
            [
                'id' => 'st-01',
                'name' => 'Straw Hat Crew [ST-01]',
                'description' => 'El punto de partida perfecto. Aprende el estilo de juego agresivo de Luffy y su tripulación. Mecánicas sencillas, gran poder.',
                'price' => 11.99,
                'image_url' => 'https://en.onepiece-cardgame.com/images/products/decks/st01/img_package.png',
                'color' => 'red', // For UI styling
                'features' => ['Agresivo', 'Para Principiantes', 'Mecánica Rush']
            ]
        ];

        return Inertia::render('Academy/Index', [
            'starterDecks' => $starterDecks
        ]);
    }
}
