<?php

namespace app\core;

class Input{

    private static $_input = null;

    /**
     * @param $name
     * @param null $default
     * @return null
     */
    public function __construct()
    {

    }

    public static function get($name, $default = null)
    {
        $value = Input::input();
        return isset($value[$name]) ? $value[$name] : $default;
    }
    public static function all()
    {
        return  Input::input();

    }

    private static function input(){
       // die(json_encode(getallheaders()));
        if(Input::$_input === null)
        {
            $requestBody = file_get_contents("php://input");
            $requestBody = strlen($requestBody) == 0 ? json_encode($_REQUEST) : $requestBody;
            $requestQueryString = $_SERVER['QUERY_STRING'];
            $return = [];

            if(Request::isGetMethod())
            {
                parse_str($requestQueryString, $return);
            }
            else{
                $contentType = '';
                foreach (getallheaders() as $name => $value){
                    $name = strtolower($name);
                    if($name === 'content-type')
                    {
                        $contentType = $value;
                    }
                }

                if(strpos(strtolower($contentType),'application/json')  !==  false)
                {
                    $return = json_decode($requestBody, true);
                    if(!is_array($return))
                    {
                        $return = [];
                    }
                }
                else if(strpos(strtolower($contentType),'application/x-www-form-urlencoded')  !==  false)
                {
                    parse_str($requestBody, $return);
                }
            }

            Input::$_input = $return;

            return $return;
        }
        else
        {
            return Input::$_input;
        }
    }
}