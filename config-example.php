<?php
$useTitle = "My Home Page";
$useDomain = "my.example.com";
$cacheBust = false;
$searchPrefix = "https://www.bing.com/search?q=";
$localDef = array("192.168.1");
$vpnDef = array("10.7.204", "172.185.32.1");
$LauncherIcons = array(
    (object) [
        'img' => 'icons/mail.png',
        'caption' => 'Mail',
        'link' => 'https://mail.google.com',
        'access' => 'any',
        'protocol' => 'any'
    ],
    (object) [
        'img' => 'icons/contacts.png',
        'caption' => 'Address Book',
        'link' => 'https://contacts.google.com',
        'access' => 'remote',
        'protocol' => 'https'
    ],
    (object) [
        'img' => 'icons/Music.png',
        'caption' => 'Pandora',
        'link' => 'https://www.pandora.com',
        'access' => array ('local', 'vpn'),
        'protocol' => 'http'
    ]
);
?>
