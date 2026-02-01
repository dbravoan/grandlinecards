<?php

namespace App\Http\Controllers\Web\Front;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

use App\Services\Meta\MetaService;

class MetaController extends Controller
{
    public function __construct(
        private MetaService $metaService
    ) {}

    public function index(): Response
    {
        return Inertia::render('Meta/Index', [
            'topLeaders' => $this->metaService->getTopLeaders(5),
            'trendingCards' => $this->metaService->getTrendingCards(10),
        ]);
    }
}
