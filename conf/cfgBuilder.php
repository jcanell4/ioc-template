<?php

if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', "../");
require_once(DOKU_TPL_INCDIR . 'conf/cfgIdConstants.php');

class cfgBuilder {

	function __construct() {}

    public function getArrayCfg($dir) {
        return $this->dirToArray($dir);
    }

    /**
        Construcción del array
    **/
    private function dirToArray($dir) {
        include_once ("$dir/arrayParcialCfg.php");
        $arrCfg = $arrParcial;
        $arrJS = $this->buscaJS("$dir/js");
        foreach ($arrJS as $k => $v) {
            $arrCfg['parms']['DJO'][$k] = $v;
        }

        $ret = $this->addCfgArray("$dir/items");
        if ($ret) {
            $arrCfg['items'] = $ret;
        }
        return $arrCfg;
    }

    /**
        Llamada recursiva para la construcción del array
    **/
    private function addCfgArray($dir) {
        $item = array();
        $sdir = scandir($dir);
        foreach ($sdir as $value) {
            if (!in_array($value, array(".",".."))) {
                $ruta_completa = "$dir/$value";
                if (is_dir($ruta_completa)) {
                    $item[$value] = $this->dirToArray($ruta_completa);
                }
            }
        }
        return $item;
    }

    /**
        Devuelve el contenido de cada archivo JS en un elemento del array de retorno
    **/
    private function buscaJS($dir) {
        if (is_dir($dir)) {
            $js = array();
            $sdir = scandir($dir);
            foreach ($sdir as $value) {
                $arxiu = "$dir/$value";
                if (!is_dir($arxiu)) {
                    $fh = fopen($arxiu, "rb");
                    $nom = substr($value, 0, -3);
                    $js[$nom] = "function(){".trim(fread($fh, filesize($arxiu)), " \t\n\r\0\x0B")."}";
                    fclose($fh);
                }
            }
            return $js;
        }
        else
            return NULL;
    }

    /**
        Escribe el contenido de un array en un fichero de texto php
    **/
	public function writeArrayToFile($arr, $file) {
        $sortida = "<?php\r\n\$needReset = 0;";
        $sortida .= "\r\n\$arrIocCfgGUI = " . var_export($arr, true) . ";";
        // Obtiene las constantes definidas en la clase cfgIdConstants
        $oCfgClass = new ReflectionClass ('cfgIdConstants');
        $aConstants = $oCfgClass->getConstants();
        // Convierte el array de constantes en un array de patrones y otro de sustituciones
        foreach ($aConstants as $k => $v) {
            $aPatrones[] = "/\b(cfgIdConstants::$k)\b/";
            $aSustituciones[] = $v;
        }
        
        $sortida = preg_replace($aPatrones, $aSustituciones, $sortida);
        $fh = fopen($file, "w");
        fwrite($fh, $sortida);
        fclose($fh);
        return $arr;
    }
}
?>
