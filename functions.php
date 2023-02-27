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
    $serverIP = explode('.',$_SERVER['SERVER_ADDR']);
    $localIP  = explode('.',$_SERVER['REMOTE_ADDR']);
    $visitorIP = getUserIpAddr();
    $isLocal = false;
    $isLocal = (
      ($serverIP[0] == $localIP[0]) && 
      ($serverIP[1] == $localIP[1]) && 
      ($serverIP[2] == $localIP[2])
    );
    if(!$isLocal) {
        if ($visitorIP == gethostbyname($useDomain)) {
            $isLocal = true;
        }
    }
    if(!$isLocal) {
        $isLocal = (
            ($localIP[0] == $vpnDef[0]) &&
            ($localIP[1] == $vpnDef[1]) &&
            ($localIP[2] == $vpnDef[2]) &&
            ($localIP[3] == $vpnDef[3])
        );
        if($isLocal) { 
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
