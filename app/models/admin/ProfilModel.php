<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 06/11/2020
 * Time: 00:59
 */

namespace app\models\admin;

use app\core\BaseModel;

class ProfilModel extends BaseModel
{
    /**
     * ProfilModel constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        parent::__construct($params);
    }

    /**
     * ProfilModel destruct.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * @throws \Jacwright\RestServer\RestException
     */
    public function profilProcessing()
    {
        $this->table = "sf_profil";
        $this->champs = ["sf_profil.id","sf_profil.libelle","sf_profil.etat"];
        
        return $this->__processing();
    }

}