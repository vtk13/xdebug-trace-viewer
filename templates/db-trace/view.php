<?php
use Vtk13\TraceView\Dto\Node;
use Vtk13\TraceView\Formatters\DbTraceHtml;

/* @var $traceId string */
/* @var $main Node */
/* @var $nodes Node[] */
/* @var $term_function string */
/* @var $term_parameter string */
/* @var $term_filename string */
?>
<div class="row head-forms">

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Search for function name</div>
            <div class="panel-body">
                <form action="/db-trace/search-function" method="GET">
                    <input type="hidden" name="trace" value="<?php echo $traceId; ?>">
                    <input type="text" name="term_function" value="<?php echo $term_function; ?>">
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Search for parameter/return value</div>
            <div class="panel-body">
                <form action="/db-trace/search-parameter" method="GET">
                    <input type="hidden" name="trace" value="<?php echo $traceId; ?>">
                    <input type="text" name="term_parameter" value="<?php echo $term_parameter; ?>">
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Filter by filename</div>
            <div class="panel-body">
                <form action="/db-trace/filter-filename" method="GET">
                    <input type="hidden" name="trace" value="<?php echo $traceId; ?>">
                    <input type="text" name="term_filename" value="<?php echo $term_filename; ?>">
                </form>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-sm-12">
        <div class="list-group">
            <div class="list-group-item active">
                <?php echo DbTraceHtml::nodeLine($traceId, $main, []); ?>
            </div>
            <?php foreach ($nodes as $node) { ?>
                <div class="list-group-item">
                    <?php echo DbTraceHtml::nodeLine($traceId, $node, []); ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>