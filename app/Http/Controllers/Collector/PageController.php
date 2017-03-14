<?php

namespace App\Http\Controllers\Collector;

use Illuminate\Http\Request;
use Illuminate\Http\Respone;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Page;
use App\Models\Cookie;
use App\Events\NewPageEvent;

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
        $page->project_id=$project->_id;
        $page->url=$request->input('url');
        $page->referrer=$request->input('referrer','');
        $page->ip=$this->getIP();
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

    protected function getIP()
    {
        $realip = NULL;
        if ($realip !== NULL) return $realip;
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                foreach ($arr AS $ip) {
                    $ip = trim($ip);
                    if ($ip != 'unknown') {
                        $realip = $ip;
                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else{
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $realip = $_SERVER['REMOTE_ADDR'];
                }else{
                    $realip = '0.0.0.0';
                }
            }
        }else{
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        }
            preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
            $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
            return $realip;
    }
}
