<?php

/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 15/02/2017
 * Time: 21:11
 */

namespace app\controllers\admin;

use app\core\BaseController;
use app\core\ConfigFile;
use app\core\Utils;

class ConfigController extends BaseController
{
    private $default;
    private $config;
    private $espace = [];
    private $database = [];
    private $constant = [];
    private $next_db;
    public function __construct()
    {
        parent::__construct();
        $this->config = (array)$this->appConfig;
        $this->views->setData(['appConfig'=>$this->config]);
        $this->default = [
            "APP"=>[
                "\n;Variable d'environnement",
                "projet"=>$this->appConfig->projet,
                "env"=>$this->appConfig->env,
                "log"=>($this->appConfig->log == "1" ? "on" : "off"),
                "profile_level"=>$this->appConfig->profile_level,
                "law_generate"=>($this->appConfig->law_generate == "1" ? "on" : "off"),
                "mail_from"=> $this->appConfig->mail_from,
                "use_api_client"=>($this->appConfig->use_api_client == "1" ? "on" : "off"),
                "session_name"=>$this->appConfig->session_name."\n",

                ";Default page",
                "default_controller"=>"Home",
                "default_action"=>"index\n",

                ";Default template",
                "default_header"=>"header",
                "default_sidebar"=>"sidebar",
                "default_footer"=>"footer\n",
            ]
        ];
        foreach ($this->config as $key => $value){
            if(Utils::startsWith($key, 'space_'))
                $this->espace[str_replace('space_', '', strtolower($key))] = ($value == "1" ? "on" : "off");
            elseif(Utils::startsWith($key, 'CONST_'))
                $this->constant[str_replace('CONST_', '', $key)] = $value;
        }
        foreach ($this->dbConfig as $key => $value) {
            $key = explode("_", $key);
            if(!in_array($key[0], array_keys($this->database))) {
                if(is_null($this->next_db)) {
                    $this->next_db = intval(str_replace("DB", "", $key[0]));
                    $this->next_db = $this->next_db == 0 ? $this->next_db + 1 : $this->next_db;
                }
                elseif($this->next_db < intval(str_replace("DB", "", $key[0])))
                    $this->next_db = intval(str_replace("DB", "", $key[0]));
                $this->database[$key[0]] = [];
            }
            $this->database[$key[0]][implode("_", $key)] = $value;
        }
        $this->next_db++;
        $this->views->initTemplate(["header"=>"header", "sidebar"=>"sidebar-config", "footer"=>"footer"]);
    }

    public function index()
    {
        $this->views->getTemplate();
    }

    public function spaceModal()
    {
        $this->modal();
    }

    public function databaseModal()
    {
        if(count($this->paramGET) > 0) {
            $data['nbr_db'] = intval(str_replace("DB", "", $this->paramGET[0]));
            $data['nbr_db'] = $data['nbr_db'] == 0 ? '' : $data['nbr_db'];
            $data['currentDB'] = $this->database[$this->paramGET[0]];
        }
        else $data['nbr_db'] = $this->next_db;
        $this->views->setData($data);
        $this->modal();
    }

    public function constantModal()
    {
        if(count($this->paramGET) > 0)
            $data['currentConst'] = ["name"=>$this->paramGET[0], "value"=>$this->constant[$this->paramGET[0]]];
        $data['constant'] = $this->constant;
        unset($data['constant'][$this->paramGET[0]]);
        $data['constant'] = array_keys($data['constant']);
        $this->views->setData($data);
        $this->modal();
    }

    public function space()
    {
        $this->views->setData(['espace'=>$this->espace]);
        $this->views->getTemplate();
    }

    public function constant()
    {
        if(count($this->paramPOST) == 2) {
            $this->constant[strtoupper($this->paramPOST["name"])] = $this->paramPOST["value"];
            $this->setConstant();
            $this->setEspace();
            $this->writeConfig();
            Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            Utils::redirect("config", "constant");
            exit();
        }
        $this->views->setData(['constant'=>$this->constant]);
        $this->views->getTemplate();
    }

    public function deleteConstant()
    {
        if(isset($this->constant[strtoupper($this->paramGET[0])])) {
            unset($this->constant[strtoupper($this->paramGET[0])]);
            $this->setConstant();
            $this->setEspace();
            $this->writeConfig();
            Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
        }else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        Utils::redirect("config", "constant");
    }

    public function database()
    {
        $this->views->setData(['database'=>$this->database]);
        $this->views->getTemplate();
    }

    public function updateConfig()
    {
        foreach ($this->paramPOST as $key => $value) $this->default["APP"][$key] = $value;
        $this->setConstant();
        $this->setEspace();
        $this->writeConfig();
        Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
        Utils::redirect("config", "index");
    }

    public function addSpace()
    {
        $espace = strtolower($this->paramPOST['space']);
        $this->espace[$espace] = "on";
        $this->setConstant();
        $this->setEspace();
        $this->writeConfig();
        $this->addSpaceDep($espace);
        Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
        Utils::redirect("config", "space");
    }

    public function addDatabase()
    {
        if(isset($this->paramPOST['id_db'])) {
            $db = $this->paramPOST;
            $db[$db['id_db'].'_PREFIX'] = isset($db[$db['id_db'].'_PREFIX']) ? $db[$db['id_db'].'_PREFIX'] : '';
            if($db[$db['id_db'].'_TYPE'] == 'sqlite'){
                unset($db[$db['id_db'].'_HOST']);
                unset($db[$db['id_db'].'_USER']);
                unset($db[$db['id_db'].'_PASSWORD']);
            }
            unset($db['id_db']);
            $this->database[$this->paramPOST['id_db']] = $db;
            $ini = new \app\core\ConfigFile('config/db.config.ini', 'Configuration pour la base de données');
            $ini->add_array($this->database);
            $ini->write();
        }else{
            $create = (isset($this->paramPOST['create']) && $this->paramPOST['DB_TYPE'] == 'mysql');
            unset($this->paramPOST['create']);
            $this->database["DB".$this->next_db] = $this->paramPOST;
            $ini = new \app\core\ConfigFile('config/db.config.ini', 'Configuration pour la base de données');
            $ini->add_array($this->database);
            $ini->write();
            if($create) {
                try {
                    \app\core\Database::create($this->paramPOST["DB".$this->next_db."_NAME"]);
                } catch (\PDOException $e) {
                    header('Location: '.WEBROOT.'error/'.base64_encode($e->getMessage()));
                    exit(500);
                } catch (\Jacwright\RestServer\RestException $e) {}
            }
        }
        Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
        Utils::redirect("config", "database");
    }

    public function deleteDatabase()
    {
        if(isset($this->database[$this->paramGET[0]])){
            unset($this->database[$this->paramGET[0]]);
            $ini = new \app\core\ConfigFile('config/db.config.ini', 'Configuration pour la base de données');
            $ini->add_array($this->database);
            $ini->write();
            Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
        }else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        Utils::redirect("config", "database");
    }

    private function addSpaceDep($espace){

        Utils::createDir("app/controllers/$espace");
        Utils::createDir("app/language/$espace");
        Utils::createDir("app/models/$espace");
        Utils::createDir("app/views/$espace/home");
        Utils::createDir("app/views/$espace/template");

        $data = file_get_contents(ROOT."app/core/BaseSpace/HomeController.txt");
        $data = str_replace("_espace_", $espace, $data);
        file_put_contents(ROOT."app/controllers/$espace/HomeController.php", $data);

        $data = file_get_contents(ROOT."app/core/BaseSpace/HomeModel.txt");
        $data = str_replace("_espace_", $espace, $data);
        file_put_contents(ROOT."app/models/$espace/HomeModel.php", $data);

        $data = file_get_contents(ROOT."app/core/BaseSpace/index.txt");
        $data = str_replace("_espace_", $espace, $data);
        file_put_contents(ROOT."app/views/$espace/home/index.php", $data);

        copy(ROOT.'app/core/BaseSpace/template/header.php', ROOT."app/views/$espace/template/header.php");
        copy(ROOT.'app/core/BaseSpace/template/sidebar.php', ROOT."app/views/$espace/template/sidebar.php");
        copy(ROOT.'app/core/BaseSpace/template/footer.php', ROOT."app/views/$espace/template/footer.php");

        copy(ROOT.'app/core/BaseSpace/fr.lang', ROOT."app/language/$espace/fr.lang");
    }

    private function deleteSpaceDep($espace){
        rmdir(ROOT."app/controllers/$espace");
        rmdir(ROOT."app/language/$espace");
        rmdir(ROOT."app/models/$espace");
        rmdir(ROOT."app/views/$espace");
        $tab = [ROOT."app/controllers/$espace", ROOT."app/language/$espace", ROOT."app/models/$espace", ROOT."app/views/$espace"];
        foreach ($tab as $item) {
            if(is_dir($item)){
                $dir = $item;
                $dir_iterator = new \RecursiveDirectoryIterator($dir);
                $iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::CHILD_FIRST);
                foreach($iterator as $fichier) $fichier->isDir() ? rmdir($fichier) : unlink($fichier);
                rmdir($dir);
            }
        }
    }
    
    public function stateSpace()
    {
        $espace = $this->espace[$this->paramGET[0]];
        if(!is_null($espace) && ($espace == 'on' || $espace == 'off')) {
            $this->espace[$this->paramGET[0]] = (($espace == "on") ? "off" : "on");
            $this->setConstant();
            $this->setEspace();
            $this->writeConfig();
            Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
        }
        else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        Utils::redirect("config", "space");
    }

    public function deleteSpace()
    {
        $espace = $this->espace[$this->paramGET[0]];
        if(!is_null($espace) && ($espace == 'on' || $espace == 'off')) {
            unset($this->espace[$this->paramGET[0]]);
            $this->setConstant();
            $this->setEspace();
            $this->writeConfig();
            $this->deleteSpaceDep($this->paramGET[0]);
            Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
        }
        else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        Utils::redirect("config", "space");
    }

    private function writeConfig() {
        $ini = new ConfigFile('config/app.config.ini', 'Fichier de configuration');
        $ini->add_array($this->default);
        $ini->write();
    }

    private function setEspace() {
        if(count($this->espace) > 0) {
            $this->default['APP'][] = ";Define space";
            foreach ($this->espace as $key => $value)
                $this->default['APP']["space_$key"] = $value;
            $this->default['APP']["space_$key"] = $value."\n";

            foreach ($this->espace as $key => $value){
                $this->default['APP'][] = ";$key page";
                $this->default['APP'][$key."_controller"] = isset($this->config[$key."_controller"]) ? $this->config[$key."_controller"] : "Home";
                $this->default['APP'][$key."_action"] = isset($this->config[$key."_action"]) ? $this->config[$key."_action"]."\n" : "index\n";
            }

            foreach ($this->espace as $key => $value){
                $this->default['APP'][] = ";$key template";
                $this->default['APP'][$key."_header"] = isset($this->config[$key."_header"]) ? $this->config[$key."_header"] : "header";
                $this->default['APP'][$key."_sidebar"] = isset($this->config[$key."_sidebar"]) ? $this->config[$key."_sidebar"] : "sidebar";
                $this->default['APP'][$key."_footer"] = isset($this->config[$key."_footer"]) ? $this->config[$key."_footer"]."\n" : "footer\n";
            }
        }
    }

    private function setConstant() {
        if(count($this->constant) > 0) {
            $this->default['APP'][] = ";Define constant";
            foreach ($this->constant as $key => $value)
                $this->default['APP']["CONST_".strtoupper($key)] = $value;
            $this->default['APP']["CONST_".strtoupper($key)] = $value."\n";
        }
    }
}