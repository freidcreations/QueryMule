<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasWhereBetween
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasWhereBetween
{
    /**
     * @param $column
     * @param $from
     * @param $to
     * @return FilterInterface
     * @throws SqlException
     */
    public function whereBetween($column, $from, $to): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->where($column, $this->query()->logical()->omitTrailingSpace()->between($from, $to));

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}