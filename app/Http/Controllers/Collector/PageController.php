<?php

namespace App\Http\Controllers\Collector;

use Request;
use Response;
use App\Http\Controllers\Controller;

class PageController extends Controller{
  public function js(){
    $id=Request::input('app');
    return view('collector.js',['id'=>$id]);
  }

  public function index(){
    $app=App::where('name',Request::input('app'))->first();
    if($app==null)return;
    $stream = new StreamHandler(storage_path().'/app/'.Date("Y-m-d").'/'.$app->name.'/page.log', Logger::DEBUG);
    $log = new Logger('page');
    $log->pushHandler($stream);

    $output = "%datetime% %message%\n";
    $formatter = new LineFormatter($output);
    $stream->setFormatter($formatter);

    $data=[
      'url'=>Request::input('url','null'),
      'referrer'=>Request::input('referrer','null'),
      'browser'=>UA::getBroswer(),
      'platform'=>UA::getPlatForm(),
      'app'=>UA::getApp(),
      'user_id'=>User::getInstance()->user_id,
      'ip'=>UA::get_client_real_ip(),
      'visiter_id'=>'null',
      'prev_session'=>0,
      'current_session'=>0,
      'session_counter'=>0
    ];

    if(Request::has('cookie')){
      $cookies=explode(".",Request::input('cookie'));
      $data['visiter_id']=$cookies[0].".".$cookies[1];
      $data['prev_session']=$cookies[2];
      $data['current_session']=$cookies[3];
      $data['session_counter']=$cookies[4];
    }

    $msg=implode(" ",$data);

    $log->addInfo($msg);

    return Response::make("", 200,[
      'Access-Control-Allow-Credentials'=>'true',
      'Access-Control-Allow-Origin'=>isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:"*"
    ]);

  }
}
