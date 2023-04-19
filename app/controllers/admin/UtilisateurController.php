<?php

/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 05/11/2020
 * Time: 16:11
 */

namespace app\controllers\admin;

use app\core\BaseController;
use app\core\Utils;

class UtilisateurController extends BaseController
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = $this->model("utilisateur");
    }

    public function list()
    {
        $this->views->getTemplate();
    }

    public function add()
    {
        if(count($this->paramPOST) > 0) {
            $result = $this->model->set(["table"=>"sf_user","champs"=>$this->paramPOST]);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        Utils::redirect("utilisateur", "list");
    }

    public function update()
    {
        if(count($this->paramPOST) > 0) {
            $param['table'] = "sf_user";
            $param['condition'] = ["id = "=>$this->paramPOST['id']];
            unset($this->paramPOST['id']);
            $param['champs'] = $this->paramPOST;
            $result = $this->model->set($param);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        Utils::redirect("utilisateur", "list");
    }

    public function delete()
    {
        if(isset($this->paramGET[0])) {
            $param['table'] = "sf_user";
            $param['condition'] = ["id = "=>$this->paramGET[0]];
            $result = $this->model->set($param);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        Utils::redirect("utilisateur", "list");
    }

    public function activate()
    {
        if(intval($this->paramGET[0]) > 0) {
            $result = $this->model->set(["table" => "sf_user", "champs" => ["etat"=>1],"condition" => ["id = "=>$this->paramGET[0]]]);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        Utils::redirect("utilisateur", "list");
    }

    public function deactivate()
    {
        if(intval($this->paramGET[0]) > 0) {
            $result = $this->model->set(["table" => "sf_user", "champs" => ["etat"=>0],"condition" => ["id = "=>$this->paramGET[0]]]);
            if($result !== false) Utils::setMessageALert(["success",$this->lang["actionsuccess"]]);
            else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        }
        else Utils::setMessageALert(["error",$this->lang["actionechec"]]);
        Utils::redirect("utilisateur", "list");
    }

    public function utilisateurModal()
    {
        $data = [];
        if($this->paramGET[0]) $data['utilisateur'] = $this->model->get(["table"=>"sf_user","condition"=>["id = "=>$this->paramGET[0]]])[0];
        $data["profil"] = $this->model->get(["table"=>"sf_profil"]);
        $this->views->setData($data);
        $this->modal();
    }

    public function affectation()
    {
        $data['idUser'] = $this->paramGET[0];
        $param = [
            "table" => "sf_user u",
            "champs" => ["u.id","u.prenom","u.nom","u.email","p.id as idProfil","p.libelle as profil","u.etat"],
            "jointure" => ["INNER JOIN sf_profil p ON u.sf_profil_id = p.id"],
            "condition" => ["u.id = " => $data['idUser']]
        ];
        $result = $this->model->get($param);

        if(count($result) == 0) Utils::redirect("utilisateur", "list");
        else $result = $result[0];

        $data['idProfil'] = $result->idProfil;
        $data['nomProfil'] = $result->profil;
        $param = [
            'table'=>'sf_droit d',
            'champs'=> ['d.id', 'd.libelle as droit', 'sm.libelle as sous_module', 'm.libelle as module', 'd.id AS id_aff', 'd.id AS etat_aff', 'd.id AS id_aff_user', 'd.id AS etat_aff_user'],
            'jointure'=> [
                'INNER JOIN sf_sous_module sm on d.sf_sous_module_id = sm.id',
                'INNER JOIN sf_module m on sm.sf_module_id = m.id',
                'INNER JOIN sf_affectation_droit ad on ad.sf_droit_id = d.id'
            ],
            'condition'=> ['ad.sf_profil_id = '=>$data['idProfil'], 'ad.etat = '=>1]
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

            $param = [
                'table'=>'sf_affectation_droit_user',
                'champs'=>['id', 'etat'],
                'condition'=>['sf_affectation_droit_id ='=>$data['droit'][$key]->id_aff,'sf_user_id ='=>$data['idUser']]
            ];
            $temp = $this->model->get($param);
            $data['droit'][$key]->id_aff_user = $temp[0]->id;
            $data['droit'][$key]->etat_aff_user = $temp[0]->etat;
        }
        $data['droit'] = Utils::setArrayDroit($data['droit'], $this->appConfig->profile_level);
        $this->views->setData($data);
        $this->views->getTemplate();
    }

    public function addAffectation()
    {
        $param = [
            'table'=>'sf_droit d',
            'champs'=>['adu.id'],
            'jointure'=>[
                "INNER JOIN sf_affectation_droit ad ON ad.sf_droit_id = d.id",
                "INNER JOIN sf_affectation_droit_user adu on adu.sf_affectation_droit_id = ad.id"
            ],
            'condition'=>['ad.sf_profil_id ='=>$this->paramPOST['idProfil'],'adu.sf_user_id ='=>$this->paramPOST['idUser'], 'ad.etat ='=>1, 'adu.etat ='=>1]
        ];
        $data['droit'] = $this->model->get($param);

        if (count($this->paramPOST['update'])>0) {
            foreach ($data['droit'] as $item)
                if (!in_array($item->id, $this->paramPOST['update']))
                    $this->model->set(["table" => "sf_affectation_droit_user","champs" => ['etat' => 0],"condition" => ['id =' => $item->id]]);

            foreach ($this->paramPOST['update'] as $item)
                $this->model->set(["table" => "sf_affectation_droit_user","champs" => ['etat' => 1],"condition" => ['id =' => $item]]);

        }
        elseif(count($data['droit'])>0)
            foreach ($data['droit'] as $item)
                $this->model->set(["table" => "sf_affectation_droit_user","champs" => ['etat' => 0],"condition" => ['id =' => $item->id]]);

        if (count($this->paramPOST['add'])>0)
            foreach ($this->paramPOST['add'] as $item)
                $this->model->set(["table" => "sf_affectation_droit_user","champs" => ['sf_affectation_droit_id' => $item, 'sf_user_id' => $this->paramPOST['idUser']]]);

        Utils::redirect("utilisateur", "affectation", [$this->paramPOST['idUser']]);
    }

    /**
     * @throws \Jacwright\RestServer\RestException
     */
    public function utilisateurProcessing()
    {
        $param = [
            "button"=> [
                "modal" => [
                    [["utilisateur/utilisateurModal", "utilisateur/update"],"utilisateur/utilisateurModal","fa fa-edit"]
                ],
                "default" => [
                    ["champ"=>"etat","val"=>[["utilisateur/activate","fa fa-toggle-off"],["utilisateur/deactivate","fa fa-toggle-on"]]],
                    ["utilisateur/delete/","fa fa-trash"]
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
        if($this->appConfig->profile_level == 2) array_push($param["button"]["default"],["utilisateur/affectation/","fa fa-male"]);
        $this->processing($this->model, "utilisateurProcessing", $param);
    }
}