<?php

namespace App\Services\Content;

use OpenAI\Client;
use Illuminate\Support\Facades\Log;

class AiContentService
{
    public function __construct(
        private Client $client
    ) {}

    public function generateExcerpt(string $content): string
    {
        if (empty($content)) {
            return '';
        }

        try {
            $response = $this->client->chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant for a One Piece TCG blog. Summarize the following content in a catchy, SEO-friendly excerpt (max 160 chars). Language: Spanish.'],
                    ['role' => 'user', 'content' => $content],
                ],
                'temperature' => 0.7,
            ]);

            return trim($response->choices[0]->message->content);

        } catch (\Exception $e) {
            Log::error("AI Excerpt Generation Failed: " . $e->getMessage());
            return ''; // Fail silently or handle in controller
        }
    }
}
