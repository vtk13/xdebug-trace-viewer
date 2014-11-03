
<div class="panel panel-default">
    <div class="panel-heading">XDEBUG options</div>
    <ul class="list-group">
        <li class="list-group-item">
            <span class="glyphicon <?php echo function_exists('xdebug_enable') ? 'glyphicon-ok' : 'glyphicon-remove'; ?>"></span>
            <?php echo 'xdebug installed: ' . (function_exists('xdebug_enable') ? 'true' : 'false'); ?>
        </li>
        <?php

        $xdebugOptions = array(
            'xdebug.auto_trace'         => array(),
            'xdebug.trace_output_dir'   => array(),
            'xdebug.trace_output_name'  => array(),
            'xdebug.trace_format'       => array(
                'validValue' => 1,
            ),
            'xdebug.trace_options'      => array(),
            'xdebug.collect_params'     => array(),
        );

        foreach ($xdebugOptions as $option => $params) {
            ?>
            <li class="list-group-item">
                <?php
                if (isset($params['validValue'])) {
                    $isValid = ini_get($option) == $params['validValue'];
                    ?><span class="glyphicon <?php echo $isValid ? 'glyphicon-ok' : 'glyphicon-remove'; ?>"></span> <?php
                }
                echo $option . ': ' . ini_get($option);
                ?>
            </li>
        <?php
        }
        ?>
    </ul>
</div>