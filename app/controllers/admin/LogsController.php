<?php

/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 15/02/2017
 * Time: 21:11
 */

namespace app\controllers\admin;

use app\core\BaseController;
use app\core\Session;
use app\core\Utils;

class LogsController extends BaseController
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = $this->model("logs");
    }

    /**
     * @droit Lister les logs - SMF
     */
    public function liste()
    {
        $this->views->getTemplate();
    }

    /**
     * @throws \Jacwright\RestServer\RestException
     */
    public function listeProcessing()
    {
        $param = [
            "button"=> [
                "modal" => [
                    ["module/moduleModal","module/moduleModal","fa fa-edit"]
                ],
                "default" => [
                    ["module/deleteModule/","fa fa-trash"]
                ],
                "custom" => []
            ],
            "tooltip"=> [
                "modal" => [
                    "Modifier"
                ],
                "default" => [
                    "Supprimer"
                ]
            ],
            "classCss"=> [
                "modal" => [],
                "default" => ["confirm"]
            ],
            "attribut"=> [
                "modal" => [],
                "default" => []
            ],
            "args"=>null,
            "dataVal"=>[
                ["champ"=>"etat","val"=>["Désactiver"=>["<span style='.temp::before{text-align: right;}' class='temp text-info'>Désactiver</span>"],"Activer"=>["<span  class='temp text-success' >Activer</span><style>.temp::before{text-align: right;}</style>"]]]
            ],
            "fonction"=>[]
        ];
        $this->processing($this->model, "getListeProcess", $param);
    }

}