<?php

namespace Src\Catalog\Ingestion\Infrastructure\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; // Assuming GD driver
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class InterventionImageProcessor
{
    public function __construct()
    {
        // $this->manager = new ImageManager(new Driver());
    }

    public function process(string $url, string $cardId): string
    {
        // Extract Set ID (e.g. OP01 from OP01-001)
        $parts = explode('-', $cardId);
        $setId = $parts[0] ?? 'MISC';

        // Check if file already exists (likely downloaded by Python scraper)
        // We check common extensions
        $extensions = ['png', 'jpg', 'jpeg', 'webp'];
        foreach ($extensions as $ext) {
            $candidate = "cards/{$setId}/{$cardId}.{$ext}";
            if (Storage::disk('public')->exists($candidate)) {
                return $candidate;
            }
        }

        // 1. Download
        $response = Http::get($url);
        if ($response->failed()) {
            throw new \Exception("Failed to download image: $url");
        }
        $imageContent = $response->body();

        // 2. Storage
        
        // Determine extension from URL
        $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
        if (!$extension) {
            $extension = 'png'; // Default
        }
        
        $path = "cards/{$setId}/{$cardId}.{$extension}";
        
        // Ensure directory exists
        if (!Storage::disk('public')->exists("cards/{$setId}")) {
            Storage::disk('public')->makeDirectory("cards/{$setId}");
        }
        
        Storage::disk('public')->put($path, $imageContent);

        return $path;
    }
}
