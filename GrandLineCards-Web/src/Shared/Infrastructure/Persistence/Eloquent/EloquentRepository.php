<?php
declare(strict_types=1);
namespace GrandLineCards\Shared\Infrastructure\Persistence\Eloquent;

use App\Models\Card as EloquentCard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Logging\QueryLogger;

abstract class EloquentRepository
{
    /** @return class-string<Model> */
    abstract protected function model(): string;

    // Methods create/update/find removed to avoid conflict with Domain Repository interfaces
    // Implement them in specific repositories using model() or newQuery()

    public function matching(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query; // Actually EloquentCriteriaConverter usage is external to this usually, or passed in.
        // Example in prompt: matching($eloquentCriteria)->get()
    }
    
    public function newQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->model()::query();
    }
}
