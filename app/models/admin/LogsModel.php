<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 27/02/2017
 * Time: 16:03
 */

namespace app\models\admin;

use app\core\BaseModel;

class LogsModel extends BaseModel
{

    /**
     * HomeModel constructor.
     * @throws \Jacwright\RestServer\RestException
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * HomeModel destruct.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Processing logs
     * @throws \Jacwright\RestServer\RestException
     */
    public function getListeProcess()
    {
        $this->table = 'sf_logs';
        $this->champs = ['id', 'action', 'currenttable', 'currentid', 'description', 'datecreation', 'result', 'sf_user_id'];
        return $this->__processing();
    }

}