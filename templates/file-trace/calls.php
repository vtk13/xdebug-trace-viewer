<?php
use Vtk13\LibXdebugTrace\Trace\Line;
use Vtk13\LibXdebugTrace\Trace\Node;
use Vtk13\TraceView\Formatters\TraceHtml;

/* @var $nodes Node[] */
/* @var $trace string */
/* @var $traceFile string */
/* @var $traceLine string */
?><div class="row">
    <div class="col-md-4">
        <?php include 'templates/widgets/trace-search.php'; ?>
        <?php include 'templates/widgets/file-hierarchy.php'; ?>
    </div>
    <div class="col-md-8">
        <div class="list-group">
        <?php
        echo '<div class="list-group-item active">All calls of ' .
            TraceHtml::line($trace, new Line($traceFile, $traceLine)) . '</div>';
        foreach ($nodes as $node) {
            ?>
            <div class="list-group-item">
                <?php echo TraceHtml::nodeLine($trace, $node, false); ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>
</div>