<?php
declare(strict_types=1);
namespace GrandLineCards\Shared\Infrastructure\Persistence\Eloquent;

use GrandLineCards\Shared\Domain\Criteria\Criteria;
use GrandLineCards\Shared\Domain\Criteria\Filter;
use GrandLineCards\Shared\Domain\Criteria\FilterGroup;
use GrandLineCards\Shared\Domain\Criteria\FilterOperator;
use Illuminate\Database\Query\Builder;

final class EloquentCriteriaConverter
{
    public function __construct(
        private readonly Criteria $criteria,
        private readonly array $hydrator
    ) {}

    public function convert(Builder|\Illuminate\Database\Eloquent\Builder $query): Builder|\Illuminate\Database\Eloquent\Builder
    {
        if ($this->criteria->hasFilters()) {
            foreach ($this->criteria->filters()->filters() as $filter) {
                if ($filter instanceof FilterGroup) {
                    $this->applyGroup($query, $filter);
                } else {
                    $this->apply($query, $filter);
                }
            }
        }

        if ($this->criteria->hasOrder()) {
            foreach ($this->criteria->order()->orders() as $order) {
                $query->orderBy(
                    $this->mapField($order['orderBy']->value()),
                    $order['orderType']->value()
                );
            }
        }

        if ($this->criteria->offset() !== null) {
            $query->skip($this->criteria->offset());
        }

        if ($this->criteria->limit() !== null) {
            $query->take($this->criteria->limit());
        }

        return $query;
    }

    private function apply(Builder|\Illuminate\Database\Eloquent\Builder $query, Filter $filter): void
    {
        $field = $this->mapField($filter->field()->value());
        $value = $filter->value()->value();
        $operator = $filter->operator()->value();

        if ($operator === FilterOperator::CONTAINS) {
            $query->where($field, 'like', "%{$value}%");
        } elseif ($operator === FilterOperator::NOT_CONTAINS) {
            $query->where($field, 'not like', "%{$value}%");
        } elseif ($operator === FilterOperator::STARTS_WITH) {
            $query->where($field, 'like', "{$value}%");
        } elseif ($operator === FilterOperator::ENDS_WITH) {
            $query->where($field, 'like', "%{$value}");
        } elseif ($operator === FilterOperator::IN) {
             if (is_array($value)) {
                 $query->whereIn($field, $value);
             } else {
                 $query->whereIn($field, explode(',', (string) $value));
             }
        } else {
            $query->where($field, $operator, $value);
        }
    }

    private function applyGroup(Builder|\Illuminate\Database\Eloquent\Builder $query, FilterGroup $group): void
    {
        $query->where(function ($q) use ($group) {
            foreach ($group->filters() as $filter) {
                if ($group->glue() === 'or') {
                     $q->orWhere(function($subQ) use ($filter) {
                        $this->apply($subQ, $filter);
                     });
                } else {
                    $this->apply($q, $filter);
                }
            }
        });
    }

    private function mapField(string $field): string
    {
        return $this->hydrator[$field] ?? $field;
    }
}
