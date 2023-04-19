<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 27/02/2017
 * Time: 16:03
 */

namespace app\models\admin;

use app\core\BaseModel;

class UserModel extends BaseModel
{

    /**
     * UserModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * UserModel destruct.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * @param $param
     * @return bool|mixed
     * @throws \Jacwright\RestServer\RestException
     */
    public function insertUser($param)
    {
        $this->table = "sf_user";
        $this->__addParam($param);
        return $this->__insert();
    }

    /**
     * @param $param
     * @return bool|mixed
     * @throws \Jacwright\RestServer\RestException
     */
    public function updateUser($param)
    {
        $this->table = "sf_user";
        $this->__addParam($param);
        return $this->__update();
    }

    /**
     * @param $param
     * @return bool|mixed
     * @throws \Jacwright\RestServer\RestException
     */
    public function deleteUser($param)
    {
        $this->table = "sf_user";
        $this->__addParam($param);
        return $this->__delete();
    }

    /**
     * @param null $param
     * @return array|bool
     * @throws \Jacwright\RestServer\RestException
     */
    public function getUser($param = null)
    {
        $this->table = "sf_user u";
        $this->__addParam($param);
        return $this->__select();
    }

    /**
     * @return bool|mixed
     * @throws \Jacwright\RestServer\RestException
     */
    public function getListeProcess()
    {
        $this->table = "sf_user u";
        $this->champs = ["u.id","u.prenom","u.nom","u.email","p.libelle as profil","u.etat"];
        $this->jointure = ["INNER JOIN sf_profil p ON u.sf_profil_id = p.id"];
        return $this->__processing();
    }
}