<?php
namespace Vtk13\TraceView\Formatters;

use Vtk13\TraceView\Dto\Node;

class DbTraceHtml
{
    public static function line($traceName, $file, $line)
    {
        $basename = basename($file);
        $href = '/file-trace/view/' . urlencode($traceName) . '/'
            . urlencode($file) .'#line' . $line;
        return <<<HTML
<a title="Jump to source {$file}" href="{$href}">{$basename}:{$line}</a>
HTML;
    }

    public static function nodeLine($traceId, Node $node)
    {
        $ts = '<span class="label label-info pull-right">' . number_format(($node->time_end - $node->time_start) * 100000) . ' us</span>';
        $calls = '';

        if (isset($node->returnValue)) {
            if (strlen($node->returnValue) > 30) {
                $event = '<div title="Return value">' . htmlspecialchars($node->returnValue) . '</div>';
                $event = '$(' . json_encode($event) . ').dialog({width: "80%"});';
                $event = htmlspecialchars($event);
                $return = <<<HTML
-> <span class="a" onclick="{$event}">return</span>
HTML;
            } else {
                $return = '-> ' . htmlspecialchars($node->returnValue);
            }
        } else {
            $return = '';
        }

        $args = array();
        foreach ($node->parameters as $parameter) {
            // TODO
            if ($parameter->order_id == -1) {
                continue;
            }

            if (preg_match('~^class (.*?) {~', $parameter->value, $matches)
                || preg_match('~^(array) \(~', $parameter->value, $matches)
            ) {
                $event = '<div title="Parameter value">' . htmlspecialchars($parameter->value) . '</div>';
                $event = '$(' . json_encode($event) . ').dialog({width: "80%"});';
                $event = htmlspecialchars($event);
                $args[] = <<<HTML
<span class="a" title="Parameter value" onclick="{$event}">{$matches[1]}</span>
HTML;
            } else {
                $args[] = htmlspecialchars($parameter->value);
            }
        }
        $arguments = implode(', ', $args);
        $nodeId = <<<HTML
<a title="View stack trace" href="/db-trace/call?trace={$traceId}&id={$node->call_id}">#{$node->call_id}</a>
HTML;

        $fileName = self::line($traceId, $node->file, $node->line);

        return <<<HTML
{$nodeId} {$ts} {$fileName} {$node->function}({$arguments}) {$return} {$calls}
HTML;
    }
}
