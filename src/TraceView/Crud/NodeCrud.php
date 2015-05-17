<?php
namespace Vtk13\TraceView\Crud;

use Vtk13\LibSql\IDatabase;
use Vtk13\TraceView\Dto\Node;

class NodeCrud
{
    /**
     * @var AbstractCrud
     */
    private $crud;

    public function __construct(IDatabase $db)
    {
        $this->crud = new AbstractCrud($db, 'traceview_nodes', Node::class);
    }

    /**
     * @param $where
     * @return Node[]
     */
    public function selectList($where)
    {
        return $this->crud->selectList($where);
    }

    /**
     * @param $where
     * @return Node
     */
    public function selectOne($where)
    {
        return $this->crud->selectOne($where);
    }

    public function insert(Node $item)
    {
        return $this->crud->insert($item);
    }

    public function update(Node $item)
    {
        return $this->crud->update($item);
    }

    public function remove(Node $item)
    {
        return $this->crud->remove($item);
    }
}
