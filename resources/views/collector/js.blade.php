var __ja = __ja || [];
__ja.push(['_setApp', '<?php echo $id;?>'],['_setToken','<?php echo csrf_token();?>']);

(function ($) {
$.ja={};

var getCookie=function (c_name){
  if (document.cookie.length>0){
    c_start=document.cookie.indexOf(c_name + "=")
    if (c_start!=-1){
      c_start=c_start + c_name.length+1
      c_end=document.cookie.indexOf(";",c_start)
      if (c_end==-1) c_end=document.cookie.length
        return unescape(document.cookie.substring(c_start,c_end))
      }
    }
  return "";
}

var setCookie=function (c_name,value,expiredays){
  var exdate=new Date()
  exdate.setDate(exdate.getDate()+expiredays)
  document.cookie=c_name+ "=" +escape(value)+
  ((expiredays==null) ? "" : ";expires="+exdate.toGMTString())
}

var ja_cookie=getCookie('__jauvi');
if (ja_cookie!=null && ja_cookie!=""){
  var ja_cookies=ja_cookie.split(".");
  var thecount=parseInt(ja_cookies[4])+1;
  ja_cookie=ja_cookies[0]+"."+ja_cookies[1]+"."+ja_cookies[3]+"."+(new Date()).valueOf()+"."+thecount;
  setCookie('__jauvi',ja_cookie,730);
}else{
  var tmp=(new Date()).valueOf();
  ja_cookie=Math.ceil(Math.random()*(999999999-100000000)+100000000)+"."+tmp;
  ja_cookie+="."+tmp+"."+tmp+".1";
  setCookie('__jauvi',ja_cookie,730);
}

var ja_ajax=new XMLHttpRequest();
ja_ajax.open("POST","<?php echo env('APP_URL');?>/page",true);
ja_ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
ja_ajax.withCredentials = true;
var ja_params={};
ja_params.cookie=ja_cookie;
if(document){
  ja_params.url=document.URL || 'null';
  ja_params.referrer = document.referrer || 'null';
}

//解析_maq配置
if(__ja) {
    for(var i in __ja) {
        switch(__ja[i][0]) {
            case '_setApp':
                ja_params.app = __ja[i][1];
                break;
            case '_setToken':
                ja_params._token = __ja[i][1];
                break;
            default:
                break;
        }
    }
}

var ja_args = '';
  for(var i in ja_params) {
    if(ja_args != '') {
      ja_args += '&';
  }
  ja_args += i + '=' + encodeURIComponent(ja_params[i]);
}
ja_ajax.send(ja_args);

//事件
$.ja.sendEvent=function(action,extra){
	var ja_ajax=new XMLHttpRequest();
	ja_ajax.open("POST","<?php echo env('APP_URL');?>/event",true);
	ja_ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	ja_ajax.withCredentials = true;
	var ja_args='';
	ja_args='app='+ja_params.app+'&action='+action+'&extra='+(extra?JSON.stringify(extra):"{}")+'&cookie='+encodeURIComponent(ja_cookie)+"&url="+encodeURIComponent(ja_params.url);
  console.log(ja_args);
	ja_ajax.send(ja_args);
}

var ja_event_node=document.querySelectorAll("[jay-analytics-data]");
for(var i=0;i<ja_event_node.length;i++){
	// [0]事件名 [1]action [2]额外信息,json格式
	var attr_data=eval("(["+ja_event_node[i].getAttribute("jay-analytics-data")+"])");
	for(var j=0;j<attr_data.length;j++){
		var executeSendEvent=function(para){
			return function(){
				$.ja.sendEvent(para[1],para[2]?JSON.parse(para[2]):{});
			}
		};
		if(ja_event_node[i].addEventListener){
			ja_event_node[i].addEventListener(attr_data[j][0],executeSendEvent(attr_data[j]));
		}else{//兼容ie8
			ja_event_node[i].attachEvent("on"+attr_data[j][0],executeSendEvent(attr_data[j]));
		}
	}

}

})(window);
