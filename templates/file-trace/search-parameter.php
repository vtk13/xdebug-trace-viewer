<?php
use Vtk13\LibXdebugTrace\Trace\Node;
use Vtk13\TraceView\Formatters\TraceHtml;

/* @var $nodes Node[] */
/* @var $trace string */
/* @var $searchType string */
?><div class="row">
    <div class="col-md-4">
        <?php include 'templates/widgets/trace-search.php'; ?>
        <?php include 'templates/widgets/file-hierarchy.php'; ?>
    </div>
    <div class="col-md-8">
        <div class="list-group">
            <div class="list-group-item active">Search results for parameter value</div>
            <?php
            foreach ($nodes as $node) {
                echo '<div class="list-group-item">';
                echo TraceHtml::nodeLine($trace, $node, true, true);
                echo '</div>';
            }

            if (count($nodes) >= SEARCH_RESULTS_LIMIT) {
                echo '<div class="list-group-item">Search result limited to ' . SEARCH_RESULTS_LIMIT . '  results. You can control it with limit GET parameter</div>';
            }
            ?>
        </div>
    </div>
</div>