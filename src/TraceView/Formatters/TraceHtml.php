<?php
namespace Vtk13\TraceView\Formatters;

use Vtk13\LibXdebugTrace\Trace\Line;
use Vtk13\LibXdebugTrace\Trace\Node;

class TraceHtml
{
    public static function line($traceName, Line $line)
    {
        $basename = basename($line->file);
        return <<<HTML
<a title="Jump to source {$line->file}" href="/trace/view?trace={$traceName}&file={$line->file}#line{$line->line}">{$basename}:{$line->line}</a>
HTML;
    }

    /**
     * @param $traceName
     * @param Node $node
     * @param bool $withFileName
     * @param bool $withParameters false - assumed node is just line of code, not a particular call
     * @return string
     */
    public static function nodeLine($traceName, Node $node, $withFileName = true, $withParameters = true)
    {
        if ($withParameters) {
            $args = array();
            foreach ($node->parameters as $parameter) {
                if (preg_match('~class (.*?) {~', $parameter, $matches)
                    || preg_match('~(array) \(~', $parameter, $matches)
                ) {
                    $event = '<div title="Parameter value">' . $parameter . '</div>';
                    $event = '$(' . json_encode($event) . ').dialog({width: "80%"});';
                    $event = htmlspecialchars($event);
                    $args[] = <<<HTML
    <span class="a" title="Parameter value" onclick="{$event}">{$matches[1]}</span>
HTML;
                } else {
                    $args[] = $parameter;
                }
            }
            $arguments = implode(', ', $args);
            $nodeId = <<<HTML
<a title="View stack trace" href="/trace/call?trace={$traceName}&id={$node->callId}">#{$node->callId}</a>
HTML;
        } else {
            $arguments = '';
            $nodeId = '';
        }

        if ($withFileName) {
            $fileName = self::line($traceName, $node->getLine());
        } else {
            $fileName = '';
        }
        return <<<HTML
{$nodeId} {$fileName} {$node->function}({$arguments})
HTML;
    }
}
