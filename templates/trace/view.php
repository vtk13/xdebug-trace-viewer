<div class="row">
    <div class="col-md-4">
        <?php include 'templates/widgets/trace-search.php'; ?>
        <?php include 'templates/widgets/file-hierarchy.php'; ?>
    </div>
    <div class="col-md-8">
        <?php
        use Vtk13\LibXdebugTrace\FileUtil\File;
        use Vtk13\LibXdebugTrace\Parser\Parser;
        use Vtk13\LibXdebugTrace\Trace\Line;

        function lineMenu($trace, $file, $line)
        {
            $trace = htmlspecialchars($trace);
            $file = htmlspecialchars($file);
            $line = htmlspecialchars($line);
            return <<<HTML
<div class="line-menu" data-trace="{$trace}" data-file="{$file}" data-line="{$line}">
    <span class="pull-right glyphicon glyphicon-remove a line-menu-close"></span>
    <div><span class="a view-level-up">One level up</span></div>
    <div><span class="a view-level-down">One level down</span></div>
    <div><span class="a view-all-calls">View all calls</span></div>
    <div><span class="a view-call-tree">Full call tree leading to this line</span></div>
</div>
HTML;
        }

        /**
         * @param $traceFileName
         * @param Line[] $fileCoverage
         */
        function drawFileUsage($traceFileName, $fileCoverage)
        {
            /* @var $first Line */
            $first = reset($fileCoverage);
            $file = file($first->file);
            foreach ($file as $k => $line) {
                $lineNum = $k + 1;
                $covered = isset($fileCoverage[$lineNum]);
                $lineNumStr = str_pad($lineNum, strlen(count($file)), ' ', STR_PAD_LEFT);
                if ($covered) {
                    $lineNumStr = '<span class="a line-menu-show">' . $lineNumStr . '</span>';
                    $class = 'covered';
                } else {
                    $class = '';
                }
                $lineMenu = lineMenu($traceFileName, $first->file, $lineNum);
                echo "<div id=\"line{$lineNum}\" class=\"line {$class}\">{$lineMenu}[{$lineNumStr}] " . htmlspecialchars($line) . '</div>';
            }
        }

        if (isset($_GET['trace']) && isset($_GET['file'])) {
            try {
                $file = $fm->getTraceFile($_GET['trace']);
                $parser = new Parser();
                $trace = $parser->parse($file);
                $fileCoverage = $trace->fileCoverage(new File($_GET['file']));
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">Executed lines in <?php echo $_GET['file']; ?></div>
                    <div class="panel-body"><?php drawFileUsage($_GET['trace'], $fileCoverage); ?></div>
                </div>
            <?php
            } catch (Exception $ex) {
                ?><div class="panel panel-danger"><div class="panel-heading">
                    <?php echo $ex->getMessage(); ?>
                </div></div><?php
            }
        }
        ?>
    </div>
</div>