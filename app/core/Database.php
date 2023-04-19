<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 17/08/2017
 * Time: 11:01
 */

namespace app\core;
use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    private $connexion = null;
    private $eloquent = null;
    private static $appConfig = null;
    private static $dbConfig = null;
    private static $instance = null;
    private static $param = [];
    private static $paramEloquent = [];
    private static $cache = [];

    /**
     * Database constructor.
     * @param int $db_active
     * @param bool $with_db
     * @throws \Exception
     */
    public function __construct($db_active = 1, $with_db = true)
    {
        self::$appConfig = self::getAppConfig();
        self::$dbConfig = self::getDbConfig();
        $db_active = intval($db_active) == 1 ? '_' : $db_active."_";

        if($with_db == 'eloquent'){
            if(self::$dbConfig['DB'.$db_active.'TYPE'] == 'mysql') {
                try{
                    self::$paramEloquent = [
                        "driver" => self::$dbConfig['DB'.$db_active.'TYPE'],
                        "host" =>self::$dbConfig['DB'.$db_active.'HOST'],
                        "database" => self::$dbConfig['DB'.$db_active.'NAME'],
                        "username" => self::$dbConfig['DB'.$db_active.'USER'],
                        "password" => self::$dbConfig['DB'.$db_active.'PASSWORD']
                    ];
                    if(isset(self::$dbConfig['DB'.$db_active.'PORT'])) self::$paramEloquent['port'] = self::$dbConfig['DB'.$db_active.'PORT'];

                    $this->eloquent = new Capsule;
                    $this->eloquent->addConnection(self::$paramEloquent);
                    $this->eloquent->setAsGlobal();
                    $this->eloquent->bootEloquent();
                    $key = "key".sha1(json_encode(self::$paramEloquent));
                    self::$cache[$key] = $this->eloquent;
                }catch(\PDOException $ex) {
                    if(apiCall === true) throw $ex;
                    else {
                        Utils::setMessageError(['500',$ex->getMessage()]);
                        Utils::redirect("error","error", [], "default");
                        throw $ex;
                    }
                }
            }else throw new \Exception("Seule la base de données MySQL est supportée pour le moment");
        }else{
            try {
                $with_db = $with_db == "default";
                $dsn = '';
                if($with_db) {
                    $dsn = (self::$dbConfig['DB'.$db_active.'TYPE'] == 'sqlite')
                        ? 'sqlite:'.ROOT . 'config/'.self::$dbConfig['DB'.$db_active.'NAME'].'.db'
                        : self::$dbConfig['DB'.$db_active.'TYPE'] . ':dbname=' . self::$dbConfig['DB'.$db_active.'NAME'] . ';host=' . self::$dbConfig['DB'.$db_active.'HOST'];
                }
                elseif(self::$dbConfig['DB'.$db_active.'TYPE'] == 'mysql') $dsn = self::$dbConfig['DB'.$db_active.'TYPE'] . ':host=' . self::$dbConfig['DB'.$db_active.'HOST'];
                if (socketConnexion === true && filter_var(self::$dbConfig['DB'.$db_active.'HOST'], FILTER_VALIDATE_IP) === false)
                    throw new \PDOException('La valeur de DB'.$db_active.'HOST dans config/db.config.ini doit etre au format IP', 500);
                if(isset(self::$dbConfig['DB'.$db_active.'PORT'])) $dsn .= ';port='.self::$dbConfig['DB'.$db_active.'PORT'];
                elseif (socketConnexion === true) throw new \PDOException('Vous devez definir DB'.$db_active.'PORT = [PORT] dans config/db.config.ini', 500);


                if (isset($dsn)) {
                    $this->connexion = (self::$dbConfig['DB'.$db_active.'TYPE'] == 'sqlite')
                        ? new \PDO($dsn)
                        : new \PDO($dsn, self::$dbConfig['DB'.$db_active.'USER'], self::$dbConfig['DB'.$db_active.'PASSWORD'], [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]);
                    $this->connexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                    $key = $dsn;
                    self::$param = [$dsn];
                    if(isset(self::$dbConfig['DB'.$db_active.'USER'])) {
                        array_push(self::$param, self::$dbConfig['DB'.$db_active.'USER']);
                        $key .= self::$dbConfig['DB'.$db_active.'USER'];
                    }
                    $key = "key:" . sha1($key);
                    self::$cache[$key] = $this->connexion;

                }else throw new \PDOException('DNS not define !', 500);
            }
            catch (\PDOException $ex) {
                if(apiCall === true || socketConnexion === true) throw $ex;
                else {
                    Utils::setMessageError(['500',$ex->getMessage()]);
                    Utils::redirect("error","error", [], "default");
                    throw $ex;
                }
            }
        }
    }

    /**
     * @param int $db_active
     * @param bool $with_db
     * @return Capsule|null
     * @throws \Exception
     */
    public static function getConnexionEloquent($db_active = 1, $with_db = true)
    {
        $with_db = $with_db ? 'eloquent' : '';
        self::$dbConfig = array_map(function ($item){ return trim($item);}, \parse_ini_file(ROOT . 'config/db.config.ini'));
        $db_active = intval($db_active) == 1 ? '' : $db_active;

        try{
            if(count(self::$paramEloquent) === 0) self::$instance = new Database($db_active, $with_db);
            else {

                $key = "key".sha1(json_encode(self::$paramEloquent));

                self::$instance = (!isset(self::$cache[$key])) ? new Database($db_active, $with_db) : self::$cache[$key];
            }
        }catch(\Exception $ex) {
            throw $ex;
        }
        return self::$instance->eloquent;
    }

    /**
     * @param int $db_active
     * @param bool $with_db
     * @return null|\PDO
     * @throws \Exception
     */
    public static function getConnexion($db_active = 1, $with_db = true)
    {
        $with_db = $with_db ? 'default' : '';
        self::$dbConfig = array_map(function ($item){ return trim($item);}, \parse_ini_file(ROOT . 'config/db.config.ini'));
        $db_active = intval($db_active) == 1 ? '' : $db_active;

        try{
            if(count(self::$param) === 0) self::$instance = new Database($db_active, $with_db);
            else {
                $dsn = ($with_db)
                    ? self::$dbConfig['DB'.$db_active.'TYPE'] . ':dbname=' . self::$dbConfig['DB'.$db_active.'NAME'] . ';host=' . self::$dbConfig['DB'.$db_active.'HOST']
                    : self::$dbConfig['DB'.$db_active.'TYPE'] . ':host=' . self::$dbConfig['DB'.$db_active.'HOST'];
                $key = $dsn;
                if($dsn !== self::$param[0]){
                    if(isset(self::$dbConfig['DB'.$db_active.'USER']))
                        $key .= self::$dbConfig['DB'.$db_active.'USER'];
                    $key = "key:" . sha1($key);
                    self::$instance = (in_array($key, self::$cache)) ? self::$cache[$key] : new Database($db_active, $with_db);
                }
                elseif((isset(self::$param[1]) && isset(self::$dbConfig['DB'.$db_active.'USER'])) && (self::$param[1] !== self::$dbConfig['DB'.$db_active.'USER'])){
                    $key .= self::$dbConfig['DB'.$db_active.'USER'];
                    $key = "key:" . sha1($key);
                    self::$instance = (in_array($key, self::$cache)) ? self::$cache[$key] : new Database($db_active, $with_db);
                }
            }
        }catch(\PDOException $ex) {
            throw $ex;
        }
        return self::$instance->connexion;
    }

    /**
     * @param $db_name
     * @param int $db_active
     * @return bool
     * @throws \Exception
     */
    public static function create($db_name, $db_active = 1)
    {
        try {
            $connexion = self::getConnexion($db_active, false);
            return $connexion->prepare("CREATE DATABASE IF NOT EXISTS `".$db_name."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci")->execute();
        }catch(\PDOException $ex) {
            throw $ex;
        }
    }

    /**
     * @param int $db_active
     * @return bool|int
     * @throws \Exception
     */
    public static function generateTable($db_active = 1)
    {
        self::$dbConfig = self::getDbConfig();
        $connexion = self::getConnexion($db_active);
        $result = false;
        $db_active = intval($db_active) == 1 ? '' : $db_active;
        if(file_exists(ROOT.'config/DB.'.self::$dbConfig['DB'.$db_active.'_TYPE'].'.sql')) {
            $sql = file_get_contents(ROOT.'config/DB.'.self::$dbConfig['DB'.$db_active.'_TYPE'].'.sql');
            try{
                $result = $connexion->exec($sql);
            }catch(\PDOException $ex){
                throw $ex;
            }
        }
        return $result;
    }

    /**
     * @return null|object
     */
    public static function getAppConfig()
    {
        return (object)\parse_ini_file(ROOT . 'config/app.config.ini');
    }

    /**
     * @return array|bool|null
     */
    public static function getDbConfig()
    {
        return array_map(function ($item){return trim($item);}, \parse_ini_file(ROOT . 'config/db.config.ini'));
    }
}