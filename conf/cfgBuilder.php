<?php

if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', "../");
require_once(DOKU_TPL_INCDIR . 'conf/cfgIdConstants.php');
require_once(DOKU_TPL_INCDIR . 'conf/default.php');

class cfgBuilder {

    private $arrJS_AMD = array();
    
    function __construct() {}

    public function getArrayCfg($dir) {
        $ret = $this->dirToArray($dir);
        return array("arrCfg" => $ret, "amd" => $this->arrJS_AMD);
    }

    /**
        Construcción del array
    **/
    private function dirToArray($dir) {
        include_once ("$dir/arrayParcialCfg.php");
        // $arrParcial es el nombre del array definido en cada uno de los ficheros arrayParcialCfg.php
        $arrCfg = $arrParcial;
        
        if(isset($arrCfg['hidden']) && $arrCfg['hidden']){
            return NULL;
        }
        
        $arrJS_AMD_tmp = $this->buscaJS_AMD("$dir/js/amd");
        if ($arrJS_AMD_tmp) {
            foreach ($arrJS_AMD_tmp as $v) {
                $this->arrJS_AMD[] = $v;
            }
        }

        $arrJS_SET = $this->buscaJS_SET("$dir/js/set");
        if ($arrJS_SET) {
            foreach ($arrJS_SET as $k => $v) {
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
        $sdir = @scandir($dir);
        // mira si existe el subdirectorio items
        if ($sdir) {
            foreach ($sdir as $value) {
                if (!in_array($value, array(".",".."))) {
                    $ruta_completa = "$dir/$value";
                    if (is_dir($ruta_completa)) {
                        $child = $this->dirToArray($ruta_completa);
                        if($child){
                            $item[$value] = $child;
                        }
                    }
                }
            }
        }
        return $item;
    }

    /**
        Devuelve el contenido de cada archivo JS/SET en un elemento del array de retorno
    **/
    private function buscaJS_SET($dir) {
        if (is_dir($dir)) {
            $js = array();
            $sdir = @scandir($dir);
            foreach ($sdir as $value) {
                $arxiu = "$dir/$value";
                if (!is_dir($arxiu) && substr(strrchr($value, "."), 1) === "js") {
                    $fh = fopen($arxiu, "rb");
                    $nom = substr($value, 0, -3);
                    $js[$nom] = "function(){var _ret=null; ";
                    $js[$nom].= trim(fread($fh, filesize($arxiu)), " \t\r\n\0\x0B");
                    $js[$nom].= "return _ret;}";
                    $js[$nom] = str_replace('"', "'", $js[$nom]);
                    $js[$nom] = str_replace(array("\r\n", "\r", "\n"), "", $js[$nom]);
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
        Devuelve el contenido de cada archivo JS/AMD en un elemento del array de retorno
    **/
    private function buscaJS_AMD($dir) {
        if (is_dir($dir)) {
            $amd = array();
            $sdir = @scandir($dir);
            foreach ($sdir as $value) {
                $arxiu = "$dir/$value";
                if (!is_dir($arxiu) && substr(strrchr($value, "."), 1) === "js") {
                    $fh = fopen($arxiu, "rb");
                    if ($fh) {
                        $contingut = array();
                        //Para todas las líneas del fichero
                        while (($buffer = fgets($fh)) !== false) {
                            //Lee una línea del fichero
                            $buffer = trim($buffer, " \t\r\n\0\x0B");
                            if (preg_match('/\/{2}[\s]*include/i', $buffer) == 1) {
                                $buffer = str_replace(array('"', "'"), "", $buffer);
                                $r = explode(":", preg_replace(array("/(?:\/+\s*include)\s*(.*)/i", "/(?:;\s*alias)\s*(.*)/i"), array("$1",":$1"), $buffer));
                                $contingut['require'][] = trim($r[0]);
                                $contingut['alias'][] = ($r[1]) ? trim($r[1]) : trim(substr(strrchr($r[0], "/"), 1));
                            }
                            elseif (substr($buffer, 0, 2) !== "//") {   //Elimina las líneas de comentario
                                $contingut['codi'] .= ($buffer == "") ? "" : "$buffer\n";
                            }
                        }
                        fclose($fh);
                        $sortida = null;
                        //Construye la función require. En primer lugar la "cabecera"
                        if ($contingut['require']) {
                            foreach ($contingut['require'] as $v) {
                                $sortida .= ",\"$v\"\n";
                            }
                            $sortida = "require([\n" . substr($sortida, 1) . "], function (";
                            //Añade los punteros de la función  
                            foreach ($contingut['alias'] as $v) {
                                $sortida .= "$v,";
                            }
                            $amd[] = substr($sortida, 0, -1) . ") {\n" . $contingut['codi'] . "});";
                        }
                    }
                }
            }
            //return array("require" => $require, "codi" => $codi);
            return $amd;
        }
        else
            return NULL;
    }
    
    /**
        Escribe el contenido de un array en un fichero de texto php
    **/
	public function writeArrayToFile($arr, $file) {
        global $conf;
        $sortida = "<?php\nfunction " . $conf['ioc_function_array_gui_needReset'] . "(){\n  \$_needReset = 0;\n  return \$_needReset;\n}\n";
        $sortida.= "\nfunction " . $conf['ioc_function_array_gui'] . "(){\n";
        $sortida.= "\$_arrIocCfgGUI = " . var_export($arr, true) . ";\n\nreturn \$_arrIocCfgGUI;\n}";
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

    /**
        Escribe el contenido de un array en un fichero de texto .js
    **/
	public function writeAMDToFile($arr, $file) {
        //Concatena todos los módulos javascript en un único string
        foreach ($arr as $v) {
            $sortida .= "$v\n";
        }
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
        return $sortida;
    }
}
?>
