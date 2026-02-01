<?php

namespace Src\Catalog\Application;

use App\Models\CardPrice;

class UpdateInventoryAction
{
    public function execute(int $cardId, float $price, int $stock): CardPrice
    {
        return CardPrice::updateOrCreate(
            ['card_id' => $cardId],
            [
                'price_eur' => $price,
                'stock' => $stock
            ]
        );
    }
}
