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
        
        if (!$this->aParams['selected']) {
            $this->aParams['selected'] = FALSE;
        }
        if ($this->aParams['label'] && !$this->aParams['toolTip']) {
            $this->aParams['toolTip'] = $this->aParams['label'];
        }
    }

    public abstract function getRenderingCode();

    function get($key) {
        return $this->aParams[$key];
    }
    function set($key, $value) {
        $this->aParams[$key] = $value;
    }

    /**
     * Retorna la etiqueta del component.
     *
     * @return string
     */
    function getLabel() {
        return $this->get('label');
    }
    /**
     * Retorna la id del component.
     *
     * @return string
     */
    function getId() {
        return $this->get('id');
    }
    /**
     * Retorna el tooltip del component.
     *
     * @return string
     */
    function getToolTip() {
        return $this->get('toolTip');
    }
    /**
     * Retorna true si es seleccionat o false en cas contrari
     *
     * @return bool
     */
    function isSelected() {
        return $this->get('selected');
    }
    /**
     * Estableix la etiqueta del component.
     *
     * @param string $label
     */
    function setLabel($v) {
        $this->set('label', $v);
    }
    /**
     * Estableix la id del component.
     *
     * @param string $id
     */
    function setId($v) {
        $this->set('id', $v);
    }
    /**
     * Estableix el tooltip del component.
     *
     * @param string $tip
     */
    function setToolTip($v) {
        $this->set('toolTip', $v);
    }
    /**
     * Estableix que el component es seleccionat si es true o no seleccionat si es false.
     *
     * @param bool $value
     */
    function setSelected($v) {
        $this->set('selected', $v);
    }
}