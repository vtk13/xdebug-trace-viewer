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
            $margin = 0;
            foreach ($stack->getStraightTrace() as $each) {
                $class = ($each == $node ? 'bg-success' : '');
                ?>
                <div class="list-group-item <?php echo $class; ?>" style="margin-left: <?php echo $margin; ?>px;">
                    <?php echo TraceHtml::nodeLine($traceName, $each); ?>
                </div>
                <?php
                $margin += 5;
                $each = $each->parent;
            }
            foreach ($underlyingNodes as $each) {
                ?>
                <div class="list-group-item" style="margin-left: <?php echo $margin; ?>px;">
                    <?php echo TraceHtml::nodeLine($traceName, $each); ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>