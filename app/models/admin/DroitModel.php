<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 06/11/2020
 * Time: 00:42
 */

namespace app\models\admin;

use app\core\BaseModel;

class DroitModel extends BaseModel
{
    /**
     * DroitModel constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        parent::__construct($params);
    }

    /**
     * DroitModel destruct.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * @throws \Jacwright\RestServer\RestException
     */
    public function droitProcessing()
    {
        $this->table = "sf_droit";
        $this->champs = ["sf_droit.id","sf_droit.libelle","sf_droit.espace","sf_sous_module.libelle AS sous_module","sf_droit.controller","sf_droit.action","sf_droit.etat"];
        $this->jointure = ["INNER JOIN sf_sous_module ON sf_droit.sf_sous_module_id = sf_sous_module.id"];
        return $this->__processing();
    }

}