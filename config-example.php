<?php
$searchPrefix = "https://www.bing.com/search?q=";
$useDomain = "my.example.com";
$localDef = array ("192", "168", "10");
$vpnDef = array ("172","185","136");
$LauncherIcons = array(
    (object) [
        'img' => 'icons/icon1.png',
        'caption' => 'Mail',
        'link' => 'http://outlook.office365.com',
        'access' => 'any',
        'protocol' => 'any'
    ],
    (object) [
        'img' => 'icons/icon2.png',
        'caption' => 'Icon 2',
        'link' => 'http://example.com',
        'access' => 'remote',
        'protocol' => 'https'
    ],
    (object) [
        'img' => 'icons/icon3.png',
        'caption' => 'Icon 3',
        'link' => 'http://example.com',
        'access' => array ('local', 'vpn'),
        'protocol' => 'http'
    ]
);
?>