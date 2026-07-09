<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasActiveFlag
{
    public static function bootHasActiveFlag(): void
    {
        static::addGlobalScope('active', function (Builder $query) {
            $query->where($query->getModel()->getActiveColumn(), true);
        });
    }

    public function getActiveColumn(): string
    {
        return $this->activeColumn;
    }

    public function delete(): bool
    {
        if (! $this->exists) {
            return false;
        }

        if ($this->fireModelEvent('deleting') === false) {
            return false;
        }

        $this->{$this->getActiveColumn()} = false;
        $saved = $this->save();

        $this->fireModelEvent('deleted', false);

        return $saved;
    }

    public function restore(): bool
    {
        $this->{$this->getActiveColumn()} = true;

        return $this->save();
    }

    public function scopeWithInactive(Builder $query): Builder
    {
        return $query->withoutGlobalScope('active');
    }

    public function scopeOnlyInactive(Builder $query): Builder
    {
        return $query->withoutGlobalScope('active')
            ->where($query->getModel()->getActiveColumn(), false);
    }
}