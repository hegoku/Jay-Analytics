<?php
require __DIR__.'/bootstrap/autoload.php';

$public_dir=__DIR__."/public";

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$http = new swoole_http_server("127.0.0.1", 9501);
$http->on('WorkerStart', function ($serv, $worker_id){
    //echo $worker_id."\n";
});
$http->on('WorkerStop', function ($serv, $worker_id){
    //echo "stop:".$worker_id."\n";
});
$http->on('request', function ($request, $response) use($kernel,$http,$public_dir) {
    echo memory_get_usage()."\n";
    setGlobal($request);
    if (is_file($public_dir.$_SERVER['REQUEST_URI'])) {
        $response->end(file_get_contents($public_dir.$_SERVER['REQUEST_URI']));
        return;
    }
    $laravel_response = $kernel->handle($laravel_request = Illuminate\Http\Request::capture());
    
    foreach ($laravel_response->headers->allPreserveCase() as $name => $values) {
        foreach ($values as $value) {
            $response->header($name, $value);
        }
    }
    foreach ($laravel_response->headers->getCookies() as $cookie) {
        $response->cookie($cookie->getName(), $cookie->getValue(), $cookie->getExpiresTime(), $cookie->getPath(), $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());
    }

    ob_start();

    $laravel_response->send();
    $kernel->terminate($laravel_request, $laravel_response);

    $result = ob_get_clean();
    $response->end($result);
    echo memory_get_usage()."\n";
});
$http->start();

function setGlobal($request)
{
    if (isset($request->get)) {
        $_GET = $request->get;
    } else {
        $_GET = array();
    }
    if (isset($request->post)) {
        $_POST = $request->post;
    } else {
        $_POST = array();
    }
    if (isset($request->files)) {
        $_FILES = $request->files;
    } else {
        $_FILES = array();
    }
    if (isset($request->cookie)) {
        $_COOKIE = $request->cookie;
    } else {
        $_COOKIE = array();
    }
    if (isset($request->server)) {
        $_SERVER = $request->server;
    } else {
        $_SERVER = array();
    }
    //todo: necessary?
    foreach ($_SERVER as $key => $value) {
        $_SERVER[strtoupper($key)] = $value;
        unset($_SERVER[$key]);
    }
    $_REQUEST = array_merge($_GET, $_POST, $_COOKIE);
    $_SERVER['REQUEST_URI'] = $request->server['request_uri'];
    foreach ($request->header as $key => $value) {
        $_key = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        $_SERVER[$_key] = $value;
    }
    $_SERVER['REMOTE_ADDR'] = $request->server['remote_addr'];
}
?>
