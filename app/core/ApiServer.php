<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 17/08/2017
 * Time: 11:01
 */

namespace app\core;

use app\common\CommonApiServer;
use app\core\controllers\ClientController;
use app\core\controllers\ClientSoapController;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use \Jacwright\RestServer\RestException;
use \Defuse\Crypto\Key;

abstract class ApiServer extends Restruction
{
    private   static $server;
    protected $api;
    protected $microService;
    protected $service;
    protected $key_crypt;
    protected $appConfig;
    protected $request_headers;
    protected $url;
    protected $lang;
    protected static $paramRequest  = [];
    public    $params;
    public    $apiClient;
    public    $apiClientSoap;
    public    $key_token;
    public    $token;
    public    $_USER;
    public    $rstGenLaw;
    public    $lang_choice;
    public    $authMessage;


    /**
     * ApiServer constructor.
     * @param null $type
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function __construct($type = null)
    {
        $this->url = explode("/", $_GET['url']);
        $this->url[(count($this->url)-2)] = ucfirst($this->url[(count($this->url)-2)]);
        $_GET['url'] = implode("/", $this->url);
        array_shift($this->url);
        $this->service = array_pop($this->url);
        $this->api = array_pop($this->url);
        $this->microService = array_pop($this->url);
        $this->microService =  is_null($this->microService) ? "default" : $this->microService;
        $this->url = explode("/", $_GET['url']);
        array_shift($this->url);

        if($type == null) {
            try{
                $mode = (ENV == "PROD") ? 'production' : 'debug';
                self::$server = new RestServer($mode);
                self::$server->format = 'application/json';
                $this->deleteCash();
                $_GET['format'] = "json";
            }catch(\Exception $ex) {
                self::$server->setStatus(500);
                self::$server->sendData($this->response(['code'=>500, 'error'=>true, 'data'=>[$ex->getMessage()]]));
            }
        }else {
            $this->request_headers = getallheaders();
            $header_exclu = ['user-agent', 'accept', 'content-type', 'content-length', 'cache-control', 'postman-token', 'host', 'accept-encoding', 'cookie', 'connection'];
            foreach ($this->request_headers as $key => $item) {
                if(in_array(strtolower($key), $header_exclu)) unset($this->request_headers[$key]);
                else $this->request_headers[strtolower($key)] = $item;
            }

            if(isset($this->request_headers['token'])) {
                $this->token = $this->request_headers['token'];
                unset($this->request_headers['token']);
            }
            $this->request_headers["lang"] = $this->request_headers["lang"] ?? "fr";
            $this->lang_choice = $this->request_headers["lang"];
            $this->lang = Language::getLang($this->lang_choice);

            if(method_exists($this, 'onConstruct')) $this->onConstruct();

            $this->appConfig = \parse_ini_file(ROOT . 'config/app.config.ini');
            $this->key_token = $this->appConfig['CONST_KEY_TOKEN'];
            $this->key_crypt = $this->appConfig['CONST_KEY_CRYPT'];
            if(is_null($this->key_crypt)) {
                $this->key_crypt = Key::createNewRandomKey();
                $this->key_crypt = $this->key_crypt->saveToAsciiSafeString();
                Utils::addAppConfigConstant("KEY_CRYPT", $this->key_crypt);
            }else $this->key_crypt = Utils::getKey_crypt();


            if(in_array("generateLaw", $this->url)){
                if($this->appConfig['law_generate'] == 1) $this->rstGenLaw = $this->addDroitServices();
                elseif(!isset($this->rstGenLaw)) $this->rstGenLaw = $this->response(['code'=>503]);
            }

            $this->appConfig = (object)$this->appConfig;
            if($this->appConfig->use_api_client == "1") {
                $this->apiClient = ClientController::initClient();
                $this->apiClientSoap = ClientSoapController::initClientSoap();
            }

            if($this->appConfig->api_secure == "1") $this->applySecureAPI();
        }
    }

    /**
     * @throws RestException
     */
    public function authorized()
    {
        if(!is_object($this->_USER)) return false;
        if(isset($this->_USER->{"admin"}) && $this->_USER->admin == 1) return true;
        $class = $this->microService !== "default" ? ROOT."app/webservice/".$this->microService."/".$this->api.".php" : ROOT."app/webservice/".$this->api.".php";

        if(file_exists($class)) {
            try {
                $class = $this->microService !== "default" ? 'app\webservice\\'.$this->microService.'\\'.$this->api : 'app\webservice\\'.$this->api;
                $reflection = new \ReflectionClass($class);
                $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
                $tabExclude = ["__construct", "authorize", "authorized", "getserver", "deletecash", "setapi", "setpatch", "setparamrequest", "options", "model", "sendmail", "applysecureapi", "response", "log", "onconstruct"];
                $tabMethods = ["GET", "POST", "PUT", "DELETE"];
                foreach ($methods as $key => $item) $methods[$key] = in_array(strtolower($item->name), $tabExclude) ? '' : $item;
                $methods = array_values(Utils::setPurgeArray($methods));
                $apiAuth = $this->api;
                $tabAuthGlobal = [];
                foreach ($methods as $item) {
                    $item = $item->getDocComment();
                    $item = trim(preg_replace("#@|\t|\r|\*/|/|\*#", "", $item));
                    $item = trim(preg_replace("#\n#", "*", $item));
                    $item = explode('*', $item);
                    $item = Utils::setPurgeArray($item);

                    foreach ($item as $item2) {
                        if(str_replace(" ", "/", $item2) == "url/".$_SERVER['REQUEST_METHOD']."/".$this->service) {

                            foreach ($item as $item3) {
                                if(Utils::startsWith($item3, "authorize") !== false) return true;
                                else{
                                    if(Utils::startsWith($item3, "alias-droit") !== false) {
                                        $item3 = Utils::setPurgeArray(explode("|", array_values(Utils::setPurgeArray(explode("alias-droit", $item3)))[0]));
                                        $tabAuth = [];
                                        foreach ($item3 as $item4) {
                                            foreach ($tabMethods as $oneM) {
                                                if(strpos($item4, " $oneM ") !== false) {
                                                    $apiAuth = explode(" $oneM ", $item4);
                                                    $item4 = "$oneM ".$apiAuth[1];
                                                    $apiAuth = ucfirst($apiAuth[0]);
                                                }
                                            }
                                            $item4 = str_replace(" ", "/", $item4);
                                            $model = new Model();
                                            $model->espace = $this->microService !== "default" ? $this->microService : "default";
                                            if(count($this->espace_law) > 0 && isset($this->espace_law[$model->espace])) $model->espace = $this->espace_law[$model->espace];
                                            $model->apiCall = true;
                                            $model->setUSER($this->_USER);
                                            array_push($tabAuth, $model->authorized($apiAuth, $item4));
                                        }
                                        array_push($tabAuthGlobal, !in_array(false, $tabAuth, true));
                                    }
                                    if(Utils::startsWith($item3, "droit") !== false) {
                                        $item2 = array_values(Utils::setPurgeArray(explode("url", $item2)));
                                        $item2 = str_replace(" ", "/", $item2[0]);

                                        if($item2 == $_SERVER['REQUEST_METHOD']."/".$this->service) {
                                            $model = new Model();
                                            $model->espace = $this->microService !== "default" ? $this->microService : "default";
                                            if(count($this->espace_law) > 0 && isset($this->espace_law[$model->espace])) $model->espace = $this->espace_law[$model->espace];
                                            $model->apiCall = true;
                                            $model->setUSER($this->_USER);
                                            array_push($tabAuthGlobal, $model->authorized($this->api, $item2));
                                        }
                                    }
                                }
                            }
                            return in_array(true, $tabAuthGlobal, true);
                        }
                    }
                }
                return false;
            } catch (\ReflectionException $ex) {
                return false;
            }
        }return false;
    }

    /**
     * @return RestServer
     */
    public function getServer()
    {
        return self::$server;
    }

    public function deleteCash()
    {
        self::$server->refreshCache();
    }

    public function setApi()
    {
        $api = $this->microService !== "default" ? "app\webservice\\".$this->microService."\\".$this->api : "app\webservice\\".$this->api;
        if(!class_exists($api)) {
            self::$server->setStatus(200);
            self::$server->sendData($this->response(['code'=>404, 'error'=>true]));
            exit(0);
        }
        return $api;
    }

    public function setPatch()
    {
        return $this->microService !== "default" ? "server/".$this->microService."/".$this->api : "server/".$this->api;
    }

    public function setParamRequest()
    {
        $paramRequest = Input::all();
        unset($paramRequest['url']);
        self::$paramRequest = $paramRequest;
    }

    public function options(){}

    /**
     * @param $model
     * @param string $espace
     * @return string
     */
    protected function model($model, $espace = SPACE)
    {
        $espace = $espace == "default" ? "app\models\\" : "app\models\\" . $espace . "\\";
        $model = $espace . ucfirst($model) . 'Model';
        if(file_exists(ROOT.str_replace("\\", "/", $model).'.php')){
            $model = new $model();
            $model->apiCall = true;
            $model->setUSER($this->_USER);
            return $model;
        }else {
            self::$server->setStatus(200);
            self::$server->sendData($this->response(['code'=>500, 'error'=>true, 'data'=>'Class '.str_replace("app\\models\\", "", $model).' not found']));
            exit(0);
        }
    }

    /**
     * @param array $param
     * @return bool
     */
    protected function sendMail(array $param)
    {
        if (count($param) > 0) {
            extract($param);
            if (isset($subject) && isset($content) && isset($email)) {
                try {
                    if (isset($data)) extract($data);
                    $mail = new PHPMailer();
                    $mail->SetLanguage($this->request_headers["lang"]);
                    $mail->CharSet = 'UTF-8';
                    $mail->isHTML(true);
                    $mail->setFrom($this->appConfig->mail_from);
                    $mail->addAddress($email);
                    $mail->Subject = $subject;
                    $email->Body = '<html><head><meta charset="utf-8"></head><body>';
                    if (file_exists(ROOT . Prefix_View . $content . '.php')) {
                        ob_start();
                        include(ROOT . Prefix_View . $content . '.php');
                        $mail->Body .= ob_get_clean();
                    } else $mail->Body .= $content;
                    $email->Body .= '</body></html>';
                    if (isset($joint) && count($joint) > 0) {
                        $file = [];
                        $index = 1;
                        foreach ($joint as $onpj) {
                            if ($onpj['path'] == "serveur") {
                                $file["file"] = ROOT . $onpj['content'];
                                $file["ext"] = explode(".", $onpj['content'])[1];
                                $mail->addAttachment($file["file"], $index . '.' . $file["ext"]);
                            } elseif ($onpj['path'] == "generate") {
//                                $file["file"] = $this->views->exportToPdf($onpj['content'], $index, 'S');
//                                $file["ext"] = "pdf";
//                                $mail->addStringAttachment($file["file"], $index . '.' . $file["ext"]);
                            }
                            $index++;
                        }
                    }
                    return $mail->send();
                } catch (Exception $e) {
                    Utils::setMessageError([$e->getMessage()]);
                    Utils::redirect("error", "error", "default");
                    return false;
                }
            }
        }
        return false;
    }

    private function applySecureAPI()
    {
        $remote_ip = $_SERVER['REMOTE_ADDR'];

        if(!(isset($this->request_headers['apikey']) && isset($this->request_headers['secretkey']))){
            $this->getServer()->setStatus(200);
            $this->getServer()->sendData($this->response(["code"=>401, "error"=>true, "msg"=>"Le API_KEY et/ou le SECRET_KEY est(sont) absent(s) dans l'entéte !", "data"=>[]]));
            exit(0);
        }

        $model = new Model();
        $model->apiCall = true;
        $local = $model->get(["table"=>"auth_servers", "condition"=>["UID ="=>API_UID]]);
        $local = $local['data'] ?? [];
        if(!(count($local) > 0)) {
            $this->getServer()->setStatus(200);
            $this->getServer()->sendData($this->response(["code"=>401, "error"=>true, "msg"=>"Les paramétres du serveur avec l'UID '".API_UID."' ne sont pas disponibles !", "data"=>[]]));
            exit(0);
        }elseif($local[0]->state != 1) {
            $this->getServer()->setStatus(200);
            $this->getServer()->sendData($this->response(["code"=>401, "error"=>true, "msg"=>"Le statut est désactivé pour le serveur avec l'UID '".API_UID."'", "data"=>[]]));
            exit(0);
        }

        $remote = $model->get(["table"=>"auth_servers", "condition"=>["api_key ="=>$this->request_headers['apikey'],"secret_key ="=>$this->request_headers['secretkey']]]);
        $remote = $remote['data'] ?? [];
        if(!(count($remote) > 0)) {
            $this->getServer()->setStatus(200);
            $this->getServer()->sendData($this->response(["code"=>401, "error"=>true, "msg"=>"Les paramétres du client avec l'apikey ".$this->request_headers['apikey']." et la secretkey ".$this->request_headers['secretkey']." ne sont pas disponibles !", "data"=>[]]));
            exit(0);
        }elseif($remote[0]->state != 1) {
            $this->getServer()->setStatus(200);
            $this->getServer()->sendData($this->response(["code"=>401, "error"=>true, "msg"=>"Le statut est désactivé pour le client avec l'apikey ".$this->request_headers['apikey']." et la secretkey ".$this->request_headers['secretkey'], "data"=>[]]));
            exit(0);
        }
        $local = $local[0];
        $remote = $remote[0];

        if($this->appConfig->api_ip_restruct == "1") {
            if($remote->adress_ip !== $remote_ip) {
                $this->getServer()->setStatus(200);
                $this->getServer()->sendData($this->response(["code"=>401, "error"=>true, "msg"=>"L'adresse IP ".$remote_ip." n'est pas enregistré avec les paramétres du remote", "data"=>[]]));
                exit(0);
            }
        }
        $autorise = $model->get(["table"=>"server_authorizes", "condition"=>["uid_local_server ="=>$local->uid,"uid_server_lowed ="=>$remote->uid]]);
        $autorise = $autorise['data'] ?? [];
        if(!(count($autorise) > 0)){
            $this->getServer()->setStatus(200);
            $this->getServer()->sendData($this->response(["code"=>401, "error"=>true, "msg"=>"Aucune affectation avec l'uid_local '".$local->uid."' et l'uid_server '".$remote->uid."'", "data"=>[]]));
            exit(0);
        }elseif($autorise[0]->state != 1) {
            $this->getServer()->setStatus(200);
            $this->getServer()->sendData($this->response(["code"=>401, "error"=>true, "msg"=>"Le statut est désactivé pour l'affectation avec l'uid_local '".$local->uid."' et l'uid_server '".$remote->uid."'", "data"=>[]]));
            exit(0);
        }
    }

    use Response;

    use CommonApiServer;
}