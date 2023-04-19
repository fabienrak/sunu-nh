<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 31/01/2018
 * Time: 12:52
 */

namespace app\common;

trait CommonApiServer
{
    public function onConstruct()
    {
        if($this->token == "" && isset($this->request_headers['_USER'])) $this->_USER = json_decode($this->request_headers['_USER']);
    }
}