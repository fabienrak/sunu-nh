<?php

/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 15/02/2017
 * Time: 21:11
 */

namespace app\core\controllers;

use app\core\BaseController;
use app\core\Utils;

class ErrorController extends BaseController
{
    public function __construct()
    {
        parent::__construct(false);
    }

    public function index()
    {
        $this->views->getPage(null);
    }

    public function error()
    {
        $this->views->getPage(null);
    }

    public function unsetMessage()
    {
        Utils::unsetMessage($this->paramGET[0]);
    }
}