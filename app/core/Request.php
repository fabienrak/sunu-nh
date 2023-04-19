<?php

namespace app\core;

class Request{

    /**
     *
     */
    public function __contruct(){
        
    }




    /**
     * @return string
     */
    public static function getRequestMethod(){
        return strtolower(@$_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return bool
     */
    public static function isGetMethod(){
        return self::getRequestMethod() === 'get';
    }

    /**
     * @return bool
     */
    public static function isPostMethod(){
        return self::getRequestMethod() === 'post';
    }

    /**
     * @return array
     */
    public static function getAllHttpMethod(){
        return [
            'get',
            'post',
            'put',
            'patch',
            'delete',
            'update'
        ];
    }

    /**
     * @return mixed
     */
    public static function getRequestUri(){
        return $_SERVER['REQUEST_URI'];
    }
    /**
     * @return mixed
     */
    public  function getHeaders(){
        $headers = array();
        foreach($_SERVER as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }
        return $headers;
    }
    /**
     * @return string
     */
    public  function getToken(){

        return str_replace(getenv('JWT_PREFIX')." ","",@$this->getHeaders()["Authorization"]);
    }

    public  function all(){
        return Input::all();
    }

    public  function get($key){
        return Input::get($key);
    }

    
}