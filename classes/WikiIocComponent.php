<?php
/**
 * Description of WikiIocComponent
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', DOKU_TPLINC.'classes/');
if (!defined('DOKU_TPL_CONF')) define('DOKU_TPL_CONF', DOKU_TPLINC.'conf/');

require_once(DOKU_TPL_CLASSES.'WikiIocBuilder.php');
require_once(DOKU_TPL_CONF.'js_packages.php');


abstract class WikiIocComponent extends WikiIocBuilder{
    private $id;
    private $label;
    private $toolTip;
    private $selected;
    
    function __construct($label="", $id=NULL, $reqPackage=array()){
       parent::__construct($reqPackage);
       $this->label=$label;
       $this->toolTip=$label;
       $this->id=$id;
       $this->selected=FALSE;
    }
    
    function getLabel(){
        return $this->label;
    }
    
    function getId(){
        return $this->id;
    }
    
    function setLabel($label){
        $this->label=$label;
    }
    
    function setId($id){
        $this->id=$id;
    }
    
    function getToolTip(){
        return $this->toolTip;
    }
    
    function setToolTip($tip){
        $this->toolTip=$tip;
    }

	function isSelected(){
        return $this->selected;
    }
 
    function setSelected($value){
        $this->selected=$value;
    }
}
?>
