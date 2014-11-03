<?php
namespace Vtk13\TraceView\Controllers;

use Vtk13\LibXdebugTrace\FileUtil\FilesManager;
use Vtk13\LibXdebugTrace\Parser\Parser;
use Vtk13\LibXdebugTrace\Trace\Line;
use Vtk13\LibXdebugTrace\Trace\Node;
use Vtk13\Mvc\Handlers\AbstractController;
use Vtk13\Mvc\Http\JsonResponse;

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
