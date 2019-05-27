<?php
/**
 * Description of ControlTplGenerator
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined('DOKU_INC')) die();
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', WikiGlobalConfig::tplIncDir());
require_once (DOKU_TPL_INCDIR . 'conf/generator/WikiIocTplGeneratorExceptions.php');

class ControlTplGenerator {
    const defaultClass = "WikiIocButton";

    private $controls;

    public function addControlScript($scriptPath, $aReplacements=NULL){
        if(!file_exists($scriptPath)){
            throw new FileScriptNotFoundException($scriptPath);
        }
        $textScript = file_get_contents($scriptPath);
        if($aReplacements){
            $textScript = str_replace($aReplacements["search"], $aReplacements["replace"], $textScript);
        }
        $this->controls["controlScript"][]=$textScript;
    }

    public function addWikiIocButton($class=NULL, $params=NULL, $name=NULL, $nameIsPre=false){
        if ($class == NULL){
            $class = self::defaultClass;
        }
        if(!is_string($class)){
            $name = $params;
            $params = $class;
            $class = self::defaultClass;
        }

        if (!empty($params)) $this->checkParams($params);
        if (!$name){
            $name = sprintf("iX%03d_%s", count($this->controls["IocButtonControls"]), $this->getFirstParamIn(array("DOM", "DJO"), "id", $params));
            if (empty($name)){
                throw new RequiredParamNotFoundException("id");
            }
        }elseif($nameIsPre){
            $name .= "_".$this->getFirstParamIn(array("DOM", "DJO"), "id", $params);
        }
        $this->controls["IocButtonControls"][] = array("name" => $name, "class" => $class, "parms" => $params);
    }

    public function getControlScripts(){
        return $this->controls["controlScript"];
    }

    public function getWikiIocButtonControls(){
        return $this->controls["IocButtonControls"];
    }

    private function checkParams($params){
        $keyPattern = array("DOM", "CSS", "DJO", "PRP");
        $keys = array_keys($params);
        $ret=TRUE;
        for($i=0; ($ret && $i<sizeof($keys)); $i++){
            $ret = in_array($keys[$i], $keyPattern);
        }
        if(!$ret){
            throw new UnknownTypeParamException($keys[$i]);
        }
    }

    private function getFirstParamIn($type, $key, $params){
        if(is_array($type)){
            $atype=$type;
            if(!is_array($key)){
                $akey = array_fill(0, sizeof($type), $key);
            }else{
                $akey=$key;
            }
        }else if(is_array($key)){
            $akey=$key;
            if(!is_array($type)){
                $atype = array_fill(0, sizeof($key), $type);
            }else{
                $atype=$type;
            }
        }else{
            $atype=array($type);
            $akey=array($key);
        }

        $ret="";
        for($i=0; empty($ret) && $i<sizeof($atype); $i++){
            if(in_array($akey[$i], array_keys($params[$atype[$i]]))){
                $ret = $params[$atype[$i]][$akey[$i]];
            }
        }
        return $ret;
    }
}
