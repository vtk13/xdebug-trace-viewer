
<div class="panel panel-default">
    <div class="panel-heading">XDEBUG options</div>
    <ul class="list-group">
        <li class="list-group-item">
            <span class="glyphicon <?php echo function_exists('xdebug_enable') ? 'glyphicon-ok' : 'glyphicon-remove'; ?>"></span>
            <?php echo 'xdebug installed: ' . (function_exists('xdebug_enable') ? 'true' : 'false'); ?>
        </li>
        <?php

        $xdebugOptions = array(
            'xdebug.auto_trace'             => array(),
            'xdebug.trace_enable_trigger'   => array(),
            'xdebug.trace_output_dir'       => array(),
            'xdebug.trace_output_name'      => array(),
            'xdebug.collect_assignments'    => array(
                'recommended' => 0,
                'comment'   => '"1" may cause xdebug segmentation fault',
            ),
            'xdebug.trace_format'           => array(
                'recommended' => 1,
            ),
            'xdebug.trace_options'          => array(
                'recommended' => 0,
            ),
            'xdebug.collect_params'         => array(
                'href'      => 'http://xdebug.org/docs/all_settings#collect_params',
                'comment'   => '"0" give you more compact traces<br>"3" give you more information for debug',
            ),
            'xdebug.var_display_max_data'       => array(),
            'xdebug.var_display_max_depth'      => array(),
            'xdebug.var_display_max_children'   => array(),

        );

        foreach ($xdebugOptions as $option => $params) {
            if (isset($params['href'])) {
                echo "<a target=\"_blank\" href=\"{$params['href']}\" class=\"list-group-item\">";
            } else {
                echo '<li class="list-group-item">';
            }
            if (isset($params['recommended'])) {
                $isValid = ini_get($option) == $params['recommended'];
                ?><span class="glyphicon <?php echo $isValid ? 'glyphicon-ok' : 'glyphicon-remove'; ?>"></span> <?php
            }
            echo $option . ': ' . ini_get($option);
            if (isset($params['comment'])) {
                echo "<br>{$params['comment']}";
            }

            if (isset($params['href'])) {
                echo '</a>';
            } else {
                echo '</li>';
            }
        }
        ?>
    </ul>
</div>