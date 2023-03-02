<?php
function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
function getAccessLevel($localDef, $vpnDef, $useDomain) {
    $serverSub = substr($_SERVER['SERVER_ADDR'], 0, strrpos($_SERVER['SERVER_ADDR'], '.'));
    $remoteSub = substr($_SERVER['REMOTE_ADDR'], 0, strrpos($_SERVER['REMOTE_ADDR'], '.'));
    $visitorIP = getUserIpAddr();
    $visitorSub = substr($visitorIP, 0, strrpos($visitorIP, '.'));
//    echo "server: " . $serverSub . ", remote: " . $remoteSub . ", visitor: ". $visitorIP;
//    echo "localDef: " . $localDef . ", vpnDef: " . $vpnDef . "<br>";
    $isLocal = false;
    $isLocal = ($serverSub == $remoteSub);
    if(!$isLocal) {
        if ($visitorIP == gethostbyname($useDomain)) {
            $isLocal = true;
        }
    }
    if(!$isLocal) {
        $isLocal = ($localDef == $serverSub);
    }
    if(!$isLocal) {
        if ($vpnDef == $serverSub) {
            return "vpn";
        }
    } 
    if ($isLocal) {
        return "local";
    } else {
        return "remote";
    }
}
function getProtocol() {
    $isHttps = false;
    if(isset($_SERVER['HTTPS'])) {
	    $isHttps = true;
    }
}
?>
