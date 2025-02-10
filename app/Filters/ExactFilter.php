<?php

namespace App\Filters;

class ExactFilter extends Filter
{
    public function applyFilter(): void
    {
        $this->addFilterCondition('id', $this->searchTerm);
    }

    protected function getSearchableColumns(): array
    {
        return ['id'];
    }
}
