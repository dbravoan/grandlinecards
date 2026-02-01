<?php

namespace App\Http\Controllers\Web\Front;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function index(): Response
    {
        $events = [
            [
                'id' => 1,
                'title' => 'Store Championship Vol. 1',
                'date' => '2026-03-15',
                'location' => 'Grand Line Shop, Madrid',
                'type' => 'Competitivo',
                'description' => 'Compite por la carta promo exclusiva de Winner.',
                'image' => '/images/events/store-champ.jpg'
            ],
            [
                'id' => 2,
                'title' => 'Treasure Cup',
                'date' => '2026-04-05',
                'location' => 'Barcelona Exhibition Center',
                'type' => 'Major',
                'description' => 'Torneo mayor con premios en efectivo y cartas de arte alternativo.',
                'image' => '/images/events/treasure-cup.jpg'
            ],
            [
                'id' => 3,
                'title' => 'Prerelease: Memorial Collection',
                'date' => '2026-02-28',
                'location' => 'Todas las tiendas oficiales',
                'type' => 'Casual',
                'description' => 'Sé el primero en jugar con las nuevas cartas del Extra Booster.',
                'image' => '/images/events/prerelease.jpg'
            ],
            [
                'id' => 4,
                'title' => 'Liga Pirata Semanal',
                'date' => 'Cada Viernes',
                'location' => 'Grand Line Shop',
                'type' => 'Local',
                'description' => 'Torneo semanal para probar mazos y ganar sobres.',
                'image' => '/images/events/weekly.jpg'
            ],
            [
                'id' => 5,
                'title' => 'Flagship Battle Main Event',
                'date' => '2026-06-20',
                'location' => 'Online Discord',
                'type' => 'Online',
                'description' => 'El evento más grande del año. Clasificatorio mundial.',
                'image' => '/images/events/flagship.jpg'
            ],
        ];

        return Inertia::render('Events/Index', [
            'events' => $events
        ]);
    }
}
