<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: _date_
 * Time: _heure_
 */

namespace _namespace_;

use app\core\BaseModel;

class _Name_crud_Model extends BaseModel
{
    /**
     * _Name_crud_Model constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        parent::__construct($params);
    }

    /**
     * _Name_crud_Model destruct.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * @throws \Jacwright\RestServer\RestException
     */
    public function _name_crud_Processing()
    {
        $this->table = "_name_crud_table_";
        $this->champs = [_champs_processing_];
        _jointure_processing_;
        return $this->__processing();
    }

}