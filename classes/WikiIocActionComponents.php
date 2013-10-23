<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IoctplControlSelector
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

class WikiIocActionTabContainer extends WikiIocActionComponent{
    const DEFAULT_TAB_TYPE=0;
    const RESIZING_TAB_TYPE=1;
    const SCROLLING_TAB_TYPE=2;
    private $tabs;
    private $selected;
    private $tabType; //0= normal, 1=resizing, 2=scrolling
    private $bMenuButton; 
    private $bScrollingButtons; 
    
    public function __construct($label = "", $tabType=0, $id = NULL, 
                                $reqPackage=NULL){
        global $js_packages;
        if($reqPackage==NULL){
            $reqPackage=array(
                array(
                    "name" => "ioc",
                    "location" => $js_packages["ioc"],
                ),
                array(
                    "name" => "dojo",
                    "location" => $js_packages["dojo"],
                ),
                array(
                    "name" => "dijit",
                    "location" => $js_packages["dijit"],
                )
            );
        }
        parent::__construct($label, $id, $reqPackage);
        $this->tabType=$tabType;
        $this->bMenuButton=FALSE;
        $this->bScrollingButtons=FALSE;
    }
    
    function putTab($id, &$tab){
        if($tab->getId()==NULL){
            $tab->setId($id);
        }
        if(!is_array($this->tabs)){
            $this->selected=$id;
            $tab->setSelected(TRUE);
        }else if($tab->isSelected()){
            $this->selectTab($id);
        }

        $ret = $this->tabs[$id];
        $this->tabs[$id]=&$tab;
        return $ret;
    }
    
    function getTab($id){
        return $this->tabs[$id];
    }
    
    function removeTab($id){
        $ret = $this->tabs[$id];
        unset($this->tabs[$id]);
        return $ret;
    }
    
    function removeAllTabs(){
        unset($this->tabs);
    }
    
    function selectTab($id){
        if(array_key_exists($id, $this->tabs)){
            if(array_key_exists($this->selected, $this->tabs)){
                $this->tabs[$this->selected]->setSelected(FALSE);
            }
            $this->selected=$id;
            $this->tabs[$id]->setSelected(TRUE);
        }
    }
    
    function getTabType(){
        return $this->tabType;
    }
    
    function setTabType(/*int*/ $type){
        $this->tabType=$type;
    }
    
    function hasMenuButton(){
       return $this->bMenuButton;
    }

    function setMenuButton(/*boolean*/ $value){
        $this->bMenuButton=$value;
    }

    function hasScrollingButtons(){
       return $this->bScrollingButtons;
    }

    function setScrollingButtons(/*boolean*/ $value){
        $this->bScrollingButtons=$value;
    }
    
    protected function getPreContent(){
        $ret = '<div data-dojo-type="dijit.layout.TabContainer" ';
        $ret.= 'persist="false" ';
        if($this->tabType==2 /*SCROLLING*/){
            $ret.= 'controllerWidget="dijit.layout.ScrollingTabController" ';           
            $ret.='useMenu="'.($this->hasMenuButton()?'true" ':'false" ');
            $ret.='useSlider="'.($this->hasScrollingButtons()?'true" ':'false" ');
        }elseif ($this->tabType==1 /*RESIZING*/) {
            $ret.= 'controllerWidget="ioc.gui.ResizingTabController" ';           
            $ret.='useMenu="'.($this->hasMenuButton()?'true" ':'false" ');
        }else /*NORMAL o DEFAULT*/{
            $ret.= 'controllerWidget="dijit.layout,TabController" ';                       
        }
        $ret.= 'style="min-width: 1em; min-height: 1em; width: 100%; height: 100%;">';
        return $ret;        
    }
    
    protected function getPostContent(){
        $ret = "</div>\n";
        return $ret;
    }
    
    public function getRenderingCode() {
        $ret = $this->getPreContent();
        foreach ($this->tabs as $container){
            $ret .= $container->getRenderingCode();
        }
        $ret .= $this->getPostContent();
        return $ret;
    }
}

class WikiIocActionContainer extends WikiIocActionComponent{
    private $selected;

    function __construct($label="", $id=NULL, 
                                $requiredPakages=NULL){
        global $js_packages;
        if($reqPackage==NULL){
            $reqPackage=array(
                array(
                    "name" => "dojo",
                    "location" => $js_packages["dojo"],
                ),
                array(
                    "name" => "dijit",
                    "location" => $js_packages["dijit"],
                )
            );
        }
        parent::__construct($label, $id, $reqPackage);
        $this->selected=FALSE;
    }

    function isSelected(){
        return $this->selected;
    }
 
    function setSelected($value){
        $this->selected=$value;
    }
    
    protected function getPreContent(){
        $ret = '<div id="'.$this->getId()
                    .'" data-dojo-type="dijit.layout.ContentPane" title="'
                    .$this->getLabel().'" tooltip="'.$this->getToolTip()
                    .'" extractContent="false" preventCache="false" '
                    .'preload="false" refreshOnShow="false" ';
        if($this->isSelected()){
            $ret .= 'selected="true" ';
        }
        $ret .= 'closable="false" doLayout="false">'."\n";
        return $ret;
    }
    
    protected function getPostContent(){
        $ret = "</div>\n";
        return $ret;
    }
   
    protected function getContent(){
        return "";
    }

    public function getRenderingCode() {
        $ret = $this->getPreContent()
                    .$this->getContent()   
                    .$this->getPostContent();
        return $ret;
    }
}

//class IoctplActionButton extends WikiIocActionComponent{
//}

class WikiIocActionContainerFromMenuPage extends WikiIocActionContainer{
   private $page;
   
   function __construct($label="", $page=NULL, $id=NULL){
       parent::__construct($label, $id);
       $this->page=$page;
   }
   
   function getPageName(){
       return $this->page;
   }

   function setPageName($value){
        $this->page=$value;
   }
   
   public function getContent() {
        if($this->page!=NULL){
        $ret .= "    <div class=\"tb_container\">\n"
                   .tpl_include_page($this->page, false)."\n"
                   ."    </div>\n";
        }else{
            $ret="";
        }
        return $ret;
    }
}

class WikiIocActionContainerFromPage extends WikiIocActionContainer{
   private $page;
   
   function __construct($label="", $page=NULL, $id=NULL){
        global $js_packages;
        $reqPackage=array(                
            array(
                "name" => "ioc",
                "location" => $js_packages["ioc"],
            )
        );
        parent::__construct($label, $id, $reqPackage);
        $this->page=$page;
   }
   
   function getPageName(){
       return $this->page;
   }

   function setPageName($value){
        $this->page=$value;
   }
   
    protected function getPreContent(){
        $ret = '<div id="'.$this->getId()
                    .'" data-dojo-type="ioc.gui.ContentTabDokuwikiPage" title="'
                    .$this->getLabel().'" tooltip="'.$this->getToolTip()
                    .'" extractContent="false" preventCache="false" '
                    .'preload="false" refreshOnShow="false" ';
        if($this->isSelected()){
            $ret .= 'selected="true" ';
        }
        $ret .= 'closable="false" doLayout="false">'."\n";
        return $ret;
    }
    
   public function getContent() {
        if($this->page!=NULL){
        $ret .= "    <div class=\"tb_container\">\n"
                   .tpl_include_page($this->page, false)."\n"
                   ."    </div>\n";
        }else{
            $ret="";
        }
        return $ret;
    }
}

class WikiIocActionTreeContainer extends WikiIocActionContainer{
   private $treeDataSource;
   private $rootValue;
   private $pageDataSource;
   
   function __construct($label="", $treeDataSource=NULL, $pageDataSource=NULL, 
                                                    $rootValue="", $id=NULL){
       $reqPackage=array(                
           array(
               "name" => "ioc",
               "location" => $js_packages["ioc"],
           )
       );
       parent::__construct($label, $id, $reqPackage);
       $this->treeDataSource=$treeDataSource;
       $this->rootValue=$rootValue;
       $this->pageDataSource=$pageDataSource;
   }
   
   function getRootValue(){
       return $this->rootValue;
   }

   function setRootValue($value){
        $this->rootValue=$value;
   }
   
   function getTreeDataSource(){
       return $this->treeDataSource;
   }

   function setTreeDataSource($value){
        $this->treeDataSource=$value;
   }
   
   function getPageDataSource(){
       return $this->pageDataSource;
   }

   function setPageDataSource($value){
        $this->pageDataSource=$value;
   }
   
    protected function getPreContent(){
        $ret = '<div id="'.$this->getId()
                    .'" data-dojo-type="ioc.gui.ContentTabDokuwikiNsTree" title="'
                    .$this->getLabel().'" tooltip="'.$this->getToolTip()
                    .'" extractContent="false" preventCache="false" '
                    .'preload="false" refreshOnShow="false" '
                    .'data-dojo-props=\''
                    .'treeDataSource:"'.$this->treeDataSource;
        if($this->rootValue!==NULL){
            $ret .= '", rootValue:"'
                    .$this->rootValue;
        }
        if($this->pageDataSource!==NULL){
            $ret .='", urlBase:"'
                    .$this->pageDataSource;
        }
        $ret .= '"\' ' ;
        if($this->isSelected()){
            $ret .= 'selected="true" ';
        }
        $ret .= 'style="overflow:auto; " closable="false" doLayout="false">'."\n";
        return $ret;
    }
    
   public function getContent() {
//        if($this->page!=NULL){
//        $ret .= "    <div class=\"tb_container\">\n"
//                   .tpl_include_page($this->page, false)."\n"
//                   ."    </div>\n";
//        }else{
//            $ret="";
//        }
//        return $ret;
       return "";
    }
}
?>
