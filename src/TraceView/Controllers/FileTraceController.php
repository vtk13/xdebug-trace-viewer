<?php
namespace Vtk13\TraceView\Controllers;

use Exception;
use Vtk13\LibXdebugTrace\FileUtil\TraceOutputDir;
use Vtk13\LibXdebugTrace\Trace\ITraceList;
use Vtk13\LibXdebugTrace\Trace\Line;
use Vtk13\LibXdebugTrace\Trace\Node;
use Vtk13\Mvc\Handlers\AbstractController;
use Vtk13\Mvc\Http\JsonResponse;
use Vtk13\Mvc\Http\RedirectResponse;
use Vtk13\TraceView\Registry;

define('SEARCH_RESULTS_LIMIT', isset($_GET['limit']) ? $_GET['limit'] : 200);

class FileTraceController extends AbstractController
{
    /**
     * @var ITraceList
     */
    protected $tl;

    public function __construct()
    {
        parent::__construct('file-trace');
        $this->tl = new TraceOutputDir();
    }

    public function indexGET()
    {

    }

    public function viewGET($trace, $file = null)
    {
        return [
            'trace' => $trace,
            'file'  => $file,
        ];
    }

    public function callsGET()
    {
        $file = isset($_GET['file']) ? $_GET['file'] : null;
        $line = isset($_GET['line']) ? $_GET['line'] : null;

        $nodes = array();

        $trace = $this->tl->getTrace($_GET['trace']);
        $trace->traverse(function(Node $node) use ($file, $line, &$nodes) {
            if ($node->file == $file && $node->line == $line) {
                $nodes[] = $node;
            }
        });

        return array(
            'trace'     => $_GET['trace'],
            'traceFile' => $file,
            'traceLine' => $line,
            'nodes'     => $nodes,
        );
    }

    public function callGET()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        $found = null;
        $underlyingNodes = array();
        $trace = $this->tl->getTrace($_GET['trace']);
        $trace->traverse(function(Node $node) use ($id, &$found, &$underlyingNodes) {
            if ($node->callId == $id) {
                $found = $node;
            }
            if ($node->parent && $node->parent->callId == $id) {
                $underlyingNodes[] = $node;
            }
        });

        return array(
            'trace'             => $_GET['trace'],
            'node'              => $found,
            'underlyingNodes'   => $underlyingNodes,
        );
    }

    public function searchFunctionGET()
    {
        $term = isset($_GET['function_term']) ? $_GET['function_term'] : null;
        $mod  = isset($_GET['function_mod']) ? $_GET['function_mod'] : null;

        /* @var $nodes Node[] */
        $nodes = array();

        if ($term) {
            $trace = $this->tl->getTrace($_GET['trace']);
            $trace->traverse(function(Node $node) use ($term, $mod, &$nodes) {
                if (count($nodes) < SEARCH_RESULTS_LIMIT) {
                    if (preg_match("~{$term}~{$mod}", $node->function)) {
                        $id = $node->getLine()->getId();
                        if (isset($nodes[$id])) {
                            $nodes[$id]->hits++;
                        } else {
                            $nodes[$id] = $node;
                            // TODO use SplObjectStorage?
                            $nodes[$id]->hits = 1;
                        }
                    }
                }
            });
        }

        return array(
            'trace' => $_GET['trace'],
            'term'  => $term,
            'nodes' => $nodes,
        );
    }

    public function searchParameterGET()
    {
        $term = isset($_GET['parameter_term']) ? $_GET['parameter_term'] : null;
        $mod  = isset($_GET['parameter_mod']) ? $_GET['parameter_mod'] : null;

        $nodes = array();

        if ($term) {
            $trace = $this->tl->getTrace($_GET['trace']);
            $trace->traverse(function(Node $node) use ($term, $mod, &$nodes) {
                if (count($nodes) < SEARCH_RESULTS_LIMIT) {
                    if ($node->returnValue && preg_match("~{$term}~{$mod}", $node->returnValue)) {
                        $nodes[$node->getId()] = $node;
                    } else {
                        foreach ($node->parameters as $param) {
                            if (preg_match("~{$term}~{$mod}", $param)) {
                                $nodes[$node->getId()] = $node;
                                break;
                            }
                        }
                    }
                }
            });
        }

        return array(
            'trace' => $_GET['trace'],
            'term'  => $term,
            'nodes' => $nodes,
        );
    }

    public function callTreeGET()
    {
        $trace  = $_GET['trace'];
        $file   = $_GET['file'];
        $line   = $_GET['line'];

        $trace = $this->tl->getTrace($trace);
        $lineInfo = $trace->callTree(new Line($file, $line));

        return new JsonResponse($lineInfo);
    }

    public function levelUpGET()
    {
        $trace  = $_GET['trace'];
        $file   = $_GET['file'];
        $line   = $_GET['line'];

        $trace = $this->tl->getTrace($trace);

        $nodes = array();
        $trace->traverse(function(Node $node) use (&$nodes, $file, $line) {
            if ($node->file == $file && $node->line == $line && $node->parent) {
                $nodes[$node->parent->getLine()->getId()] = array(
                    'file'      => $node->parent->file,
                    'line'      => $node->parent->line,
                    'function'  => $node->parent->parent ? $node->parent->parent->function : '{main}',
                );
            }
        });

        return new JsonResponse($nodes);
    }

    public function levelDownGET()
    {
        $trace  = $_GET['trace'];
        $file   = $_GET['file'];
        $line   = $_GET['line'];

        $trace = $this->tl->getTrace($trace);

        $nodes = array();
        $trace->traverse(function(Node $node) use (&$nodes, $file, $line) {
            if ($node->parent && $node->parent->file == $file && $node->parent->line == $line) {
                $nodes[$node->getLine()->getId()] = array(
                    'file'      => $node->file,
                    'line'      => $node->line,
                    'function'  => $node->parent->function,
                );
            }
        });

        return new JsonResponse($nodes);
    }

    public function importPOST($trace)
    {
        if (!TRACEVIEW_MYSQL) {
            throw new Exception('Enable database support to use import');
        }

        $db = Registry::getInstance()->getDb();
        if ($db->selectValue('SELECT id FROM traceview_traces WHERE file_name="' . $db->escape($trace) . '"')) {
            throw new Exception("Trace {$trace} already imported");
        }

        $traceInfo = $this->tl->getTraceInfo($trace);
        $parsedTrace = $this->tl->getTrace($trace);

        $db->insert('traceview_traces', array(
            'name'      => $trace,
            'file_name' => $trace,
            'm_time'    => $traceInfo->mTime,
            'size'      => $traceInfo->size,
        ));
        $traceId = $db->insertId();

        $parsedTrace->traverse(function(Node $node) use ($db, $traceId) {
            foreach ($node->parameters as $k => $value) {
                $db->insert('traceview_values', array(
                    'trace_id'      => $traceId,
                    'call_id'       => $node->callId,
                    'order_id'      => $k,
                    'type'          => $node->getType($value),
                    'value'         => $value,
                ));
            }

            $db->insert('traceview_values', array(
                'trace_id'      => $traceId,
                'call_id'       => $node->callId,
                'order_id'      => -1,
                'type'          => $node->getType($node->returnValue),
                'value'         => $node->returnValue,
            ));

            $db->insert('traceview_nodes', array(
                'trace_id'      => $traceId,
                'call_id'       => $node->callId,
                'parent_id'     => $node->parent ? $node->parent->callId : 0,
                'level'         => $node->level,
                'time_start'    => $node->timeStart,
                'time_end'      => $node->timeEnd,
                'function'      => $node->function,
                'include_file'  => $node->includeFile,
                'file'          => $node->file,
                'line'          => $node->line,
            ));
        });

        return new RedirectResponse('/');
    }

    public function deletePOST($trace)
    {
        if ($this->tl->getTraceInfo($trace)) {
            unlink($this->tl->directory . '/' . $trace);
        }
        return new RedirectResponse('/');
    }
}
