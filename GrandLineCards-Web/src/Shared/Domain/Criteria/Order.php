<?php
declare(strict_types=1);
namespace GrandLineCards\Shared\Domain\Criteria;

final class Order
{
    /** @var array<int, array{orderBy: OrderBy, orderType: OrderType}> */
    private array $orders = [];

    private function __construct(OrderBy $orderBy, OrderType $orderType) {
        $this->orders[] = ['orderBy' => $orderBy, 'orderType' => $orderType];
    }

    public static function createDesc(OrderBy $orderBy): self {
        return new self($orderBy, OrderType::desc());
    }

    public static function none(): self {
        return new self(new OrderBy(''), OrderType::none());
    }

    public static function fromValues($orderBy, ? string $order = null): self {
        if (is_array($orderBy)) {
            if ($orderBy === []) return self::none();
            $first = array_shift($orderBy);
            [$f, $d] = self::normalizePair($first);
            $instance = new self(new OrderBy($f), new OrderType($d ??  OrderType::ASC));
            foreach ($orderBy as $pair) {
                [$field, $dir] = self::normalizePair($pair);
                $instance->add(new OrderBy($field), new OrderType($dir ??  $instance->orderType()->value()));
            }
            return $instance;
        }
        if ($orderBy === null) return self::none();
        return new self(new OrderBy($orderBy), new OrderType($order ?  strtolower($order) : null));
    }

    public function add(OrderBy $orderBy, OrderType $orderType): void {
        $this->orders[] = ['orderBy' => $orderBy, 'orderType' => $orderType];
    }

    public function orderBy(): OrderBy { return $this->orders[0]['orderBy']; }
    public function orderType(): OrderType { return $this->orders[0]['orderType']; }
    public function orders(): array { return $this->orders; }

    public function isNone(): bool {
        return \count(array_filter(
            $this->orders,
            static fn($o) => !$o['orderType']->isNone()
        )) === 0;
    }

    public function serialize(): string {
        return implode(',', array_map(
            static fn($o) => sprintf('%s.%s', $o['orderBy']->value(), $o['orderType']->value()),
            $this->orders
        ));
    }

    private static function normalizePair(array $pair): array {
        return [
            $pair[0] ??  throw new \InvalidArgumentException('Order field missing'),
            $pair[1] ?? OrderType::ASC
        ];
    }
}
