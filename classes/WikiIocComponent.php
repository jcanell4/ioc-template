<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WikiIocComponent
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */

//check if we are running within the DokuWiki environment
if (!defined("DOKU_INC")){
    die();
}

if(!define(DOKU_TPL_CLASSES)){
    define(DOKU_TPL_CLASSES, DOKU_TPLINC.'classes/');
}
require_once(DOKU_TPL_CLASSES.'WikiIocComponentManager.php');

abstract class WikiIocComponent{
    protected $requiredPakages;  //WE MUST CONTROL PAKAGE LEVEL ONLY
    
    function __construct($requiredPakages=array()){
        $this->requiredPakages=$requiredPakages;
        WikiIocComponentManager::Instance()->processComponent($this);
    }
    
    function setRequiredPakages($requiredPakages=array()){
        $this->requiredPakages=$requiredPakages;
    }
    
    function addRequiredPasckage($name, $location){
        $this->requiredResources[$reqResources]=true;
    }

    function getRequiredPackages(){
        return $this->requiredPakages;
    }
    
    abstract function getRenderingCode();    
}

?>
