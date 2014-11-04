<?php
use Vtk13\LibXdebugTrace\Trace\Node;
/* @var $nodes Node[] */
/* @var $traceName string */
foreach ($nodes as $node) {
    $basename = basename($node->file);
    echo <<<HTML
<div class="pre">
    <a title="{$node->file}" href="/trace/view?trace={$traceName}&file={$node->file}#line{$node->line}">{$basename}:{$node->line}</a> {$node->function}()
</div>
HTML;
}
