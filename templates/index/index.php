<?php
use Vtk13\LibXdebugTrace\Db\DbTraceList;
use Vtk13\LibXdebugTrace\FileUtil\TraceOutputDir;
use Vtk13\TraceView\Registry;

$fileTraces = new TraceOutputDir();
?>
<div class="row">
    <?php if (TRACEVIEW_MYSQL) { ?>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Choose trace file to analyse:</div>
                <div class="list-group">
                    <?php
                    foreach ($fileTraces->listTracesInfo() as $traceInfo) {
                        ?>
                        <div class="list-group-item">
                            <form method="post" action="/file-trace/delete/<?php echo urlencode($traceInfo->fileName); ?>" class="pull-right" onsubmit="return confirm('Delete trace?');">
                                <button type="submit" title="Remove trace"><span class="glyphicon glyphicon-remove"></span></button>
                            </form>
                            <form method="post" action="/file-trace/import/<?php echo urlencode($traceInfo->fileName); ?>" class="pull-right">
                                <button type="submit" title="Import trace file into the database"><span class="glyphicon glyphicon-import"></span></button>
                            </form>
                            <a href="/file-trace/view/<?php echo urlencode($traceInfo->fileName); ?>"><?php echo $traceInfo->fileName; ?></a><br>
                            <?php echo date('Y-m-d H:i:s', $traceInfo->mTime) , ',  ',  number_format($traceInfo->size); ?> bytes
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading">Traces from database:</div>
                <div class="list-group">
                    <?php
                    $dbTraces = new DbTraceList(Registry::getInstance()->getDb());
                    $tracesInfo = $dbTraces->listTracesInfo();
                    if ($tracesInfo) {
                        foreach ($tracesInfo as $traceInfo) {
                            ?>
                            <div class="list-group-item">
                                <form method="post" action="/db-trace/delete/<?php echo urlencode($traceInfo->id); ?>" class="pull-right" onsubmit="return confirm('Delete trace?');">
                                    <button type="submit" title="Remove trace"><span class="glyphicon glyphicon-remove"></span></button>
                                </form>
                                <a href="/db-trace/view/<?php echo urlencode($traceInfo->id); ?>"><?php echo $traceInfo->name; ?></a><br>
                                <?php echo date('Y-m-d H:i:s', $traceInfo->mTime) , ',  ',  number_format($traceInfo->size); ?> bytes
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="list-group-item">No traces imported yet.</div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">Choose trace file to analyse:</div>
                <div class="list-group">
                    <?php
                    foreach ($fileTraces->listTracesInfo() as $traceInfo) {
                        ?>
                        <div class="list-group-item">
                            <form method="post" action="/file-trace/delete/<?php echo urlencode($traceInfo->fileName); ?>" class="pull-right" onsubmit="return confirm('Delete trace?');">
                                <button type="submit" title="Remove trace"><span class="glyphicon glyphicon-remove"></span></button>
                            </form>
                            <a href="/file-trace/view/<?php echo urlencode($traceInfo->fileName); ?>"><?php echo $traceInfo->fileName; ?></a><br>
                            <?php echo date('Y-m-d H:i:s', $traceInfo->mTime) , ',  ',  number_format($traceInfo->size); ?> bytes
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="col-md-3">
        <?php include 'templates/widgets/xdebug-options.php'; ?>
    </div>
</div>