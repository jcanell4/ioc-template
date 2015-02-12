<?php

if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', "../");
require_once(DOKU_TPL_INCDIR . 'conf/cfgIdConstants.php');
require_once(DOKU_TPL_INCDIR . 'conf/default.php');

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
        // $arrParcial es el nombre del array definido en cada uno de los ficheros arrayParcialCfg.php
        $arrCfg = $arrParcial;
        $arrJS = $this->buscaJS("$dir/js/set");
        if ($arrJS) {
            foreach ($arrJS as $k => $v) {
                $arrCfg['parms']['DJO'][$k] = $v;
            }
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
        // mira si existe el subdirectorio items
        if ($sdir) {
            foreach ($sdir as $value) {
                if (!in_array($value, array(".",".."))) {
                    $ruta_completa = "$dir/$value";
                    if (is_dir($ruta_completa)) {
                        $item[$value] = $this->dirToArray($ruta_completa);
                    }
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
                if (!is_dir($arxiu) && substr(strrchr($value, "."), 1) === "js") {
                    $fh = fopen($arxiu, "rb");
                    $nom = substr($value, 0, -3);
                    $js[$nom] = "function(){".trim(fread($fh, filesize($arxiu)), " \t\r\n\0\x0B")."}";
                    $js[$nom] = str_replace('"', "'", $js[$nom]);
                    $js[$nom] = str_replace(array("\r\n", "\r", "\n"), " ", $js[$nom]);
                    $js[$nom] = preg_replace('/\s\s+/', ' ', $js[$nom]);
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
        global $conf;
        $sortida = "<?php\r\nfunction " . $conf['ioc_function_array_gui_needReset'] . "(){\r\n  \$_needReset = 0;\r\n  return \$_needReset;\r\n}\r\n";
        $sortida.= "\r\nfunction " . $conf['ioc_function_array_gui'] . "(){\r\n";
        $sortida.= "\$_arrIocCfgGUI = " . var_export($arr, true) . ";\r\n\r\nreturn \$_arrIocCfgGUI;\r\n}";
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
