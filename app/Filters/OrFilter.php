<?php

namespace App\Filters;

class OrFilter extends Filter
{
    public function applyFilter(): void
    {
        $this->addFilterCondition('id', $this->searchTerm);
        $this->addFilterCondition('user_id', 4); // Example of additional condition
    }

    protected function getSearchableColumns(): array
    {
        return ['id'];
    }
}
