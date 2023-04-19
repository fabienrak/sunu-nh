<?php

/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 15/02/2017
 * Time: 19:44
 */

namespace app\core\services;

use app\core\controllers\ErrorController;
use app\core\Security;
use app\core\Utils;

class App extends Security
{
    private $method = null;
    private $controller = null;
    private $params = [];
    
    /**
     * App constructor.
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Jacwright\RestServer\RestException
     */
    public function __construct()
    {
        parent::__construct();

        $paramError = array_values($this->url);
        $this->defineConst();
        if(strtolower($this->url[0]) !== "server") {
            $this->controller = new ErrorController();
            $this->method = 'index';
        }

        $file = sprintf(ROOT . str_replace("\\", "/", Prefix_Controller) . '%sController.php', ucfirst($this->url[0]));
        $controller = $this->url[0];

        if (file_exists($file)) {
            $this->controller = Prefix_Controller . ucfirst($this->url[0]) . 'Controller';
            try {
                $reflection = new \ReflectionClass($this->controller);
            } catch (\ReflectionException $ex) {
                Utils::setMessageError(['000', $ex->getMessage()]);
                Utils::redirect("error", "error", [$ex->getMessage()], "default");
                exit();
            }
            $this->method = !isset($this->url[1]) ? $this->method : $this->url[1];
            $this->controller = new $this->controller();
            unset($this->url[1]);
            if(strtolower($this->url[0]) == "server") $this->method = "api";

            unset($this->url[0]);
            if (method_exists($this->controller, $this->method) || method_exists($this->controller, $this->method . "__")) {
                $this->method = (method_exists($this->controller, $this->method)) ? $this->method : $this->method . "__";

                if( !is_null($this->controller->_USER)
                    && !in_array(strtolower($controller), ['error', 'language', 'webserviceclient', 'webserviceserver', 'install'])
                    && !Utils::endsWith(strtolower($this->method),"processing")
                    && !Utils::endsWith(strtolower($this->method),"modal")
                    && !Utils::endsWith($this->method,"__")) {
                    $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
                    $methods = array_map(function ($item) {return !in_array($item->name, [$this->method]) ? '' : $item;}, $methods);
                    $methods = Utils::setPurgeArray($methods);
                    $methods = array_values($methods);
                    $methods = $methods[0]->getDocComment();
                    $methods = trim(preg_replace("#@|\t|\r|\*/|/|\*#", "", $methods));
                    $methods = trim(preg_replace("#\n#", "*", $methods));
                    $methods = explode('*', $methods);
                    $methods = @Utils::setPurgeArray($methods);

                    if(!in_array("authorize", $methods)) {
                        if(!Utils::authorized($controller,$this->method)) {
                            $param = [];
                            if(count($this->controller->historique["current"]) > 2) {
                                $param = $this->controller->historique["current"];
                                unset($param[0]);unset($param[1]);
                                $param = array_values($param);
                            }
                            Utils::setMessageALert(["warning",$this->controller->lang['accesdenidedtext'], $this->controller->lang['accesdenided']]);
                            Utils::redirect(strtolower($this->controller->historique["current"][0]), $this->controller->historique["current"][1], $param);
                            exit();
                        }
                    }
                }
                if (count($this->url) > 0) foreach ($this->url as $key => $val) (is_int($key)) ? array_push($this->params, $val) : $this->params[$key] = $val;
            } else {
                $this->controller = new ErrorController();
                $this->method = 'index';
                Utils::setMessageError(['404', $paramError]);
            }
        } else Utils::setMessageError(['404', $paramError]);
        try {
            if ((method_exists($this->controller, 'setParamRequest')))
                $this->controller->setParamRequest();
            else{
                if (count($this->params) > 0) $this->controller->setParamGET(Utils::setBase64_decode_array($this->params));
                if (count($_POST) > 0) $this->controller->setParamPOST($_POST);
                if (count($_FILES) > 0) $this->controller->setParamFILE($_FILES);
                $this->controller->setLang();
                $this->controller->setUrl([$controller, $this->method]);
            }
            @call_user_func_array([$this->controller, $this->method], []);
        }
        catch (\Exception $ex) {
            if (method_exists($this->controller, 'getServer')) {
                $this->controller->getServer()->setStatus($ex->getCode());
                $this->controller->getServer()->sendData(['error' => ['code' => $ex->getCode(), 'message' => $ex->getMessage()]]);
                header("Content-Type: application/json");
            } else {
                Utils::setMessageError(['000', $ex->getMessage()]);
                Utils::redirect("error", "error", [$ex->getMessage()], "default");
            }
            exit();
        }
    }

    private function defineConst()
    {
        $coreController = (in_array(strtolower($this->url[0]), ["server", "client", "language", "error"]));
        if (\array_key_exists('space_'.$this->url[0], $this->appConfig)) {
            if(!($this->appConfig['space_'.$this->url[0]] == 1)) {
                Utils::setMessageError(['000', 'Espace '.$this->url[0].' désactivé']);
                Utils::redirect("error", "error", [], "default");
            }
            define('SPACE', $this->url[0]);
            define('WEBROOT', RACINE.SPACE."/");
            define('SESSIONNAME', $this->appConfig['session_name'] . "_" . strtoupper($this->url[0]));
            define('Prefix_Controller', ($coreController ? 'app\core\controllers\\' : 'app\controllers\\'.$this->url[0].'\\'));
            define('Prefix_Model', 'app\models\\'.$this->url[0].'\\');
            define('Prefix_View', 'app/views/' . $this->url[0] . '/');
            define('Prefix_Lang', 'app/language/' . $this->url[0] . '/');
            unset($this->url[0]);
            $this->url = (!empty($this->url)) ? array_values($this->url) : ['Home', 'index'];
        } else {
            define('SPACE', "default");
            define('WEBROOT', RACINE);
            define('SESSIONNAME', $this->appConfig['session_name']);
            define('Prefix_Controller', ($coreController ? 'app\core\controllers\\' : 'app\controllers\\'));
            define('Prefix_Model', 'app\models\\');
            define('Prefix_View', 'app/views/');
            define('Prefix_Lang', "app/language/");
        }
    }
}