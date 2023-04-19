<?php

/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 15/02/2017
 * Time: 20:02
 */

namespace app\core\controllers;

use app\core\ApiClient;

class ClientController extends ApiClient
{
    private static $instance = null;

    public static function initClient() : ClientController {
        if(static::$instance === null) self::$instance = new ClientController();
        return self::$instance;
    }

    public static function get($uri, $params = []) {
        parent::$method = "get";
        return self::send($uri, $params);
    }

    public static function post($uri, $params = []) {
        parent::$method = "post";
        return self::send($uri, $params);
    }

    public static function put($uri, $params = []) {
        parent::$method = "put";
        return self::send($uri, $params);
    }

    public static function delete($uri, $params = []) {
        parent::$method = "delete";
        return self::send($uri, $params);
    }

    public static function options($uri, $params = []) {
        parent::$method = "options";
        return self::send($uri, $params);
    }

    private static function send($uri, $params = []){
        parent::$uri = $uri;
        parent::$params = $params;
        parent::request();
        return parent::result();
    }
}