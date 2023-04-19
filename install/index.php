<?php

if(!file_exists('.htaccess')) {
    $data = "Options -MultiViews
RewriteEngine On
RewriteBase ".$_SERVER['REQUEST_URI']."
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]";
    file_put_contents(".htaccess", $data);
}
require_once '../vendor/autoload.php';
require_once ('template.php');
if(file_exists(ROOT.'.htaccess')) $url[0] = 'finish';
template($url[0]);