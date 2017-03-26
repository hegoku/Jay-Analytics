<?php

namespace App\Http\Controllers\Collector;

use Illuminate\Http\Request;
use Illuminate\Http\Respone;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Page;
use App\Models\Cookie;
use App\Models\Browser;
use App\Models\Screen;
use App\Events\NewPageEvent;
use App\Library\IP;
use App\Library\UA;

class PageController extends Controller
{
    public function js(Request $request)
    {
        $id=$request->input('app');
        return view('collector.js',['id'=>$id]);
    }

    public function index(Request $request)
    {
        $project=Project::find($request->input('app'));
        if ($project==null) {
            return response()->make(
                "",
                404,
                [
                    'Access-Control-Allow-Credentials'=>'true',
                    'Access-Control-Allow-Origin'=>isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:"*"
                ]
            );
        }

        $page=new Page;
        $page->project_id=new \MongoDB\BSON\ObjectID($project->_id);
        $page->url=$request->input('url');
        $page->referrer=$request->input('referrer','');
        $page->ip=IP::get();
        $page->created_at=Date("Y-m-d H:i:s");

        $page->cookie=new Cookie();
        $page->user_agent=$_SERVER['HTTP_USER_AGENT'];
        $page->extra=null;

        if ($request->has('cookie')) {
            $cookies=explode(".",$request->input('cookie'));
            $page->cookie->visiter_id=$cookies[0].".".$cookies[1];
            $page->cookie->prev_session=(int)$cookies[2];
            $page->cookie->current_session=(int)$cookies[3];
            $page->cookie->session_counter=(int)$cookies[4];
        }

        $page->screen=new Screen();
        $page->screen->width=(int)$request->input('sh',0);
        $page->screen->height=(int)$request->input('sw',0);
        $page->screen->color_depth=(int)$request->input('cd',0);

        $ua = new UA($_SERVER['HTTP_USER_AGENT']);
        $page->platform=$ua->platform();
        $page->mobile=$ua->mobile();
        $page->browser=new Browser();
        $page->browser->name=$ua->browser();
        $page->browser->version=$ua->version();

        /*$data=[
            'url'=>Request::input('url','null'),
            'referrer'=>Request::input('referrer','null'),
            'browser'=>UA::getBroswer(),
            'platform'=>UA::getPlatForm(),
            'app'=>UA::getApp(),
            'ip'=>UA::get_client_real_ip(),
            'visiter_id'=>'null',
            'prev_session'=>0,
            'current_session'=>0,
            'session_counter'=>0
        ];*/

        if ($page->save()) {
            event(new NewPageEvent($page));
        }

        return response()->make(
            "",
            200,
            [
                'Access-Control-Allow-Credentials'=>'true',
                'Access-Control-Allow-Origin'=>isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:"*"
            ]
        );

    }

}
