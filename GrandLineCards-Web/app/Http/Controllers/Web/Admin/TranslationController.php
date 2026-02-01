<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class TranslationController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Translations');
    }
}
