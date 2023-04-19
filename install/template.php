<?php
/**
 * Created by PhpStorm.
 * User: seeynii.faay
 * Date: 8/27/19
 * Time: 9:43 AM
 */

define('RACINE', \str_replace('install/index.php',"", $_SERVER['SCRIPT_NAME']));
define('ASSETS', \str_replace('install/index.php',"assets/", $_SERVER['SCRIPT_NAME']));
define('WEBROOT', \str_replace('index.php',"", $_SERVER['SCRIPT_NAME']));
define('ROOT', \str_replace('install/index.php',"", $_SERVER['SCRIPT_FILENAME']));
define('Prefix_View', 'app/views/');
define('socketConnexion', true);
$composerJson = json_decode(file_get_contents(ROOT."composer.json"));
define('VERSION', $composerJson->version);

function template($page) {

    $url = parseUrl();

    if(file_exists(ROOT . Prefix_View . 'template/header-install.php'))
        require_once (ROOT . Prefix_View . 'template/header-install.php');

    if(file_exists(ROOT . 'install/views/' . $page . '.php')) require_once (ROOT . 'install/views/' . $page . '.php');
    else require_once (ROOT . 'install/views/error.php');

    if(file_exists(ROOT . Prefix_View . 'template/footer-install.php'))
        require_once (ROOT . Prefix_View . 'template/footer-install.php');
}

function parseUrl()
{
    $temp = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY);
    $temp = htmlentities($temp);
    $_GET['url'] = htmlentities($_GET['url']);
    $url = $_GET['url'] !== '' ? explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)) : ['index'];
    if($temp !== null) {
        $temp = explode("&", $temp);
        if(count($temp) > 0){
            foreach ($temp as $item) {
                if(strpos($item, "=") !== false) {
                    $item = explode("=", $item);
                    $url[$item[0]] = $item[1];
                }
            }
        }
    }
    return $url;
}

$url = parseUrl();