<?php
namespace Vtk13\TraceView;

use Vtk13\LibSql\IDatabase;

class Registry
{
    /**
     * @var IDatabase
     */
    protected $db;

    /**
     * @return Registry
     */
    public static function getInstance()
    {
        static $registry;
        if ($registry === null) {
            $registry = new self();
        }
        return $registry;
    }

    public function setDb(IDatabase $db)
    {
        $this->db = $db;
    }

    /**
     * @return IDatabase
     */
    public function getDb()
    {
        return $this->db;
    }
}
