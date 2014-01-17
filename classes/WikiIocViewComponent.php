<?php
/**
 * Description of WikiIocComponent
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', tpl_incdir().'classes/');
if (!defined('DOKU_TPL_CONF')) define('DOKU_TPL_CONF', tpl_incdir().'conf/');

require_once(DOKU_TPL_CLASSES.'WikiIocBuilder.php');
require_once(DOKU_TPL_CONF.'js_packages.php');


abstract class WikiIocComponent extends WikiIocBuilder{
	private /* WikiIocCfgComponent*/ $cfg;
    
    function __construct($cfg, $reqPackage=array()){
		$this->cfg = $cfg;
		parent::__construct($reqPackage);
    }
    
    function setLabel($label){
        $this->cfg->setLabel($label);
    }
    function setId($id){
        $this->cfg->setId($id);
    }
    function setToolTip($tip){
        $this->cfg->setToolTip($tip);
    }
    function setSelected($value){
        $this->cfg->setSelected($value);
    }
    
    function getLabel(){
        return $this->cfg->getLabel();
    }
    function getId(){
        return $this->cfg->getId();
    }
    function getToolTip(){
        return $this->cfg->getToolTip();
    }
	function isSelected(){
        return $this->cfg->isSelected();
    }
}
?>
