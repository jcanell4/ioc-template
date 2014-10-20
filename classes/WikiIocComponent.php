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
    function __construct($aParms = array("id" => NULL, "label" => ""), $reqPackage = array()) {
        parent::__construct($reqPackage);
        $this->id       = $aParms['id'];
        $this->label    = $aParms['label'];
        $this->toolTip  = $aParms['label'];
        $this->selected = FALSE;
    }

    public abstract function getRenderingCode();

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
     * Retorna el tooltip del component.
     *
     * @return string
     */
    function getToolTip() {
        return $this->toolTip;
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
     * Estableix el tooltip del component.
     *
     * @param string $tip
     */
    function setToolTip($tip) {
        $this->toolTip = $tip;
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