<?php
/**
 */

//check if we are running within the DokuWiki environment
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', DOKU_TPLINC.'classes/');

require_once(DOKU_TPL_CLASSES.'WikiIocBuilderManager.php');

abstract class WikiIocCfgBuilder{
    protected $requiredPakages;  //WE MUST CONTROL PAKAGE LEVEL ONLY
    
    function __construct($requiredPakages=array()){
        $this->requiredPakages=$requiredPakages;
        WikiIocBuilderManager::Instance()->processComponent($this);
    }
    
    function setRequiredPakages($requiredPakages=array()){
        $this->requiredPakages=$requiredPakages;
    }
    
    function getRequiredPackages(){
        return $this->requiredPakages;
    }
}
?>
