<?php
namespace Vtk13\TraceView\Dto;

class Node
{
    public $trace_id;
    public $call_id;
    public $parent_id;
    public $level;
    public $time_start;
    public $time_end;
    public $function;
    public $include_file;
    public $file;
    public $line;

    /**
     * @var Value[]
     */
    public $parameters = [];
}
