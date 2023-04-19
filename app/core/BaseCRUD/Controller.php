<?php

/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: _date_
 * Time: _heure_
 */

namespace _namespace_;

use app\core\BaseController;
use app\core\Utils;

class _Name_crud_Controller extends BaseController
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = $this->model("_name_crud_");
    }

    public function list()
    {
        $this->views->getTemplate();
    }

    public function add()
    {
        if(count($this->paramPOST) > 0) {
            $result = $this->model->set(["table"=>"_name_crud_table_","champs"=>$this->paramPOST]);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        Utils::redirect("_name_crud_", "list");
    }

    public function update()
    {
        if(count($this->paramPOST) > 0) {
            $param['table'] = "_name_crud_table_";
            $param['condition'] = ["id = "=>$this->paramPOST['id']];
            unset($this->paramPOST['id']);
            $param['champs'] = $this->paramPOST;
            $result = $this->model->set($param);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        Utils::redirect("_name_crud_", "list");
    }

    public function delete()
    {
        if(isset($this->paramGET[0])) {
            $param['table'] = "_name_crud_table_";
            $param['condition'] = ["id = "=>$this->paramGET[0]];
            $result = $this->model->set($param);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        Utils::redirect("_name_crud_", "list");
    }

    public function activate()
    {
        if(intval($this->paramGET[0]) > 0) {
            $result = $this->model->set(["table" => "_name_crud_table_", "champs" => ["etat"=>1],"condition" => ["id = "=>$this->paramGET[0]]]);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        Utils::redirect("_name_crud_", "list");
    }

    public function deactivate()
    {
        if(intval($this->paramGET[0]) > 0) {
            $result = $this->model->set(["table" => "_name_crud_table_", "champs" => ["etat"=>0],"condition" => ["id = "=>$this->paramGET[0]]]);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        Utils::redirect("_name_crud_", "list");
    }

    public function _name_crud_Modal()
    {
        $data = [];
        if($this->paramGET[0]) $data['_name_crud_'] = $this->model->get(["table"=>"_name_crud_table_","condition"=>["id = "=>$this->paramGET[0]]])[0];
        _foreign_keys_;
        $this->views->setData($data);
        $this->modal();
    }

    //_add_methods_;

    /**
     * @throws \Jacwright\RestServer\RestException
     */
    public function _name_crud_Processing()
    {
        $param = [
            "button"=> [
                "modal" => [
                    [["_name_crud_/_name_crud_Modal", "_name_crud_/update"],"_name_crud_/_name_crud_Modal","fa fa-edit"]
                ],
                "default" => [
                    ["champ"=>"etat","val"=>[["_name_crud_/activate","fa fa-toggle-off"],["_name_crud_/deactivate","fa fa-toggle-on"]]],
                    ["_name_crud_/delete/","fa fa-trash"]
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
            "fonction"=>[_fonction_processing_]
        ];
        _add_params_;
        $this->processing($this->model, "_name_crud_Processing", $param);
    }
}