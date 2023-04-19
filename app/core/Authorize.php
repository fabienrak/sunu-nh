<?php
/**
 * Created by PhpStorm.
 * User: seeynii.faay
 * Date: 10/22/19
 * Time: 10:31 AM
 */

namespace app\core;

trait Authorize
{

    /**
     * @return bool
     */
    public function authorize()
    {
        if(is_null($this->token) || $this->token == "") {
            $this->authMessage = $this->response(['code'=>400, 'error'=>true, 'msg'=>'Token manquant dans l\'entéte', 'data'=>$this->token]);
            return false;
        }
        $result = TokenJWT::verif($this->token, $this->key_token);
        if($result == -1) {
            $this->authMessage = $this->response(['code'=>504, 'error'=>true, 'msg'=>'Token expiré']);
            return false;
        }
        elseif($result == -2) {
            $this->authMessage = $this->response(['code'=>504, 'error'=>true, 'msg'=>'Token invalide']);
            return false;
        }
        else {
            $model = new Model();
            $model->apiCall = true;
            $result = $model->get(["db_active"=>1, "table"=>"sf_user", "condition"=>["id = "=>$this->_USER->id]]);
            if($result['data'][0]->token == $this->token) {
                if($this->_USER->admin == 1 || parent::authorized()) return true;
                else {
                    $this->authMessage = is_null($this->authMessage) ? $this->response(['code'=>401, 'error'=>true]) : $this->authMessage;
                    return false;
                }
            }
            else {
                $this->authMessage = $this->response(['code'=>504, 'error'=>true,]);
                return false;
            }
        }
    }

    use Response;
}