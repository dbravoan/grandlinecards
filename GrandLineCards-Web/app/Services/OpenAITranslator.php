<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAITranslator
{
    private string $apiKey;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('services.openai.key', env('OPENAI_API_KEY'));
        $this->model = config('services.openai.model', 'gpt-4o');
    }

    public function translate(string $text, array $context = []): ?string
    {
        if (empty($text)) {
            return null;
        }

        if (empty($this->apiKey)) {
            Log::warning('OpenAI API Key not found.');
            return '[MOCK AI] ' . $text; // Fallback for dev without key
        }

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $this->getSystemPrompt($context)
                        ],
                        [
                            'role' => 'user',
                            'content' => $text
                        ]
                    ],
                    'temperature' => 0.3, // Lower temp for technical accuracy
                ]);

            if ($response->successful()) {
                return trim($response->json('choices.0.message.content'));
            }

            Log::error('OpenAI API Error: ' . $response->body());
            return '[ERROR] ' . $text;

        } catch (\Exception $e) {
            Log::error('OpenAI Exception: ' . $e->getMessage());
            return '[EXCEPTION] ' . $text;
        }
    }

    private function getSystemPrompt(array $context): string
    {
        $glossary = collect(config('tcg-glossary.keywords', []))
            ->map(fn($es, $en) => "- **$en** -> $es")
            ->join("\n");
            
        return <<<EOT
Eres un traductor experto en el Juego de Cartas Coleccionables de One Piece (One Piece TCG).
Tu misión es traducir el siguiente efecto de carta del inglés al español (España).

REGLAS DE ORO:
1. Usa lenguaje técnico y preciso. Mantén la estructura de las frases de efecto.
2. Respeta ESTRICTAMENTE el siguiente glosario de términos clave:
{$glossary}

3. No traduzcas nombres propios de ataques o personajes si usualmente se mantienen en inglés, a menos que sea obvio (ej: "Straw Hat Crew" -> "Piratas de Sombrero de Paja").
4. El formato de salida debe ser solo el texto traducido, sin explicaciones ni comillas.
EOT;
    }
}
