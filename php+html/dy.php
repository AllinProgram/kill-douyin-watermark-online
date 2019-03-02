<?php

if (!empty($_GET['url'])) {
    $url = $_GET['url'];
    $str = GET($url, 1);
    preg_match("/video_id=(.*?)&/i", $str, $arr);
    if (count($arr) >= 1) {
        $str = GET("https://aweme.snssdk.com/aweme/v1/play/?video_id=".$arr[1]."&line=0", 0);
        preg_match('#<a href="(.*?)">#', $str, $arr2);
        if (count($arr2) >= 1) {
            $arr3 = explode("//", $arr2[1]);//把http替换成https就能完美解决有时候有用有时候没用鬼毛病
            if (!empty($arr3)) {
                //header("content-type:video/mp4");
                //header("Location: "."https://".$arr3[1]);
                if (!empty($_GET['way']) && $_GET['way'] == "txt") { //纯文本输出	
                    exit("https://".$arr3[1]);
                }
                elseif(!empty($_GET['way']) && $_GET['way'] == "json") { //json文本输出
                    $aray = ['code' =>200, 'msg' =>'success', 'url' =>"https://".$arr3[1]];
                    exit(json_encode($aray, false));
                } else { //跳转到改地址播放
                    header("Location: "."https://".$arr3[1]);
                }

            }

        }
    }

} else {
    echo "null";
}

function Get($url, $foll = 0) {
    //初始化 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); //访问的url
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //完全静默
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //忽略https       
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //忽略https     
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["user-agent: Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25"]); //UA
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $foll); //默认为$foll=0,大概意思就是对照模块网页访问的禁止301 302 跳转。
    $output = curl_exec($ch); //获取内容
    curl_close($ch); //关闭
    return $output; //返回
}
?>
