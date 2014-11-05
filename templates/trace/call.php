<?php
use Vtk13\LibXdebugTrace\Trace\Node;
use Vtk13\LibXdebugTrace\Trace\StackTrace;
use Vtk13\TraceView\Formatters\TraceHtml;

/* @var $node Node */
/* @var $traceName string */
/* @var $underlyingNodes Node[] */

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
        $each = $node;
        while ($each->parent) {
            ?>
            <div class="list-group-item">
                <?php echo TraceHtml::nodeLine($traceName, $each); ?>
            </div>
            <?php
            $each = $each->parent;
        }
        ?>
        </div>

        <div class="list-group">
            <div class="list-group-item active">underlying nodes of node #<?php echo $node->callId; ?></div>
            <?php
                foreach ($underlyingNodes as $each) {
                    ?>
                    <div class="list-group-item">
                        <?php echo TraceHtml::nodeLine($traceName, $each); ?>
                    </div>
                <?php
                }
            ?>
        </div>
</div>
</div>