<?php

namespace Src\Catalog\Ingestion\Infrastructure\Services;

use OpenAI\Client;

class OpenAITranslator
{
    public function __construct(
        private Client $client
    ) {}

    public function translate(string $cardName, string $effectText): array
    {
        if (empty($effectText)) {
            return [
                'name_es' => $cardName, // Names usually kept or simple? User asked for translation? Name might need lookup.
                // Re-reading requirements: "Nombre original" (usually English/Japanese). 
                // "card_translations: name, effect_text".
                // We should translate name too if it makes sense, but mostly Effect.
                'effect_es' => null,
                'keywords' => []
            ];
        }

        $prompt = <<<EOT
You are an expert translator for the One Piece Trading Card Game. 
Translate the following card effect text from English to Spanish (Spain).
Use official terminology found in the Spanish community:
- "Rush" -> "Prisa"
- "Blocker" -> "Bloqueador"
- "Banish" -> "Destierro" (or consistent community term)
- "On Play" -> "Al Jugar"
- "Activate: Main" -> "Activar: Principal"
- "Rest" -> "Girar" / "Descansar" (Use 'Girar' for action, 'Descansada' for state if appropriate)
- "Active" -> "Activa"
- "DON!!" -> "DON!!"

Input Text: "$effectText"

Return ONLY a JSON object with this structure:
{
    "effect_es": "translated text...",
    "keywords": ["Rush", "Blocker", ...] (List of English keywords present in the text)
}
EOT;

        $response = $this->client->chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a TCG localization expert.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'response_format' => ['type' => 'json_object'],
        ]);

        $content = $response->choices[0]->message->content;
        return json_decode($content, true);
    }
}
