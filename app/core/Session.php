<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: 17/08/2017
 * Time: 11:03
 */

namespace app\core;
class Session
{
    /**
     * Détruit la session actuelle
     */
    public static function destroySession()
    {
        Utils::sessionStarted();
        session_destroy();
        unset($_SESSION[SESSIONNAME]);
    }

    /**
     * Détruit la cookie actuelle
     */
    public static function destroyCookies()
    {
        Utils::sessionStarted();
        setcookie("lang", "", -1);
    }

    /**
     * @param $name
     */
    public static function destroyAttributSession($name)
    {
        Utils::sessionStarted();
        unset($_SESSION[$name]);
    }

    /**
     * Ajoute un attribut à la session
     *
     * @param string $nom Nom de l'attribut
     * @param string $valeur Valeur de l'attribut
     */
    public static function setAttribut($nom, $valeur)
    {
        Utils::sessionStarted();
        $_SESSION[$nom] = serialize($valeur);
    }

    /**
     * Ajoute un tableau d'attribut à la session
     *
     * @param string $nom Nom de l'attribut
     * @param array $valeur Valeur de l'attribut
     */
    public static function setAttributArray($nom, array $valeur)
    {
        Utils::sessionStarted();
        $_SESSION[$nom] = $valeur;
    }

    /**
     * Renvoie un tableau de valeurs de l'attribut demandé
     *
     * @param string $nom Nom de l'attribut
     * @return array Valeur de l'attribut
     * @throws Exception Si l'attribut n'existe pas dans la session
     */
    public static function getAttributArray($nom)
    {
        Utils::sessionStarted();
        return (self::existeAttribut($nom) && is_array($_SESSION[$nom])) ? $_SESSION[$nom] : null;
    }

    /**
     * Initialise les Cookies
     *
     * @param string $nom Nom de l'attribut
     * @param string $valeur Valeur de l'attribut
     */
    public static function initCookie($nom, $valeur)
    {
        Utils::sessionStarted();
        setcookie($nom, serialize($valeur), time() + 604800, "/");
    }

    /**
     * Modifie la valeur d'une Cookie
     *
     * @param string $nom Nom de l'attribut
     * @param string $valeur Valeur de l'attribut
     */
    public static function setCookie($nom, $valeur)
    {
        Utils::sessionStarted();
        $_COOKIE[$nom] = serialize($valeur);
    }

    /**
     * Retourne une Cookie
     * @param $nom
     * @return mixed
     */
    public static function getCookie($nom)
    {
        Utils::sessionStarted();
        return unserialize($_COOKIE[$nom]);
    }

    /**
     * Renvoie vrai si l'attribut existe dans la Cookie
     *
     * @param string $nom Nom de l'attribut
     * @return bool Vrai si l'attribut existe et sa valeur n'est pas vide
     */
    public static function existeCookie($nom)
    {
        Utils::sessionStarted();
        return (isset($_COOKIE[$nom]) && $_COOKIE[$nom] != "");
    }

    /**
     * Renvoie vrai si l'attribut existe dans la session
     *
     * @param string $nom Nom de l'attribut
     * @return bool Vrai si l'attribut existe et sa valeur n'est pas vide
     */
    public static function existeAttribut($nom)
    {
        Utils::sessionStarted();
        return (isset($_SESSION[$nom]));
    }

    /**
     * Renvoie la valeur de l'attribut demandé
     *
     * @param string $nom Nom de l'attribut
     * @return string Valeur de l'attribut
     * @throws Exception Si l'attribut n'existe pas dans la session
     */
    public static function getAttribut($nom)
    {
        Utils::sessionStarted();
        return (self::existeAttribut($nom)) ? unserialize($_SESSION[$nom]) : null;
    }

    /**
     * @param $nom
     */
    public static function isConnected($nom)
    {
        Utils::sessionStarted();
        if (!self::existeAttribut($nom)) Utils::redirect();
    }

    /**
     * @param array $val
     */
    public static function set_User_Connecter(array $val)
    {
        Utils::sessionStarted();
        self::setAttributArray(SESSIONNAME, $val);
    }
}