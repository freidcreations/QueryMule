<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;



use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasCols
 * @package QueryMule\Query\Common\Sql
 */
trait HasCols
{
    /**
     * @var int
     */
    private $columnIndex = 0;

    /**
     * @var array
     */
    private $columnKeys = [];

    /**
     * @param array $cols
     * @param string|null $alias
     * @return SelectInterface
     * @throws SqlException
     */
    public function cols(array $cols = [Sql::SQL_STAR], ?string $alias = null): SelectInterface
    {
        if($this instanceof SelectInterface) {
            $this->columnKeys = array_merge($this->columnKeys, array_keys($cols));

            $values = array_values($cols);

            $query = $this->query();
            $sql = array_reduce($values, function (Sql $sql, $col) use ($query, $alias) {
                if ($this->columnIndex !== 0) {
                    $sql->append(',', [], false);
                }

                $sql->ifThenAppend(!is_null($alias), $query->accent()->append($alias) . '.', [], false)
                    ->append($col !== Sql::SQL_STAR ? $query->accent()->append($col) : $col)
                    ->ifThenAppend(isset($this->columnKeys[$this->columnIndex]) && is_string($this->columnKeys[$this->columnIndex]), Sql::AS)
                    ->ifThenAppend(isset($this->columnKeys[$this->columnIndex]) && is_string($this->columnKeys[$this->columnIndex]), $this->columnKeys[$this->columnIndex]);

                $this->columnIndex++;

                return $sql;
            }, $this->query()->sql());

            $this->query()->append(Sql::COLS, $sql);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}