<?php

/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 15/02/2017
 * Time: 20:02
 */

namespace app\core\controllers;

use app\core\ApiServer;

class ServerController extends ApiServer
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    public function api()
    {
        $this->getServer()->addClass($this->setApi(), $this->setPatch());
        $this->getServer()->handle();
    }
}