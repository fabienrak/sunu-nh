<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 06/11/2020
 * Time: 00:46
 */

namespace app\models\admin;

use app\core\BaseModel;

class SousmoduleModel extends BaseModel
{
    /**
     * SousmoduleModel constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        parent::__construct($params);
    }

    /**
     * SousmoduleModel destruct.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * @throws \Jacwright\RestServer\RestException
     */
    public function sousmoduleProcessing()
    {
        $this->table = "sf_sous_module";
        $this->champs = ["sf_sous_module.id","sf_sous_module.libelle","sf_module.libelle AS module","sf_sous_module.etat","sf_sous_module.code"];
        $this->jointure = ["INNER JOIN sf_module ON sf_sous_module.sf_module_id = sf_module.id"];
        return $this->__processing();
    }

}