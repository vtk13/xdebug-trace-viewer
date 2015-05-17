<?php
namespace Vtk13\TraceView\Controllers;

use Vtk13\Mvc\Handlers\AbstractController;
use Vtk13\Mvc\Http\RedirectResponse;
use Vtk13\TraceView\Crud\NodeCrud;
use Vtk13\TraceView\Crud\ValueCrud;
use Vtk13\TraceView\Registry;

class DbTraceController extends AbstractController
{
    /**
     * @var NodeCrud
     */
    private $nodeCrud;

    /**
     * @var ValueCrud
     */
    private $valueCrud;

    public function __construct()
    {
        parent::__construct('db-trace');
        $this->nodeCrud = new NodeCrud(Registry::getInstance()->getDb());
        $this->valueCrud = new ValueCrud(Registry::getInstance()->getDb());
    }

    public function deletePOST($trace)
    {
        $db = Registry::getInstance()->getDb();
        $db->delete('traceview_values', 'trace_id=' . (int)$trace);
        $db->delete('traceview_nodes' , 'trace_id=' . (int)$trace);
        $db->delete('traceview_traces', 'id=' . (int)$trace);
        return new RedirectResponse('/');
    }

    public function viewGET($traceId)
    {
        $traceId = (int)$traceId;

        $main = $this->nodeCrud->selectOne("trace_id={$traceId} AND level=1");
        $nodes = $this->nodeCrud->selectList("trace_id={$traceId} AND level=2");
        foreach ($nodes as $node) {
            $node->parameters = $this->valueCrud->selectList("call_id={$node->call_id}");
        }

        return [
            'traceId'           => $traceId,
            'term_function'     => '',
            'term_parameter'    => '',
            'term_filename'     => '',
            'main'              => $main,
            'nodes'             => $nodes,
        ];
    }
}
