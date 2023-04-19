<?php
/**
 * Created by PhpStorm.
 * User: seeynii.faay
 * Date: 10/22/19
 * Time: 10:31 AM
 */

namespace app\core;

trait Response
{
    public function log($response) {
        if(isset($_SERVER['HTTP_TOKEN'])){
            $response = (array)$response;
            $appConfig = \parse_ini_file(ROOT . 'config/app.config.ini');
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
            $paramRequest = Input::all();
            unset($paramRequest['url']);
            $_USER = TokenJWT::decode($_SERVER['HTTP_TOKEN'], KEY_TOKEN);

            if($appConfig['log'] == 1) {
                $microService = $this->microService == 'default' ? "app\webservice\\".$this->api : "app\webservice\\".$this->microService."\\".$this->api;
                if(class_exists($microService)) {
                    $reflection = new \ReflectionClass($microService);
                    $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
                    $methods = Utils::setPurgeArray(array_map(function ($item) use($microService) {return $item->class == $microService ? $item : ''; },$methods));
                    $model = new Model();
                    $model->apiCall = true;
                    $idModule = $action = null;
                    foreach ($methods as $key => $item) {
                        $doc = $item->getDocComment();
                        if(strpos($doc, "@droit") != false) {
                            $item = trim(preg_replace("#@|\t|\r|\*/|/|\*#", "", $item));
                            $item = trim(preg_replace("#\n#", "*", $item));
                            $item = explode('*', $item);
                            $item = Utils::setPurgeArray($item);
                            foreach ($item as $item2) {
                                if(str_replace(" ", "/", $item2) == "url/".$_SERVER['REQUEST_METHOD']."/".$this->service) {
                                    foreach ($item as $item3) {
                                        if(Utils::startsWith($item3, 'droit')) {
                                            $item3 = explode("-", $item3);
                                            $action = trim(str_replace("droit", "", $item3[0]));
                                            $item3 = trim($item3[1]);
                                            $idModule = $model->get(['table'=>'module m', 'champs'=>['m.id'], 'jointure'=>['INNER JOIN sous_module sm ON sm.module_id = m.id'], 'condition'=>['sm.code ='=>$item3]]);
                                            $idModule = $idModule['data'][0]->{'id'};
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if(!is_null($idModule)) {
                        $params = [];
                        $params['request_url'] = $_SERVER['REQUEST_METHOD']." | http://".$_SERVER['HTTP_HOST']."/".$_SERVER['REQUEST_URI'];
                        $params['request_params'] = serialize($paramRequest);
                        $response["data"] = [];
                        $params['response'] = serialize($response);
                        $params['utilisateur_id'] = $_USER->id;
                        $params['action'] = $action;
                        $params['module_id'] = $idModule;
                        $model->set(["table"=>"sf_log_api", "champs"=>$params]);
                    }
                }
            }
        }
    }

    public function response($params = []) {

        $this->lang_choice = $this->request_headers["lang"];
        $this->lang = Language::getLang($this->lang_choice);
        if(!is_array($params)) $params = (array)$params;

        $response = $params;
        if(!isset($_SERVER['HTTP_PROCESS'])) {
            $response = [];
            $response['code'] = isset($params['code']) ? $params['code'] : 200;
            $response['error'] = isset($params['error']) ? $params['error'] : true;
            $response['msg'] = !isset($params['msg']) ? $this->lang[$response['code']] : $params['msg'];
            $response['data'] = isset($params['data']) ? $params['data'] : [];
        }
        return $response;
    }
}