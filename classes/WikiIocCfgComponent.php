<?php
/**
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', DOKU_TPLINC.'classes/');
if (!defined('DOKU_TPL_CONF')) define('DOKU_TPL_CONF', DOKU_TPLINC.'conf/');

abstract class WikiIocCfgComponent {
    private $id;
    private $label;
    private $toolTip;
    private $selected;
    
    function __construct($label="", $id=NULL){
       $this->label = $label;
       $this->toolTip = $label;
       $this->id = $id;
       $this->selected = FALSE;
    }
	
    
    function setLabel($label){
        $this->label=$label;
    }
    function setId($id){
        $this->id=$id;
    }
    function setToolTip($tip){
        $this->toolTip=$tip;
    }
    function setSelected($value){
        $this->selected=$value;
    }
    
    function getLabel(){
        return $this->label;
    }
    function getId(){
        return $this->id;
    }
    function getToolTip(){
        return $this->toolTip;
    }
	function isSelected(){
        return $this->selected;
    }
}
?>
