<?php

/**
 * Description of WikiIocComponents
 *
 * @author Rafael Claver
 */

//check if we are running within the DokuWiki environment
if (!defined("DOKU_INC")){
    die();
}
if(!define(DOKU_TPL_CLASSES)){
    define(DOKU_TPL_CLASSES, DOKU_TPLINC.'classes/');
}
if(!define(DOKU_TPL_CONF)){
    define(DOKU_TPL_CONF, DOKU_TPLINC.'conf/');
}
require_once(DOKU_TPL_CLASSES.'WikiIocActionComponent.php');
require_once(DOKU_TPL_CONF.'js_packages.php');

class WikiIocContainer extends WikiIocComponent{
	private $objects;

	function __construct($label="", $id=NULL, $autoSize=false, $display=true, $displayBlock=true/*, $baseUrl=NULL*/){
		global $js_packages;
		$reqPackage=array(
			array("name" => "ioc"
				,"location" => $js_packages["ioc"]
			),
			array("name" => "dojo"
				,"location" => $js_packages["dojo"]
			),
			array("name" => "dijit"
				,"location" => $js_packages["dijit"]
			)
		);
        parent::__construct($label, $id, $reqPackage/*, $baseUrl*/);
		$this->autoSize = $autoSize;
		$this->display = $display;
		$this->displayBlock = $displayBlock;
	}

	function putObject($id, &$object){
        if($object->getId() == NULL){
            $object->setId($id);
        }
        $ret = $this->$object;
        return $ret;
    }

	protected function getPreContent(){
        $ret = '<div data-dojo-type="dijit.layout.ContentPane" extractContent="false" preventCache="false" preload="false" refreshOnShow="false" doLayout="true" region="right" splitter="true" minSize="0" maxSize="Infinity" style="padding:0px; width: 60px;" closable="true">';
        return $ret;
	}
	
    protected function getPostContent(){
        $ret = "</div>\n";
        return $ret;
    }
    
	public function getRenderingCode() {
        $ret = $this->getPreContent();
        foreach ($this->objects as $obj){
            $ret .= $obj->getRenderingCode();
        }
        $ret .= $this->getPostContent();
        return $ret;
    }
}	

?>
