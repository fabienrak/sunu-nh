<?php

/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 15/02/2017
 * Time: 21:11
 */

namespace app\core\controllers;

use app\core\BaseController;
use app\core\Language;
use app\core\Session;

class LanguageController extends BaseController
{
    public function __construct()
    {
        parent::__construct(false);
    }

    public function index()
    {
        $this->paramPOST['arg'] = strtolower($this->paramPOST['arg']);
        if(file_exists(ROOT."app/language/".$this->paramPOST['arg'].".lang")) Session::setAttribut("lang",$this->paramPOST['arg']);
        else Session::setAttribut("lang","fr");
        Session::setAttribut("lang",$this->paramPOST['arg']);
        echo Session::getAttribut("lang");
    }

    public function getLang()
    {
        if (!Session::existeAttribut("lang")) Session::setAttribut("lang", "fr");
        echo json_encode(Language::getLang(Session::getAttribut('lang')));
        exit();
    }
}