<?php
use Vtk13\Mvc\Handlers\ControllerRouter;
use Vtk13\Mvc\Http\Request;
use Vtk13\TraceView\Registry;

require_once '../vendor/autoload.php';

ini_set('include_path', '..:' . ini_get('include_path'));

if (($config = stream_resolve_include_path('config.php'))) {
    include $config;
} else {
    define('TRACEVIEW_MYSQL', false);
}

$router = new ControllerRouter('Vtk13\\TraceView\\Controllers\\');
$response = $router->handle(Request::createFromGlobals());

header($response->getStatusLine());
foreach ($response->getHeaders() as $name => $content) {
    header($name . ': ' . $content);
}
echo $response->getBody();
