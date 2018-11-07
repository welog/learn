<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>跳转微信中</title>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport">
<meta content="telephone=no" name="format-detection">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<?php
// 统一调用微信白名单接口：https://wq.jd.com/mjgj/link/GetOpenLink?callback=getOpenLink&rurl=https://dc2.jd.com/auto.php?service=transfer&type=pms&to=（这里是拼接自己的内容地址比如http://mjbbs.jd.com/data/attachment/forum/201806/08/173526pb2zpjzzooo2ofze.jpg）
if($_GET['t']) {
    // include("admin/config.php");
    // include("admin/function.php");
    $code = $_GET['t'];
    $info = query ( "jump_logs", "where code='" . $code . "'" );
    if($info['code'] == ''){
        echo '跳转失败';
        exit(0);
    }

    if($info['state'] == '1'){
        if($info['count'] >= $info['num']){
            //判断跳转条数
            echo '跳转失败';
            exit(0);
        }
        //判断是否到期
        $time = strtotime($info['time']);
        if(time() > $time){
            echo '跳转失败';
            exit;
        }
    }else{
        echo '跳转失败';
        exit;
    }

    if($info['www_url'] == ''){
        echo '请先配置落地页';
        exit;
    }else{
        $w_url_code = $info['rl'];
    }
?>
<style>
*{ margin:0 auto;}html,body{height:100%;}.container {margin-top: 100px;text-align: center;}.icon {width: 70px;height: 70px;}#ellipsis {display: inline-block;width: 0;}
</style>
</head>
<body>
<div class="container">
<p class="text">正在跳转到微信 <span id="ellipsis">&#160;&#160;&#160;</span></p></div>
<script type="text/javascript">
var ellipsis = ['&#160;&#160;&#160;', '.&#160;&#160;', '..&#160;', '...'];
var index = 0;
var $ellipsis = document.getElementById('ellipsis');
setInterval(function () {
$ellipsis.innerHTML = ellipsis[index++ % 4];
}, 500);</script>

<?php
function get_ticket($code){
    //初始化
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
    $headers = array();
    $headers[] = 'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 11_1_2 like Mac OS X; zh-CN) AppleWebKit/537.51.1 (KHTML, like Gecko) Mobile/15B202 UCBrowser/11.7.7.1031 Mobile  AliApp(TUnionSDK/0.1.20)';
    $headers[] = 'Referer: https://m.mall.qq.com/release/?busid=mxd2&ADTAG=jcp.h5.index.dis';
    $headers[] = 'Content-Type:application/x-www-form-urlencoded; charset=UTF-8';
 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $content = curl_exec($ch);
    curl_close($ch);
    //$arr = json_decode($content,1);
    //if($arr['success'] == '1'){
    //    $shotCode = $arr['shotCode'];
    //}else{
    //    $shotCode = '';
    //}
    //preg_match('/openlink\":\"(.*?)\"}/',$content,$result);
    //$url = $result[1];
    preg_match('/href=\"(.*?)#wechat/',$content,$result);
    $url = $result[1];
    return $url;
}
    $time = time()-$info['ticket_time'];
    $minute=floor($time/60);
    query_update ( "jump_logs", "count=count+1". " where code='" . $code . "'" );
    if($minute >= 59){
        //如果超过1小时，更新ticket
        $url = get_ticket($w_url_code);
        if($url){
            query_update ( "jump_logs", "ticket_time='".time()."', ticket='" . $url . "' where code='" . $code . "'" );
            $ticket_url = $url.'#';
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'baiduboxapp')||strpos($_SERVER['HTTP_USER_AGENT'], 'baiduboxapp')){//安卓百度手机APP
                echo '<script>window.location.href = "bdbox://utils?action=sendIntent&minver=7.4&params=%7b%22intent%22%3a%22'.$url.'%23wechat_redirect%23wechat_redirect%23Intent%3bend%22%7d";</script>';
            }else{
                echo '<script>window.location.href = "'.$ticket_url.'";</script>';
            }
        }
    }else{
        $ticket_url = $info['ticket'].'#';
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'baiduboxapp')||strpos($_SERVER['HTTP_USER_AGENT'], 'baiduboxapp')){//安卓百度手机APP
            echo '<script>window.location.href = "bdbox://utils?action=sendIntent&minver=7.4&params=%7b%22intent%22%3a%22'.$info['ticket'].'%23wechat_redirect%23wechat_redirect%23Intent%3bend%22%7d";</script>';
        }else{
            echo '<script>window.location.href = "'.$ticket_url.'";</script>';
        }
    }
}
?>
</body>
</html>