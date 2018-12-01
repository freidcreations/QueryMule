<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhereNotIn
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasOrWhereNotIn
{
    use Common;

    /**
     * @param $column
     * @param array $values
     * @return $this
     */
    public function orWhereNotIn($column, array $values = [])
    {
        if($this instanceof FilterInterface) {
            $this->orWhereNot($column, null, $this->logical()->omitTrailingSpace()->in($values));
        }

        return $this;
    }
}