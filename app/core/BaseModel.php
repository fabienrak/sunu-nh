<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 17/08/2017
 * Time: 11:01
 */

namespace app\core;

use app\common\CommonModel;
use app\core\controllers\ClientController;
use app\core\controllers\ClientSoapController;
use \Jacwright\RestServer\RestException;

abstract class BaseModel
{
    private   $dbConfig = null;
    private   $connexion = null;
    protected $appConfig = null;
    protected $apiClient;
    protected $apiClientSoap;
    protected $_USER = null;
    protected $table = null;
    protected $requete = null;
    protected $db_prefix = null;
    protected $jointure = [];
    protected $champs = [];
    protected $value = [];
    protected $condition = [];
    protected $filter = [];
    protected $sort = [];
    protected $limit = [];
    protected $group = [];
    public    $url = null;
    public    $espace = "default";
    public    $apiCall = false;
    public    $socketConnexion = false;
    public    $db_active = 1;
    public    $params = [];

    /**
     * BaseModel constructor.
     * @param array $params
     */
    protected function __construct($params = [])
    {
        if(count($params) > 0) foreach ($params as $key => $val) if (isset($this->{$key})) $this->{$key} = $val;
        if(isset($_GET['url'])) {
            $this->url = explode("/", $_GET['url']);
            array_shift($this->url);
        }
        $this->db_active = $this->db_active == 1 ? '' : $this->db_active;
        $this->appConfig = (object)\parse_ini_file(ROOT . 'config/app.config.ini');
        $this->dbConfig = \parse_ini_file(ROOT . 'config/db.config.ini');
        if ($this->socketConnexion === false && $this->apiCall === false) {
            $this->_USER = (Session::existeAttribut(SESSIONNAME)) ? Session::getAttributArray(SESSIONNAME)[0] : null;
            if($this->appConfig->use_api_client == "1") {
                $this->apiClient = ClientController::initClient();
                $this->apiClientSoap = ClientSoapController::initClientSoap();
            }
        }
        $this->db_prefix = (isset($this->dbConfig['DB_' . $this->db_active . 'PREFIX']) && $this->dbConfig['DB_' . $this->db_active . 'PREFIX'] !== '') ? $this->dbConfig['DB_' . $this->db_active . 'PREFIX'] : "";
        if($this->db_prefix != '' && !Utils::endsWith($this->db_prefix, '_')) $this->db_prefix .= "_";
    }

    /**
     * Detruit la connexion à la BD
     */
    protected function __destruct()
    {
        $this->connexion = null;
        $this->__reset();
    }

    /**
     * @param $method
     */
    private function setPrefix($method)
    {
        if ($this->db_prefix !== '') {
            $aliasReplace = [];
            $this->table = (strpos(strtolower($this->table), " as ") != false) ? explode(" AS ", str_replace(" as ", " AS ", $this->table)) : explode(" ", $this->table);
            foreach ($this->table as $key => $item) {
                if($key == 1) $aliasReplace[$item] = $this->db_prefix . $item;;
                $this->table[$key] = $this->db_prefix . $item;
            }
            $this->table = implode(" ", $this->table);

            if (count($this->jointure) > 0) {
                $this->jointure = array_map(function ($oneJoin) {
                    return Utils::setPurgeArray(explode(" ", $oneJoin));
                }, $this->jointure);
                $temp = ["inner", "left", "right", "join", "outer"];
                foreach ($this->jointure as $keyJoin => $jointure) {
                    $jointure = array_values($jointure);
                    foreach ($jointure as $key => $one) {
                        if (!in_array(strtolower($one), $temp)) {
                            $jointure[$key] = $this->db_prefix . $one;
                            if(strtolower($jointure[($key + 1)]) != "on") {
                                $aliasReplace[$jointure[($key + 1)]] = $this->db_prefix . $jointure[($key + 1)];
                                $jointure[($key + 1)] = $this->db_prefix . $jointure[($key + 1)];
                            }
                            $jointure[(count($jointure) - 1)] = $this->db_prefix . $jointure[(count($jointure) - 1)];
                            $jointure[(count($jointure) - 3)] = $this->db_prefix . $jointure[(count($jointure) - 3)];
                            $this->jointure[$keyJoin] = implode(" ", $jointure);
                            break;
                        }
                    }
                }
            }

            if ($method != "select") {
                foreach ($this->champs as $key => $value) {
                    if (count(explode(".", $key)) == 2) {
                        $this->champs[$this->db_prefix . $key] = $value;
                        unset($this->condition[$key]);
                    }
                }
            }
            else {
                foreach ($aliasReplace as $keyAlias => $valAlias) {
                    foreach ($this->champs as $key => $value) {
                        if(strpos($value, "$keyAlias.") !== false)
                            $this->champs[$key] = str_replace("$keyAlias.", "$valAlias.", $value);
                    }
                }
            }

            if (count($this->condition) > 0) {
                if(count($this->value) > 0){
                    foreach ($aliasReplace as $keyAlias => $valAlias) {
                        foreach ($this->condition as $key => $value) {
                            if(strpos($value, "$keyAlias.") !== false)
                                $this->condition[$key] = str_replace("$keyAlias.", "$valAlias.", $value);
                        }
                    }
                }else{
                    foreach ($aliasReplace as $keyAlias => $valAlias) {
                        foreach ($this->condition as $key => $value) {
                            if(strpos($key, "$keyAlias.") !== false){
                                $this->condition[str_replace("$keyAlias.", "$valAlias.", $key)] = $value;
                                unset($this->condition[$key]);
                            }
                        }
                    }
                }
            }

            if (count($this->group) > 0) {
                $this->group = array_map(function ($one) {
                    $one = explode(".", $one);
                    return (count($one) == 2) ? $this->db_prefix . implode(".", $one) : $one;
                }, $this->group);
            }

            if (count($this->sort) > 0) {
                $temp = explode(".", $this->sort[0]);
                if (count($temp) == 2) $this->sort[0] = $this->db_prefix . $this->sort[0];
            }
        }
        try {
            if(!defined("apiCall")) define("apiCall", $this->apiCall);
            if(!defined("socketConnexion")) define("socketConnexion", $this->socketConnexion);
            $this->connexion = Database::getConnexion(str_replace("_", "", $this->db_active));
        } catch (\Exception $ex) {
            if($this->apiCall === true) Utils::responseAPI(500, $this->response(['code'=>500, 'error'=>true, 'msg'=>$ex->getMessage(), 'data'=>[]]));
            elseif($this->socketConnexion) {
                print ($ex->getMessage()."\n");exit();
            }
            else {
                Utils::setMessageError(['500',$ex->getMessage()]);
                Utils::redirect("error","error", [], "default");
                exit();
            }
        }
    }

    /**
     * @param string $return
     * @return array|bool|int|string
     * @throws RestException
     */
    protected function __select($return = 'object')
    {
        try {

            if (!\is_null($this->table)) {

                $this->setPrefix("select");

                $this->requete = "SELECT * ";

                if (count($this->champs) > 0) $this->requete = "SELECT " . implode(",", $this->champs);

                $this->requete .= " FROM " . $this->table . " ";

                if (count($this->jointure) > 0) $this->requete .= implode(" ", $this->jointure) . " ";

                if (count($this->condition) > 0) {
                    if (count($this->value) == 0) {
                        $this->value = array_values($this->condition);
                        $this->condition = array_map(function ($one) {
                            return $one = $one . ' ?';
                        }, array_keys($this->condition));
                    }
                    $this->requete .= " WHERE " . implode(" AND ", $this->condition);
                }

                if (count($this->group) > 0) $this->requete .= " GROUP BY " . implode(", ", $this->group);

                if (count($this->sort) > 0)
                    $this->requete .= (count($this->sort) === 1) ? " ORDER BY " . $this->sort[0] . " ASC" : " ORDER BY " . $this->sort[0] . " " . $this->sort[1];

                if (count($this->limit) > 0)
                    $this->requete .= (count($this->limit) === 1) ? " LIMIT 0, " . $this->limit[0] : " LIMIT " . $this->limit[0] . " ," . $this->limit[1];
            }

            if (!\is_null($this->requete)) {
                $resultat = $this->connexion->prepare($this->requete);
                $resultat->execute($this->value);
                $this->__reset();
                if($this->apiCall === true) return ($return == 'array') ? $this->response(["code"=>200, "error"=>false, "data"=>$resultat->fetchAll(\PDO::FETCH_ASSOC)]) : $this->response(["code"=>200, "error"=>false, "data"=>$resultat->fetchAll(\PDO::FETCH_OBJ)]);
                else return ($return == 'array') ? $resultat->fetchAll(\PDO::FETCH_ASSOC) : $resultat->fetchAll(\PDO::FETCH_OBJ);
            }
        }
        catch (\PDOException $ex) {
            return $this->sfReturn($ex);
        }
        $this->__reset();
        return ($this->apiCall === true) ? $this->response(['code'=> 500, 'error'=> true, 'msg'=> 'Erreur dans la requete SQL'.$this->requete]) : false;
    }

    /**
     * @return array|bool|string
     * @throws \Exception
     */
    protected function __execute()
    {
        if (!\is_null($this->requete)
            && Utils::startsWith(strtolower($this->requete), "select")
            && Utils::startsWith(strtolower($this->requete), "insert")
            && Utils::startsWith(strtolower($this->requete), "update")
            && Utils::startsWith(strtolower($this->requete), "delete"))
        {
            try {
                $this->connexion = Database::getConnexion($this->db_active);
                $resultat = $this->connexion->prepare($this->requete);
                $rst = $resultat->execute($this->value);

                if (Utils::startsWith(strtolower($this->requete), "select")){
                    $rst = $resultat->fetchAll(\PDO::FETCH_OBJ);
                    $this->__reset();
                    if($this->apiCall === true) return $this->response(["code"=>200, "error"=>false, "data"=>$rst]);
                    else return $rst;
                }
                elseif (Utils::startsWith(strtolower($this->requete), "insert")){
                    $rst = ($rst == true) ? $this->connexion->lastInsertId() : $rst;
                    $this->__reset();
                    if($this->apiCall === true) return $this->response(["code"=>($rst > 0 ? 201 : 304), "error"=>false, "data"=>$rst]);
                    else return $rst;
                }else {
                    $code = Utils::startsWith(strtolower($this->requete), "delete") ? (($rst == true) ? 204 : 304) : (($rst == true) ? 201 : 304);
                    $this->__reset();
                    if($this->apiCall === true) return $this->response(["code"=>$code, "error"=>false, "data"=>$rst]);
                    else return $rst;
                }

            }
            catch (\PDOException $ex) {
                return $this->sfReturn($ex);
            }
        }
        $this->__reset();
        return ($this->apiCall === true) ? $this->response(['code'=> 500, 'error'=> true, 'msg'=> 'Erreur dans la requete SQL'.$this->requete]) : false;
    }

    /**
     * @param string $return
     * @return array|bool|mixed
     * @throws RestException
     */
    protected function __detail($return = 'object')
    {
        $result = $this->__select($return);
        if($this->apiCall === true) return (count($result) == 1) ? $this->response(["code"=>200, "error"=>false, "data"=>$result]) : $this->response(["code"=>404, "error"=>false]);
        else return (count($result) == 1) ? $result[0] : false;
    }

    /**
     * @return array|bool|int|string
     * @throws RestException
     */
    protected function __insert()
    {
        try {
            if (!\is_null($this->table) && \count($this->champs) > 0) {

                $this->setPrefix("insert");

                if ($this->table !== "logs") $description = serialize(["champs" => $this->champs, "condition" => $this->condition]);

                $this->value = array_values($this->champs);
                $this->champs = array_keys($this->champs);
                $this->requete = "INSERT INTO " . $this->table . " (" . implode(',', $this->champs) . ") VALUES (";
                $temp = [];
                foreach ($this->value as $item) array_push($temp, "?");
                $this->requete .= implode(',', $temp) . ")";

                $resultat = $this->connexion->prepare($this->requete);
                $resultat = $resultat->execute($this->value);
                $lastInsertId = $this->connexion->lastInsertId();
                if (isset($description) && $this->table !== "logs" && $this->appConfig->log == 1) $this->__logs(["action" => "insert", "currenttable" => $this->db_prefix . $this->table, "description" => $description, "currentid" => $lastInsertId, "result" => 'Reussie']);
                $this->__reset();
                if($this->apiCall === true) return ($resultat !== 0) ? $this->response(["code"=>201, "error"=>false, "data"=>$lastInsertId]) : $this->response(["code"=>304, "error"=>false]);
                else return ($resultat !== 0) ? $lastInsertId : false;
            }
        }
        catch (\PDOException $ex) {
            if (isset($description) && $this->table !== "logs" && $this->appConfig->log == 1) {
                $paramLogs = ["action" => "insert", "currenttable" => $this->db_prefix . $this->table, "description" => $description, "currentid" => $this->connexion->lastInsertId(), "result" => 'Echoue'];
                $this->__logs($paramLogs);
            }
            return $this->sfReturn($ex);
        }
        $this->__reset();
        return ($this->apiCall === true) ? $this->response(['code'=> 500, 'error'=> true, 'msg'=> 'Erreur dans la requete SQL'.$this->requete]) : false;
    }

    /**
     * @return array|bool|int|string
     * @throws RestException
     */
    protected function __update()
    {
        try {
            if (!\is_null($this->table) && \count($this->champs) > 0 && \count($this->condition) > 0) {

                $this->setPrefix("update");

                if ($this->table !== "logs") $description = serialize(["champs" => $this->champs, "condition" => $this->condition]);

                if (count($this->value) == 0) {
                    $this->value = array_values($this->champs);
                    $valueCond = array_values($this->condition);
                    $this->condition = array_map(function ($one) {
                        return $one = $one . '?';
                    }, array_keys($this->condition));
                } else $this->value = array_merge(array_values($this->champs), $this->value);

                $this->requete = "UPDATE " . $this->table . " SET ";
                $this->champs = array_map(function ($one) {
                    return $one = (count(explode('=', $one)) > 1) ? $one . ' ?' : $one . ' = ?';
                }, array_keys($this->champs));
                $this->requete .= implode(',', $this->champs) . "  WHERE " . implode(" AND ", $this->condition);
                $this->value = (isset($valueCond)) ? array_merge($this->value, $valueCond) : $this->value;

                $resultat = $this->connexion->prepare($this->requete);
                $resultat = $resultat->execute($this->value);
                if (isset($description) && $this->table !== "logs" && $this->appConfig->log == 1) $this->__logs(["action" => "update", "currenttable" => $this->db_prefix . $this->table, "description" => $description, "currentid" => $this->connexion->lastInsertId(), "result" => 'Reussie']);
                $this->__reset();
                if($this->apiCall === true) return $resultat ? $this->response(["code"=>201, "error"=>false]) : $this->response(["code"=>304, "error"=>false]);
                else return $resultat;
            }
        }
        catch (\PDOException $ex) {
            if (isset($description) && $this->table !== "logs" && $this->appConfig->log == 1) {
                $paramLogs = ["action" => "update", "currenttable" => $this->db_prefix . $this->table, "description" => $description, "currentid" => $this->connexion->lastInsertId(), "result" => 'Echoue'];
                $this->__logs($paramLogs);
            }
            return $this->sfReturn($ex);
        }
        $this->__reset();
        return ($this->apiCall === true) ? $this->response(['code'=> 500, 'error'=> true, 'msg'=> 'Erreur dans la requete SQL'.$this->requete]) : false;
    }

    /**
     * @return array|bool
     * @throws RestException
     */
    protected function __delete()
    {
        try {
            if (!\is_null($this->table) && \count($this->condition) > 0) {

                $this->setPrefix("delete");

                if ($this->table !== "logs") $description = serialize(["champs" => $this->champs, "condition" => $this->condition]);

                $this->requete = "DELETE FROM " . $this->table;

                if (count($this->value) == 0) {
                    $this->value = array_values($this->condition);
                    $this->condition = array_map(function ($one) {
                        return $one = $one . ' ?';
                    }, array_keys($this->condition));
                }
                $this->requete .= " WHERE " . implode(" AND ", $this->condition);

                $resultat = $this->connexion->prepare($this->requete);
                $resultat->execute($this->value);
                if (isset($description) && $this->table !== "logs" && $this->appConfig->log == 1) $this->__logs(["action" => "delete", "currenttable" => $this->db_prefix . $this->table, "description" => $description, "currentid" => $this->connexion->lastInsertId(), "result" => 'Reussie']);
                $this->__reset();
                if($this->apiCall === true) return ($resultat->rowCount() == 1) ? $this->response(["code"=>204, "error"=>false]) : $this->response(["code"=>304, "error"=>false]);
                else return $resultat->rowCount() == 1 ? true : false;
            }
        }
        catch (\PDOException $ex) {
            if (isset($description) && $this->table !== "logs" && $this->appConfig->log == 1) {
                $paramLogs = ["action" => "delete", "currenttable" => $this->db_prefix . $this->table, "description" => $description, "currentid" => $this->connexion->lastInsertId(), "result" => 'Echoue'];
                $this->__logs($paramLogs);
            }
            return $this->sfReturn($ex);
        }
        $this->__reset();
        return ($this->apiCall === true) ? $this->response(['code'=> 500, 'error'=> true, 'msg'=> 'Erreur dans la requete SQL'.$this->requete]) : false;
    }

    /**
     * @return array|bool|int|string
     * @throws RestException
     */
    protected function __processing()
    {
        $requeteCount = null;
        try {
            if (!\is_null($this->table)) {

                $this->setPrefix("select");

                $this->requete = "SELECT * ";

                if (count($this->champs) > 0) {
                    $this->requete = "SELECT " . implode(",", $this->champs);
                    $requeteCount = "SELECT COUNT(" . explode(' AS ', str_replace(' as ', ' AS ', $this->champs[0]))[0] . ") AS total";
                }
                $this->requete .= " FROM " . $this->table . " ";
                $requeteCount .= " FROM " . $this->table . " ";

                if (count($this->jointure) > 0) {
                    $this->requete .= implode(" ", $this->jointure) . " ";
                    $requeteCount .= implode(" ", $this->jointure) . " ";
                }

                unset($this->champs[0]);

                if (Session::existeAttribut("default_sort")) {
                    $this->sort = Session::getAttributArray("default_sort");
                    Utils::unsetDefaultSort();
                };

                $this->champs = array_map(function ($one) {
                    return $one = explode(" AS ", str_replace(' as ', ' AS ', $one))[0];
                }, array_values($this->champs));

                if (count($this->condition) > 0) {
                    if (count($this->value) == 0) {
                        $this->value = array_values($this->condition);
                        $this->condition = array_map(function ($one) {
                            return $one = $one . ' ?';
                        }, array_keys($this->condition));
                    }
                    $this->requete .= "  WHERE " . implode(" AND ", $this->condition);
                    $requeteCount .= "  WHERE " . implode(" AND ", $this->condition);
                    if ($_REQUEST['search']['value'] != "") {
                        $this->requete .= " AND (" . implode(" LIKE ? OR ", $this->champs) . " LIKE ? )";
                        $requeteCount .= " AND (" . implode(" LIKE ? OR ", $this->champs) . " LIKE ? )";
                        foreach ($this->champs as $item) array_push($this->value, "%" . $_REQUEST['search']['value'] . "%");
                    }
                } elseif ($_REQUEST['search']['value'] != "") {
                    $this->requete .= " WHERE (" . implode(" LIKE ? OR ", $this->champs) . " LIKE ? )";
                    $requeteCount .= " WHERE (" . implode(" LIKE ? OR ", $this->champs) . " LIKE ? )";
                    foreach ($this->champs as $item) array_push($this->value, "%" . $_REQUEST['search']['value'] . "%");
                }
            }

            if (count($this->group) > 0) {
                $this->requete .= " GROUP BY " . implode(", ", $this->group);
                $requeteCount .= " GROUP BY " . implode(", ", $this->group);
            }

            if (count($this->sort) > 0) {
                $_REQUEST['order'][0]['column'] = intval($this->sort[0]) - 1;
                $_REQUEST['order'][0]['dir'] = $this->sort[1];
            }

            $this->requete .= (intval($_REQUEST['order'][0]['column']) < count($this->champs)) ?
                " ORDER BY " . $this->champs[$_REQUEST['order'][0]['column']] . " " . strtoupper($_REQUEST['order'][0]['dir']) :
                " ORDER BY " . $this->champs[0] . " " . strtoupper($_REQUEST['order'][0]['dir']);

            $this->requete .= " LIMIT " . $_REQUEST['start'] . " ," . $_REQUEST['length'];

            if (!\is_null($this->requete)) {
                $resultat = $this->connexion->prepare($this->requete);
                (count($this->value) > 0) ? $resultat->execute($this->value) : $resultat->execute();

                $total = $this->connexion->prepare($requeteCount);
                (count($this->value) > 0) ? $total->execute($this->value) : $total->execute();

                $this->__reset();
                if($this->apiCall === true) return $this->response(["code"=>200, "error"=>false, "data"=>[$resultat->fetchAll(\PDO::FETCH_ASSOC), $total->fetchAll(\PDO::FETCH_OBJ)[0]->total]]);
                else return [$resultat->fetchAll(\PDO::FETCH_ASSOC), $total->fetchAll(\PDO::FETCH_OBJ)[0]->total];
            }
        }
        catch (\PDOException $ex) {
            return $this->sfReturn($ex);
        }
        $this->__reset();
        return ($this->apiCall === true) ? $this->response(['code'=> 500, 'error'=> true, 'msg'=> 'Erreur dans la requete SQL'.$this->requete]) : false;
    }

    /**
     * @return array|bool|int|string
     * @throws RestException
     */
    protected function __pagination()
    {
        if(!(isset($this->params['limit']['start']) && isset($this->params['limit']['length']))) $this->params['limit'] = ['start'=>0, 'length'=>10];
        $requeteCount = null;
        if (!\is_null($this->table)) {
            try {
                $this->setPrefix("select");

                $this->requete = "SELECT *";
                $requeteCount = "SELECT COUNT(id) AS total";

                if (count($this->champs) > 0) {
                    $this->requete = "SELECT " . implode(",", $this->champs);
                    $requeteCount = "SELECT COUNT(" . explode(' AS ', str_replace(' as ', ' AS ', $this->champs[0]))[0] . ") AS total";
                }

                $this->requete .= " FROM " . $this->table . " ";
                $requeteCount .= " FROM " . $this->table . " ";

                if (count($this->jointure) > 0) {
                    $this->requete .= implode(" ", $this->jointure) . " ";
                    $requeteCount .= implode(" ", $this->jointure) . " ";
                }

                unset($this->champs[0]);

                if (Session::existeAttribut("default_sort")) {
                    $this->sort = Session::getAttributArray("default_sort");
                    Utils::unsetDefaultSort();
                };

                $this->champs = array_map(function ($one) {
                    return $one = explode(" AS ", str_replace(' as ', ' AS ', $one))[0];
                }, array_values($this->champs));

                if (count($this->condition) > 0) {
                    if (count($this->value) == 0) {
                        $this->value = array_values($this->condition);
                        $this->condition = array_map(function ($one) {
                            return $one = $one . ' ?';
                        }, array_keys($this->condition));
                    }
                    $this->requete .= "  WHERE " . implode(" AND ", $this->condition);
                    $requeteCount .= "  WHERE " . implode(" AND ", $this->condition);
                    if (isset($this->params['search'])) {
                        $this->requete .= " AND (" . implode(" LIKE ? OR ", $this->champs) . " LIKE ? )";
                        $requeteCount .= " AND (" . implode(" LIKE ? OR ", $this->champs) . " LIKE ? )";
                        foreach ($this->champs as $item) array_push($this->value, "%" . $this->params['search'] . "%");
                    }
                } elseif (isset($this->params['search'])) {
                    $this->requete .= " WHERE (" . implode(" LIKE ? OR ", $this->champs) . " LIKE ? )";
                    $requeteCount .= " WHERE (" . implode(" LIKE ? OR ", $this->champs) . " LIKE ? )";
                    foreach ($this->champs as $item) array_push($this->value, "%" . $this->params['search'] . "%");
                }

                if (count($this->group) > 0) {
                    $this->requete .= " GROUP BY " . implode(", ", $this->group);
                    $requeteCount .= " GROUP BY " . implode(", ", $this->group);
                }

                if (count($this->sort) > 0)
                    $this->requete .= (count($this->sort) === 1) ? " ORDER BY " . $this->sort[0] . " ASC" : " ORDER BY " . $this->sort[0] . " " . $this->sort[1];

                $this->requete .= "LIMIT " . $this->params['limit']['start'] . " ," . $this->params['limit']['length'];

                if (!\is_null($this->requete)) {
                    $resultat = $this->connexion->prepare($this->requete);
                    (count($this->value) > 0) ? $resultat->execute($this->value) : $resultat->execute();

                    $total = $this->connexion->prepare($requeteCount);
                    (count($this->value) > 0) ? $total->execute($this->value) : $total->execute();

                    $this->__reset();
                    if($this->apiCall === true) return $this->response(["code"=>200, "error"=>false, "data"=>[$resultat->fetchAll(\PDO::FETCH_ASSOC), $total->fetchAll(\PDO::FETCH_OBJ)[0]->total]]);
                    else return [$resultat->fetchAll(\PDO::FETCH_ASSOC), $total->fetchAll(\PDO::FETCH_OBJ)[0]->total];
                }
            }
            catch (\PDOException $ex) {
                return $this->sfReturn($ex);
            }
        }
        $this->__reset();
        return ($this->apiCall === true) ? $this->response(['code'=> 500, 'error'=> true, 'msg'=> 'Erreur dans la requete SQL'.$this->requete]) : false;
    }

    /**
     * @throws \Exception
     */
    public function beginTransaction()
    {
        $this->connexion = Database::getConnexion($this->db_active);
        $this->connexion->beginTransaction();
    }

    /**
     * @throws \Exception
     */
    public function commit()
    {
        $this->connexion = Database::getConnexion($this->db_active);
        $this->connexion->commit();
    }

    /**
     * @throws \Exception
     */
    public function rollBack()
    {
        $this->connexion = Database::getConnexion($this->db_active);
        $this->connexion->rollBack();
    }

    /**
     * @param \PDOException $ex
     * @return int|string
     */
    public function sfReturn(\PDOException $ex)
    {
        $this->__reset();
        if($this->apiCall === true)  {
            Utils::responseAPI(500, $this->response(['code'=>500, 'error'=>true, 'msg'=>$ex->getMessage(), 'data'=>[$ex->getTrace()]]));
            return 0;
        }
        elseif($this->socketConnexion) {
            print ($ex->getMessage()."\n".$ex->getTrace()[2]['file'] . " - " . $ex->getTrace()[2]['line'] . "\n" . $ex->getTrace()[1]['file'] . " - " . $ex->getTrace()[1]['line']."\n");exit();
        }
        else {
            Utils::setMessageError([
                'sql',
                $this->requete . " ** " . implode("; ", $this->value) . " ** " . "<br/>"
                . $ex->getMessage() . ' <a target=\'_blank\' href=\'https://stackoverflow.com/search?q=' . $ex->getMessage() . '\'>Stack help me !</a><br/>'
                . $ex->getTrace()[2]['file'] . " - " . $ex->getTrace()[2]['line'] . "<br/>" . $ex->getTrace()[1]['file'] . " - " . $ex->getTrace()[1]['line']
            ]);
            $this->__reset();
            return $this->requete . " ** " . implode("; ", $this->value) . " ** " . $ex->getMessage() . " - " . $ex->getTrace()[2]['file'] . " - " . $ex->getTrace()[2]['line'] . " - " . $ex->getTrace()[1]['file'] . " - " . $ex->getTrace()[1]['line'];
        }
    }

    private function __reset()
    {
        $this->table = null;
        $this->requete = null;
        $this->jointure = [];
        $this->champs = [];
        $this->value = [];
        $this->condition = [];
        $this->sort = [];
        $this->limit = [];
        $this->group = [];
    }

    /**
     * @param $param
     * @throws \Jacwright\RestServer\RestException
     * @return mixed
     */
    private function __logs($param)
    {
        $this->__reset();
        $param['sf_user_id'] = $this->_USER->id;
        $this->table = "logs";
        $this->champs = $param;
        $this->__insert();
        Utils::writeFileLogs(implode(" ** ", $param));
        return $param['currentid'];
    }

    /**
     * @param $param
     */
    protected function __addParam($param)
    {
        if (isset($param['db_active'])) $this->db_active = $param['db_active'];
        if (isset($param['requete'])) $this->requete = $param['requete'];
        if (isset($param['table'])) $this->table = $param['table'];
        if (isset($param['jointure']) && count($param['jointure']) > 0) $this->jointure = $param['jointure'];
        if (isset($param['champs']) && count($param['champs']) > 0) $this->champs = $param['champs'];
        if (isset($param['condition']) && count($param['condition']) > 0) $this->condition = $param['condition'];
        if (isset($param['value']) && count($param['value']) > 0) $this->value = $param['value'];
        if (isset($param['sort']) && count($param['sort']) > 0) $this->sort = $param['sort'];
        if (isset($param['limit']) && count($param['limit']) > 0) $this->limit = $param['limit'];
        if (isset($param['group']) && count($param['group']) > 0) $this->group = $param['group'];
    }

    /**
     * @param $controller
     * @param $action
     * @param null $module
     * @param null $sousModule
     * @param bool $data
     * @return array|bool|int|string
     * @throws RestException
     */
    private function __authorized($controller, $action, $module = null, $sousModule = null, $data = false)
    {
        if(is_null($this->_USER)) return true;
        $espace = $this->apiCall ? strtoupper($this->espace) : strtoupper(SPACE);
        $this->table = "sf_affectation_droit ad";
        $this->champs = $data ? ['d.*'] : ['d.id'];
        $this->jointure = ($this->appConfig->profile_level == 1) ? [
            "INNER JOIN sf_profil p ON ad.sf_profil_id = p.id",
            "INNER JOIN sf_droit d ON ad.sf_droit_id = d.id",
            "INNER JOIN sf_sous_module sm ON d.sf_sous_module_id = sm.id",
            "INNER JOIN sf_module m ON sm.sf_module_id = m.id"
        ] : [
            "INNER JOIN sf_profil p ON ad.sf_profil_id = p.id",
            "INNER JOIN sf_droit d ON ad.sf_droit_id = d.id",
            "INNER JOIN sf_sous_module sm ON d.sf_sous_module_id = sm.id",
            "INNER JOIN sf_module m ON sm.sf_module_id = m.id",
            "INNER JOIN sf_affectation_droit_user adu ON ad.id = adu.sf_affectation_droit_id",
            "INNER JOIN sf_user u ON adu.sf_user_id = u.id"
        ];

        if (!is_null($module) && is_null($sousModule)) {
            $this->condition = ($this->appConfig->profile_level == 1) ?
                ["p.id =" => $this->_USER->{PROFIL_ATT}, "UPPER(d.espace) =" => $espace, "UPPER(m.libelle) =" => strtoupper($module), "p.etat =" => 1, "d.etat =" => 1, "ad.etat =" => 1] :
                ["u.id =" => $this->_USER->id, "p.id =" => $this->_USER->{PROFIL_ATT}, "UPPER(d.espace) =" => $espace, "UPPER(m.libelle) =" => strtoupper($module), "p.etat =" => 1, "d.etat =" => 1, "ad.etat =" => 1, "adu.etat =" => 1];
        } elseif (!is_null($sousModule) && is_null($module)) {
            $this->condition = ($this->appConfig->profile_level == 1) ?
                ["p.id =" => $this->_USER->{PROFIL_ATT}, "UPPER(d.espace) =" => $espace, "UPPER(sm.libelle) =" => strtoupper($sousModule), "p.etat =" => 1, "d.etat =" => 1, "ad.etat =" => 1] :
                ["u.id =" => $this->_USER->id, "p.id =" => $this->_USER->{PROFIL_ATT}, "UPPER(d.espace) =" => $espace, "UPPER(sm.libelle) =" => strtoupper($sousModule), "p.etat =" => 1, "d.etat =" => 1, "ad.etat =" => 1, "adu.etat =" => 1];
        } else {
            $this->condition = ($this->appConfig->profile_level == 1) ?
                ["p.id =" => $this->_USER->{PROFIL_ATT}, "UPPER(d.espace) =" => $espace, "UPPER(d.controller) =" => strtoupper($controller), "UPPER(d.action) =" => strtoupper($action), "p.etat =" => 1, "d.etat =" => 1, "ad.etat =" => 1] :
                ["u.id =" => $this->_USER->id, "p.id =" => $this->_USER->{PROFIL_ATT}, "UPPER(d.espace) =" => $espace, "UPPER(d.controller) =" => strtoupper($controller), "UPPER(d.action) =" => strtoupper($action), "p.etat =" => 1, "d.etat =" => 1, "ad.etat =" => 1, "adu.etat =" => 1];
        }
        $this->db_active = 1;
        return $data ? $this->__select() : ((is_null($this->_USER) || $this->_USER->admin == 1) ? true : (count($this->__select()['data']) > 0));
    }

    /**
     * @param $param
     * @return array|bool|int|string
     * @throws RestException
     */
    public function get($param)
    {
        $this->__addParam($param);
        return $this->__select();
    }

    /**
     * @param $param
     * @return array|bool|int|string
     * @throws RestException
     */
    public function set($param)
    {
        $this->__addParam($param);
        return (isset($param['champs']) && isset($param['condition'])) ? $this->__update() : ((!isset($param['champs']) && isset($param['condition'])) ? $this->__delete() : $this->__insert());
    }

    /**
     * @param $controller
     * @param $action
     * @param null $module
     * @param null $sousModule
     * @param bool $data
     * @return array|bool|int|string
     * @throws RestException
     */
    public function authorized($controller, $action, $module = null, $sousModule = null, $data = false)
    {
        return $this->__authorized($controller, $action, $module, $sousModule, $data);
    }

    /**
     * @param null $USER
     */
    public function setUSER($USER)
    {
        $this->_USER = $USER;
    }

    /**
     * @return array|bool
     */
    public function getDbConfig()
    {
        $temp = $this->dbConfig;
        $db_active = $this->db_active == "" ? "DB_" : "DB".$this->db_active."_";
        foreach ($temp as $key => $val)
            if(!Utils::startsWith($key, $db_active)) unset($temp[$key]);
        unset($temp[$db_active.'PASSWORD']);
        return $temp;
    }

    use CommonModel;

    use Response;
}
