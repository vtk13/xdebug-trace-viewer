<?php
use Vtk13\LibXdebugTrace\FileUtil\FilesManager;

?>
<div class="row">
    <div class="col-md-3">
        <?php include 'templates/widgets/xdebug-options.php'; ?>
    </div>

    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">Choose trace file to analyse:</div>
            <div class="list-group">
                <?php
                $fm = new FilesManager();
                foreach ($fm->listTraceFiles() as $file) {
                    ?>
                    <a href="/trace/view?trace=<?php echo urlencode($file->getBaseName()); ?>" class="list-group-item">
                        <?php echo $file->getBaseName(); ?><br>
                        <?php echo $file->getMTime('Y-m-d H:i:s') , ',  ',  number_format($file->getSize()); ?> bytes
                    </a>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>