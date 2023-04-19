<?php
/**
 * Created by PhpStorm.
 * User: Seyni Faye
 * Date: 02/07/2018
 * Time: 11:49
 */

namespace app\webservice;

use app\core\ApiServer;
use app\core\Authorize;
use app\core\TokenJWT;
use \Jacwright\RestServer\RestException;

class Api extends ApiServer
{
    private $model;

    /**
     * Api constructor.
     * @throws RestException
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function __construct()
    {
        parent::__construct(__CLASS__);
        $this->model = $this->model("user", "admin");
    }

    use Authorize;

    /**
     * @noAuth
     * @url OPTIONS /token
     */
    public function getTokenOp()
    {

    }

    /**
     * @noAuth
     * @url POST /token
     * @return array
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function getToken()
    {
        $param["condition"] = ["login = "=>parent::$paramRequest["login"]];
        $result = $this->model->getUser($param);
        if(count($result['data']) == 0) {
            $result['msg'] = 'Login incorrect';
            $result['error'] = true;
            $result['code'] = 401;
            $result['data'] = $this->params;
            return $this->response($result);
        }
        return (password_verify(parent::$paramRequest['password'], $result['data'][0]->password)) ?
            $this->response(['code'=>200, 'error'=>false, 'data'=>['token'=> TokenJWT::encode($result["data"][0], $this->key_token, [1, "jour"])]]):
            $this->response(['code'=>401, 'error'=>true, 'msg'=>'Mot de passe incorrect']);
    }

    /**
     * @url POST /addUser
     */
    public function insertUser()
    {
        return $this->model->insertUser(parent::$paramRequest);
    }

    /**
     * @url POST /updateUser
     */
    public function updateUser()
    {
        $id = parent::$paramRequest["id"];
        unset(parent::$paramRequest["id"]);
        $param = [
            "champs"=>parent::$paramRequest,
            "condition"=>["id = "=>$id]
        ];
        return $this->model->updateUser($param);
    }

    /**
     * @url GET /deleteUser
     */
    public function deleteUser()
    {
        $param = ["condition"=>["id = "=>parent::$paramRequest["id"]]];
        return $this->model->deleteUser($param);
    }

    /**
     * @url GET /getUser
     */
    public function getUser()
    {
        $param = [];
        if(isset(parent::$paramRequest["id"])) $param["condition"] = ["id = "=>parent::$paramRequest["id"]];
        return $this->model->getUser($param);
    }

    /**
     * @noAuth
     * @url POST /sendMail
     */
    public function sendMailService()
    {
        if(!(isset(parent::$paramRequest['template']) && isset(parent::$paramRequest['email']))) return $this->response(['code'=>400]);

        if(isset(parent::$paramRequest['mail_from'])) $this->appConfig->mail_from = parent::$paramRequest['mail_from'];
        $content = parent::$paramRequest['template'];
        $result = false;
        $msg = "Le mail n'a pas pu etre envoyé";
        $code = 405;
        $data = parent::$paramRequest;
        unset($data['email']);unset($data['template']);unset($data['mail_from']);
        if(file_exists(ROOT."app/views/template-mail/$content.php")) {
            $subject = ($content == "create-user") ? "Création compte" : (($content == "update-password-user") ? "Regénération mot de passe" : "PHCO MAIL");
            if(parent::$paramRequest['titre']) $subject = parent::$paramRequest['titre'];
            if(is_string(parent::$paramRequest['email'])) {
                $data = [
                    "subject"=>$subject,
                    "email"=>parent::$paramRequest['email'],
                    "content"=>"template-mail/$content",
                    "data"=> $data
                ];
                //return $this->response(["error"=>false, "data"=>$data]);
                $result = $this->sendMail($data);
            }elseif(is_array(parent::$paramRequest['email'])) {
                foreach (parent::$paramRequest['email'] as $mail) {
                    $data_ = [
                        "subject"=>$subject,
                        "email"=>$mail,
                        "content"=>"template-mail/$content",
                        "data"=> $data
                    ];
                    $result = $this->sendMail($data_);
                }
            }
        }else {
            $msg = "Le template mail $content n'existe pas";
            $code = 404;
        }
        return $result ? $this->response(["error"=>false, "msg"=>"Mail envoyé avec succés à cette adresse ".parent::$paramRequest['email']]) : $this->response(["code"=>$code, "error"=>true, "msg"=>$msg]);
    }

    /**
     * @noAuth
     * @url POST /sendMailCDC
     */
    public function sendMailCDC()
    {
        $result = \mail(parent::$paramRequest['email'], parent::$paramRequest['sujet'], parent::$paramRequest['message'], parent::$paramRequest['entete']);
        return $result ? $this->response(["error"=>false, "msg"=>"Mail envoyé avec succés à cette adresse ".parent::$paramRequest['email']]) : $this->response(["code"=>500, "error"=>true, "msg"=>"Le mail n'a pas pu etre envoyé"]);
    }
}