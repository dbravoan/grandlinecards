<?php

namespace App\Services\PriceIntelligence;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\PricePoint;
use App\Models\Card;

class PriceScraperService
{
    protected $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

    /**
     * Scrape price for a specific card.
     * 
     * @param Card $card
     * @return PricePoint|null
     */
    public function updatePriceForCard(Card $card): ?PricePoint
    {
        // For now, we focus on Cardmarket as the primary source for EU (Grand Line Cards seems EU based given Spanish context)
        // We can expand to TCGPlayer later.
        
        $price = $this->scrapeCardmarket($card->card_id);
        
        if ($price) {
            return PricePoint::create([
                'card_id' => $card->id,
                'source' => 'cardmarket',
                'price' => $price,
                'currency' => 'EUR',
                'url' => null, // Would need to extract URL from search result
            ]);
        }

        return null;
    }

    protected function scrapeCardmarket(string $cardCode): ?float
    {
        // 1. Search
        $searchUrl = "https://www.cardmarket.com/en/OnePiece/Products/Search?searchString=" . urlencode($cardCode);
        
        try {
            $response = Http::withUserAgent($this->userAgent)->get($searchUrl);
            
            if (!$response->successful()) {
                return null;
            }

            $html = $response->body();
            $crawler = new Crawler($html);

            // Check if we are on a list page or detail page.
            // Detail page usually has "Price Trend" or "Available items"
            // List page has a table of cards.
            
            // Try to find "Price Trend" data.
            // Selector might need adjustment based on valid HTML.
            // Usually found in dt/dd pairs or specific classes.
            
            // Example structure on Cardmarket detail page:
            // <dt>Price Trend</dt>
            // <dd><span>12,50 €</span></dd>
            
            // Let's look for the Price Trend label and get the next sibling.
            $priceNode = $crawler->filter('dt:contains("Price Trend") + dd')->first();
            
            if ($priceNode->count() > 0) {
                $priceText = $priceNode->text();
                // Parse "12,50 €" -> 12.50
                // Remove non-numeric except comma and dot.
                // EU format: 1.234,56 € -> 1234.56
                return $this->parseEuroPrice($priceText);
            }
            
            // If we are on a search result page (multiple versions/parallels), 
            // we should pick the first one that matches the ID exactly if possible.
            // For MVP, if we don't land on detail page, we skip.
            // Enhancing this would require following the first link in the results table.
            
        } catch (\Exception $e) {
            // Log error or ignore
            return null;
        }

        return null;
    }

    protected function parseEuroPrice(string $price): float
    {
        // Remove currency symbol and spaces
        $price = preg_replace('/[^\d,.]/', '', $price);
        
        // Setup for European format: 1.234,56
        // Remove dots (thousands separator)
        $price = str_replace('.', '', $price);
        // Replace comma with dot (decimal separator)
        $price = str_replace(',', '.', $price);
        
        return (float) $price;
    }
}
