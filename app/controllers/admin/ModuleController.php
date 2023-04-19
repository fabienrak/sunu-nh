<?php

/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 06/11/2020
 * Time: 00:47
 */

namespace app\controllers\admin;

use app\core\BaseController;
use app\core\Utils;

class ModuleController extends BaseController
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = $this->model("module");
    }

    public function list()
    {
        $this->views->getTemplate();
    }

    public function add()
    {
        if(count($this->paramPOST) > 0) {
            $result = $this->model->set(["table"=>"sf_module","champs"=>$this->paramPOST]);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        Utils::redirect("module", "list");
    }

    public function update()
    {
        if(count($this->paramPOST) > 0) {
            $param['table'] = "sf_module";
            $param['condition'] = ["id = "=>$this->paramPOST['id']];
            unset($this->paramPOST['id']);
            $param['champs'] = $this->paramPOST;
            $result = $this->model->set($param);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        Utils::redirect("module", "list");
    }

    public function delete()
    {
        if(isset($this->paramGET[0])) {
            $param['table'] = "sf_module";
            $param['condition'] = ["id = "=>$this->paramGET[0]];
            $result = $this->model->set($param);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        Utils::redirect("module", "list");
    }

    public function activate()
    {
        if(intval($this->paramGET[0]) > 0) {
            $result = $this->model->set(["table" => "sf_module", "champs" => ["etat"=>1],"condition" => ["id = "=>$this->paramGET[0]]]);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        Utils::redirect("module", "list");
    }

    public function deactivate()
    {
        if(intval($this->paramGET[0]) > 0) {
            $result = $this->model->set(["table" => "sf_module", "champs" => ["etat"=>0],"condition" => ["id = "=>$this->paramGET[0]]]);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        Utils::redirect("module", "list");
    }

    public function moduleModal()
    {
        $data = [];
        if($this->paramGET[0]) $data['module'] = $this->model->get(["table"=>"sf_module","condition"=>["id = "=>$this->paramGET[0]]])[0];
        
        $this->views->setData($data);
        $this->modal();
    }

    

    /**
     * @throws \Jacwright\RestServer\RestException
     */
    public function moduleProcessing()
    {
        $param = [
            "button"=> [
                "modal" => [
                    [["module/moduleModal", "module/update"],"module/moduleModal","fa fa-edit"]
                ],
                "default" => [
                    ["champ"=>"etat","val"=>[["module/activate","fa fa-toggle-off"],["module/deactivate","fa fa-toggle-on"]]],
                    ["module/delete/","fa fa-trash"]
                ],
                "custom" => []
            ],
            "tooltip"=> [
                "modal" => [
                    "Modifier"
                ],
                "default" => [
                    ["champ"=>"etat","val"=>["Activer","Desactiver"]],
                    "Supprimer"
                ]
            ],
            "classCss"=> [
                "modal" => [],
                "default" => [null, "confirm"]
            ],
            "attribut"=> [
                "modal" => [],
                "default" => []
            ],
            "args"=>null,
            "dataVal"=>[
                ["champ"=>"etat","val"=>[["<span style='.temp::before{text-align: right;}' class='temp text-warning'>Désactiver</span>"],["<span  class='temp text-success' >Activer</span><style>.temp::before{text-align: right;}</style>"]]]
            ],
            "fonction"=>[]
        ];
        
        $this->processing($this->model, "moduleProcessing", $param);
    }
}