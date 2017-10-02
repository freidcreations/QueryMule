<?php

namespace QueryMule\Demo\Table;

use QueryMule\Query\Repository\Table\AbstractTable;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Class Book
 * @package QueryMule\demo\table
 */
class Book extends AbstractTable
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'book';
    }

    /**
     * @param $id
     * @return FilterInterface
     */
    public function filterByBookId($id) : FilterInterface
    {
        return $this->filter->where(function (FilterInterface $filter) use ($id) {
            $filter->where('book_id', '=?', $id);
        });
    }
}