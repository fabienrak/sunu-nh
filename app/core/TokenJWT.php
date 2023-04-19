<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 17/08/2017
 * Time: 11:01
 */

namespace app\core;

abstract class TokenJWT
{
    /**
     * @param $params
     * @param $key
     * @param array $expire
     * @return string
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public static function encode($params, $key, $expire = [5, "minute"]) {
        $data['data'] = $params;
        $data['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
        if(count($expire) > 0) $data["sf_token_expire"] = Utils::getDateFuturFromDate($expire);
        if(!is_string($data)) {
            $data['json_encode'] = true;
            $data = json_encode($data);
        }
        $data = Utils::cryptString($data, Utils::getKey_crypt());
        return \JWT::encode($data, $key);
    }

    /**
     * @param $tokenJWT
     * @param $key
     * @return int|mixed
     */
    public static function decode($tokenJWT, $key) {
        try{
            $infoToken = \JWT::decode($tokenJWT, $key, ['HS256']);
            $infoToken = Utils::decryptString($infoToken, Utils::getKey_crypt());
            if(is_string($infoToken) && strpos($infoToken, 'json_encode') !== false)
                $infoToken = (array)json_decode($infoToken);
            return $infoToken['data'];
        }catch(\Exception $ex){
            return -2;
        }
    }

    /**
     * @param $tokenJWT
     * @param $key
     * @return bool|int|object
     *
     *  si retourne -1 alors le Token a expiré
     *  si retourne -2 alors le Token est invalide
     *
     */
    public static function verif($tokenJWT, $key) {
        try{
            $result = \JWT::decode($tokenJWT, $key, ['HS256']);
            $result = Utils::decryptString($result, Utils::getKey_crypt());

            if(is_string($result) && strpos($result, 'json_encode') !== false) {
                $result = (array)json_decode($result);
                unset($result['json_encode']);
            }
            return (strtotime(Utils::getDateNow(true)) < strtotime($result['sf_token_expire'])) ? ($result['HTTP_USER_AGENT'] === $_SERVER['HTTP_USER_AGENT'] ? 0 : -3) : -1;
        }catch(\Exception $ex) {
            return -2;
        }
    }
}