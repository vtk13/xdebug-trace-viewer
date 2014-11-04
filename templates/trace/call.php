<?php
use Vtk13\LibXdebugTrace\Trace\Node;
use Vtk13\LibXdebugTrace\Trace\StackTrace;
use Vtk13\TraceView\Formatters\TraceHtml;

/* @var $node Node */
/* @var $traceName string */

?><div class="row">
    <div class="col-md-4">
        <?php include 'templates/widgets/trace-search.php'; ?>
        <?php include 'templates/widgets/file-hierarchy.php'; ?>
    </div>
    <div class="col-md-8">
        <div class="list-group">
            <div class="list-group-item active">Stack trace for node #<?php echo $node->callId; ?></div>
        <?php
        $stack = new StackTrace($node);
        while ($node->parent) {
            ?>
            <div class="list-group-item">
                <?php echo TraceHtml::nodeLine($traceName, $node); ?>
            </div>
            <?php
            $node = $node->parent;
        }
        ?>
        </div>
</div>
</div>