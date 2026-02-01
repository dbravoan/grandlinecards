<?php

namespace Src\Catalog\Ingestion\Infrastructure\Sources;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Src\Catalog\Ingestion\Domain\Contracts\CardSourceInterface;
use Src\Catalog\Ingestion\Domain\DTOs\RawCardData;
use Symfony\Component\DomCrawler\Crawler;

class OfficialSiteScraper implements CardSourceInterface
{
    private Client $client;
    private string $baseUrl = 'https://en.onepiece-cardgame.com/cardlist/';

    public function __construct()
    {
        $this->client = new Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            ],
            'timeout' => 30,
        ]);
    }

    public function fetchBySet(string $setCode): Collection
    {
        // The official site often lists all cards or uses filters. 
        // We will fetch the main list and filter, or use the search parameter ?series=$setCode
        // URL pattern: https://en.onepiece-cardgame.com/cardlist/?series=XXXX
        
        // Mapping simple set codes to their likely internal series ID if needed, 
        // but often the search parameter accepts the code or we might need to fetch all and filter.
        // For simplicity, we fetch the series page if we can mapping code to ID, or just search.
        // Let's assume we pass the query param.
        
        $url = $this->baseUrl . '?series=' . $this->mapSetToSeries($setCode);
        $html = $this->client->get($url)->getBody()->getContents();
        
        return $this->parseHtml($html);
    }

    public function fetchOne(string $cardId): ?RawCardData
    {
        // Difficult to fetch just one efficiently without an API.
        // We fetch the set list and find the card.
        $setCode = explode('-', $cardId)[0];
        $collection = $this->fetchBySet($setCode);
        
        return $collection->first(fn(RawCardData $card) => $card->id === $cardId);
    }

    private function parseHtml(string $html): Collection
    {
        $crawler = new Crawler($html);
        $cards = collect();

        // The site has a list of links with class 'modalOpen' that trigger the modal.
        // The data is in a corresponding DL with id matching the data-src (or href) of the link.
        
        // 1. Find all Card Triggers
        $crawler->filter('.modalOpen')->each(function (Crawler $node) use ($cards, $crawler) {
            $dataSrc = $node->attr('data-src'); // e.g., "#OP01-001" or "OP01-001" usually with hash in href, data-src sometimes without?
            // Subagent said data-src="#EB04-011".
            $cardId = ltrim($dataSrc, '#');
            
            // 2. Find the Data Container
            // The data container is a dl with id="$cardId"
            // Note: DomCrawler filterXPath might be safer for IDs with special chars, but these are standard.
            // We search the WHOLE document for this ID.
            $dataNode = $crawler->filter("#$cardId");
            
            if ($dataNode->count() === 0) {
                return;
            }

            // Extract Data
            $name = $dataNode->filter('.cardName')->text('');
            $infoCol = $dataNode->filter('.infoCol')->text(''); 
            // "EB04-011 | C | CHARACTER"
            
            $parts = array_map('trim', explode('|', $infoCol));
            $rarity = $parts[1] ?? 'Unknown';
            $type = $parts[2] ?? 'Unknown'; // This is "CHARACTER" / "LEADER" etc.
            
            // Attributes
            $color = $dataNode->filter('.color')->text('Color: Unknown');
            $color = str_replace(['Color', 'Attribute'], '', $color); // Cleanup "ColorGreen" -> "Green" if headers are inside
            // Looking at HTML: <div class="color"><h3>Color</h3>Green</div>
            // text() gets "ColorGreen". We need to strip "Color".
            
            $cost = (int) filter_var($dataNode->filter('.cost')->text('0'), FILTER_SANITIZE_NUMBER_INT);
            $power = (int) filter_var($dataNode->filter('.power')->text('0'), FILTER_SANITIZE_NUMBER_INT);
            $counter = (int) filter_var($dataNode->filter('.counter')->text('0'), FILTER_SANITIZE_NUMBER_INT);
            
            $effectText = $dataNode->filter('.text')->html(); 
            // We want text but preserving newlines or some structure? 
            // For now, let's take text() and clean it or html() and strip tags properly later.
            // Let's take text usually.
            $effectText = $dataNode->filter('.text')->text('');
            $effectText = str_replace('Effect', '', $effectText); // Remove Header
            
            $triggerText = null; // Sometimes trigger is separate or part of effect
             
            // Subtypes / Feature
            $feature = $dataNode->filter('.feature')->text('');
            $feature = str_replace('Type', '', $feature);
            $attributes = array_map('trim', explode('/', $feature));

            // Image URL
            $imgNode = $node->filter('img');
            $imageUrl = '';
            
            if ($imgNode->count()) {
                // Check data-src first (lazy loading), then src
                $imageUrl = $imgNode->attr('data-src') ?: $imgNode->attr('src');
            }
            
            if ($imageUrl) {
                if (str_starts_with($imageUrl, '..')) {
                    $imageUrl = 'https://en.onepiece-cardgame.com' . substr($imageUrl, 2);
                } elseif (str_starts_with($imageUrl, '/')) {
                    $imageUrl = 'https://en.onepiece-cardgame.com' . $imageUrl;
                }
            }
            
            $cards->push(new RawCardData(
                id: $cardId,
                setId: explode('-', $cardId)[0],
                name: $name,
                rarity: $rarity,
                color: trim($color),
                type: ucwords(strtolower(trim($type))),
                cost: $cost,
                power: $power,
                counter: $counter,
                life: null, // Leaders have life, need to parse if type is leader
                attributes: $attributes,
                effectText: trim($effectText),
                triggerText: trim($triggerText),
                imageUrl: $imageUrl
            ));
        });

        return $cards;
    }

    /**
     * Fetch available sets and their IDs from the official site.
     * Returns ['OP-01' => '569101', 'ST-01' => '569001', ...]
     */
    public function getAvailableSets(): array
    {
        // Fetch the main page to get the filter list
        $html = $this->client->get($this->baseUrl)->getBody()->getContents();
        $crawler = new Crawler($html);
        
        $sets = [];
        
        // The filter is a select with name="series"
        $crawler->filter('select[name="series"] option')->each(function (Crawler $node) use (&$sets) {
            $value = $node->attr('value');
            $text = $node->text();
            
            // Text format: "BOOSTER PACK -Romance Dawn- [OP-01]"
            // We want to extract "OP-01"
            if (preg_match('/\[([A-Z0-9]+-[0-9]+)\]/', $text, $matches)) {
                $code = $matches[1];
                $sets[$code] = $value;
            } elseif (str_contains(strtolower($text), 'promotion')) {
                $sets['P'] = $value;
            }
        });
        
        return $sets;
    }

    private function mapSetToSeries(string $code): string
    {
        // We'll fetch the available sets to find the ID.
        // In a real app, we should cache this result.
        // For now, we fetch it every time this method is called (which is once per scrape command usually).
        $sets = $this->getAvailableSets();
        
        return $sets[$code] ?? '';
    }
}
