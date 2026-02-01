<?php

namespace Src\Marketplace\Shop\Domain;

use Src\Shared\Domain\AggregateRoot;

class Product extends AggregateRoot
{
    public function __construct(
        public readonly string $id,
        public string $name,
        public string $description,
        public int $priceInCents,
        public int $stock,
        public string $imageUrl,
        public string $category // 'sealed', 'single', 'accessory'
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->priceInCents / 100, // Display as float
            'stock' => $this->stock,
            'image_url' => $this->imageUrl,
            'category' => $this->category,
        ];
    }
}
