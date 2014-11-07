<div class="panel panel-default">
    <div class="panel-heading">Hierarchy of used files</div>
    <div class="panel-body">
        <?php
        use Vtk13\LibXdebugTrace\FileUtil\Directory;
        use Vtk13\LibXdebugTrace\FileUtil\File;
        use Vtk13\LibXdebugTrace\FileUtil\FilesManager;
        use Vtk13\LibXdebugTrace\Parser\Parser;

        function fileId(File $trace, File $file)
        {
            return substr(md5($trace->getFullName()), 0, 6) . '-' . substr(md5($file->getFullName()), 0, 6);
        }

        function drawFileHierarchy(File $file, $traceFileName, $currentFileName, $level = 1)
        {
            if ($level == 1 && $file instanceof Directory) {
                foreach ($file->subItems as $child) {
                    drawFileHierarchy($child, $traceFileName, $currentFileName, $level + 1);
                }
            } elseif ($file instanceof Directory) {
                ?>
                <div>
                    <div>
                        <span id="<?php echo fileId(new File($traceFileName), $file); ?>" class="toggler store glyphicon glyphicon-plus"></span>
                        <?php echo $file->getBaseName(); ?>
                        <span class="label label-info"><?php echo $file->hits; ?></span>
                    </div>
                    <div class="sub-list" style="padding-left: 15px">
                        <?php
                        foreach ($file->subItems as $child) {
                            drawFileHierarchy($child, $traceFileName, $currentFileName, $level + 1);
                        }
                        ?>
                    </div>
                </div>
            <?php
            } else {
                ?>
                <div>
                    <a id="<?php echo fileId(new File($traceFileName), $file); ?>"
                       href="<?php echo '/trace/view?trace=' . urlencode($traceFileName) . '&file=' . urlencode($file->getFullName()) ?>"
                       class="<?php echo $currentFileName == $file->getFullName() ? 'active' : ''; ?>">
                        <?php echo $file->getBaseName(); ?></a>
                    <span class="label label-info"><?php echo $file->hits; ?></span>
                </div>
            <?php
            }
        }

        if (isset($_GET['trace'])) {
            try {
                $fm = new FilesManager();
                $file = $fm->getTraceFile($_GET['trace']);
                $parser = new Parser();
                $trace = $parser->parse($file);

                drawFileHierarchy($trace->fileHierarchy(), $_GET['trace'], isset($_GET['file']) ? $_GET['file'] : null);
            } catch (Exception $ex) {
                ?><div class="panel panel-danger"><div class="panel-heading">
                    <?php echo $ex->getMessage(); ?>
                </div></div><?php
            }
        }
        ?>
    </div>
</div>