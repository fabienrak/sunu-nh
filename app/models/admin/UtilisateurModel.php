<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 05/11/2020
 * Time: 16:11
 */

namespace app\models\admin;

use app\core\BaseModel;

class UtilisateurModel extends BaseModel
{
    /**
     * UtilisateurModel constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        parent::__construct($params);
    }

    /**
     * UtilisateurModel destruct.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * @throws \Jacwright\RestServer\RestException
     */
    public function utilisateurProcessing()
    {
        $this->table = "sf_user";
        $this->champs = ["sf_user.id","sf_user.prenom","sf_user.nom","sf_user.email","sf_user.login","sf_user.password AS _password_","sf_profil.libelle AS profil","sf_user.admin AS _admin_","sf_user.connect AS _connect_","sf_user.etat"];
        $this->jointure = ["INNER JOIN sf_profil ON sf_user.sf_profil_id = sf_profil.id"];
        return $this->__processing();
    }

}