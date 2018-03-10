<?php
declare(strict_types=1);

namespace test\Builder\Sql\MySql;

use PHPUnit\Framework\TestCase;
use QueryMule\Builder\Sql\MySql\Filter;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Class FilterTest
 * @package test\Builder\Sql\Sqlite
 */
class FilterTest extends TestCase
{
    /**
     * @var FilterInterface
     */
    private $filter;

    public function setUp()
    {
        $this->filter = new Filter();
    }

    public function tearDown()
    {
        $this->filter = null;
    }

    public function testSelectWhere()
    {
        $query = $this->filter->where('col_a','=?','some_value')->build();
        $this->assertEquals("WHERE `col_a` =?", $query->sql());
        $this->assertEquals(['some_value'],$query->parameters());
    }

    public function testSelectWhereWithAlias()
    {
        $query = $this->filter->where('t.col_a','=?','some_value')->build();
        $this->assertEquals("WHERE `t`.`col_a` =?", $query->sql());
        $this->assertEquals(['some_value'],$query->parameters());
    }

    public function testSelectWhereAndNestedWhere()
    {
        $query = $this->filter->where('col_a','=?','some_value_a')->where(function(\QueryMule\Query\Sql\Statement\FilterInterface $query){
            $query->where('col_b','=?','some_value_b');
            $query->where('col_c','=?','some_value_c');
            $query->where(function(\QueryMule\Query\Sql\Statement\FilterInterface $query){
                $query->where('col_d','=?','some_value_d');
            });
        })->build();

        $this->assertEquals("WHERE `col_a` =? AND ( `col_b` =? AND `col_c` =? AND ( `col_d` =? ) )", $query->sql());
        $this->assertEquals(['some_value_a','some_value_b','some_value_c','some_value_d'],$query->parameters());
    }

    public function testSelectWhereAndWhere()
    {
        $query = $this->filter->where('col_a','=?','some_value_a')->where('col_b','=?','some_value_b')->build();
        $this->assertEquals("WHERE `col_a` =? AND `col_b` =?", $query->sql());
        $this->assertEquals(['some_value_a','some_value_b'],$query->parameters());
    }

    public function testSelectWhereOrWhere()
    {
        $query = $this->filter->where('col_a','=?','some_value_a')->orWhere('col_b','=?','some_value_b')->build();
        $this->assertEquals("WHERE `col_a` =? OR `col_b` =?", $query->sql());
        $this->assertEquals(['some_value_a','some_value_b'],$query->parameters());
    }

}