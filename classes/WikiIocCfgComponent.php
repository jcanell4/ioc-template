<?php

if(!defined("DOKU_INC")) die();
if(!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', tpl_incdir() . 'classes/');
if(!defined('DOKU_TPL_CONF')) define('DOKU_TPL_CONF', tpl_incdir() . 'conf/');

/**
 * Class WikiIocCfgComponent
 * Aquesta es la superclasse de totes les classes de configuració dels components WikiIocCfg*.
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
abstract class WikiIocCfgComponent {
    private $id;
    private $label;
    private $toolTip;
    private $selected;

    /**
     * @param string $label etiqueta que es mostrarà al component
     * @param string $id    id del component
     */
    function __construct($label = "", $id = NULL) {
        $this->label    = $label;
        $this->toolTip  = $label;
        $this->id       = $id;
        $this->selected = FALSE;
    }

    /**
     * @param string $label
     */
    function setLabel($label) {
        $this->label = $label;
    }

    /**
     * @param string $id id del component
     */
    function setId($id) {
        $this->id = $id;
    }

    /**
     * @param string $tip tooltip del component
     */
    function setToolTip($tip) {
        $this->toolTip = $tip;
    }

    /**
     * @param bool $value true si està seleccionat o fals en cas contrari
     */
    function setSelected($value) {
        $this->selected = $value;
    }

    /**
     * @return string etiqueta del component
     */
    function getLabel() {
        return $this->label;
    }

    /**
     * @return string id del component
     */
    function getId() {
        return $this->id;
    }

    /**
     * @return string tooltip del component
     */
    function getToolTip() {
        return $this->toolTip;
    }

    /**
     * @return bool true si està seleccionat o fals en cas contrari
     */
    function isSelected() {
        return $this->selected;
    }
}