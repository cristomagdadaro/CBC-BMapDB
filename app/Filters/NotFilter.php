<?php

namespace App\Filters;

class NotFilter extends Filter
{
    public function applyFilter(): void
    {
        $this->addFilterCondition('id', 1);
    }

    protected function getSearchableColumns(): array
    {
        return ['id'];
    }
}
