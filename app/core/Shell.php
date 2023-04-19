<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 17/08/2017
 * Time: 11:01
 */

namespace app\core;
class Shell
{
    private static $instance = null;
    private $argv = [];
    private $appConfig;
    private $dbConfig;
    private $shellModel = [];
    private $action = null;
    private $params = [];

    /**
     * Shell constructor.
     */
    public function __construct()
    {
        error_reporting(E_ALL ^ E_NOTICE);
        define('ROOT', $_SERVER['PWD']."/");
        $this->shellModel = new Model(["socketConnexion"=>true]);
        $this->appConfig = \parse_ini_file(ROOT . 'config/app.config.ini');
        $this->dbConfig = $this->shellModel->getDbConfig();
    }

    public function makecrud() {
        if(count($this->params) > 0){
            if($this->params[0] === "-i") {
                $space = ["default"];
                foreach ($this->appConfig as $key => $value)
                    if(Utils::startsWith($key, 'space_'))
                        array_push($space, str_replace('space_', '', strtolower($key)));
                do {
                    $table = readline("Le nom de la table cible pour générer le CRUD (*) --table: ");
                }while(!$table);
                $rename = readline("Donner un autre nom à utiliser au lieu du nom de la table --rename: ");
                $space = readline("L'espace ou sera générer le CRUD [".implode(" | ", $space)."] --space: ");
                $force = readline("Si 'y' et que le CRUD existe déja il sera regénérer --force(y/n): ");
                $force = $force == "y" ? true : false;
                $space = $space == '' || $space == 'default' ? '' : $space;
            }
            else{
                $tabArg = [];
                foreach ($this->params as $item) {
                    $item = explode(":", $item);
                    $item[0] = str_replace("--", "", $item[0]);
                    $tabArg[$item[0]] = $item[1] ?? true;
                }
                extract($tabArg);
            }
        }
        if(isset($table) && $table !== false) {
            $table = strtolower($table);
            $tableAlias = $rename ?? null;
            $space = $space ?? '';
            if(isset($force)) $force = $force == 'true' ? true : false;
            if(isset($force) && $force === true) {
                $argvUnmake = ["maker", "unmake:crud", "--name:".($rename ?? $table)];
                if($space !== '') array_push($argvUnmake, "--space:$space");
                self::domake($argvUnmake);
            }

            if($space != '') {
                if(!isset($this->appConfig["space_$space"])) {
                    print("L'espace $space n'existe pas !\n");
                    exit();
                }
            }

            if(!is_null($tableAlias)) {
                $spaceCont = isset($this->appConfig["space_$space"]) ? "app\controllers\\$space" : "app\controllers";
                $spaceModel = isset($this->appConfig["space_$space"]) ? "app\models\\$space" : "app\models";
                $pathCont = isset($this->appConfig["space_$space"]) ? ROOT."app/controllers/$space/".ucfirst($tableAlias)."Controller.php" : ROOT."app/controllers/".ucfirst($tableAlias)."Controller.php";
                $pathModel = isset($this->appConfig["space_$space"]) ? ROOT."app/models/$space/".ucfirst($tableAlias)."Model.php" : ROOT."app/models/".ucfirst($tableAlias)."Model.php";
                $pathViews = isset($this->appConfig["space_$space"]) ? ROOT."app/views/$space/$tableAlias" : ROOT."app/views/$tableAlias";
                if(file_exists($pathCont)){
                    print("Le controlleur ".ucfirst($tableAlias)."Controller existe déja !\n");
                    exit();
                }
            }
            else {
                $spaceCont = isset($this->appConfig["space_$space"]) ? "app\controllers\\$space" : "app\controllers";
                $spaceModel = isset($this->appConfig["space_$space"]) ? "app\models\\$space" : "app\models";
                $pathCont = isset($this->appConfig["space_$space"]) ? ROOT."app/controllers/$space/".ucfirst($table)."Controller.php" : ROOT."app/controllers/".ucfirst($table)."Controller.php";
                $pathModel = isset($this->appConfig["space_$space"]) ? ROOT."app/models/$space/".ucfirst($table)."Model.php" : ROOT."app/models/".ucfirst($table)."Model.php";
                $pathViews = isset($this->appConfig["space_$space"]) ? ROOT."app/views/$space/$table" : ROOT."app/views/$table";
            }

            $params = ($this->dbConfig["DB".$this->shellModel->db_active."_TYPE"] == "mysql") ?
                ['table'=>'INFORMATION_SCHEMA.COLUMNS', 'champs'=>['COLUMN_NAME', 'DATA_TYPE', 'COLUMN_COMMENT', 'IS_NULLABLE', 'COLUMN_TYPE', 'COLUMN_KEY'], 'condition'=>['TABLE_NAME ='=>$table, 'TABLE_SCHEMA ='=>$this->dbConfig["DB".$this->shellModel->db_active."_NAME"]]]:
                ['table'=>'sqlite_master', 'champs'=>['sql'], 'condition'=>['tbl_name = ? AND sql IS NOT NULL'], 'value'=>[$table]];
            $result = $this->shellModel->get($params);

            if(count($result) == 0) {
                print("La table $table n'existe pas !\n");
                exit();
            }
            else {
                if($this->dbConfig["DB".$this->shellModel->db_active."_TYPE"] == "sqlite") {
                    $temp = explode("(", $result[0]->sql);
                    $temp = trim(preg_replace("#\n|\t|\r|\*/|\*#", " ", $temp[1]));
                    $temp = str_replace("`", "", str_replace(")", "", $temp));
                    $temp = explode(",", $temp);
                    $comment = null;
                    foreach ($temp as $key => $item){
                        if(\app\core\Utils::startsWith(trim($item), "_comment_column_")){
                            $comment = str_replace("'", "", trim(explode("DEFAULT", $item)[1]));
                            unset($temp[$key]);
                        }
                    }

                    if(!is_null($comment)) {
                        $comment = explode(";", $comment);
                        $comment = array_map(function ($one){ $one = explode("|", $one); return [$one[0] => $one[1]];}, $comment);
                        foreach ($comment as $key => $item) {
                            $comment[array_keys($item)[0]] = array_values($item)[0];
                            unset($comment[$key]);
                        }
                    }
                    foreach ($temp as $key => $item) {
                        $item = str_replace("NOT NULL", "NOTNULL", $item);
                        $item = explode(" ", trim($item));
                        $item[1] = (strtoupper($item[1]) == "INTEGER" || strtoupper($item[1]) == "INT") ? "int" : ((strtoupper($item[1]) == "DATE") ? "DATETIME" : "varchar");
                        $tempCom = !is_null($comment) ? ($comment[$item[0]] ?? '') : '';
                        $temp[$key] = (Object)[
                            "COLUMN_NAME" => $item[0],
                            "DATA_TYPE" => $item[1],
                            "COLUMN_COMMENT" => $tempCom,
                            "IS_NULLABLE" => (in_array("NOTNULL", array_values($item)) ? "NO" : "YES"),
                        ];
                    }
                    $result = $temp;
                }
                $table2 = $tableAlias ?? $table;
                $result = array_map(function ($one) use ($table2) {
                    $tabType = [
                        "number"=>["INT", "TINYINT", "SMALLINT", "MEDIUMINT", "BIGINT", "DECIMAL", "FLOAT", "DOUBLE", "REAL", "BIT", "BOOLEAN", "SERIAL"],
                        "text"=>["YEAR", "CHAR", "VARCHAR", "TINYTEXT", "TEXT", "MEDIUMTEXT", "LONGTEXT", "BINARY", "VARBINARY", "TINYBLOB", "MEDIUMBLOB", "BLOB", "LONGBLOB","SET"],
                        "enum"=>["ENUM"],
                        "date"=>["DATE"],
                        "time"=>["TIME"],
                        "datetime-local"=>["DATETIME"]
                    ];
                    foreach ($tabType as $key => $val){
                        if(in_array(strtoupper($one->DATA_TYPE), $val)){
                            $one->DATA_TYPE = $key;
                            if($key == "enum") {
                                $one->COLUMN_TYPE = str_replace("enum(", "", $one->COLUMN_TYPE);
                                $one->COLUMN_TYPE = str_replace(")", "", $one->COLUMN_TYPE);
                                $one->COLUMN_TYPE = str_replace("'", "", $one->COLUMN_TYPE);
                                $temp = [$table2,$one->COLUMN_NAME];
                                $one->COLUMN_TYPE = array_map(function ($one2) use($temp){
                                    return '<option value="'.$one2.'" <?= ($'.$temp[0].'->'.$temp[1].' == "'.$one2.'") ? "selected" : "" ?> >'.$one2.'</option>';
                                }, explode(",", $one->COLUMN_TYPE));
                            }
                            return $one;
                        }
                    }
                    $one->DATA_TYPE = strtoupper($one->DATA_TYPE) == 'TIMESTAMP' ? $one->DATA_TYPE : "text";
                    return $one;
                }, $result);
                $controller = file_get_contents(ROOT.'app/core/BaseCRUD/Controller.php');
                $controller = str_replace("_namespace_", $spaceCont, $controller);
                $controller = str_replace("_Name_crud_", ucfirst(($tableAlias ?? $table)), $controller);
                $controller = str_replace("_name_crud_table_", $table, $controller);
                $controller = str_replace("_name_crud_", ($tableAlias ?? $table), $controller);
                $fonction_processing = $result;
                $fonction_processing = \app\core\Utils::setPurgeArray(array_map(function ($one){ return ($one->DATA_TYPE == "datetime-local" || $one->DATA_TYPE == "date" || $one->DATA_TYPE == "timestamp") ? '"'.$one->COLUMN_NAME.'"=>"getDateFR"' : '' ; }, $fonction_processing));
                $controller = str_replace("_fonction_processing_", (count($fonction_processing) == 0 ? '': implode(',', $fonction_processing)), $controller);
                $foreign_keys = $result;
                $foreign_keys = array_map(function ($one) use($table) {
                    if(\app\core\Utils::endsWith($one->COLUMN_NAME,"_id")) {
                        $one = '$data["'.str_replace("_id", "", str_replace("sf_", "", str_replace($this->dbConfig["DB".$this->shellModel->db_active."_PREFIX"], "", $one->COLUMN_NAME))).'"] = $this->model->get(["table"=>"'.str_replace("_id", "", $one->COLUMN_NAME).'"]);';
                    }else $one = '';
                    return $one;
                }, $foreign_keys);
                $foreign_keys = \app\core\Utils::setPurgeArray($foreign_keys);
                $controller = (count($foreign_keys) > 0) ? str_replace("_foreign_keys_;", implode('', $foreign_keys), $controller) : str_replace("_foreign_keys_;", '', $controller);
                if($table === 'sf_user') {
                    $addMethod = file_get_contents(ROOT.'app/core/BaseCRUD/userMethod.txt');
                    $controller = str_replace("//_add_methods_;", str_replace("_name_crud_", ($tableAlias ?? $table), $addMethod), $controller);
                    $controller = str_replace("_add_params_", 'if($this->appConfig->profile_level == 2) array_push($param["button"]["default"],["'.($tableAlias ?? $table).'/affectation/","fa fa-male"])', $controller);
                }
                elseif($table === 'sf_profil') {
                    $addMethod = file_get_contents(ROOT.'app/core/BaseCRUD/profilMethod.txt');
                    $controller = str_replace("//_add_methods_;", str_replace("_name_crud_", ($tableAlias ?? $table), $addMethod), $controller);
                    $controller = str_replace("_add_params_", 'array_push($param["button"]["default"],["'.($tableAlias ?? $table).'/affectation/","fa fa-male"])', $controller);
                }
                else{
                    $controller = str_replace("//_add_methods_;", "", $controller);
                    $controller = str_replace("_add_params_;", "", $controller);
                }
                $controller = str_replace("_date_", gmdate("d/m/Y"), $controller);
                $controller = str_replace("_heure_", gmdate("H:i"), $controller);

                //
                // creation du fichier controller
                //
                file_put_contents($pathCont, $controller);

                $model = file_get_contents(ROOT.'app/core/BaseCRUD/Model.php');
                $model = str_replace("_namespace_", $spaceModel, $model);
                $model = str_replace("_Name_crud_", ucfirst(($tableAlias ?? $table)), $model);
                $model = str_replace("_name_crud_table_", $table, $model);
                $model = str_replace("_name_crud_", ($tableAlias ?? $table), $model);
                $champProcess = $result;
                $jointureProcess = [];
                foreach ($champProcess as $key => $item) {
                    if(strpos($item->COLUMN_COMMENT,"out_list") !== false) $champProcess[$key] = $item->COLUMN_NAME .' AS _'.$item->COLUMN_NAME.'_';
                    elseif(strpos($item->COLUMN_COMMENT,"remp_") !== false) {
                        $tableJoinName = str_replace("_id", "", str_replace("sf_", "", str_replace($this->dbConfig["DB".$this->shellModel->db_active."_PREFIX"], "", $item->COLUMN_NAME)));
                        $tableJoin = str_replace("_id", "", $item->COLUMN_NAME);
                        array_push($jointureProcess, "INNER JOIN $tableJoin ON $table.".$item->COLUMN_NAME." = $tableJoin.id");
                        $remp = explode("remp_", $item->COLUMN_COMMENT);
                        array_shift($remp);
                        $remp = trim(explode(" ", $remp[0])[0]);
                        $champProcess[$key] = "$tableJoin.$remp AS $tableJoinName";
                    }else $champProcess[$key] = $item->COLUMN_NAME;
                }
                $champProcess = array_map(function ($one) use ($table){ return strpos($one, ".") !== false ? $one : "$table.$one";}, $champProcess);
                if(count($jointureProcess) > 0) {
                    $jointureProcess = '$this->jointure = ['. '"'.implode('","', $jointureProcess).'"]';
                    $model = str_replace("_jointure_processing_", $jointureProcess, $model);
                }else $model = str_replace("_jointure_processing_;", "", $model);
                $champProcess = '"'.implode('","', $champProcess).'"';
                $model = str_replace("_champs_processing_", $champProcess, $model);
                $model = str_replace("_date_", gmdate("d/m/Y"), $model);
                $model = str_replace("_heure_", gmdate("H:i"), $model);

                //
                // creation du fichier model
                //
                file_put_contents($pathModel, $model);

                if(!is_dir($pathViews)) \app\core\Utils::createDir((str_replace(ROOT, "", $pathViews)));
                $views = file_get_contents(ROOT.'app/core/BaseCRUD/views/liste.php');
                $views = str_replace("_Name_crud_", ucfirst(($tableAlias ?? $table)), $views);
                $views = str_replace("_name_crud_", ($tableAlias ?? $table), $views);
                $champProcessViews = $result;
                $paramUse = ["dbConfig" => $this->dbConfig, "db_active"=>$this->shellModel->db_active];
                $champProcessViews = array_map(function ($one) use ($paramUse){ extract($paramUse); return (strpos($one->COLUMN_COMMENT,"out_list") !== false) ? '' : '<th>'.ucfirst(str_replace("_", " ", str_replace("_id", "", str_replace("sf_", "", str_replace($this->dbConfig["DB".$this->shellModel->db_active."_PREFIX"], "", $one->COLUMN_NAME))))).'</th>' ; }, $champProcessViews);
                array_shift($champProcessViews);
                $champProcessViews = \app\core\Utils::setPurgeArray($champProcessViews);
                array_push($champProcessViews, '<th>Action</th>');
                $champProcessViews = '<tr>'.implode('', $champProcessViews).'</tr>';
                $views = str_replace("_champs_processing_views_", $champProcessViews, $views);

                //
                // creation du fichier list de la vue
                //
                file_put_contents("$pathViews/list.php", $views);

                $views = file_get_contents(ROOT.'app/core/BaseCRUD/views/Modal.php');
                $views = str_replace("_Name_crud_", ucfirst(($tableAlias ?? $table)), $views);
                $views = str_replace("_name_crud_", ($tableAlias ?? $table), $views);
                $champModalViews = $result;
                $paramUse["table"] = ($tableAlias ?? $table);
                $paramUse["table_"] = $table;
                $champModalViews = array_map(function ($one) use($paramUse) {
                    extract($paramUse);
                    if(isset($table) && isset($table_)){
                        if((strtoupper($one->DATA_TYPE) == 'TIMESTAMP' || strpos($one->COLUMN_COMMENT,"out_form") !== false)) $input = '';
                        elseif(strpos($one->COLUMN_COMMENT,"remp_") !== false) {
                            $remp = explode("remp_", $one->COLUMN_COMMENT);
                            array_shift($remp);
                            $remp = trim(explode(" ", $remp[0])[0]);
                            $input = '<div class="form-group" style="width: 100%;padding: 10px;"><label for="'.$one->COLUMN_NAME.'" class="control-label">'.ucfirst(str_replace("_", " ", str_replace("_id", "", str_replace("sf_", "", str_replace($paramUse[0]["DB".$paramUse[1]."_PREFIX"], "", $one->COLUMN_NAME))))).($one->IS_NULLABLE == "NO" ? " *" : "").'</label><select '.($one->IS_NULLABLE == "NO" ? "required" : "").' name="'.$one->COLUMN_NAME.'" id="'.$one->COLUMN_NAME.'" class="form-control" style="width: 100%"><?php foreach ($'.str_replace("_id", "", str_replace("sf_", "", str_replace($paramUse[0]["DB".$paramUse[1]."_PREFIX"], "", $one->COLUMN_NAME))).' as $item) { ?><option value="<?= $item->id ?>" <?= ($'.$table.'->'.$one->COLUMN_NAME.' == $item->id) ? "selected" : "" ?> ><?= $item->'.$remp.' ?></option><?php } ?></select><span class="help-block with-errors"> </span></div>';
                        }elseif($one->DATA_TYPE == "enum") $input = '<div class="form-group" style="width: 100%;padding: 10px;"><label for="'.$one->COLUMN_NAME.'" class="control-label">'.ucfirst($one->COLUMN_NAME).'</label><select '.($one->IS_NULLABLE == "NO" ? "required" : "").' name="'.$one->COLUMN_NAME.'" id="'.$one->COLUMN_NAME.'" class="form-control" style="width: 100%">'.implode(" ", $one->COLUMN_TYPE).'</select><span class="help-block with-errors"> </span></div>';
                        else{
                            $datCrypt = ($one->COLUMN_KEY == "UNI") ? 'onchange="unique(this, \'<?= \app\core\Utils::cryptString(serialize(["table" => "'.$table_.'", "champ" => "'.$one->COLUMN_NAME.'"])) ?>\')"' : '';
                            $input = '<div class="form-group" style="width: 100%;padding: 10px;"><label for="'.$one->COLUMN_NAME.'" class="control-label">'.ucfirst(str_replace("_", " ", str_replace("_id", "", str_replace("sf_", "", str_replace($paramUse[0]["DB".$paramUse[1]."_PREFIX"], "", $one->COLUMN_NAME))))).($one->IS_NULLABLE == "NO" ? " *" : "").'</label> <input '.$datCrypt.($one->IS_NULLABLE == "NO" ? " required" : "").' type="'.$one->DATA_TYPE.'" id="'.$one->COLUMN_NAME.'" name="'.$one->COLUMN_NAME.'" class="form-control" value="<?= $'.$table.'->'.$one->COLUMN_NAME.'; ?>" style="width: 100%" placeholder="'.(ucfirst(str_replace("_", " ", $one->COLUMN_NAME))).'"> <span class="help-block with-errors"> </span></div>';
                        }
                        return $input;
                    }else return $one;
                }, $champModalViews);

                array_shift($champModalViews);
                $champModalViews = \app\core\Utils::setPurgeArray($champModalViews);
                $champModalViews = implode('', $champModalViews);
                $views = str_replace("_champs_modal_views_", $champModalViews, $views);

                //
                // creation du fichier modal de la vue
                //
                file_put_contents("$pathViews/".($tableAlias ?? $table)."Modal.php", $views);

                if($table === 'sf_user') {
                    $views = file_get_contents(ROOT.'app/core/BaseCRUD/views/userAffectation.php');
                    $views = str_replace("_name_crud_", ($tableAlias ?? $table), $views);
                    file_put_contents("$pathViews/affectation.php", $views);
                }
                elseif($table === 'sf_profil') {
                    $views = file_get_contents(ROOT.'app/core/BaseCRUD/views/profilAffectation.php');
                    $views = str_replace("_name_crud_", ($tableAlias ?? $table), $views);
                    file_put_contents("$pathViews/affectation.php", $views);
                }
            }
        }
        else{
            print("L'argument [--table:valeur] est obligatoire !\n");
            exit();
        }
    }

    public function unmakecrud() {
        if($this->params[0] === "-i"){
            $space = ["default"];
            foreach ($this->appConfig as $key => $value)
                if(Utils::startsWith($key, 'space_'))
                    array_push($space, str_replace('space_', '', strtolower($key)));
            do {
                $name = readline("Le nom du CRUD (*) --name: ");
            }while(!$name);
            $space = readline("L'espace ou a été générer le CRUD [".implode(" | ", $space)."] --space: ");
            $space = $space == '' || $space == 'default' ? '' : $space;
        }else{
            $tabArg = [];
            foreach ($this->params as $item) {
                $item = explode(":", $item);
                $item[0] = str_replace("--", "", $item[0]);
                $tabArg[$item[0]] = $item[1];
            }
            extract($tabArg);
        }

        $table = $name ?? null;
        if(!is_null($table)) {
            $table = strtolower($table);
            $space = $space ?? '';
            $appConfig = \parse_ini_file(ROOT . 'config/app.config.ini');
            if($space != '') {
                if(!isset($appConfig["space_$space"])) {
                    print("L'espace $space n'existe pas !\n");
                    exit();
                }
            }
            $line = readline("Etes vous sur de vouloir effectuer cette opération IRREVERSIBLE ? (y/n) : ");
            if($line === "y" || $line === "Y") {
                $pathCont = isset($appConfig["space_$space"]) ? ROOT."app/controllers/$space/".ucfirst($table)."Controller.php" : ROOT."app/controllers/".ucfirst($table)."Controller.php";
                $pathModel = isset($appConfig["space_$space"]) ? ROOT."app/models/$space/".ucfirst($table)."Model.php" : ROOT."app/models/".ucfirst($table)."Model.php";
                $pathViews = isset($appConfig["space_$space"]) ? ROOT."app/views/$space/$table" : ROOT."app/views/$table";

                if(file_exists($pathCont)) \app\core\Utils::setDeleteFiles(str_replace(ROOT, "", $pathCont));
                if(file_exists($pathModel)) \app\core\Utils::setDeleteFiles(str_replace(ROOT, "", $pathModel));
                if(is_dir($pathViews)) \app\core\Utils::setDeleteFiles(str_replace(ROOT, "", $pathViews));
            }else {
                print("opeartion annulée !\n");
                exit();
            }
        }
        else{
            print("L'argument [--name:valeur] est obligatoire !\n");
            exit();
        }
    }

    /**
     * @param $argv
     */
    public static function domake($argv)
    {
        array_shift($argv);
        if(self::$instance === null) self::$instance = new Shell();
        self::$instance->argv = $argv;
        $action = self::$instance->action = str_replace(":", "", self::$instance->argv[0]);
        self::$instance->params = self::$instance->argv;
        array_shift(self::$instance->params);
        self::$instance->$action();
    }
}