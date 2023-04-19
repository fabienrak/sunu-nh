<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 06/11/2020
 * Time: 00:47
 */

namespace app\models\admin;

use app\core\BaseModel;

class ModuleModel extends BaseModel
{
    /**
     * ModuleModel constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        parent::__construct($params);
    }

    /**
     * ModuleModel destruct.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * @throws \Jacwright\RestServer\RestException
     */
    public function moduleProcessing()
    {
        $this->table = "sf_module";
        $this->champs = ["sf_module.id","sf_module.libelle","sf_module.etat","sf_module.code"];
        
        return $this->__processing();
    }

}