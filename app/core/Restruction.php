<?php
/**
 * Created by PhpStorm.
 * User: seeynii.faay
 * Date: 10/4/19
 * Time: 1:08 PM
 */

namespace app\core;

use Jacwright\RestServer\RestException;

class Restruction
{
    protected $espace_law = [];

    protected function addDroitControllers() {
        try {
            Session::setAttribut("total", 0);
            Session::setAttribut("nbr", 0);
            $controllers = $this->getControllers();
            foreach ($controllers as $oneCont) $this->addDroitController($oneCont);
            if(intval(Session::getAttribut("nbr")) > 0) Utils::setMessageALert(["success","configuration des droits ".Session::getAttribut("nbr")."/".Session::getAttribut("total")." ajouté(s)"]);
            Session::destroyAttributSession("total");
            Session::destroyAttributSession("nbr");
        } catch (\ReflectionException $ex) {
            Utils::setMessageError(['000',$ex->getMessage()]);
            Utils::redirect("error", "error", [], "default");
            exit();
        } catch (RestException $ex) {
            Utils::setMessageError(['000',$ex->getMessage()]);
            Utils::redirect("error", "error", [], "default");
            exit();
        }
    }

    /**
     * @param $classe
     * @throws \ReflectionException
     * @throws \Jacwright\RestServer\RestException
     */
    private function addDroitController($classe) {
        $model = new Model();
        $classe = SPACE === "default" ? 'app\controllers\\'.$classe.'Controller' : 'app\controllers\\'.SPACE.'\\'.$classe.'Controller';
        $reflection = new \ReflectionClass($classe);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methods = array_map(function ($item){return  in_array($item->name, ['__construct', 'authorized', 'setParamGET', 'setParamPOST', 'setParamFILE']) || Utils::endsWith($item->name, '__') || Utils::endsWith($item->name, 'Processing') || Utils::endsWith($item->name, 'Modal') ? '' : $item;},$methods);
        $methods = @Utils::setPurgeArray($methods);

        foreach ($methods as $oneMethod) {
            $action = $oneMethod->name;
            $controller = $oneMethod->class;
            $controller = str_replace('Controller', '', $controller);
            $controller = (SPACE === "default") ? str_replace('app\controllers\\', '', $controller) : str_replace('app\controllers\\'.SPACE.'\\', '', $controller);
            $doc = $oneMethod->getDocComment();
            if($doc != false) {
                $droit = explode("@droit", $doc);
                $droit = (isset($droit[1])) ? $droit[1] : null;
                if(!is_null($droit)) {
                    Session::setAttribut("total", (intval(Session::getAttribut("total"))+1));
                    if(count($model->get(["table"=>"sf_droit", "champs"=>["id"], "condition"=>["espace ="=>SPACE, "UPPER(controller) ="=>strtoupper($controller),"UPPER(action) ="=>strtoupper($action)]])) === 0){
                        $droit = trim(preg_replace("#\n|\t|\r|\*/|\*#", "",$droit));
                        $droit = explode("-", $droit);
                        $droit[1] = $model->get(["table"=>"sf_sous_module", "champs"=>["id"], "condition"=>["UPPER(code) ="=>strtoupper(trim($droit[1]))]]);
                        $droit[1] = (isset($droit[1][0]->id)) ? $droit[1][0]->id : 0;
                        if(intval($droit[1]) > 0) {
                            $param = [
                                "libelle" => trim($droit[0]),
                                "sous_module_id" => $droit[1],
                                "controller" => $controller,
                                "action" => $action
                            ];
                            if(!is_null(SPACE)) $param['espace'] = SPACE;
                            $param = array_map(function ($one){return trim($one);}, $param);
                            $rst = $model->set(["table"=>"sf_droit", "champs"=>$param]);
                            if($rst !== false) Session::setAttribut("nbr", (intval(Session::getAttribut("nbr"))+1));
                        }
                    }
                }
            }
        }
    }

    /**
     * @return array
     */
    private function getControllers() {
        $controllers = SPACE === "default" ? scandir(ROOT.'app/controllers/') : scandir(ROOT.'app/controllers/'.SPACE);
        $controllers = array_map(function ($item){return Utils::startsWith($item,'.') || Utils::startsWith($item,'Webservice') || Utils::startsWith($item,'Error') || Utils::startsWith($item,'Language') || Utils::startsWith($item,'Config') || !Utils::endsWith($item,'.php') ? '' : str_replace("Controller.php", "", $item);},$controllers);
        return @Utils::setPurgeArray($controllers);
    }

    /**
     * @return array
     */
    protected function addDroitServices() {
        try {
            $microServices = $this->getMicroServices();
            $model = new Model();
            $model->apiCall = true;
            $total = $dothis = 0;
            $except = [];
            foreach ($microServices as $api => $microServicesApi)
                foreach ($microServicesApi as $microService)
                    $this->addDroitService($model, $api, $microService, $total,$dothis,$except);
            return $this->response(['code'=>200, 'error'=>false, 'msg'=>"configuration des droits $dothis/$total ajouté(s)", "data"=>$except]);
        } catch (\ReflectionException $ex) {
            return $this->response(['code'=>500, 'error'=>true, 'msg'=>$ex->getMessage()]);
        } catch (RestException $ex) {
            return $this->response(['code'=>500, 'error'=>true, 'msg'=>$ex->getMessage()]);
        }
    }

    /**
     * @param $model
     * @param $espace
     * @param $microService
     * @param $total
     * @param $dothis
     * @param $except
     * @throws \ReflectionException
     */
    private function addDroitService($model, $espace, $microService, &$total, &$dothis, &$except) {
        if(is_array($microService)) {
            $tempEspace = $microService[1];
            $microService = ucfirst($microService[0]);
        }elseif(is_string($microService)) $microService = ucfirst($microService);

        $espace_ = $espace == "default" ? "" : "\\$espace";
        $microService = "app\webservice$espace_\\$microService";
        $espace = isset($tempEspace) ? $tempEspace : $espace;
        $reflection = new \ReflectionClass($microService);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methods = array_map(function ($item){return in_array($item->name, ['updateEtat', 'getCrud', 'deleteCrud', 'putCrud', 'postCrud','__construct', 'authorize', 'setPatch', 'authorized', 'getServer', 'deleteCash', 'setParamRequest', 'options', 'log', 'response', 'onConstruct', 'setApi', 'setApiServer']) || Utils::endsWith($item->name, '__') || Utils::endsWith($item->name, 'Processing') || Utils::endsWith($item->name, 'Modal') ? '' : $item;},$methods);
        $methods = Utils::setPurgeArray($methods);

        foreach ($methods as $oneMethod) {
            $action = $droit = null;
            $microService = str_replace("app\webservice$espace_\\", '', $oneMethod->class);
            $doc = $oneMethod->getDocComment();
            if(strpos($doc, "@droit") != false) {
                $pattern = "#(@[a-zA-Z]+\s*[a-zA-Z0-9].*)#";
                preg_match_all($pattern, $doc, $droits, PREG_PATTERN_ORDER);
                foreach ($droits[0] as $item) {
                    if(Utils::startsWith($item, '@url')) $action = explode("@url", $item)[1];
                    elseif(Utils::startsWith($item, '@droit')) $droit = trim(str_replace("@droit", "", $item));
                }
                $action = str_replace(" ", "", trim($action));

                if(!is_null($droit)) {
                    $total++;
                    if(count($this->espace_law) > 0 && isset($this->espace_law[$espace])) $espace = $this->espace_law[$espace];
                    $rst = $model->get(["table"=>"sf_droit", "champs"=>["id"], "condition"=>["espace ="=>$espace, "UPPER(controller) ="=>strtoupper($microService),"UPPER(action) ="=>strtoupper($action)]]);

                    if($rst['code'] == 200 && count($rst['data']) === 0) {
                        $droit = explode("-", $droit);
                        $libelle = trim($droit[0]);
                        $codeSM = trim($droit[1]);
                        $param = ["table"=>"sf_sous_module sm", "champs"=>["sm.id"], "jointure"=>["INNER JOIN sf_module m ON sm.sf_module_id = m.id"], "condition"=>["UPPER(sm.code) ="=>strtoupper($codeSM)]];
                        $sous_module_id = $model->get($param);

                        if(count($sous_module_id['data']) > 0) {
                            $code_cd = "cd_".sha1("$espace*$microService*$action");
                            $code_law_tab = $model->get(["table"=>"sf_code_droit_generer", "condition"=>["code_gene ="=>$code_cd]]);
                            if($code_law_tab['code'] == 200 && count($code_law_tab['data']) > 0) {
                                $code_param = $code_law_tab['data'][0]->code_droit;
                                $model->set(["table"=>"sf_droit", "condition"=>["code ="=>$code_param]]);
                            }else {
                                for($i = 1 ; 1 ; $i++) {
                                    $code_param = $codeSM."_$i";
                                    $code_param = $model->get(["table"=>"sf_droit", "condition"=>["code ="=>$code_param]]);
                                    if(count($code_param["data"]) == 0) {
                                        $code_param = $codeSM."_$i";
                                        break;
                                    }
                                }
                            }
                            $sous_module_id = $sous_module_id['data'][0]->id;
                            $param = [
                                "code" => $code_param,
                                "libelle" => $libelle,
                                "sous_module_id" => $sous_module_id,
                                "espace" => $espace,
                                "controller" => $microService,
                                "action" => $action
                            ];
                            $param = array_map(function ($one){return trim($one);}, $param);

                            $rst = $model->set(["table"=>"sf_droit", "champs"=>$param]);

                            if($rst['code'] == 201) {
                                $dothis++;
                                if(!($code_law_tab['code'] == 200 && count($code_law_tab['data']) > 0))
                                    $model->set(["table"=>"sf_code_droit_generer", "champs"=>["code_gene"=>$code_cd, "code_droit"=>$code_param]]);
                            } else array_push($except, "error:insert * [$espace/$microService/$action] $libelle - $codeSM - ".\json_encode($rst));
                        }else array_push($except, "error:code_sous_module * [$espace/$microService/$action] $libelle - $codeSM");
                    }
                }
            }
        }
    }

    /**
     * @return array
     */
    private function getMicroServices() {
        $tab = [];
        $services = Utils::getContentDir(ROOT.'app/webservice/');
        foreach ($services as $item) {

            if(is_dir(ROOT . "app/webservice/$item")) {
                $temp = Utils::getContentDir(ROOT . "app/webservice/$item");
                foreach ($temp as $item2)
                    if(is_file(ROOT . "app/webservice/$item/$item2"))
                        $tab[$item][] = ((count($this->espace_law) > 0 && in_array($item, array_keys($this->espace_law)))) ? [strtolower(str_replace(".php", "", $item2)), $this->espace_law[$item]] : strtolower(str_replace(".php", "", $item2));
            }elseif(is_file(ROOT . "app/webservice/$item"))
                $tab["default"][] = ((count($this->espace_law) > 0 && in_array("default", array_keys($this->espace_law)))) ? [strtolower(str_replace(".php", "", $item)), $this->espace_law["default"]] : strtolower(str_replace(".php", "", $item));
        }
        return $tab;
    }

    use Response;
}

