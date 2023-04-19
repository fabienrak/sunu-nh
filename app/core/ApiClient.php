<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 17/08/2017
 * Time: 11:01
 */

namespace app\core;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;

abstract class ApiClient
{
    private   static $client;
    private   static $result;
    protected static $uri;
    protected static $params = [];
    protected static $method = "get";

    protected static function result()
    {
        return (is_null(self::decode(self::$result))) ? (string)self::$result : self::decode(self::$result);
    }

    private static function setParams() {
        if(!isset(self::$params['headers']['Content-Type']) || strtolower(self::$params['headers']['Content-Type']) != 'application/x-www-form-urlencoded')
            self::$params['headers']['Content-Type'] = 'application/json';

        if(!isset(self::$params['headers']['Accept']))
            self::$params['headers']['Accept'] = 'application/json';

        if(strtolower(self::$method) === "get" && isset(self::$params['data'])) {
            self::$uri .= "?";
            foreach (self::$params['data'] as $key => $item) self::$uri .= (is_string($key)) ? "$key=$item&" : "$item&";
            self::$uri = (is_string($key)) ? str_replace("$key=$item&", "$key=$item", self::$uri) : str_replace("$item&", "$item", self::$uri);
            unset(self::$params['data']);
        }
        self::$params = (!isset(self::$params['data'])) ?
            [RequestOptions::HEADERS => self::$params['headers']]:
            [RequestOptions::HEADERS =>self::$params['headers'], (self::$params['headers']['Content-Type'] == 'application/json' ? RequestOptions::JSON : RequestOptions::FORM_PARAMS) => self::$params['data']];
    }

    protected static function request()
    {
        try{
            self::setParams();
            self::$client = new Client();
            $promise = self::$client->requestAsync(strtolower(self::$method), self::$uri, self::$params)->then(function ($response) {
                self::$result = $response->getBody();
            });
            $promise->wait();
        }catch(ClientException $ex) {
            self::$result = $ex->getMessage();
        }
    }

    private static function decode($json, $assoc = false, $depth = 512, $options = 0)
    {
        $data = \json_decode($json, $assoc, $depth, $options);
        if (JSON_ERROR_NONE !== json_last_error())
            return ["code" => 500, "error" => true, "msg" => "Service ".self::$uri." indisponible", "data" => []];

        return $data;
    }

}