<?php

/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 15/02/2017
 * Time: 20:02
 */

namespace app\controllers;

use app\core\BaseController;
use app\core\Utils;

class HomeController extends BaseController
{
    public function __construct()
    {
        parent::__construct(false);
    }

    public function index()
    {
        $this->views->initTemplate(["header"=>"header-home", "sidebar"=>"not-used", "footer"=>"footer-home"]);
        $this->views->getTemplate();
    }

    public function droitModal()
    {
        $this->modal();
    }

    public function documentation()
    {
        $this->views->setData(["color_body"=>"body-green"]);
        $this->views->getTemplate();
    }

    public function architecture()
    {
        $this->views->setData(["color_body"=>"body-pink"]);
        $this->views->getTemplate();
    }

    public function forum()
    {
        $this->views->setData(["color_body"=>"body-orange"]);
        $this->views->getTemplate();
    }
}