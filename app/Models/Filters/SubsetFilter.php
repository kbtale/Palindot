<?php

namespace App\Models\Filters;

use EloquentFilter\ModelFilter;

class SubsetFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    /**
     * Searches for the first match.
     *
     * @param      string         $search  The search
     *
     * @return     SubsetFilter  The product filter.
     */
    public function search($search): SubsetFilter
    {
        return $this->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('sku', 'LIKE', '%' . $search . '%');
    }

    /**
     * Filtter by user
     *
     * @param      string       $category  The category
     *
     * @return     SubsetFilter  The product filter.
     */
    public function user($user): SubsetFilter
    {
        return $this->where('user_id', $user);
    }
}