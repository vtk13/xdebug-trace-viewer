<?php
namespace Vtk13\TraceView\Controllers;

use Vtk13\Mvc\Handlers\AbstractController;
use Vtk13\Mvc\Http\RedirectResponse;
use Vtk13\TraceView\Registry;

class DbTraceController extends AbstractController
{
    public function __construct()
    {
        parent::__construct('db-trace');
    }

    public function deletePOST($trace)
    {
        $db = Registry::getInstance()->getDb();
        $db->delete('traceview_values', 'trace_id=' . (int)$trace);
        $db->delete('traceview_nodes' , 'trace_id=' . (int)$trace);
        $db->delete('traceview_traces', 'id=' . (int)$trace);
        return new RedirectResponse('/');
    }
}
