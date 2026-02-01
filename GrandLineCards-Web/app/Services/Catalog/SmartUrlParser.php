<?php

namespace App\Services\Catalog;

use App\Models\Expansion;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class SmartUrlParser
{
    // Dictionaries (Slug => Value)
    // We support both Spanish and English slugs
    
    // Colors
    private const COLORS = [
        'rojo' => 'Red',
        'red' => 'Red',
        'verde' => 'Green',
        'green' => 'Green',
        'azul' => 'Blue',
        'blue' => 'Blue',
        'morado' => 'Purple',
        'purple' => 'Purple',
        'negro' => 'Black',
        'black' => 'Black',
        'amarillo' => 'Yellow',
        'yellow' => 'Yellow',
        'multicolor' => 'Multicolor', // Special handling maybe?
    ];

    // Rarities
    private const RARITIES = [
        'lider' => 'L',
        'leader' => 'L',
        'comun' => 'C',
        'common' => 'C',
        'infrecuente' => 'UC',
        'uncommon' => 'UC',
        'rara' => 'R',
        'rare' => 'R',
        'super-rara' => 'SR',
        'super-rare' => 'SR',
        'secreta' => 'SEC',
        'secret' => 'SEC',
        'promo' => 'P',
        'special' => 'SP',
        'treasure' => 'TR',
    ];

    // Types
    private const TYPES = [
        'lider' => 'Leader', // Ambiguous with Rarity? Context matters or check priorities
        'personaje' => 'Character',
        'character' => 'Character',
        'evento' => 'Event',
        'event' => 'Event',
        'escenario' => 'Stage',
        'stage' => 'Stage',
    ];
    
    public function parse(string $slug): array
    {
        $filters = [
            'color' => [],
            'rarity' => [],
            'type' => [],
            'expansion' => [],
            'cost' => null,
            'q' => null
        ];

        // 0. Cleanup
        // Replace double slashes, trim
        $slug = trim($slug, '/');
        $segments = explode('/', $slug);

        // 1. Get Expansions Mapping
        // We cache this to avoid DB hits on every request
        $expansions = Cache::remember('expansions_slug_map', 3600, function () {
            // Map 'op01', 'op-01', 'romance-dawn' -> 'OP01'
            $map = [];
            $all = Expansion::all();
            foreach ($all as $exp) {
                // Code: OP01 -> op01
                $map[Str::slug($exp->code)] = $exp->code;
                // Name: Romance Dawn -> romance-dawn
                $map[Str::slug($exp->name)] = $exp->code;
            }
            return $map;
        });

        // 2. Iterate Segments
        foreach ($segments as $segment) {
            $normalized = Str::slug($segment); // "Coste 4" -> "coste-4"
            
            // A. Check Sets
            if (isset($expansions[$normalized])) {
                $filters['expansion'][] = $expansions[$normalized];
                continue;
            }
            // A2. Check Sets flexible (e.g. 'op-01')
            $compact = str_replace('-', '', $normalized);
            if (isset($expansions[$compact])) {
                $filters['expansion'][] = $expansions[$compact];
                continue;
            }

            // B. Check Colors
            if (isset(self::COLORS[$normalized])) {
                $filters['color'][] = self::COLORS[$normalized];
                continue;
            }

            // C. Check Rarities
            if (isset(self::RARITIES[$normalized])) {
                // If "Lider" matches both Rarity and Type, context?
                // Usually Leader is both. We add to both?
                $filters['rarity'][] = self::RARITIES[$normalized];
                
                // Edge case: 'lider' is both Type and Rarity usually.
                if ($normalized === 'lider' || $normalized === 'leader') {
                     $filters['type'][] = 'Leader';
                }
                continue;
            }

            // D. Check Types
            if (isset(self::TYPES[$normalized])) {
                $filters['type'][] = self::TYPES[$normalized];
                continue;
            }

            // E. Check Cost
            // Patterns: "cost-4", "coste-4", "4-cost", "c-4"
            if (preg_match('/^(?:cost|coste)[-]?(\d+)$/', $normalized, $matches)) {
                $filters['cost'] = (int)$matches[1];
                continue;
            }
            // Just number? Risky. "OP01" has numbers. "4" could be cost.
            // Let's support strict "cost-X" for now.

            // F. Search Query (Remainder? Or specific segment?)
            // If segment didn't match anything, maybe it's a search term?
            // "luffy"
            // Let's assume unknown segments are search terms.
            if ($filters['q']) {
                $filters['q'] .= ' ' . str_replace('-', ' ', $segment);
            } else {
                $filters['q'] = str_replace('-', ' ', $segment);
            }
        }

        return $filters;
    }
    
    /**
     * Generate metadata based on parsed filters
     */
    public function generateMetadata(array $filters): array
    {
        $titleParts = [];
        
        // Expansion
        if (!empty($filters['expansion'])) {
            $expansionNames = Expansion::whereIn('code', $filters['expansion'])->pluck('name')->toArray();
            $titleParts[] = implode(' / ', $expansionNames);
        } else {
            $titleParts[] = 'Cartas One Piece TCG';
        }

        // Color
        if (!empty($filters['color'])) {
            // Translate back to ES
            $esColors = array_map(fn($c) => match($c) {
                'Red' => 'Rojos', 'Green' => 'Verdes', 'Blue' => 'Azules', 
                'Purple' => 'Morados', 'Black' => 'Negros', 'Yellow' => 'Amarillos',
                default => $c
            }, $filters['color']);
            $titleParts[] = implode(' y ', $esColors);
        }

        // Type
        if (!empty($filters['type'])) {
             $esTypes = array_map(fn($t) => match($t) {
                'Leader' => 'Líderes', 'Character' => 'Personajes', 
                'Event' => 'Eventos', 'Stage' => 'Escenarios',
                default => $t
            }, $filters['type']);
             $titleParts[] = implode(' / ', $esTypes);
        }
        
        // Rarity
        if (!empty($filters['rarity'])) {
             $titleParts[] = 'Rareza ' . implode(',', $filters['rarity']);
        }

        // Cost
        if ($filters['cost']) {
            $titleParts[] = 'Coste ' . $filters['cost'];
        }

        // Query
        if ($filters['q']) {
            $titleParts[] = '"' . $filters['q'] . '"';
        }

        $title = implode(' - ', $titleParts) . ' | Grand Line Cards';
        $h1 = implode(' ', $titleParts);
        
        return [
            'meta_title' => $title,
            'meta_description' => "Explora la colección de $h1. Filtra por color, rareza, colección y más en Grand Line Cards.",
            'h1' => $h1
        ];
    }
}
