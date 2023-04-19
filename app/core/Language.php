<?php

/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 21/07/2016
 * Time: 12:24
 */

namespace app\core;

abstract class Language
{
    static function getLang($langs, $espace = "default")
    {
        $espace = $espace =="default" ? "app/language/" : "app/language/" . $espace . "/";
        $langs = @htmlspecialchars($langs, ENT_QUOTES);
        if (!file_exists(ROOT . $espace . $langs .'.lang')) $langs = 'fr';
        return  parse_ini_string(file_get_contents(ROOT . $espace . $langs .'.lang'));
    }
}