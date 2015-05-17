<?php
namespace Vtk13\TraceView\Crud;

use Vtk13\LibSql\IDatabase;
use Vtk13\TraceView\Utils;

class AbstractCrud
{
    /**
     * @var IDatabase
     */
    private $db;

    private $table;
    private $class;

    public function __construct(IDatabase $db, $table, $class)
    {
        $this->db = $db;
        $this->table = $table;
        $this->class = $class;
    }

    public function selectList($where)
    {
        return Utils::map(
            $this->db->select("SELECT * FROM {$this->table} WHERE {$where}"),
            $this->class
        );
    }

    public function selectOne($where)
    {
        return Utils::mapOne(
            $this->db->selectRow("SELECT * FROM {$this->table} WHERE {$where}"),
            $this->class
        );
    }

    /**
     * @param $item
     * @return bool
     */
    public function insert($item)
    {
        // TODO: Implement insert() method.
    }

    /**
     * @param $item
     * @return bool
     */
    public function update($item)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param $item
     * @return bool
     */
    public function remove($item)
    {
        // TODO: Implement remote() method.
    }
}
