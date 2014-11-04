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

        function drawFileHierarchy(File $node, $traceFileName, $currentFileName, $level = 1)
        {
            if ($level == 1 && $node instanceof Directory) {
                foreach ($node->subItems as $child) {
                    drawFileHierarchy($child, $traceFileName, $currentFileName, $level + 1);
                }
            } elseif ($node instanceof Directory) {
                ?>
                <div>
                    <div>
                        <span id="<?php echo fileId(new File($traceFileName), $node); ?>" class="toggler store glyphicon glyphicon-plus"></span>
                        <?php echo $node->getBaseName(); ?>
                    </div>
                    <div class="sub-list" style="padding-left: 15px">
                        <?php
                        foreach ($node->subItems as $child) {
                            drawFileHierarchy($child, $traceFileName, $currentFileName, $level + 1);
                        }
                        ?>
                    </div>
                </div>
            <?php
            } else {
                ?>
                <div>
                    <a id="<?php echo fileId(new File($traceFileName), $node); ?>"
                       href="<?php echo '/trace/view?trace=' . urlencode($traceFileName) . '&file=' . urlencode($node->getFullName()) ?>"
                       class="<?php echo $currentFileName == $node->getFullName() ? 'active' : ''; ?>">
                        <?php echo $node->getBaseName(); ?>
                    </a>
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