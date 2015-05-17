<?php
namespace Vtk13\TraceView\Crud;

use Vtk13\LibSql\IDatabase;
use Vtk13\TraceView\Dto\Value;

class ValueCrud
{
    /**
     * @var AbstractCrud
     */
    private $crud;

    public function __construct(IDatabase $db)
    {
        $this->crud = new AbstractCrud($db, 'traceview_values', Value::class);
    }

    /**
     * @param $where
     * @return Value[]
     */
    public function selectList($where)
    {
        $res = [];
        /* @var $value Value */
        foreach ($this->crud->selectList($where) as $value) {
            $res[$value->order_id] = $value;
        }
        return $res;
    }

    /**
     * @param $where
     * @return Value
     */
    public function selectOne($where)
    {
        return $this->crud->selectOne($where);
    }

    public function insert(Value $item)
    {
        return $this->crud->insert($item);
    }

    public function update(Value $item)
    {
        return $this->crud->update($item);
    }

    public function remove(Value $item)
    {
        return $this->crud->remove($item);
    }
}
