<?php

namespace App\Services\Translation;

class AutoTranslator
{
    private const KEYWORDS = [
        '/\[Rush\]/i' => '[Prisa]',
        '/\[Blocker\]/i' => '[Bloqueador]',
        '/\[Banish\]/i' => '[Desterrar]',
        '/\[Double Attack\]/i' => '[Doble Ataque]',
        '/\[Critical\]/i' => '[Crítico]',
        '/\[On Play\]/i' => '[Al Jugar]',
        '/\[When Attacking\]/i' => '[Al Atacar]',
        '/\[On Block\]/i' => '[Al Bloquear]',
        '/\[On K.O.\]/i' => '[Al Morir]', // Or "Al K.O."
        '/\[Your Turn\]/i' => '[Tu Turno]',
        '/\[Opponent\'s Turn\]/i' => '[Turno del Oponente]',
        '/\[Activate: Main\]/i' => '[Activar: Principal]',
        '/\[Main\]/i' => '[Principal]',
        '/\[Counter\]/i' => '[Counter]',
        '/\[Trigger\]/i' => '[Trigger]', // Often kept as Trigger, or "Disparador"
        '/\[Once Per Turn\]/i' => '[Una Vez Por Turno]',
        '/\[Don!! x(\d+)\]/i' => '[Don!! x$1]',
        '/\[Don!! -(\d+)\]/i' => '[Don!! -$1]',
    ];

    private const TERMS = [
        '/Life cards/' => 'cartas de Vida',
        '/Life area/' => 'área de Vida',
        '/your Leader/' => 'tu Líder',
        '/this Character/' => 'este Personaje',
        '/this Event/' => 'este Evento',
        '/this Stage/' => 'este Escenario',
        '/cost area/' => 'área de coste',
        '/your hand/' => 'tu mano',
        '/your deck/' => 'tu mazo',
        '/trash/' => 'papelera',
        '/power/' => 'poder',
        '/during this turn/' => 'durante este turno',
        '/during the battle/' => 'durante la batalla',
        '/Add up to/' => 'Añade hasta',
        '/Set up to/' => 'Pon hasta',
        '/Return up to/' => 'Devuelve hasta',
        '/Rest up to/' => 'Descansa hasta', // Or "Gira"
        '/K.O. up to/' => 'K.O. hasta',
        '/active/' => 'activo',
        '/rested/' => 'descansado', // Or "girado"
        '/original power/' => 'poder original',
        '/attribute/' => 'atributo',
        '/type/' => 'tipo',
        '/Look at/' => 'Mira',
        '/the top/' => 'el tope',
        '/cards from the top/' => 'cartas del tope',
        '/Then/' => 'Luego',
        '/If/' => 'Si',
        '/you have/' => 'tienes',
        '/your opponent has/' => 'tu oponente tiene',
        '/cards in their hand/' => 'cartas en su mano',
        '/cannot attack/' => 'no puede atacar',
        '/cannot be K.O.\'d/' => 'no puede ser K.O.',
    ];
    
    // Names and Attributes could be in a DB table for better management,
    // but a static list covers 90% of common cases.
    private const ATTRIBUTES = [
        'Strike' => 'Golpe',
        'Slash' => 'Corte',
        'Ranged' => 'Disparo',
        'Special' => 'Especial',
        'Wisdom' => 'Sabiduría',
    ];

    private const TYPES = [
        'Supernovas' => 'Supernovas',
        'Straw Hat Crew' => 'Sombrero de Paja',
        'Heart Pirates' => 'Piratas Heart',
        'Kid Pirates' => 'Piratas Kid',
        'Navy' => 'Marina',
        'Whitebeard Pirates' => 'Piratas de Barbablanca',
        'Animal Kingdom Pirates' => 'Piratas Bestias',
        'Big Mom Pirates' => 'Piratas de Big Mom',
        'Seven Warlords of the Sea' => 'Siete Señores de la Guerra',
        'Revolutionary Army' => 'Ejército Revolucionario',
        'Film' => 'Film',
        'Minks' => 'Minks',
        'Wano Country' => 'País de Wano',
        'Four Emperors' => 'Cuatro Emperadores',
    ];

    public function translateEffect(string $text): string
    {
        // 1. Keywords
        foreach (self::KEYWORDS as $pattern => $replacement) {
            $text = preg_replace($pattern, $replacement, $text);
        }

        // 2. Common Terms
        foreach (self::TERMS as $pattern => $replacement) {
            // Case insensitive for terms? Maybe.
            $text = preg_replace($pattern . 'i', $replacement, $text);
        }

        return $text;
    }

    public function translateName(string $name): string
    {
        // Names are mostly kept in English for Characters (Luffy is Luffy)
        // But some generic names or specific titles might change.
        // E.g. "Gum-Gum Pistol" -> "Gomu Gomu no Pistol" or "Pistola de Goma"?
        // Usually Spanish/Latam keeps attacks in English or Jap/Spanish mix.
        // For now, keep names as is, unless we have a specific dictionary.
        return $name;
    }

    public function translateType(string $type): string
    {
        // Types are often slash separated "Pirate / Straw Hat Crew"
        $parts = explode('/', $type);
        $translated = array_map(function($part) {
            $part = trim($part);
            return self::TYPES[$part] ?? $part;
        }, $parts);
        
        return implode(' / ', $translated);
    }
}
