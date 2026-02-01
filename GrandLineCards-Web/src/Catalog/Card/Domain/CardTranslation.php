<?php
declare(strict_types=1);
namespace GrandLineCards\Catalog\Card\Domain;

final class CardTranslation
{
    public function __construct(
        private Locale $locale,
        private CardName $name,
        private ?CardEffect $effect,
        private ?CardTrigger $trigger
    ) {}

    public function locale(): Locale { return $this->locale; }
    public function name(): CardName { return $this->name; }
    public function effect(): ?CardEffect { return $this->effect; }
    public function trigger(): ?CardTrigger { return $this->trigger; }

    public function toPrimitives(): array
    {
        return [
            'locale' => $this->locale->value(),
            'name' => $this->name->value(),
            'effect_text' => $this->effect?->value(),
            'trigger_text' => $this->trigger?->value(),
        ];
    }
}
