<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 25/04/2018
 * Time: 14:51
 */

namespace app\core;


class Model extends BaseModel
{
    /**
     * Model constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        parent::__construct($params);
    }

    /**
     * Model destruct.
     */
    public function __destruct()
    {
        parent::__destruct();
    }
}