<?php
declare(strict_types=1);
namespace GrandLineCards\Shared\Domain\Bus\Event;

interface EventBus {
    public function publish(DomainEvent ...$events): void;
}
