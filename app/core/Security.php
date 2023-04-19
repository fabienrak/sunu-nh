<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 17/08/2017
 * Time: 11:01
 */

namespace app\core;

use Defuse\Crypto\Key;

abstract class Security
{
    protected $url;
    protected $appConfig;

    /**
     * Security constructor.
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function __construct()
    {
        define('RACINE', \str_replace(str_replace("url=", "", $_SERVER["QUERY_STRING"]), "", $_SERVER["REQUEST_URI"]));
        define('ASSETS', RACINE."assets/");
        define('ROOT', \str_replace('app/core', '', __DIR__));
        $this->appConfig = \parse_ini_file(ROOT . 'config/app.config.ini');
        define('ENV', $this->appConfig['env']);
        define('PROFIL_ATT', (isset($this->appConfig['profil_attribut']) ? $this->appConfig['profil_attribut'] : 'sf_profil_id'));
        define('PROJET', $this->appConfig['projet']);

        if(!isset($this->appConfig['CONST_KEY_TOKEN'])) {
            Utils::addAppConfigConstant("KEY_TOKEN", Utils::getIntegerUnique(25));
            $this->appConfig = \parse_ini_file(ROOT . 'config/app.config.ini');
        }
        if(!isset($this->appConfig['CONST_KEY_CRYPT'])) {
            $key_crypt = Key::createNewRandomKey();
            $key_crypt = $key_crypt->saveToAsciiSafeString();
            Utils::addAppConfigConstant("KEY_CRYPT", $key_crypt);
            $this->appConfig = \parse_ini_file(ROOT . 'config/app.config.ini');
        }

        foreach ($this->appConfig as $key => $value) {
            if (Utils::startsWith($key, 'CONST_')) {
                $key = \strtoupper(\str_replace('CONST_', '', $key));
                define($key, $value);
            }
        }

        if(ENV == "DEV"){
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        }
        $this->url = $this->parseUrl();

        if(!(strtolower($this->url[0]) === "client" || strtolower($this->url[0]) === "server")) $this->getToken();

        header('X-Frame-Options: DENY'); // FF 3.6.9+ Chrome 4.1+ IE 8+ Safari 4+ Opera 10.5+
        header('Cache-control: private'); // IE 6 FIXheader('Cache-control: private'); // IE 6 FIX
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: *");
        header('Access-Control-Allow-Credentials: true');

        date_default_timezone_set('Africa/Dakar');
        error_reporting(-1);
        if(count($_POST) > 0) $_POST = $this->setSecurite_xss_array($_POST);
    }

    /**
     * @param $string
     * @return string
     */
    protected function setSecurite_xss($string)
    {
        $string = htmlspecialchars($string);
        $string = strip_tags($string);
        return $string;
    }

    /**
     * @param $array
     * @return array
     */
    protected function setSecurite_xss_array($array)
    {
        if(is_array($array)){
            foreach ($array as $key => $value){
                if(!\is_array($value)) $array[$key] = self::setSecurite_xss($value);
                else self::setSecurite_xss_array($value);
            }
        }else $array = htmlentities($array);
        return $array;
    }

    private function getToken() {
        if(Session::existeAttribut("_token_")) {
            if(Session::getAttributArray("_token_")["used"] == 1) {
                $token = ["name"=>Utils::random(25),"value"=>Utils::random(256),"used"=>0];
                Session::setAttributArray("_token_",$token);
                Session::setAttribut("token",sprintf('<input type="hidden" name="%s" value="%s" />', $token["name"], Utils::getPassCrypt($token["value"])));
            }
        }else {
            $token = ["name"=>Utils::random(25),"value"=>Utils::random(256),"used"=>0];
            Session::setAttributArray("_token_",$token);
            Session::setAttribut("token",sprintf('<input type="hidden" name="%s" value="%s" />', $token["name"], Utils::getPassCrypt($token["value"])));
        }

    }

    /**
     * @return array
     */
    private function parseUrl()
    {
        $temp = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY);
        $temp = htmlentities($temp);
        if(!isset($_GET['url'])) $url = [$this->appConfig['default_controller'], $this->appConfig['default_action']];
        else {
            $_GET['url'] = htmlentities($_GET['url']);
            $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
            if(\array_key_exists('space_'.$url[0], $this->appConfig) && count($url) === 1)
                $url = [$url[0],$this->appConfig[$url[0].'_controller'], $this->appConfig[$url[0].'_action']];
        }
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
}