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
//    echo "server: " . $serverSub . ", remote: " . $remoteSub . ", visitor: ". $visitorIP . "<br>";
//    echo "localDef: " . $localDef . ", vpnDef: " . $vpnDef . "<br>";
    $isLocal = false;
    $isLocal = ($serverSub == $remoteSub);
    if(!$isLocal) {
        if ($visitorIP == gethostbyname($useDomain)) {
            $isLocal = true;
        }
    }
    if(!$isLocal) {
        $isLocal = (in_array($visitorIP, $localDef));
    }
    if(!$isLocal) {
        $isLocal = (in_array($visitorSub, $localDef));
    }
    if(!$isLocal) {
        if (in_array($visitorSub, $vpnDef) || in_array($visitorIP, $vpnDef)) {
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
    if(isset($_SERVER['HTTPS'])) {
	    return "https";
    }
    return "http";
}
?>
