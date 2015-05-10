<?php
define('TRACEVIEW_MYSQL', true);
\Vtk13\TraceView\Registry::getInstance()->setDb(new Vtk13\LibSql\Mysql\Mysql('localhost', 'root', '', 'traceview'));