<?php
/**
 * Description of WikiIocBuilder
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */

//check if we are running within the DokuWiki environment
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', tpl_incdir().'classes/');

require_once(DOKU_TPL_CLASSES.'WikiIocBuilderManager.php');

abstract class WikiIocBuilder{
    protected $requiredPakages;  //WE MUST CONTROL PAKAGE LEVEL ONLY
    
    function __construct($requiredPakages=array()){
        $this->requiredPakages=$requiredPakages;
        WikiIocBuilderManager::Instance()->processComponent($this);
    }
    
    function setRequiredPakages($requiredPakages=array()){
        $this->requiredPakages=$requiredPakages;
    }
    
//    function addRequiredPasckage($name, $location){
//        $this->requiredResources[$reqResources]=true;
//    }

    function getRequiredPackages(){
        return $this->requiredPakages;
    }
    
    abstract function getRenderingCode();    
}

?>
