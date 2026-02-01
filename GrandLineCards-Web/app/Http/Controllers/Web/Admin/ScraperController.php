<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Artisan;
use Src\Catalog\Ingestion\Infrastructure\Sources\OfficialSiteScraper;

class ScraperController extends Controller
{
    public function index(OfficialSiteScraper $scraper)
    {
        // Fetch available sets to show in dropdown
        // This might ideally be cached, but for admin it's fine
        $sets = $scraper->getAvailableSets();
        
        // Transform to array compatible with select options if needed, or just pass map
        // Vue expecting: { 'OP-01': '569...', ... }
        
        // Let's format for cleaner UI: [ {value: 'OP-01', label: 'OP-01'} ]
        $options = [];
        foreach ($sets as $code => $id) {
            $options[] = ['value' => $code, 'label' => $code];
        }

        return Inertia::render('Admin/Scraper', [
            'sets' => $options
        ]);
    }

    public function run(Request $request)
    {
        $request->validate([
            'set' => 'required|string',
        ]);

        $set = $request->input('set');

        // Note: Running scraping synchronously in a web request is risky for timeouts.
        // For this implementation, we assume it fits or the user knows to wait.
        // In production, dispatch a Job: IngestSetJob::dispatch($set);
        
        try {
            Artisan::call('cards:import', [
                '--source' => 'official',
                '--set' => $set,
                '--force' => true
            ]);
            
            $output = Artisan::output();
            
            return back()->with('success', "Scraping completed for {$set}. Log: " . substr($output, 0, 200) . "...");
        } catch (\Exception $e) {
            return back()->with('error', 'Scraping failed: ' . $e->getMessage());
        }
    }
}
