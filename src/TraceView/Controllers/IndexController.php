<?php
namespace Vtk13\TraceView\Controllers;

use Vtk13\LibXdebugTrace\Trace\ITraceList;
use Vtk13\Mvc\Handlers\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @var ITraceList
     */
    protected $tl;

    public function __construct()
    {
        parent::__construct('index');
    }

    public function indexGET()
    {

    }
}
