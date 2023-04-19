<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 17/08/2017
 * Time: 11:03
 */

namespace app\core;

use app\common\CommonUtils;
use app\core\controllers\ClientController;
use Defuse\Crypto\Crypto;
use \Defuse\Crypto\Key;

class Utils
{

    public static function redirect($controleur = null, $action = "index", array $param = [], $espace = null)
    {
        $url = ($espace === "default") ? RACINE : ((is_null($espace)) ? WEBROOT : RACINE . $espace . "/");
        $action = (is_null($action)) ? "index" : $action;
        if (is_string($controleur)){
            $url .= $controleur . "/" . $action;
            if (count($param) > 0) $url .= "/" . implode('/', self::setBase64_encode_array($param));
        }
        header('Location:' . $url);
        exit();
    }

    /**
     * @param $module
     * @param null $data
     * @return null|object
     */
    public static function getModule($module, $data = null)
    {
        $info = null;
        if(file_exists(ROOT."app/modules/$module/$module.module.ini")){
            $info = \parse_ini_file(ROOT."app/modules/$module/$module.module.ini");
            $info = (object)$info;
        }
        return isset($info->{$data}) ? $info->{$data} : $info;
    }

    /**
     * @param $zipFile
     * @param $dir
     * @return bool
     */
    public static function extractZipFile($zipFile, $dir = '')
    {
        $retour = false;
        try{
            $zipFile = ROOT.$zipFile;
            $zipFile = self::endsWith($zipFile, 'zip') ? $zipFile : $zipFile.".zip";
            $dir = $dir == '' ? dirname($zipFile) : ROOT.$dir;
            if(file_exists($zipFile) && is_dir($dir)) {
                exec("unzip $zipFile -d $dir");
                $retour = true;
            }
        }catch(\Exception $ex) {
            self::redirect("error", "error", "default", [$ex->getMessage()]);
        }
        return $retour;
    }

    /**
     * @param $path
     * @param $dir
     * @return bool
     */
    public static function createZipFile($path, $dir = '')
    {
        $retour = false;
        try{
            $path = ROOT.$path;
            $zipFile = $dir == '' ? "$path.zip" : ROOT.$dir;
            if(!self::endsWith($zipFile, '.zip')) $zipFile .= ".zip";
            if(file_exists($zipFile)) unlink($zipFile);
            if(is_dir($path)) {
                ZipDir::create($path, $zipFile);
                $retour = true;
            }
        }catch(\Exception $ex) {
            self::redirect("error", "error", "default", [$ex->getMessage()]);
        }
        return $retour;
    }

    public static function sessionStarted()
    {
        if(\session_status() !== PHP_SESSION_ACTIVE && isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ini_set('session.cookie_secure', 1);
        if (\php_sapi_name() !== 'cli') {
            if (\version_compare(\phpversion(), '5.4.0', '>=')) {
                if(\session_status() !== PHP_SESSION_ACTIVE) {
                    session_cache_expire(30);
                    ini_set('session.use_strict_mode', 1);
                    session_start();
                }
            } else {
                if(\session_id() === ''){
                    session_cache_expire(30);
                    ini_set('session.use_strict_mode', 1);
                    session_start();
                }
            }
        }else {
            session_cache_expire(30);
            ini_set('session.use_strict_mode', 1);
            session_start();
        }
    }

    /**
     * @param $model
     * @return mixed
     */
    public static function getModel($model)
    {
        $_USER = (Session::existeAttribut(SESSIONNAME)) ? Session::getAttributArray(SESSIONNAME)[0] : null;
        $model = Prefix_Model . ucfirst($model) . 'Model';
        return new $model($_USER);
    }

    /**
     * @param $controller
     * @param $action
     * @param string $module
     * @param null $sousModule
     * @param bool $data
     * @return array|bool
     * @throws \Jacwright\RestServer\RestException
     */
    public static function authorized($controller, $action, $module = null, $sousModule = null, $data = false)
    {
        return (new Model())->authorized($controller, $action, $module, $sousModule, $data);
    }

    /**
     * @param $url
     * @param $params
     * @return mixed
     */
    public static function allowed($url, $params)
    {
        return self::apiClient()::post($url, $params);
    }

    public static function apiClient()
    {
        $appConfig = \parse_ini_file(ROOT . 'config/app.config.ini');
        return ($appConfig['use_api_client'] == "1") ? ClientController::initClient() : null;
    }

    /**
     * @param array $array
     * @return array
     */
    public static function setBase64_encode_array($array)
    {
        foreach ($array as $key => $value){
            if(!\is_array($value)) $array[$key] = base64_encode($value);
            else self::setBase64_encode_array($value);
        }
        return $array;
    }

    /**
     * @param array $array
     * @return array
     */
    public static function setPurgeArray($array)
    {
        if(is_array($array)){
            foreach ($array as $key => $value) {
                if(!\is_array($value)){
                    if(is_string($array[$key])) $array[$key] = trim($value);
                    if($value == '' || strlen(trim($value)) == 0)
                        unset($array[$key]);
                }
                else self::setPurgeArray($value);
            }
        }
        return $array;
    }

    /**
     * @param $dir
     * @return array
     */
    public static function getContentFileDir($dir)
    {
        $dir = scandir($dir);
        $dir = array_map(function ($item){return Utils::startsWith($item,'.') ? "" : ((pathinfo($item, PATHINFO_EXTENSION) != "") ? $item : "");},$dir);
        $dir = array_values(self::setPurgeArray($dir));
        return $dir;
    }

    /**
     * @param $dir
     * @return array
     */
    public static function getContentDir($dir)
    {
        $dir = scandir($dir);
        $dir = array_map(function ($item){return Utils::startsWith($item,'.') ? "" : $item;},$dir);
        $dir = array_values(self::setPurgeArray($dir));
        return $dir;
    }

    /**
     * @param $valeur
     * @return bool
     */
    public static function isBase64($valeur)
    {
        $decoded_data = base64_decode($valeur, true);
        $encoded_data = base64_encode($decoded_data);
        if ($encoded_data != $valeur) return false;
        else if (!ctype_print($decoded_data)) return false;
        return true;
    }

    /**
     * @param array $array
     * @return array
     */
    public static function setBase64_decode_array($array)
    {
        if(count($array) > 0){
            foreach ($array as $key => $value){
                if(!\is_array($value)) $array[$key] = self::isBase64($value) ? base64_decode($value) : $value;
                else self::setBase64_decode_array($value);
            }
        }
        return $array;
    }

    /**
     * @param int $length
     * @return string
     */
    public static function getAlphaNumerique($length = 10)
    {
        $string = "";
        $chaine = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        \srand((double)\microtime() * 1000000);
        for ($i = 0; $i < $length; $i++) $string .= $chaine[\rand() % \strlen($chaine)];
        return $string;
    }

    /**
     * @param $pass
     * @return bool|null|string
     */
    public static function getPassCrypt($pass)
    {
        $timeTarget = 0.05; // 50 millisecondes
        $cost = 8;
        $passHasher = null;
        do {
            $cost++;
            $start = \microtime(true);
            $passHasher = \password_hash($pass, PASSWORD_BCRYPT, ["cost" => $cost]);
            $end = \microtime(true);
        } while (($end - $start) < $timeTarget);
        return $passHasher;
    }

    /**
     * @param $lenght
     * @return bool|string
     */
    public static function random($lenght = 8) {
        $return = null;
        if (function_exists('openssl_random_pseudo_bytes')) {
            $byteLen = intval(($lenght / 2) + 1);
            $return = substr(bin2hex(openssl_random_pseudo_bytes($byteLen)), 0, $lenght);
        } elseif (@is_readable('/dev/urandom')) {
            $f=fopen('/dev/urandom', 'r');
            $urandom=fread($f, $lenght);
            fclose($f);
        }

        if (is_null($return)) {
            for ($i=0; $i<$lenght; ++$i) {
                if (!isset($urandom)) {
                    if ($i%2==0) {
                        mt_srand(time()%2147 * 1000000 + (double)microtime() * 1000000);
                    }
                    $rand=48+mt_rand()%64;
                } else {
                    $rand=48+ord($urandom[$i])%64;
                }

                if ($rand>57)
                    $rand+=7;
                if ($rand>90)
                    $rand+=6;

                if ($rand==123) $rand=52;
                if ($rand==124) $rand=53;
                $return .= chr($rand);
            }
        }
        return $return;
    }

    /**
     * @param $length
     * @return array
     */
    public static function getGeneratePassword($length = 8)
    {
        // on declare une chaine de caractÃ¨res
        $chaine = "abcdefghijklmnopqrstuvwxyz@ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        //nombre de caractÃ¨res dans le mot de passe
        $pass = "";
        //on fait une boucle
        for ($u = 1; $u <= $length; $u++) {
            //on compte le nombre de caractÃ¨res prÃ©sents dans notre chaine
            $nb = \strlen($chaine);
            // on choisie un nombre au hasard entre 0 et le nombre de caractÃ¨res de la chaine
            $nb = \mt_rand(0, ($nb - 1));
            // on ajoute la lettre a la valeur de $pass
            $pass .= $chaine[$nb];
        }
        // on retourne le rÃ©sultat :
        return ["pass"=>$pass,"crypt"=>self::getPassCrypt($pass)];
    }

    /**
     * @param int $length
     * @return string
     */
    public static function genererReference($length = 8)
    {
        $characts = '0123456789';
        $ref = '';
        for ($i = 0; $i < $length; $i++) {
            $ref .= \substr($characts, \rand() % (\strlen($characts)), 1);
        }
        return $ref;
    }

    /**
     * @return bool
     */
    public static function getSessionStarted()
    {
        if (\php_sapi_name() !== 'cli') {
            if (\version_compare(\phpversion(), '5.4.0', '>=')) {
                return \session_status() === PHP_SESSION_ACTIVE ? true : false;
            } else {
                return \session_id() === '' ? false : true;
            }
        }
        return false;
    }

    /**
     * @return string
     */
    public static function getBrowser()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] . "\n\n";
        switch (true) {
            case (\strpos($user_agent, 'Opera') || \strpos($user_agent, 'OPR/')) :
                return 'Opera';
                break;
            case (\strpos($user_agent, 'Edge')) :
                return 'Edge';
                break;
            case (\strpos($user_agent, 'Chrome')) :
                return 'Chrome';
                break;
            case (\strpos($user_agent, 'Safari')) :
                return 'Safari';
                break;
            case (\strpos($user_agent, 'Firefox')) :
                return $user_agent;
                break;
            case  (\strpos($user_agent, 'MSIE') || \strpos($user_agent, 'Trident/7')) :
                return 'Internet Explorer';
                break;
            default :
                return trim($user_agent);
        }
    }

    /**
     * @return mixed
     */
    public static function getIp()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $ip;
    }

    public static function getOS( $ua = '' )
    {
        if( ! $ua  ) $ua = $_SERVER['HTTP_USER_AGENT'];
        $os = 'Système d&#39;exploitation inconnu';

        $os_arr = Array(
            // -- Windows
            'Windows NT 6.1'       => 'Windows Seven',
            'Windows NT 6.0'       => 'Windows Vista',
            'Windows NT 5.2'       => 'Windows Server 2003',
            'Windows NT 5.1'       => 'Windows XP',
            'Windows NT 5.0'       => 'Windows 2000',
            'Windows 2000'         => 'Windows 2000',
            'Windows CE'           => 'Windows Mobile',
            'Win 9x 4.90'          => 'Windows Me.',
            'Windows 98'           => 'Windows 98',
            'Windows 95'           => 'Windows 95',
            'Win95'                => 'Windows 95',
            'Windows NT'           => 'Windows NT',

            // -- Linux
            'Ubuntu'               => 'Linux Ubuntu',
            'Fedora'               => 'Linux Fedora',
            'Linux'                => 'Linux',

            // -- Mac
            'Macintosh'            => 'Mac',
            'Mac OS X'             => 'Mac OS X',
            'Mac_PowerPC'          => 'Mac OS X',

            // -- Autres ...
            'FreeBSD'              => 'FreeBSD',
            'Unix'                 => 'Unix',
            'Playstation portable' => 'PSP',
            'OpenSolaris'          => 'SunOS',
            'SunOS'                => 'SunOS',
            'Nintendo Wii'         => 'Nintendo Wii',
            'Mac'                  => 'Mac',
        );

        $ua = strtolower( $ua );
        foreach( $os_arr as $k => $v )
        {
            if( preg_match( strtolower( $k ), $ua ) )
            {
                $os = $v;
                break;
            }
        }
        return $os;
    }

    /**
     * @param float $nombre
     * @param null $arg
     * @param int $decimals
     * @return string
     */
    public static function getFormatMoney($nombre = 0.0, $arg = null, $decimals = 0)
    {
        return @\number_format(floatval($nombre), $decimals, ',', ' ') . ' ' . $arg;
    }

    /**
     * @param $date
     * @param bool $heure
     * @return string
     */
    public static function getDateFR($date, $heure = true)
    {
        $tabMois = ["01"=>"Jan","02"=>"Fev","03"=>"Mar","04"=>"Avr","05"=>"Mai","06"=>"Jui","07"=>"Juil","08"=>"Aout","09"=>"Sept","10"=>"Oct","11"=>"Nov","12"=>"Dec"];
        $date    = \explode(" ",$date);
        $heur   = $date[1];
        $date    = \explode("-",$date[0]);
        $date[1] = $tabMois[$date[1]];
        $heur = ($heure) ? $heur : null;
        return (!\is_null($heur)) ? $date[2] . " / " . $date[1] . " / " . $date[0] . " " . $heur : $date[2] . " / " . $date[1] . " / " . $date[0];
    }

    /**
     * @param bool $with_time default false
     * @return false|string
     */
    public static function getDateNow($with_time = false)
    {
        return ($with_time) ? \gmdate("Y-m-d H:i:s") : \gmdate("Y-m-d");
    }

    /**
     * @param array $interval
     * @param string $dateFrom
     * @return false|string
     */
    public static function getDateFuturFromDate($interval = [1, "mois"], $dateFrom = "now")
    {
        $int = null;
        $number = intval($interval[0]);
        $number = $number == 0 ? 1 : $number;

        switch (strtolower($interval[1])){
            case "seconde"  : $int = "+".$number." Second"; break;
            case "minute"  : $int = "+".$number." Minute"; break;
            case "heure" : $int = "+".$number." Hours"; break;
            case "jour"  : $int = "+".$number." Day"; break;
            case "mois"  : $int = "+".$number." Month"; break;
            case "annee" : $int = "+".$number." Year"; break;
            default      : $int = "+".$number." ".$interval[1]; break;
        }
        return gmdate("Y-m-d H:i:s", strtotime($dateFrom." $int"));
    }

    /**
     * @param $date
     * @return string
     */
    public static function getMonthYearFR($date)
    {
        $tabMois = ["01"=>"Jan","02"=>"Fev","03"=>"Mar","04"=>"Avr","05"=>"Mai","06"=>"Jui","07"=>"Juil","08"=>"Aout","09"=>"Sept","10"=>"Oct","11"=>"Nov","12"=>"Dec"];
        $date    = \explode(" ",$date);
        $date    = \explode("-",$date[0]);
        $date[1] = $tabMois[$date[1]];
        return $date[1] . " / " . $date[0];
    }

    /**
     * @param $date
     * @return string
     */
    public static function getDayMonthFR($date)
    {
        $tabMois = ["01"=>"Jan","02"=>"Fev","03"=>"Mar","04"=>"Avr","05"=>"Mai","06"=>"Jui","07"=>"Juil","08"=>"Aout","09"=>"Sept","10"=>"Oct","11"=>"Nov","12"=>"Dec"];
        $date    = \explode(" ",$date);
        $date    = \explode("-",$date[0]);
        $date[1] = $tabMois[$date[1]];
        return $date[2] . " / " . $date[1];
    }

    /**
     * @param $date
     * @return string
     */
    public static function getDateUS($date)
    {
        $tabMois = ["01"=>"Jan","02"=>"Fev","03"=>"Mar","04"=>"Avr","05"=>"Mai","06"=>"Jui","07"=>"Juil","08"=>"Aout","09"=>"Sept","10"=>"Oct","11"=>"Nov","12"=>"Dec"];
        $date    = \explode(" ",$date);
        $heure   = $date[1];
        $date    = \explode("-",$date[0]);
        $date[1] = $tabMois[$date[1]];
        return (!\is_null($heure)) ? $date[2] . " / " . $date[1] . " / " . $date[0] . " " . $heure : $date[2] . " / " . $date[1] . " / " . $date[0];
    }

    /**
     * @param $car
     * @return string
     */
    public static function getIntegerUnique($car = 6) {
        $string = "";
        $chaine = "0123456789";
        \srand((double)\microtime()*1000000);
        for($i=0; $i<$car; $i++) {
            $string .= $chaine[\rand()%\strlen($chaine)];
        }
        return $string;
    }

    /**
     * @param $car
     * @return string
     */
    public static function getStringUnique($car = 6) {
        $string = "";
        $chaine = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        \srand((double)\microtime()*1000000);
        for($i=0; $i<$car; $i++) {
            $string .= $chaine[\rand()%\strlen($chaine)];
        }
        return $string;
    }

    /**
     * @param array $paramFiles
     * @param string $url
     * @param string $nameFile
     * @param bool $with_ext
     * @return bool
     */
    public static function setUploadFiles($paramFiles = [], $url = "", $nameFile = "", $with_ext = true)
    {
        if (\count($paramFiles) > 0 && $paramFiles["error"] != "4" && $url != "") {
            if(!self::createDir($url)) return false;
            if($nameFile == "") $nameFile = gmdate("YmdHis");
            if($with_ext) $nameFile .= ".".\pathinfo($paramFiles['name'], PATHINFO_EXTENSION);
            return (\move_uploaded_file($paramFiles['tmp_name'], ROOT.$url ."/". $nameFile)) ? $nameFile : false;
        }
        return false;
    }

    /**
     * @param array $paramFiles
     * @param string $url
     * @param string $name
     * @return bool
     */
    public static function setUploadFilesBinaire($paramFiles = [], $url = "", $name = "")
    {
        if (\count($paramFiles) > 0 && $paramFiles["error"] != "4" && $url != "") {
            if ($name == "") $name = Utils::getAlphaNumerique(5);
            if(!self::createDir($url)) return 'Error';
            $fWriteHandle = fopen($url.'/'.$name."." . \pathinfo($paramFiles['name'], PATHINFO_EXTENSION), 'w+');
            $fReadHandle = fopen($paramFiles['tmp_name'], 'rb');
            $fileContent = fread($fReadHandle, $paramFiles['size']);
            $result = fwrite($fWriteHandle, $fileContent);
            fclose($fWriteHandle);
            return ($result === false) ? $result : $name.'.'.\pathinfo($paramFiles['name'], PATHINFO_EXTENSION);
        }
        return false;
    }

    /**
     * @param $path
     * @param $newName
     * @return bool
     */
    public static function setRenameFile($path, $newName)
    {
        $dispath = explode("/",$path);
        if(count($dispath) > 0) {
            self::createDir(ROOT.implode("/",[$dispath[0]]));
            $tempDispath = $dispath;
            $newName .= ".".\pathinfo($tempDispath[count($tempDispath)-1], PATHINFO_EXTENSION);
            unset($tempDispath[count($tempDispath)-1]);
            $newName = implode("/",$tempDispath)."/".$newName;
            return rename(ROOT.$path, ROOT.$newName);
        }
        return false;
    }

    /**
     * @param string $url
     * @return bool
     */
    public static function setDeleteFiles($url = "")
    {
        if (is_dir(ROOT.$url)) {
            $objects = scandir(ROOT.$url);
            foreach ($objects as $object)
                if ($object != "." && $object != "..")
                    if (filetype(ROOT.$url."/".$object) == "dir") rmdir(ROOT.$url."/".$object); else unlink(ROOT.$url."/".$object);
            reset($objects);
            return rmdir(ROOT.$url);
        }elseif(is_file(ROOT.$url)) return \unlink(ROOT.$url);
        return false;
    }

    /**
     * @param int $index
     * @param string $sort
     */
    public static function setDefaultSort($index = 0, $sort = "ASC")
    {
        Session::setAttributArray("default_sort",[$index,$sort]);
    }

    /**
     *
     */
    public static function unsetDefaultSort()
    {
        Session::destroyAttributSession("default_sort");
    }

    /**
     * @param $url
     * @return bool
     */
    public static function createDir($url)
    {
        return ($url != "") ? ((!\is_dir(ROOT . $url)) ? \mkdir(ROOT . $url) : chmod(ROOT . $url, 0777)) : false;
    }

    /**
     * @param array $message
     */
    public static function setMessageALert(array $message)
    {
        $param = ["type"=>$message[0],"alert"=>$message[1]];
        if(isset($message[2])) $param["titre"] = $message[2];
        Session::setAttributArray("MSG_ALERT", $param);
    }

    /**
     * @return array
     */
    public static function getMessageALert()
    {
        return Session::getAttributArray("MSG_ALERT");
    }

    /**
     * @param array $message
     */
    public static function setMessageError(array $message)
    {
        Session::setAttributArray("MSG_ERROR",["type"=>$message[0],"alert"=>$message[1]]);
    }

    /**
     * @return array
     */
    public static function getMessageError()
    {
        return Session::getAttributArray("MSG_ERROR");
    }

    /**
     * @param $name
     */
    public static function unsetMessage($name)
    {
        Session::destroyAttributSession("MSG_$name");
    }

    /**
     * @param array $droits
     * @param int $level
     * @return array
     */
    public static function setArrayDroit(array $droits, $level = 1)
    {
        $retour = [];
        if($level == 1){
            foreach ($droits as $item) {
                if(array_key_exists($item->module, $retour)){
                    if(array_key_exists($item->sous_module, $retour[$item->module]))   $retour[$item->module][$item->sous_module][] = (isset($item->id_aff)) ? ["id"=>$item->id,"droit"=>$item->droit,"id_aff"=>$item->id_aff,"etat_aff"=>$item->etat_aff] : ["id"=>$item->id,"droit"=>$item->droit];
                    else $retour[$item->module][$item->sous_module][] = (isset($item->id_aff)) ? ["id"=>$item->id,"droit"=>$item->droit,"id_aff"=>$item->id_aff,"etat_aff"=>$item->etat_aff] : ["id"=>$item->id,"droit"=>$item->droit];
                }else $retour[$item->module] = [$item->sous_module=>[((isset($item->id_aff)) ? ["id"=>$item->id,"droit"=>$item->droit,"id_aff"=>$item->id_aff,"etat_aff"=>$item->etat_aff] : ["id"=>$item->id,"droit"=>$item->droit])]];
            }
        }else{
            foreach ($droits as $item) {
                if(array_key_exists($item->module, $retour)){
                    if(array_key_exists($item->sous_module, $retour[$item->module]))
                        $retour[$item->module][$item->sous_module][] = (isset($item->id_aff_user)) ? ["id"=>$item->id,"droit"=>$item->droit,"id_aff"=>$item->id_aff,"etat_aff"=>$item->etat_aff,"id_aff_user"=>$item->id_aff_user,"etat_aff_user"=>$item->etat_aff_user] : ["id"=>$item->id,"droit"=>$item->droit,"id_aff"=>$item->id_aff,"etat_aff"=>$item->etat_aff];
                    else $retour[$item->module][$item->sous_module][] = (isset($item->id_aff_user)) ? ["id"=>$item->id,"droit"=>$item->droit,"id_aff"=>$item->id_aff,"etat_aff"=>$item->etat_aff,"id_aff_user"=>$item->id_aff_user,"etat_aff_user"=>$item->etat_aff_user] : ["id"=>$item->id,"droit"=>$item->droit,"id_aff"=>$item->id_aff,"etat_aff"=>$item->etat_aff];
                }else $retour[$item->module] = [$item->sous_module=>[((isset($item->id_aff_user)) ? ["id"=>$item->id,"droit"=>$item->droit,"id_aff"=>$item->id_aff,"etat_aff"=>$item->etat_aff,"id_aff_user"=>$item->id_aff_user,"etat_aff_user"=>$item->etat_aff_user] : ["id"=>$item->id,"droit"=>$item->droit,"id_aff"=>$item->id_aff,"etat_aff"=>$item->etat_aff])]];
            }
        }
        return $retour;
    }

    /**
     * @param $errtxt
     */
    public static function writeFileLogs($errtxt)
    {
        self::createDir('logs/' . date('Y'). "/" . date('m'). '/' . date('W'));

        $fp = fopen(ROOT . 'logs/' . date('Y') . '/' . date('m') . '/' . date('W') . '/' . date("d_m_Y") . '.txt', 'a+'); // ouvrir le fichier ou le créer
        fseek($fp, SEEK_END); // poser le point de lecture à la fin du fichier
        $nouvel_ligne = $errtxt . "\r\n"; // ajouter un retour à la ligne au fichier
        fputs($fp, $nouvel_ligne); // ecrire ce texte
        fclose($fp); //fermer le fichier
    }

    /**
     * @param $params
     * @return mixed
     */
    public static function validateMail($params)
    {
        return filter_var(filter_var($params, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
    }

    /**
     * @param array $params
     * @return bool
     */
    public static function validateForm(array $params)
    {
        $retour = true;
        foreach ($params as $key => $value){
            if(\is_array($value)) self::validateForm($value);
            else {
                switch (strtolower($key)) {
                    case "email" : if(filter_var(filter_var($value, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL) === false) return false; break;
                    case "prenom" : if((filter_var($value, FILTER_VALIDATE_INT) && self::startsWith($value,"+") && (strlen($value) === 7)) === false) return false; break;
                }
            }
        }
        return $retour;
    }

    /**
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return $length === 0 || (substr($haystack, -$length) === $needle);
    }

    /**
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public static function contentString($haystack, $needle)
    {
        return preg_match('#'.$needle.'#', $haystack);
    }

    /**
     * @param $string
     * @param null $key
     * @return null|string
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public static function cryptString($string, $key = null)
    {
        if($key === null) $key = self::getKey_crypt();
        return (is_string($string)) ? Crypto::encrypt($string, $key) : null;
    }


    /**
     * @param $crypt
     * @param null $key
     * @return string
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException
     */
    public static function decryptString($crypt, $key = null)
    {
        if(is_null($crypt)) $crypt = '';
        if($key === null) $key = self::getKey_crypt();
        return Crypto::decrypt($crypt, $key);
    }

    /**
     * @return Key
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public static function getKey_crypt()
    {
        return Key::loadFromAsciiSafeString(KEY_CRYPT);
    }

    /**
     * @param $name
     * @param $val
     * @param null $appConfig_
     */
    public static function addAppConfigConstant($name, $val, $appConfig_ = null)
    {
        $appConfig = (object)\parse_ini_file(ROOT . 'config/app.config.ini');
        $default = [
            "APP"=>[
                "\n;Variable d'environnement",
                "projet"=> (!is_null($appConfig_)) ? $appConfig_->projet : $appConfig->projet,
                "env"=>(!is_null($appConfig_)) ? $appConfig_->env : $appConfig->env,
                "log"=>(!is_null($appConfig_)) ? ($appConfig_->log == "1" ? "on" : "off") : ($appConfig->log == "1" ? "on" : "off"),
                "profile_level"=> (!is_null($appConfig_)) ? $appConfig_->profile_level : $appConfig->profile_level,
                "law_generate"=>(!is_null($appConfig_)) ? ($appConfig_->law_generate == "1" ? "on" : "off") : ($appConfig->law_generate == "1" ? "on" : "off"),
                "mail_from"=> (!is_null($appConfig_)) ? $appConfig_->mail_from : $appConfig->mail_from,
                "use_api_client"=>(!is_null($appConfig_)) ? ($appConfig_->use_api_client == "1" ? "on" : "off") : ($appConfig->use_api_client == "1" ? "on" : "off"),
                "session_name"=>(!is_null($appConfig_)) ? $appConfig_->session_name."\n" : $appConfig->session_name."\n",

                ";Default page",
                "default_controller"=>"Home",
                "default_action"=>"index\n",

                ";Default template",
                "default_header"=>"header",
                "default_sidebar"=>"sidebar",
                "default_footer"=>"footer\n",
            ]
        ];

        $espace = [];
        $constant = [];
        $appConfig = (array)$appConfig;
        foreach ($appConfig as $key => $value) {
            if(Utils::startsWith($key, 'space_'))
                $espace[str_replace('space_', '', strtolower($key))] = ($value == "1" ? "on" : "off");
            elseif(Utils::startsWith($key, 'CONST_'))
                $constant[str_replace('CONST_', '', $key)] = $value;
        }
        if(!is_null($name) && !is_null($val)) self::setConstant($default, $constant, [$name, $val]);
        else self::setConstant($default, $constant);
        self::setEspace($default, $espace, $appConfig);

        $ini = new ConfigFile('config/app.config.ini', 'Fichier de configuration');
        $ini->add_array($default);
        $ini->write();
    }

    /**
     * @param $default
     * @param $espace
     * @param $config
     */
    private static function setEspace(&$default, $espace, $config) {
        if(count($espace) > 0) {
            $default['APP'][] = ";Define space";
            foreach ($espace as $key => $value)
                $default['APP']["space_$key"] = $value;
            $default['APP']["space_$key"] = $value."\n";

            foreach ($espace as $key => $value){
                $default['APP'][] = ";$key page";
                $default['APP'][$key."_controller"] = isset($config[$key."_controller"]) ? $config[$key."_controller"] : "Home";
                $default['APP'][$key."_action"] = isset($config[$key."_action"]) ? $config[$key."_action"]."\n" : "index\n";
            }

            foreach ($espace as $key => $value){
                $default['APP'][] = ";$key template";
                $default['APP'][$key."_header"] = isset($config[$key."_header"]) ? $config[$key."_header"] : "header";
                $default['APP'][$key."_sidebar"] = isset($config[$key."_sidebar"]) ? $config[$key."_sidebar"] : "sidebar";
                $default['APP'][$key."_footer"] = isset($config[$key."_footer"]) ? $config[$key."_footer"]."\n" : "footer\n";
            }
        }
    }

    /**
     * @param $default
     * @param $constant
     * @param null $new
     */
    private static function setConstant(&$default, $constant, $new = null) {
        if(count($constant) > 0) {
            $default['APP'][] = ";Define constant";
            if(!is_null($new)) $constant[$new[0]] = $new[1];
            foreach ($constant as $key => $value)
                $default['APP']["CONST_".strtoupper($key)] = $value;
            $default['APP']["CONST_".strtoupper($key)] = $value."\n";
        }
    }

    /**
     * @param $array
     * @return array
     */
    public static function array_recursive_convert($array) {
        if(is_object($array)) {
            $array = (array)$array;
            $array = self::array_recursive_convert($array);
        }
        elseif(is_array($array))
            foreach ($array as $key => $item)
                $array[$key] = self::array_recursive_convert($item);
        return $array;
    }

    /**
     * @param $code
     * @param $array
     */
    public static function responseAPI($code, $array) {
        if (function_exists('http_response_code')) {
            http_response_code($code);
        } else {
            $protocol = $_SERVER['SERVER_PROTOCOL'] ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
            $code .= ' ' . Language::getLang(Session::getAttribut('lang'))[$code];
            header("$protocol $code");
        }

        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: 0");
        header('Content-Type: application/json');
        echo json_encode($array, 0);
        exit();
    }

    use CommonUtils;
}