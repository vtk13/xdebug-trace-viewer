<?php
use Vtk13\Mvc\Handlers\ControllerRouter;
use Vtk13\Mvc\Http\Request;

require_once '../vendor/autoload.php';

ini_set('include_path', ini_get('include_path') . ':..');

$router = new ControllerRouter('Vtk13\\TraceView\\Controllers', '/', 'trace');
$response = $router->handle(Request::createFromGlobals());

header($response->getStatusLine());
foreach ($response->getHeaders() as $name => $content) {
    header($name . ': ' . $content);
}
echo $response->getBody();
