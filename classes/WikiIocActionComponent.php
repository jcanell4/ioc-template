<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WikiIocActionComponent
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined("DOKU_INC")){
    die();
}

if(!define(DOKU_TPL_CLASSES)){
    define(DOKU_TPL_CLASSES, DOKU_TPLINC.'classes/');
}
if(!define(DOKU_TPL_CONF)){
    define(DOKU_TPL_CONF, DOKU_TPLINC.'conf/');
}
require_once(DOKU_TPL_CLASSES.'WikiIocComponent.php');
require_once(DOKU_TPL_CONF.'js_packages.php');


abstract class WikiIocActionComponent extends WikiIocComponent{
    private $id;
    private $label;
    private $toolTip;
    
    function __construct($label="", $id=NULL, $reqPackage=array()){
       parent::__construct($reqPackage);
       $this->label=$label;
       $this->toolTip=$label;
       $this->id=$id;
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
}


?>
