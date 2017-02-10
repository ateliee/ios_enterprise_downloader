<?php
session_start();

/**
 * user config
 */
define('LOGIN_USER',"your user name");
define('LOGIN_PASSWORD',"your password");
define('BASE_URL',"https://".$_SERVER["HTTP_HOST"]."/path");
define('BUNDLE_ID',"jp.co.example.******");
define('ADMIN_TITLE',"Enterprise download");

define('LOGIN_SESSION',"login_".LOGIN_USER);
define('TIMEOUT',60*60*24);
define('FLASH_MESSAGE',"flash_".LOGIN_USER);
define('POST_VALUE',"flash_".LOGIN_USER);
define('DOWNLOAD_KEY',md5(date("Ymd").LOGIN_USER));
define('PLIST_URL',BASE_URL."/".DOWNLOAD_KEY."/%s.plist");
define('IPA_URL',BASE_URL."/".DOWNLOAD_KEY."/%s.ipa");

$DOWNLOAD_FILES = [
    "filename" => [
        "btn" => "Download",
        "version" => "1.0.0",
        "name" => "Enterprise",
    ],
];
