<?php

/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 06/11/2020
 * Time: 00:59
 */

namespace app\controllers\admin;

use app\core\BaseController;
use app\core\Utils;

class ProfilController extends BaseController
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = $this->model("profil");
    }

    public function list()
    {
        $this->views->getTemplate();
    }

    public function add()
    {
        if(count($this->paramPOST) > 0) {
            $result = $this->model->set(["table"=>"sf_profil","champs"=>$this->paramPOST]);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        Utils::redirect("profil", "list");
    }

    public function update()
    {
        if(count($this->paramPOST) > 0) {
            $param['table'] = "sf_profil";
            $param['condition'] = ["id = "=>$this->paramPOST['id']];
            unset($this->paramPOST['id']);
            $param['champs'] = $this->paramPOST;
            $result = $this->model->set($param);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        Utils::redirect("profil", "list");
    }

    public function delete()
    {
        if(isset($this->paramGET[0])) {
            $param['table'] = "sf_profil";
            $param['condition'] = ["id = "=>$this->paramGET[0]];
            $result = $this->model->set($param);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        Utils::redirect("profil", "list");
    }

    public function activate()
    {
        if(intval($this->paramGET[0]) > 0) {
            $result = $this->model->set(["table" => "sf_profil", "champs" => ["etat"=>1],"condition" => ["id = "=>$this->paramGET[0]]]);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        Utils::redirect("profil", "list");
    }

    public function deactivate()
    {
        if(intval($this->paramGET[0]) > 0) {
            $result = $this->model->set(["table" => "sf_profil", "champs" => ["etat"=>0],"condition" => ["id = "=>$this->paramGET[0]]]);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        Utils::redirect("profil", "list");
    }

    public function profilModal()
    {
        $data = [];
        if($this->paramGET[0]) $data['profil'] = $this->model->get(["table"=>"sf_profil","condition"=>["id = "=>$this->paramGET[0]]])[0];
        
        $this->views->setData($data);
        $this->modal();
    }

    public function affectation()
    {
        $data['idProfil'] = $this->paramGET[0];
        $param = ["table"=>"sf_profil","condition" => ["id = " => $this->paramGET[0]]];
        $result = $this->model->get($param);

        if(count($result) == 0) Utils::redirect("profil", "list");
        else $result = $result[0];

        $data['nomProfil'] = $result->profil;
        $param = [
            'table'=>'sf_droit d',
            'champs'=>['d.id', 'd.libelle as droit', 'sm.libelle as sous_module', 'm.libelle as module', 'd.id AS id_aff', 'd.id AS etat_aff'],
            'jointure'=> [
                'INNER JOIN sf_sous_module sm on d.sf_sous_module_id = sm.id',
                'INNER JOIN sf_module m on sm.sf_module_id = m.id'
            ],
            'condition'=> ['d.libelle NOT IN (?, ?)'],
            'value'=> ['Affecter les droits des utilisateurs', 'Lister les droits des utilisateurs']
        ];
        $data['droit'] = $this->model->get($param);

        foreach ($data['droit'] as $key => $droit) {
            $param = [
                'table'=>'sf_affectation_droit',
                'champs'=>['id', 'etat'],
                'condition'=>['sf_profil_id ='=>$this->paramGET[0],'sf_droit_id ='=>$droit->id]
            ];
            $temp = $this->model->get($param);

            $data['droit'][$key]->id_aff = $temp[0]->id;
            $data['droit'][$key]->etat_aff = $temp[0]->etat;
        }
        $data['droit'] = Utils::setArrayDroit($data['droit']);
        $this->views->setData($data);
        $this->views->getTemplate();
    }

    public function addAffectation()
    {
        $param = [
            'table'=>'sf_droit d',
            'champs'=>['ad.id'],
            'jointure'=>["INNER JOIN sf_affectation_droit ad ON ad.sf_droit_id = d.id"],
            'condition'=>['ad.sf_profil_id ='=>$this->paramPOST['idProfil'], 'ad.etat ='=>1]
        ];
        $data['droit'] = $this->model->get($param);
        if (count($this->paramPOST['update'])>0) {
            foreach ($data['droit'] as $item)
                if (!in_array($item->id, $this->paramPOST['update']))
                    $this->model->set(["table" => "sf_affectation_droit","champs" => ['etat' => 0],"condition" => ['id =' => $item->id]]);

            foreach ($this->paramPOST['update'] as $item)
                $this->model->set(["table" => "sf_affectation_droit","champs" => ['etat' => 1],"condition" => ['id =' => $item]]);

        }elseif(count($data['droit'])>0)
            foreach ($data['droit'] as $item)
                $this->model->set(["table" => "sf_affectation_droit","champs" => ['etat' => 0],"condition" => ['id =' => $item->id]]);

        if (count($this->paramPOST['add'])>0)
            foreach ($this->paramPOST['add'] as $item)
                $this->model->set(["table" => "sf_affectation_droit","champs" => ['sf_profil_id' => $this->paramPOST['idProfil'], 'sf_droit_id' => $item]]);

        Utils::redirect("profil", "affectation", [$this->paramPOST['idProfil']]);
    }

    /**
     * @throws \Jacwright\RestServer\RestException
     */
    public function profilProcessing()
    {
        $param = [
            "button"=> [
                "modal" => [
                    [["profil/profilModal", "profil/update"],"profil/profilModal","fa fa-edit"]
                ],
                "default" => [
                    ["champ"=>"etat","val"=>[["profil/activate","fa fa-toggle-off"],["profil/deactivate","fa fa-toggle-on"]]],
                    ["profil/delete/","fa fa-trash"]
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
        array_push($param["button"]["default"],["profil/affectation/","fa fa-male"]);
        $this->processing($this->model, "profilProcessing", $param);
    }
}