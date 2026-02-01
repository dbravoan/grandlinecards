<?php
declare(strict_types=1);
namespace GrandLineCards\Shared\Domain\Bus\Event;

abstract class DomainEvent
{
    // Basic DomainEvent implementation, can be expanded later
    public function __construct(
        private string $aggregateId,
        private string $eventId = '',
        private string $occurredOn = ''
    ) {}

    abstract public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): self;

    abstract public static function eventName(): string;

    abstract public function toPrimitives(): array;

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function occurredOn(): string
    {
        return $this->occurredOn;
    }
}
