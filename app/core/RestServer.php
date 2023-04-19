<?php
/**
 * Created by PhpStorm.
 * User: seeynii.faay
 * Date: 10/11/19
 * Time: 5:25 PM
 */
namespace app\core;

use Jacwright\RestServer\RestServer as _RestServer_;

class RestServer extends _RestServer_
{
    /**
     * @param $class
     * @param $basePath
     * @throws \ReflectionException
     */
    protected function generateMap($class, $basePath) {
        if (is_object($class)) {
            $reflection = new \ReflectionObject($class);
        } else if (class_exists($class)) {
            $reflection = new \ReflectionClass($class);
        }

        if(is_object($reflection)) {
            $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);    //@todo $reflection might not be instantiated

            foreach ($methods as $method) {
                $doc = $method->getDocComment();
                $noAuth = strpos($doc, '@noAuth') !== false;
                if (preg_match_all('/@url[ \t]+(GET|POST|PUT|PATCH|DELETE|HEAD|OPTIONS)[ \t]+\/?(\S*)/s', $doc, $matches, PREG_SET_ORDER)) {
                    $params = $method->getParameters();

                    foreach ($matches as $match) {
                        $httpMethod = $match[1];
                        $url = $basePath . $match[2];

                        if ($url && $url[strlen($url) - 1] == '/') {
                            $url = substr($url, 0, -1);
                        }

                        $call = array($class, $method->getName());
                        $args = array();

                        foreach ($params as $param) {
                            $args[$param->getName()] = $param->getPosition();
                        }

                        $call[] = $args;
                        $call[] = null;
                        $call[] = $noAuth;
                        $this->map[$httpMethod][$url] = $call;
                        $call[1] = 'options';
                        $call[4] = true;
                        $this->map['OPTIONS'][$url] = $call;
                    }
                }
            }
        }else exit();
    }

    /**
     * @param $statusCode
     * @param null $errorMessage
     * @throws \ReflectionException
     */
    public function handleError($statusCode, $errorMessage = null) {
        $method = "handle$statusCode";

        foreach ($this->errorClasses as $class) {
            if (is_object($class)) {
                $reflection = new \ReflectionObject($class);
            } else if (class_exists($class)) {
                $reflection = new \ReflectionClass($class);
            }

            if (isset($reflection)) {
                if ($reflection->hasMethod($method)) {
                    $obj = is_string($class) ? new $class() : $class;
                    $obj->$method();
                    return;
                }
            }
        }
        $param = ['code'=>$statusCode, 'error'=> true];
        if (!is_null($errorMessage)) $param['msg'] = $errorMessage;

        $this->setStatus(200);
        $this->sendData($this->response($param));
    }

    public function unauthorized($obj) {
        return $obj->authMessage;
    }

    public function getPath() {
        //@todo should only work with GET method
        $this->query = $_GET;

        $path = $this->query['url'] ;//preg_replace('/\?.*$/', '', $_SERVER['REQUEST_URI']);

        // remove root from path
        if ($this->root) $path = preg_replace('/^' . preg_quote($this->root, '/') . '/', '', $path);

        // remove trailing format definition, like /controller/action.json -> /controller/action
        // Only remove formats that are valid for RestServer
        $dot = strrpos($path, '.');
        if ($dot !== false) {
            $path_format = substr($path, $dot + 1);

            foreach (RestFormat::$formats as $format => $mimetype) {
                if ($path_format == $format) {
                    $path = substr($path, 0, $dot);
                    break;
                }
            }
        }

        // remove root path from path, like /root/path/api -> /api
        if ($this->rootPath) $path = str_replace($this->rootPath, '', $path);

        $temp = ltrim($path, '/');
        return $temp ;
    }

    /**
     * @param $class
     * @param string $basePath
     * @throws \ReflectionException
     */
    public function addClass($class, $basePath = '') {

        $this->loadCache();

        if (!$this->cached) {

            if (is_string($class) && !class_exists($class)) {
                $this->handleError(404, null);
//                throw new RestException(404, 'Invalid method or class');
            } else if (!is_string($class) && !is_object($class)) {
                $this->handleError(404, null);
//                throw new RestException(404, 'Invalid method or class; must be a classname or object');
            }

            if (substr($basePath, 0, 1) == '/') {
                $basePath = substr($basePath, 1);
            }

            if ($basePath && substr($basePath, -1) != '/') {
                $basePath .= '/';
            }

            $this->generateMap($class, $basePath);
        }
    }

    use Response;
}