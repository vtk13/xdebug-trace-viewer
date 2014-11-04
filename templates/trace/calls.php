<div class="row">
    <div class="col-md-4">
        <?php include 'templates/widgets/trace-search.php'; ?>
        <?php include 'templates/widgets/file-hierarchy.php'; ?>
    </div>
    <div class="col-md-8">
        <div class="list-group">
        <?php
        use Vtk13\LibXdebugTrace\Trace\Node;
        /* @var $nodes Node[] */
        /* @var $traceName string */
        foreach ($nodes as $node) {
            $basename = basename($node->file);
            echo <<<HTML
            <a class="list-group-item" title="{$node->file}" href="/trace/call?trace={$traceName}&id={$node->callId}">
                #{$node->callId} {$node->timeStart}s {$basename}:{$node->line} {$node->function}()
            </a>
HTML;
        }
        ?>
        </div>
</div>
</div>