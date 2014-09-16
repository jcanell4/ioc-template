<?php

if(!defined("DOKU_INC")) die();
if(!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', tpl_incdir() . 'classes/');
if(!defined('DOKU_TPL_CONF')) define('DOKU_TPL_CONF', tpl_incdir() . 'conf/');

require_once(DOKU_TPL_CLASSES . 'WikiIocBuilder.php');
require_once(DOKU_TPL_CONF . 'js_packages.php');

/**
 * Description of WikiIocComponent
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
abstract class WikiIocComponent extends WikiIocBuilder {
    private $id;
    private $label;
    private $toolTip;
    private $selected;

    /**
     * @param string  $label       etiqueta del component
     * @param string  $id          id del component
     * @param array[] $reqPackage  hash amb els packages requerit amb el format:
     *                             "array("name" => "ioc", "location" => $js_packages["ioc"])
     */
    function __construct($label = "", $id = NULL, $reqPackage = array()) {
        parent::__construct($reqPackage);
        $this->label    = $label;
        $this->toolTip  = $label;
        $this->id       = $id;
        $this->selected = FALSE;
    }

    /**
     * Retorna la etiqueta del component.
     *
     * @return string
     */
    function getLabel() {
        return $this->label;
    }

    /**
     * Retorna la id del component.
     *
     * @return string
     */
    function getId() {
        return $this->id;
    }

    /**
     * Estableix la etiqueta del component.
     *
     * @param string $label
     */
    function setLabel($label) {
        $this->label = $label;
    }

    /**
     * Estableix la id del component.
     *
     * @param string $id
     */
    function setId($id) {
        $this->id = $id;
    }

    /**
     * Retorna el tooltip del component.
     *
     * @return string
     */
    function getToolTip() {
        return $this->toolTip;
    }

    /**
     * Estableix el tooltip del component.
     *
     * @param string $tip
     */
    function setToolTip($tip) {
        $this->toolTip = $tip;
    }

    /**
     * Retorna true si es seleccionat o false en cas contrari
     *
     * @return bool
     */
    function isSelected() {
        return $this->selected;
    }

    /**
     * Estableix que el component es seleccionat si es true o no seleccionat si es false.
     *
     * @param bool $value
     */
    function setSelected($value) {
        $this->selected = $value;
    }
}