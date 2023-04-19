<?php

/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 15/02/2017
 * Time: 20:02
 */

namespace app\controllers\admin;

use app\core\BaseController;
use app\core\Session;
use app\core\Utils;

class HomeController extends BaseController
{
    private $models;
    public function __construct()
    {
        parent::__construct(false);
        $this->models = $this->model("user");
    }

    /**
     * @authorize
     */
    public function index()
    {
        $this->views->initTemplate(["header"=>"header-login", "sidebar"=>"not-used", "footer"=>"footer-login"]);
        $this->views->getTemplate();
    }

    /**
     * @authorize
     */
    public function login()
    {
        $param = [
            "champs" => ["u.*","p.libelle as profil"],
            "jointure" => ["INNER JOIN sf_profil p ON u.sf_profil_id = p.id"],
            "condition" => ["u.login = "=>$this->paramPOST['login']]
        ];
        $result = $this->models->getUser($param);

        if (count($result) === 1) {
            if(password_verify($this->paramPOST['password'], $result[0]->password)) {
                Session::set_User_Connecter($result);
                Utils::redirect("utilisateur","list");
            }else {
                Utils::setMessageAlert(["error","Mot de passe incorrect !"]);
                Utils::redirect();
            }
        }
        else{
            Utils::setMessageAlert(["error","Login incorrect !"]);
            Utils::redirect();
        }
    }

    /**
     * @authorize
     */
    public function logout()
    {
        Session::destroySession();
        Utils::redirect();
    }
}