<?php

namespace App\Http\Controllers\Web\Front;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class RulesController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Rules/Index');
    }
}
