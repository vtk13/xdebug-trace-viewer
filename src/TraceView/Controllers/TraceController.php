<?php
namespace Vtk13\TraceView\Controllers;

use Vtk13\LibXdebugTrace\FileUtil\FilesManager;
use Vtk13\LibXdebugTrace\Parser\Parser;
use Vtk13\LibXdebugTrace\Trace\Line;
use Vtk13\LibXdebugTrace\Trace\Node;
use Vtk13\Mvc\Exception\RouteNotFoundException;
use Vtk13\Mvc\Handlers\AbstractController;
use Vtk13\Mvc\Http\JsonResponse;

define('SEARCH_RESULTS_LIMIT', isset($_GET['limit']) ? $_GET['limit'] : 200);

class TraceController extends AbstractController
{
    public function __construct()
    {
        parent::__construct('trace');
    }

    public function indexGET()
    {

    }

    public function viewGET()
    {

    }

    public function callsGET()
    {
        $fm = new FilesManager();
        if (empty($_GET['trace']) || empty($file = $fm->getTraceFile($_GET['trace']))) {
            throw new RouteNotFoundException("Trace {$_GET['trace']} not found in {$fm->directory}");
        }

        $file = isset($_GET['file']) ? $_GET['file'] : null;
        $line = isset($_GET['line']) ? $_GET['line'] : null;

        $nodes = array();

        $parser = new Parser();
        $trace = $parser->parse($fm->getTraceFile($_GET['trace']));
        $trace->traverse(function(Node $node) use ($file, $line, &$nodes) {
            if ($node->file == $file && $node->line == $line) {
                $nodes[] = $node;
            }
        });

        return array(
            'traceName' => $_GET['trace'],
            'traceFile' => $file,
            'traceLine' => $line,
            'nodes'     => $nodes,
        );
    }

    public function callGET()
    {
        $fm = new FilesManager();
        if (empty($_GET['trace']) || empty($file = $fm->getTraceFile($_GET['trace']))) {
            throw new RouteNotFoundException("Trace {$_GET['trace']} not found in {$fm->directory}");
        }

        $id = isset($_GET['id']) ? $_GET['id'] : null;

        $found = null;
        $underlyingNodes = array();
        $parser = new Parser();
        $trace = $parser->parse($fm->getTraceFile($_GET['trace']));
        $trace->traverse(function(Node $node) use ($id, &$found, &$underlyingNodes) {
            if ($node->callId == $id) {
                $found = $node;
            }
            if ($node->parent && $node->parent->callId == $id) {
                $underlyingNodes[] = $node;
            }
        });

        return array(
            'traceName'         => $_GET['trace'],
            'node'              => $found,
            'underlyingNodes'   => $underlyingNodes,
        );
    }

    public function search_functionGET()
    {
        $fm = new FilesManager();
        if (empty($_GET['trace']) || empty($file = $fm->getTraceFile($_GET['trace']))) {
            throw new RouteNotFoundException("Trace {$_GET['trace']} not found in {$fm->directory}");
        }

        $term = isset($_GET['function_term']) ? $_GET['function_term'] : null;
        $mod  = isset($_GET['function_mod']) ? $_GET['function_mod'] : null;

        $nodes = array();

        if ($term) {
            $parser = new Parser();
            $trace = $parser->parse($fm->getTraceFile($_GET['trace']));
            $trace->traverse(function(Node $node) use ($term, $mod, &$nodes) {
                if (count($nodes) < SEARCH_RESULTS_LIMIT) {
                    if (preg_match("~{$term}~{$mod}", $node->function)) {
                        $id = $node->getLine()->getId();
                        if (isset($nodes[$id])) {
                            $nodes[$id]->hits++;
                        } else {
                            $nodes[$id] = $node;
                            $nodes[$id]->hits = 1;
                        }
                    }
                }
            });
        }

        return array(
            'traceName'     => $_GET['trace'],
            'term'          => $term,
            'nodes'         => $nodes,
        );
    }

    public function search_parameterGET()
    {
        $fm = new FilesManager();
        if (empty($_GET['trace']) || empty($file = $fm->getTraceFile($_GET['trace']))) {
            throw new RouteNotFoundException("Trace {$_GET['trace']} not found in {$fm->directory}");
        }

        $term = isset($_GET['parameter_term']) ? $_GET['parameter_term'] : null;
        $mod  = isset($_GET['parameter_mod']) ? $_GET['parameter_mod'] : null;

        $nodes = array();

        if ($term) {
            $parser = new Parser();
            $trace = $parser->parse($fm->getTraceFile($_GET['trace']));
            $trace->traverse(function(Node $node) use ($term, $mod, &$nodes) {
                if (count($nodes) < SEARCH_RESULTS_LIMIT) {
                    foreach ($node->parameters as $param) {
                        if (preg_match("~{$term}~{$mod}", $param)) {
                            $nodes[$node->getId()] = $node;
                            break;
                        }
                    }
                }
            });
        }

        return array(
            'traceName'     => $_GET['trace'],
            'term'          => $term,
            'nodes'         => $nodes,
        );
    }

    public function call_treeGET()
    {
        $trace  = $_GET['trace'];
        $file   = $_GET['file'];
        $line   = $_GET['line'];

        $fm = new FilesManager();
        $parser = new Parser();
        $trace = $parser->parse($fm->getTraceFile($trace));
        $lineInfo = $trace->callTree(new Line($file, $line));

        return new JsonResponse($lineInfo);
    }

    public function level_upGET()
    {
        $trace  = $_GET['trace'];
        $file   = $_GET['file'];
        $line   = $_GET['line'];

        $fm = new FilesManager();
        $parser = new Parser();
        $trace = $parser->parse($fm->getTraceFile($trace));

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

    public function level_downGET()
    {
        $trace  = $_GET['trace'];
        $file   = $_GET['file'];
        $line   = $_GET['line'];

        $fm = new FilesManager();
        $parser = new Parser();
        $trace = $parser->parse($fm->getTraceFile($trace));

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
}
