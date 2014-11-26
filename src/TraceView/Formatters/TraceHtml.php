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
     * @param bool $detailed false - assumed node is just line of code, not a particular call
     * @return string
     */
    public static function nodeLine($traceName, Node $node, $withFileName = true, $detailed = true)
    {
        if ($detailed) {
            $ts = '<span class="label label-info pull-right">' . number_format(($node->timeEnd - $node->timeStart) * 100000) . ' us</span>';
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
                if (preg_match('~^class (.*?) {~', $parameter, $matches)
                    || preg_match('~^(array) \(~', $parameter, $matches)
                ) {
                    $event = '<div title="Parameter value">' . htmlspecialchars($parameter) . '</div>';
                    $event = '$(' . json_encode($event) . ').dialog({width: "80%"});';
                    $event = htmlspecialchars($event);
                    $args[] = <<<HTML
<span class="a" title="Parameter value" onclick="{$event}">{$matches[1]}</span>
HTML;
                } else {
                    $args[] = htmlspecialchars($parameter);
                }
            }
            $arguments = implode(', ', $args);
            $nodeId = <<<HTML
<a title="View stack trace" href="/trace/call?trace={$traceName}&id={$node->callId}">#{$node->callId}</a>
HTML;
        } else {
            $ts = '';

            $params = [
                'trace' => $traceName,
                'file'  => $node->file,
                'line'  => $node->line,
            ];
            $calls = '<a href="/trace/calls?' . http_build_query($params) . '">View all ' . (isset($node->hits) ? $node->hits : '') .' calls</a>';
            $arguments = '';
            $return = '';
            $nodeId = '';
        }

        if ($withFileName) {
            $fileName = self::line($traceName, $node->getLine());
        } else {
            $fileName = '';
        }
        return <<<HTML
{$nodeId} {$ts} {$fileName} {$node->function}({$arguments}) {$return} {$calls}
HTML;
    }
}
