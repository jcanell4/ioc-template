<?php

if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', tpl_incdir());

require_once(DOKU_TPL_INCDIR . 'classes/WikiIocBuilder.php');
require_once(DOKU_TPL_INCDIR . 'conf/js_packages.php');

/**
 * Description of WikiIocComponent
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
abstract class WikiIocComponent extends WikiIocBuilder {
    private $aParams;   //array de paràmetres

    /**
     * @param array[] $aParms      hash amb els paràmetres del component
     * @param array[] $reqPackage  hash amb els packages requerit amb el format:
     *                             array("name" => "ioc", "location" => $js_packages["ioc"])
     */
    function __construct($aParms = array(), $reqPackage = array()) {
        parent::__construct($reqPackage);
        
        $this->aParams = $aParms;
        /*
        if (!$this->aParams['DOM']['selected']) {
            $this->aParams['DOM']['selected'] = FALSE;
        }
        if ($this->aParams['DOM']['label'] && !$this->aParams['DOM']['toolTip']) {
            $this->aParams['DOM']['toolTip'] = $this->aParams['DOM']['label'];
        }
        */
    }

    public abstract function getRenderingCode();

    function get($class, $key) {
        return $this->aParams[$class][$key];
    }
    function set($class, $key, $value) {
        $this->aParams[$class][$key] = $value;
    }

    /**
     * Devuelve un strig en el que se han concatenado las propiedades HTML
     * excepto las de la etiqueta style
     * @return string
     */
    function getDOM($aKeys) {
        /*
        if ($aKeys) {
            if (is_array($aKeys)) {
                foreach ($aKeys as $key) {
                    if ($this->aParams['DOM'][$key])
                        $dom .= "$key='$this->aParams['DOM'][$key]' ";
                }
            }else{
                if ($this->aParams['DOM'][$aKeys])
                        $dom .= "$key='$this->aParams['DOM'][$aKeys]' ";
            }
        }else{
            foreach ($this->aParams['DOM'] as $key => $value) {
                $dom .= "$key='$value' ";
            }
        }
        return $dom;
        */
        return $this->getDOMCSSDJO('DOM', $aKeys, '=', ' ');
    }
    /**
     * Devuelve un strig en el que se han concatenado las propiedades CSS
     * incorporadas dentro de la etiqueta style
     * @return string
     */
    function getCSS($aKeys) {
        /*
        if ($aKeys) {
            if (is_array($aKeys)) {
                foreach ($aKeys as $key) {
                    if ($this->aParams['CSS'][$key]) 
                        $style .= $this->parejaKeyValue($key, $this->aParams['CSS'][$key], ";");
                }
            }else{
                if ($this->aParams['CSS'][$aKeys])
                        $style .= $this->parejaKeyValue($aKeys, $this->aParams['CSS'][$aKeys], ";");
            }
        }else{
            foreach ($this->aParams['CSS'] as $key => $value) {
                $style .= $this->parejaKeyValue($key, $value, ";");
            }
        }
        return "style='$style'";
        */
        $style = $this->getDOMCSSDJO('CSS', $aKeys, ':', ';');
        return "style='$style'";
    }
    /**
     * Devuelve un strig en el que se han concatenado las propiedades DOJO
     * incorporadas dentro de la etiqueta data-dojo-props
     * @return string
     */
    function getDJO($aKeys) {
        /*
        if ($aKeys) {
            foreach ($aKeys as $key) {
                if ($this->aParams['DJO'][$key])
                    $djprp .= $this->parejaKeyValue($key, $this->aParams['DJO'][$key], ",");
            }
        }else{
            foreach ($this->aParams['DJO'] as $key => $value) {
                $djprp .= $this->parejaKeyValue($key, $value, ",");
            }
        }
        return "data-dojo-props=\"$djprp\"";
        */
        $djprp = $this->getDOMCSSDJO('DJO', $aKeys, ':', ',');
        return "data-dojo-props=\"$djprp\"";
    }

    /**
     * Devuelve un strig en el que se han concatenado las propiedades HTML
     * excepto las de la etiqueta style
     * @return string
     */
    function getNoDOM($aKeys) {
        /*
        if ($aKeys) {
            if (is_array($aKeys)) {
                foreach ($aKeys as $key) {
                    if ($this->aParams['DOM'][$key])
                        $dom .= "$key='$this->aParams['DOM'][$key]' ";
                }
            }else{
                if ($this->aParams['DOM'][$aKeys])
                        $dom .= "$key='$this->aParams['DOM'][$aKeys]' ";
            }
        }else{
            foreach ($this->aParams['DOM'] as $key => $value) {
                $dom .= "$key='$value' ";
            }
        }
        return $dom;
        */
        return $this->getNoDOMCSSDJO('DOM', $aKeys, '=', ' ');
    }
    /**
     * Devuelve un strig en el que se han concatenado las propiedades CSS
     * incorporadas dentro de la etiqueta style
     * @return string
     */
    function getNoCSS($aKeys) {
        /*
        if ($aKeys) {
            if (is_array($aKeys)) {
                foreach ($aKeys as $key) {
                    if ($this->aParams['CSS'][$key]) 
                        $style .= $this->parejaKeyValue($key, $this->aParams['CSS'][$key], ";");
                }
            }else{
                if ($this->aParams['CSS'][$aKeys])
                        $style .= $this->parejaKeyValue($aKeys, $this->aParams['CSS'][$aKeys], ";");
            }
        }else{
            foreach ($this->aParams['CSS'] as $key => $value) {
                $style .= $this->parejaKeyValue($key, $value, ";");
            }
        }
        return "style='$style'";
        */
        $style = $this->getNoDOMCSSDJO('CSS', $aKeys, ':', ';');
        return "style='$style'";
    }
    /**
     * Devuelve un strig en el que se han concatenado las propiedades DOJO
     * incorporadas dentro de la etiqueta data-dojo-props
     * @return string
     */
    function getNoDJO($aKeys) {
        /*
        if ($aKeys) {
            foreach ($aKeys as $key) {
                if ($this->aParams['DJO'][$key])
                    $djprp .= $this->parejaKeyValue($key, $this->aParams['DJO'][$key], ",");
            }
        }else{
            foreach ($this->aParams['DJO'] as $key => $value) {
                $djprp .= $this->parejaKeyValue($key, $value, ",");
            }
        }
        return "data-dojo-props=\"$djprp\"";
        */
        $djprp = $this->getNoDOMCSSDJO('DJO', $aKeys, ':', ',');
        return "data-dojo-props=\'$djprp\'";
    }

    /**
     * Devuelve un strig en el que se han concatenado las propiedades 
     * en el formato requerido
     * @return string
     */
    private function getDOMCSSDJO($class, $aKeys, $glue, $sep) {
        if ($aKeys) {
            if (is_array($aKeys)) {
                foreach ($aKeys as $key) {
                    if ($this->aParams[$class][$key]) 
                        $ret .= $this->parejaKeyValue($key, $this->aParams[$class][$key], $glue, $sep);
                }
            }else{
                if ($this->aParams[$class][$aKeys])
                        $ret .= $this->parejaKeyValue($aKeys, $this->aParams[$class][$aKeys], $glue, $sep);
            }
        }else{
            foreach ($this->aParams[$class] as $key => $value) {
                $ret .= $this->parejaKeyValue($key, $value, $glue, $sep);
            }
        }
        if (substr($ret,-1)===",") $ret = substr($ret,0,-1);
        return $ret;
    }
    /**
     * Crea una pareja key:value transformando valores booleanos en strings
     * @return string
     */
    private function parejaKeyValue($k, $v, $g, $s){
        if (is_bool($v)) 
            $v = ($v) ? 'true' : 'false';
        return ($g==':') ? "$k:$v$s" : "$k='$v'$s";
    }
    /**
     * Devuelve un strig en el que se han concatenado las propiedades 
     * en el formato requerido, excluyendo las claves indicadas
     * @return string
     */
    private function getNoDOMCSSDJO($class, $aKeys, $glue, $sep) {
        foreach ($this->aParams[$class] as $key => $value) {
            if (is_array($aKeys)) {
                if (in_array($key, $aKeys)==FALSE)
                    $ret .= $this->parejaKeyValue($key, $value, $glue, $sep);
            }else{
                if ($key !== $aKeys)
                    $ret .= $this->parejaKeyValue($key, $value, $glue, $sep);
            }
        }
        if (substr($ret,-1)===",") $ret = substr($ret,0,-1);
        return $ret;
    }

    /**
     * Devuelve el elemento seleccionado del array PRP de propiedades propias
     * 
     * @return string
     */
    function getPRP($key) {
        $ret = ($key) ? $this->aParams['PRP'][$key] : '';
        return $ret;
    }

    /**
     * Retorna la etiqueta del component.
     *
     * @return string
     */
    function getLabel() {
        return $this->get('DOM','label');
    }
    /**
     * Retorna la id del component.
     *
     * @return string
     */
    function getId() {
        return $this->get('DOM','id');
    }
    /**
     * Retorna el tooltip del component.
     *
     * @return string
     */
    function getToolTip() {
        return $this->get('DOM','toolTip');
    }
    /**
     * Retorna true si es seleccionat o false en cas contrari
     *
     * @return bool
     */
    function isSelected() {
        return $this->get('DOM','selected');
    }
    /**
     * Estableix la etiqueta del component.
     *
     * @param string $label
     */
    function setLabel($v) {
        $this->set('DOM','label', $v);
    }
    /**
     * Estableix la id del component.
     *
     * @param string $id
     */
    function setId($v) {
        $this->set('DOM','id', $v);
    }
    /**
     * Estableix el tooltip del component.
     *
     * @param string $tip
     */
    function setToolTip($v) {
        $this->set('DOM','toolTip', $v);
    }
    /**
     * Estableix que el component es seleccionat si es true o no seleccionat si es false.
     *
     * @param bool $value
     */
    function setSelected($v) {
        $this->set('DOM','selected', $v);
    }
}