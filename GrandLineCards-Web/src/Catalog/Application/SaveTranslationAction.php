<?php

namespace Src\Catalog\Application;

use App\Models\CardTranslation;

class SaveTranslationAction
{
    public function execute(int $cardId, string $locale, array $data): CardTranslation
    {
        return CardTranslation::updateOrCreate(
            [
                'card_id' => $cardId,
                'locale' => $locale
            ],
            array_merge($data, ['is_verified' => true])
        );
    }
}
