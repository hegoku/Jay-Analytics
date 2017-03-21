<?php
require __DIR__.'/../bootstrap/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

class A{
    public static $a=1;
}
$a=new A();
var_dump($a);
$http = new swoole_http_server("127.0.0.1", 9501);
$http->on('WorkerStart', function ($serv, $worker_id) use ($a){
    //echo $worker_id."\n";
});
$http->on('WorkerStop', function ($serv, $worker_id){
    //echo "stop:".$worker_id."\n";
});
$http->on('request', function ($request, $response) use($kernel,$a) {
    $a::$a++;
    var_dump($a);
    $a::$a=1;
    // 代码借鉴自 Illuminate\Http\Request::capture
    /*$l_request= new Symfony\Component\HttpFoundation\Request(
        isset($request->get)?$request->get:[], isset($request->post)? $request->post:[], [], 
        isset($request->cookie)?$request->cookie:[], isset($request->files)? $request->files:[], $request->server
    );

    if (
        0 === strpos($l_request->headers->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded')
        && in_array(strtoupper($l_request->server->get('REQUEST_METHOD', 'GET')), array('PUT', 'DELETE', 'PATCH'))
    ) {
        parse_str($l_request->getContent(), $data);
        $l_request->request = new  Symfony\Component\HttpFoundation\ParameterBag($data);
    }

    $l_request=Illuminate\Http\Request::createFromBase( $l_request);

    $l_response = $kernel->handle( $l_request );

    ob_start();

    $l_response->send();
    $kernel->terminate($l_request, $l_response);

    $result = ob_get_clean();
    $response->end($result);*/
});
$http->start();
?>
