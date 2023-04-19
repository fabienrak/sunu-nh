<?php
/**
 * Created by PhpStorm.
 * User: seeynii.faay
 * Date: 8/28/19
 * Time: 5:01 PM
 */

namespace app\core;

class ConfigFile
{
    var $ini;
    var $filename;

    /**
     * ini constructor.
     * @param $filename
     * @param $commentaire
     */
    public function __construct($filename, $commentaire = false) {
        $this->filename = ROOT.$filename;
        $this->ini = (!$commentaire) ? ' ' : ';'.$commentaire;
    }

    public function add_array($array) {
        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $this->under_array($val, $key);
            }
            else if (is_string($key)) {
                $this->add_value($key, $val);
            }
        }
    }

    private function under_array($tab, $groupe = false) {
        if ($groupe) {
            $this->ini .= "\n"."[".$groupe."]";
        }
        foreach ($tab as $key => $val) {
            if (!$this->add_value($key, $val)) return false;
        }
        $this->ini .= "\n";
        return true;
    }

    private function add_value($key, $val) {
        if (is_array($val)) {
            echo "Erreur : Impossible d'ajouter une valeur";
            return false;
        }
        else if (is_string($val) OR is_double($val) OR is_int($val)) {
            $this->ini .= is_int($key) ? "\n".$val : "\n".$key. " = " .$val;
        }
        else {
            echo "Erreur : Le type de donnée n'est pas supporté";
            return false;
        }
        return true;
    }

    public function write ($rewrite = true) {
        $c = true;
        if (file_exists($this->filename)) {
            if ($rewrite) {
                @unlink($this->filename);
            }
            else if (!$rewrite) {
                echo 'Erreur fatale : Le fichier ini existe déjà';
                $c = false;
                return false;
            }
        }
        if ($c) {
            $fichier = fopen($this->filename, 'w');
            if (!$fichier) {
                echo "Erreur fatale : Impossible d'ouvrir le fichier";
                return false;
            }
            if (!fwrite($fichier, $this->ini)) {
                echo "Erreur fatale : Impossible d'écrire dans le fichier";
                return false;
            }
            fclose($fichier);
        }
        chmod($this->filename, 0777);
        return $c;
    }
}