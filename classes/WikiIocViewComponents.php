<?php
/**
**/
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', DOKU_TPLINC.'classes/');
if (!defined('DOKU_TPL_CONF')) define('DOKU_TPL_CONF', DOKU_TPLINC.'conf/');

require_once(DOKU_TPL_CLASSES.'WikiIocViewComponent.php');
require_once(DOKU_TPL_CONF.'js_packages.php');

abstract class WikiIocContainer extends WikiIocComponent{
	
	function __construct($cfg=NULL, $requiredPackages=NULL){
        parent::__construct($cfg, $requiredPackages);
    }

    abstract protected function getPreContent();
    abstract protected function getPostContent();
    abstract protected function getContent();

    public function getRenderingCode() {
        $ret = $this->getPreContent()
              .$this->getContent()   
              .$this->getPostContent();
        return $ret;
    }
}

abstract class WikiIocItemsContainer extends WikiIocContainer {
	
    function __construct($cfg=NULL, $reqPackage=NULL){
        parent::__construct($cfg, $reqPackage);
    }

	public function putItem($id, &$item){
        return $this->cfg->putItem($id, &$item);
    }
    
    public function getItem($id){
        return $this->cfg->getItem($id);
    }
    
    public function removeItem($id){
        return $this->cfg->removeItem($id);
    }
    
    public function removeAllItems(){
        $this->cfg->removeAllItems();
    }
	
    public function getContent() {
        foreach ($this->cfg->items as $i){
            $ret .= $i->getRenderingCode();
        }
        return $ret;
	}
}

class WikiIocContentPane extends WikiIocContainer{
	
    function __construct($cfg=NULL, $reqPackage=NULL){
        global $js_packages;
        if($reqPackage==NULL){
            $reqPackage=array(
                array("name"=>"dojo", "location"=>$js_packages["dojo"]),
                array("name"=>"dijit", "location"=>$js_packages["dijit"])
            );
        }
        parent::__construct($cfg, $reqPackage);
    }

    protected function getPreContent(){
        $ret = "<div id='{$this->cfg->getId()}' data-dojo-type='dijit.layout.ContentPane'"
              ." title='{$this->cfg->getLabel()}' tooltip='{$this->cfg->getToolTip()}'"
              ." extractContent='false' preventCache='false'"
              ." preload='false' refreshOnShow='false'";
        if($this->cfg->isSelected()){
            $ret .= " selected='true'";
        }
        $ret .= " closable='false' doLayout='false'>\n";
        return $ret;
    }
    
    protected function getPostContent(){
        return "</div>\n";
    }
   
    protected function getContent(){
        return "";
    }

}

class WikiIocTabsContainer extends WikiIocItemsContainer{
    const DEFAULT_TAB_TYPE=0;
    const RESIZING_TAB_TYPE=1;
    const SCROLLING_TAB_TYPE=2;
    
    public function __construct($cfg=NULL, $reqPackage=NULL){
        global $js_packages;
        if($reqPackage==NULL){
            $reqPackage=array(
                array("name"=>"ioc", "location"=>$js_packages["ioc"]),
                array("name"=>"dojo", "location"=>$js_packages["dojo"]),
                array("name"=>"dijit", "location"=>$js_packages["dijit"])
            );
        }
        parent::__construct($cfg, $reqPackage);
    }
    
    function putTab($id, &$tab){
        return $this->cfg->putTab($id, &$tab);
    }
    
    function selectTab($id){
		$this->cfg->selectTab($id);		
    }
    
    function getTab($id){
        return $this->cfg->getTab($id);
    }
    function removeTab($id){
        return $this->cfg->removeTab($id);
    }
    function removeAllTabs(){
        return $this->cfg->removeAllTabs();
    }
    function getTabType(){
        return $this->cfg->getTabType();
    }
    function setTabType(/*int*/ $type){
        $this->cfg->setTabType($type);
    }
    function hasMenuButton(){
       return $this->cfg->hasMenuButton();
    }
    function setMenuButton(/*boolean*/ $value){
        $this->cfg->setMenuButton($value);
    }
    function hasScrollingButtons(){
       return $this->cfg->hasScrollingButtons();
    }
    function setScrollingButtons(/*boolean*/ $value){
        $this->cfg->setScrollingButtons($value);
    }
	
    protected function getPreContent(){
        $ret = "<div id='{$this->getId()}' data-dojo-type='dijit.layout.TabContainer' persist='false'";
        if($this->tabType==2 /*SCROLLING*/){
            $ret.=" controllerWidget='dijit.layout.ScrollingTabController'";
            $ret.=" useMenu='".($this->hasMenuButton()?'true" ':'false"');
            $ret.=' useSlider="'.($this->hasScrollingButtons()?'true" ':'false" ');
        }elseif ($this->tabType==1 /*RESIZING*/) {
            $ret.=' controllerWidget="ioc.gui.ResizingTabController"';
            $ret.=' useMenu="'.($this->hasMenuButton()?'true" ':'false" ');
        }else /*NORMAL o DEFAULT*/{
            $ret.=' controllerWidget="dijit.layout.TabController"';
        }
        $ret.= ' style="min-width: 1em; min-height: 1em; width: 100%; height: 100%;">';
        return $ret;        
    }
    
    protected function getPostContent(){
        return "</div>\n";
    }
}

class WikiIocContainerFromMenuPage extends WikiIocContentPane{
   
   function __construct($cfg=NULL){
       parent::__construct($cfg);
   }
   
   function getPageName(){
       return $this->cfg->getPageName();
   }
   function setPageName($value){
        $this->cfg->setPageName($value);
   }
   
   protected function getContent() {
		$ret="";
        if($this->getPageName() != NULL){
			$ret .= "<div class='tb_container'>\n".tpl_include_page($this->getPageName(), false)."\n</div>\n";
        }
        return $ret;
    }
}

class WikiIocContainerFromPage extends WikiIocContentPane{
   
	function __construct($cfg=NULL){
        global $js_packages;
        $reqPackage=array(
            array("name"=>"ioc", "location"=>$js_packages["ioc"])
        );
        parent::__construct($cfg, $reqPackage);
	}
   
	function getPageName(){
       return $this->cfg->getPageName();
   }
   function setPageName($value){
        $this->cfg->setPageName($value);
	}
   
	protected function getPreContent(){
		$ret = "<div id='{$this->getId()}' data-dojo-type='ioc.gui.ContentTabDokuwikiPage'"
				." title='{$this->getLabel()}' tooltip='{$this->getToolTip()}'"
				." extractContent='false' preventCache='false' preload='false' refreshOnShow='false'";
        if($this->isSelected()){
            $ret .= " selected='true'";
        }
        $ret .= " closable='false' doLayout='false'>\n";
        return $ret;
    }
    
	protected function getContent() {
		$ret = "";
        if($this->getPageName() != NULL){
			$ret .= "<div class=\"tb_container\">\n".tpl_include_page($this->getPageName(), false)."\n</div>\n";
        }
        return $ret;
    }
}

class WikiIocTreeContainer extends WikiIocContentPane{
   
   function __construct($cfg=NULL){
		global $js_packages;
		$reqPackage=array(
				array("name"=>"ioc", "location"=>$js_packages["ioc"])
		);
		parent::__construct($cfg, $reqPackage);
	}
   
   function getRootValue(){
       return $this->cfg->getRootValue();
   }
   function setRootValue($value){
        $this->cfg->setRootValue($value);
   }
   function getTreeDataSource(){
       return $this->cfg->getTreeDataSource();
   }
   function setTreeDataSource($value){
        $this->cfg->setTreeDataSource($value);
   }
   function getPageDataSource(){
       return $this->cfg->getPageDataSource();
   }
   function setPageDataSource($value){
        $this->cfg->setPageDataSource($value);
   }
   
   protected function getPreContent(){
        $ret = "<div id='{$this->getId()}' data-dojo-type='ioc.gui.ContentTabDokuwikiNsTree'"
				." title='{$this->getLabel()}' tooltip='{$this->getToolTip()}'"
				." extractContent='false' preventCache='false' preload='false' refreshOnShow='false'"
				." data-dojo-props='treeDataSource:\"{$this->getTreeDataSource()}\"";
        if($this->getRootValue() !== NULL){
            $ret .= ", rootValue:\"{$this->getRootValue()}\"";
        }
        if($this->getPageDataSource() !== NULL){
            $ret .=", urlBase:\"{$this->getPageDataSource()}\"";
        }
        $ret .= "'";
        if($this->isSelected()){
            $ret .= " selected='true'";
        }
        $ret .= " style='overflow:auto;' closable='false' doLayout='false'>\n";
        return $ret;
    }
    
   protected function getContent() {
		return "";
    }
}

class WikiIocHiddenDialog extends WikiIocItemsContainer {
	/* Descripció:
	 *		Crea un contenidor de la classe ioc.gui.ActionHiddenDialogDokuwiki
	 *		que no és visible en el moment de la seva creació.
	 *		Aquest contenidor està dissenyat per contenir items.
	 *		Els métodes de construcció els hereda de WikiIocItemsContainer.
	 */
    public function __construct($id, $label=""){
        global $js_packages;
        $reqPackage=array(
                array("name"=>"ioc", "location"=>$js_packages["ioc"]),
                array("name"=>"dojo", "location"=>$js_packages["dojo"]),
                array("name"=>"dijit", "location"=>$js_packages["dijit"])
        );
        parent::__construct($label, $id, $reqPackage);
    }

	protected function getPreContent(){
		return "\n<div id='{$this->getId()}' data-dojo-type='ioc.gui.ActionHiddenDialogDokuwiki'>";
	}
    protected function getPostContent(){
        return "</div>\n";
    }
}

class WikiDojoFormContainer extends WikiIocItemsContainer {
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Crea un contenidor de la classe ioc.gui.IocForm
	 *		dissenyat per contenir items de formulari.
	 *		Els métodes de construcció els hereda de WikiIocItemsContainer.
	 */
	private $action;
	private $urlBase;
	private $display;
	private $position;
	private $top;
	private $left;
	private $zindex;

    public function __construct($label="", $id=NULL, $action=NULL, $position="static", $top=0, $left=0, $display=true, $zindex=900){
        global $js_packages;
        $reqPackage=array(
                array("name"=>"ioc", "location"=>$js_packages["ioc"]),
                array("name"=>"dojo", "location"=>$js_packages["dojo"]),
                array("name"=>"dijit", "location"=>$js_packages["dijit"])
        );
        parent::__construct($label, $id, $reqPackage);
		$this->action = $action;
		$this->position = $position;
		$this->top = $top;
		$this->left = $left;
		$this->display = $display;
		$this->zindex = $zindex;
    }

	public function setPosition($position) {
		$this->position = $position;
	}
	public function setZindex($zindex) {
		$this->zindex = $zindex;
	}
	public function setTop($top) {
		$this->top = $top;
	}
	public function setLeft($left) {
		$this->left = $left;
	}
	public function setTopLeft($top, $left) {
		$this->top = $top;
		$this->left = $left;
	}
	public function setAction($action) {
		$this->action = $action;
	}
	public function setUrlBase($url) {
		$this->urlBase = $url;
	}
	public function setDisplay($display) {
		$this->display = $display;
	}
	
	protected function getPreContent(){
		$visible = $this->display ? 'true' : 'false';
        $ret = "<span id='{$this->getId()}' title='{$this->getLabel()}' tooltip='{$this->getToolTip()}'"
				//." extractContent='false' preventCache='false' preload='false' refreshOnShow='false' closable='false' doLayout='false'"
				." style='position:{$this->position}; top:{$this->top}px; left:{$this->left}px; z-index:{$this->zindex};'>\n";
		if ($this->action==NULL)
			$ret.= "<script>alert('No s'ha definit l\'element action al formulari [{$this->getLabel()}].');</script>\n";
		$ret.= "<span id='{$this->getId()}_form' data-dojo-type='ioc.gui.IocForm'"
				." data-dojo-props=\"action:'{$this->action}', urlBase:'{$this->urlBase}', visible:$visible\">\n";
		return $ret;
	}
    protected function getPostContent(){
		$ret = "</span>\n</span>\n";
        return $ret;
    }
}

class WikiIocDropDownButton extends WikiIocContainer{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Dibuixa un botó de la classe ioc.gui.IocDropDownButton
	 * Propietats:
	 *		Accepta n paràmetres que configuren l'aspecte del botó:
	 *		- autoSize: true/false
	 *				true: indica que el seu tamany depen del tamany del contenidor pare
	 *				false: el seu tamany és el tamany estàndar d'un boto, és a dir, té el tamany que ocupa el texte del botó
	 *		- display: true/false
	 *				true: indica que és visible.
	 *				false: indica que no és visible.
	 *		- displayBlock: true/false
	 *				true: utilitzarà la classe CSS .iocDisplayBlock {display:block}.
	 *				false: utilitzarà la classe CSS dijitInline.
	 */
	private $autoSize;
	private $display;
	private $displayBlock;
	private $actionHidden;

	function __construct($id=NULL, $label="", $autoSize=false, $display=true, $displayBlock=true, $actionHidden=NULL){
		global $js_packages;
		$reqPackage=array(
			array("name"=>"ioc", "location"=>$js_packages["ioc"]),
			array("name"=>"dojo", "location"=>$js_packages["dojo"]),
			array("name"=>"dijit", "location"=>$js_packages["dijit"])
		);
        parent::__construct($label, $id, $reqPackage);
		$this->autoSize = $autoSize;
		$this->display = $display;
		$this->displayBlock = $displayBlock;
		$this->actionHidden = $actionHidden;
	}
	
	public function setAutoSize($autoSize) {
		$this->autoSize = $autoSize;
	}
	public function setDisplay($display) {
		$this->display = $display;
	}
	public function setDisplayBlock($displayBlock) {
		$this->displayBlock = $displayBlock;
	}
	public function setActionHidden($actionHidden) {
		$this->actionHidden = $actionHidden;
	}
	
    protected function getPreContent(){
		$autoSize = $this->autoSize ? 'true' : 'false';
		$display = $this->display ? 'true' : 'false';
		$displayBlock = $this->displayBlock ? "iocDisplayBlock" : "dijitInline";
		
		$ret = "\n<div id='{$this->getId()}' data-dojo-type='ioc.gui.IocDropDownButton' class='$displayBlock' style='font-size:0.75em'"
				." data-dojo-props=\"autoSize:$autoSize, visible:$display\">" 
				."\n<span>{$this->getLabel()}</span>";
        return $ret;
	}
	
    protected function getPostContent(){
        $ret = "\n</div>\n";
        return $ret;
    }
   
    protected function getContent(){
		if ($this->actionHidden !== NULL)
			$ret = $this->actionHidden->getRenderingCode();
		else
			$ret = "";
        return $ret;
    }
}	

class WikiDojoButton extends WikiIocComponent{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Dibuixa un botó de la classe dijit.form.Button
	 * Propietats:
	 *		Accepta paràmetres que configuren l'aspecte del botó:
	 *		- display: true/false
	 *				true: indica que és visible.
	 *				false: indica que no és visible.
	 *		- displayBlock: true/false
	 *				true: utilitzarà la classe CSS .iocDisplayBlock {display:block}.
	 *				false: utilitzarà la classe CSS dijitInline.
	 * 
	 *		Accepta paràmetres que configuren l'acció del botó:
	 *		- action
	 */
	private $action;
	protected $display;
	protected $displayBlock;
	protected $fontSize;

	function __construct($label="", $id=NULL, $action=NULL, $display=true, $displayBlock=true, $fontSize=1, $type='button', $reqPackage=NULL){
		global $js_packages;
        if($reqPackage==NULL){
			$reqPackage=array(
				array("name"=>"dojo", "location"=>$js_packages["dojo"]),
				array("name"=>"dijit","location"=>$js_packages["dijit"])
			);
		}
		if ($id == NULL) $id = $label;
        parent::__construct($label, $id, $reqPackage);
		$this->action = $action;
		$this->display = $display;
		$this->displayBlock = $displayBlock;
		$this->fontSize = $fontSize;
		$this->type = $type;
	}

	public function setAction($action) {
		$this->action = $action;
	}
	public function setDisplay($display) {
		$this->display = $display;
	}
	public function setDisplayBlock($displayBlock) {
		$this->displayBlock = $displayBlock;
	}
	public function setFontSize($fontSize) {
		$this->fontSize = $fontSize;
	}
	public function setType($type) {
		$this->type = $type;
	}

	public function getRenderingCode() {
		$visible = $this->display ? 'true' : 'false';
		$displayBlock = $this->displayBlock ? "iocDisplayBlock" : "dijitInline";
		
		$ret = "<input id='{$this->getId()}' class='$displayBlock' type='{$this->type}' data-dojo-type='dijit.form.Button'"
				." data-dojo-props=\"onClick: function(){{$this->action}}, visible:$visible\"" 
				." label='{$this->getLabel()}' tabIndex='-1' intermediateChanges='false'"
				." iconClass='dijitNoIcon' style='font-size:{$this->fontSize}em;'></input>\n";
        return $ret;
    }
}	

class WikiIocButton extends WikiDojoButton{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Dibuixa un botó de la classe ioc.gui.IocButton
	 * Propietats:
	 *		Accepta paràmetres que configuren l'aspecte del botó:
	 *		- autoSize: true/false
	 *				true: indica que el seu tamany depen del tamany del contenidor pare
	 *				false: el seu tamany és el tamany estàndar d'un boto, és a dir, té el tamany que ocupa el texte del botó
	 *		- display: true/false
	 *				true: indica que és visible.
	 *				false: indica que no és visible.
	 *		- displayBlock: true/false
	 *				true: utilitzarà la classe CSS .iocDisplayBlock {display:block}.
	 *				false: utilitzarà la classe CSS dijitInline.
	 * 
	 *		Accepta paràmetres que configuren l'acció del botó:
	 *		- query
	 */
	private $query;
	private $autoSize;

	function __construct($label="", $id=NULL, $query=NULL, $autoSize=false, $display=true, $displayBlock=true){
		global $js_packages;
		$reqPackage=array(
			array("name"=>"ioc", "location"=>$js_packages["ioc"])
		);
        parent::__construct($label, $id, $query, $display, $displayBlock, 'button', $reqPackage);
		$this->query = $query;
		$this->autoSize = $autoSize;		
	}

	public function setQuery($query) {
		$this->query = $query;
	}
	public function setAutoSize($autoSize) {
		$this->autoSize = $autoSize;
	}

	public function getRenderingCode() {
		$autoSize = $this->autoSize ? 'true' : 'false';
		$display = $this->display ? 'true' : 'false';
		$displayBlock = $this->displayBlock ? "iocDisplayBlock" : "dijitInline";
		
		$ret = "\n<input id='{$this->getId()}' class='$displayBlock' type='button' data-dojo-type='ioc.gui.IocButton'"
				." data-dojo-props=\"query:'{$this->query}', autoSize:$autoSize, visible:$display\"" 
				." label='{$this->getLabel()}' tabIndex='-1' intermediateChanges='false'"
				." iconClass='dijitNoIcon' style='font-size:0.75em;'></input>\n";
        return $ret;
    }
}	

class WikiIocFormInputField extends WikiIocComponent{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Dibuixa un item que pot ser albergat en un contenidor
	 *		Aquest item és un input textbox de la classe dijit.form.TextBox
	 */
	private $name;
	private $type;

	function __construct($label="", $id=NULL, $name=NULL, $type=NULL){
		global $js_packages;
		$reqPackage=array(
			array("name"=>"dojo", "location"=>$js_packages["dojo"])
		);
        parent::__construct($label, $id, $reqPackage);
        $this->name = ($name == NULL) ? $this->getId() : $name;
		$this->type = ($type == NULL) ? "" : "type='$type' ";
	}
	
	public function setType($type){
		$this->type = ($type == NULL) ? "" : "type='$type' ";
	}
	
    public function getRenderingCode() {
        return "<label for='{$this->getId()}'>{$this->getLabel()}</label> <input data-dojo-type='dijit.form.TextBox' id='{$this->getId()}' name='{$this->name}' {$this->type}/><br />";
    }
}

class WikiDojoToolBar extends WikiIocItemsContainer{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		És un contenidor que construeix una barra de botons.
	 *		Permet establir la seva posició i tamany.
	 */
	private $position;
	private $zindex;
	private $top;
	private $left;

	function __construct($label="", $id=NULL, $position="static", $top=0, $left=0, $zindex=900){
		global $js_packages;
		$reqPackage=array(
			array("name"=>"dojo", "location"=>$js_packages["dojo"]),
			array("name"=>"dijit","location"=>$js_packages["dijit"])
		);
		if ($id==NULL) $id=$label;
        parent::__construct($label, $id, $reqPackage);
		$this->position = $position;
		$this->zindex = $zindex;
		$this->top = $top;
		$this->left = $left;
	}
	
	public function setPosition($position) {
		$this->position = $position;
	}
	public function setZindex($zindex) {
		$this->zindex = $zindex;
	}
	public function setTop($top) {
		$this->top = $top;
	}
	public function setLeft($left) {
		$this->left = $left;
	}
	public function setTopLeft($top, $left) {
		$this->top = $top;
		$this->left = $left;
	}
	
    protected function getPreContent(){
		if ($this->position=="absolute" && ($this->top==NULL || $this->left==NULL)) {
			$ret = "\n<span missatgeError='nombre incorrecte de paràmetres'>\n";
		}else {
			$ret = "\n<span style='position:{$this->position}; top:{$this->top}px; left:{$this->left}px; z-index:{$this->zindex};'>";
		}
		$ret .= "\n<span data-dojo-type='dijit.Toolbar'>\n";
        return $ret;
	}
	
    protected function getPostContent(){
        $ret = "\n</span>\n</span>\n";
        return $ret;
    }
}	

class WikiIocLeftContainer extends WikiIocItemsContainer{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Crea un contenidor per allotjar els items de la part esquerra.
	 *		Està dissenyat per contenir blocs d'itmes.
	 * Mètodes:
	 *		putItem: afegeix un nou item al contenidor
	 */
	function __construct($label="", $id=NULL){
		global $js_packages;
		$reqPackage=array(
			array("name"=>"dojo", "location"=>$js_packages["dojo"]),
			array("name"=>"dijit","location"=>$js_packages["dijit"])
		);
		if ($id==NULL) $id=$label;
        parent::__construct($label, $id, $reqPackage);
	}
	
    protected function getPreContent(){
		$ret = '<div data-dojo-type="dijit.layout.ContentPane" extractContent="false" preventCache="false" preload="false" refreshOnShow="false" doLayout="true" region="left" splitter="true" minSize="150" maxSize="Infinity" style="width: 190px;" closable="false">\n';
        return $ret;
	}
	protected function getPostContent(){
        $ret = "</div>\n</div>\n";
        return $ret;
    }
}

class WikiIocRightContainer extends WikiIocItemsContainer{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Crea un contenidor per allotjar els items de la #zona de canvi de mode# (part dreta).
	 *		Està dissenyat per contenir els botons.
	 * Mètodes:
	 *		putItem: afegeix un nou item al contenidor
	 */
	function __construct($label="", $id=NULL){
		global $js_packages;
		$reqPackage=array(
			array("name"=>"dojo", "location"=>$js_packages["dojo"]),
			array("name"=>"dijit","location"=>$js_packages["dijit"])
		);
		if ($id==NULL) $id=$label;
        parent::__construct($label, $id, $reqPackage);
	}
	
    protected function getPreContent(){
        return "";
	}
	protected function getPostContent(){
        return "";
    }
}

class WikiIocMetaInfoContainer extends WikiIocItemsContainer{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Crea un contenidor per allotjar els items de la #zona de propietats# (part esquerre).
	 *		Està dissenyat per contenir els div de propietats.
	 * Mètodes:
	 *		putItem: afegeix un nou item al contenidor
	 */
	function __construct($label="", $id=NULL){
		global $js_packages;
		$reqPackage=array(
			array("name"=>"dojo", "location"=>$js_packages["dojo"]),
			array("name"=>"dijit","location"=>$js_packages["dijit"])
		);
		if ($id==NULL) $id=$label;
        parent::__construct($label, $id, $reqPackage);
	}
	
    protected function getPreContent(){
        return "<span id='{$this->getId()}' data-dojo-type='dijit.layout.AccordionContainer' data-dojo-props='id:\"{$this->getId()}\"' duration='200' persist='false' style='min-width: 1em; min-height: 1em; width: 100%; height: 100%;'>\n";
	}
	protected function getPostContent(){
        return "</span>";
    }
}

class WikiIocProperty extends WikiIocComponent{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Crea un contenidor per a la zona de propietats
	 */
	function __construct($label="", $id=NULL, $title="", $selected=false){
		global $js_packages;
		$reqPackage=array(
			array("name"=>"dojo", "location"=>$js_packages["dojo"]),
			array("name"=>"dijit","location"=>$js_packages["dijit"])
		);
        parent::__construct($label, $id, $reqPackage);
		$this->title = $title;
		$this->selected = ($selected) ? "selected='true'" : "";
	}
	
	public function getRenderingCode() {
		$ret = "<div data-dojo-type='dijit.layout.ContentPane' title='{$this->title}' extractContent='false' preventCache='false' preload='false' refreshOnShow='false' {$this->selected} closable='false' doLayout='false'></div>\n";
		return $ret;
	}
}

class WikiIocCentralTabsContainer extends WikiIocItemsContainer{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Crea un contenidor per allotjar els items centrals.
	 *		Està dissenyat per contenir itmes com a pestanyes.
	 * Mètodes:
	 *		putTab: afegeix un nou item al contenidor
	 * Propietats:
	 *		tabType: 0=sin botones, 1=con botones para el scroll horizontal de pestañas
	 *		tabSelected: conté el id de la pestanya seleccionada
	 */
    const DEFAULT_TAB_TYPE=0;
    const SCROLLING_TAB_TYPE=1;
    private $tabType; 
    private $tabSelected;
    private $bMenuButton; 
    private $bScrollingButtons; 
    
    public function __construct($label="", $tabType=0, $id=NULL, $reqPackage=NULL){
        global $js_packages;
        if($reqPackage==NULL){
            $reqPackage=array(
                array("name"=>"ioc", "location"=>$js_packages["ioc"]),
                array("name"=>"dojo", "location"=>$js_packages["dojo"]),
                array("name"=>"dijit", "location"=>$js_packages["dijit"])
            );
        }
		if ($id==NULL) {$id=$label;}
        parent::__construct($label, $id, $reqPackage);
        $this->tabType=$tabType;
        $this->bMenuButton=FALSE;
        $this->bScrollingButtons=FALSE;
    }
    
    function putTab($id, &$tab){
		if(!is_array($this->items)){
            $this->tabSelected=$id;
            $tab->setSelected(TRUE);
		}else if($tab->isSelected()){
            $this->selectTab($id);
		}
		$ret = $this->putItem($id, $tab);
		return $ret;
    }
    function getTab($id){
        return $this->getItem($id);
    }
    function removeTab($id){
        return $this->removeItem($id);
    }
    function removeAllTabs(){
        return $this->removeAllItems();
    }
    function selectTab($id){
        if(array_key_exists($id, $this->items)){
            if(array_key_exists($this->tabSelected, $this->items)){
                $this->items[$this->tabSelected]->setSelected(FALSE);
            }
            $this->tabSelected=$id;
            $this->items[$id]->setSelected(TRUE);
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
		$useMenu = $this->hasMenuButton() ? "true" : "false";
		$useSlider = $this->hasScrollingButtons() ? "true" : "false";
		
        $ret = "<div id='{$this->getId()}' data-dojo-type='dijit.layout.TabContainer' persist='false'";
        if($this->tabType==1 /*SCROLLING*/){
            $ret.=" controllerWidget='dijit.layout.ScrollingTabController'";
	        $ret.=" useMenu='$useMenu'";
		    $ret.=" useSlider='$useSlider'";
        }else /*DEFAULT*/{
			$ret.=" controllerWidget='dijit.layout.TabController'";
		}
        $ret.=" style='min-width: 1em; min-height: 1em; width: 100%; height: 100%;'>\n";
        return $ret;        
    }
    
    protected function getPostContent(){
        $ret = "</div>\n";
        return $ret;
    }
}

class WikiIocHeadContainer extends WikiIocItemsContainer{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Crea un contenidor per allotjar els items de la capçalera.
	 * Mètodes:
	 *		putItem: afegeix un nou item al contenidor
	 */
    public function __construct($id=NULL, $label="", $reqPackage=NULL){
        parent::__construct($label, $id, $reqPackage);
    }
	
	protected function getPreContent(){
		return "";
	}
	protected function getPostContent(){
		return "";
	}
}

class WikiIocHeadLogo extends WikiIocComponent{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Dibuixa el logo IOC
	 */
	public function getRenderingCode() {
		$ret = "<span style='top: 2px; left: 0px; width: 240px; height: 50px; position: absolute; z-index: 900;'>\n"
				."<img alt='logo' style='position: absolute; z-index: 900; top: 0px; left: 10px; height: 50px; width: 200px;' src='".DOKU_TPL."img/logo.png'></img>\n"
				."</span>\n";
		return $ret;
	}
}

class WikiIocBottomContainer extends WikiIocContainer{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Crea un contenidor per escriure missatges d'informació per l'usuari.
	 */
	private $missatge;
	
    public function __construct($label="", $id=NULL){
		global $js_packages;
		$reqPackage=array(
			array("name"=>"dojo", "location"=>$js_packages["dojo"]),
			array("name"=>"dijit","location"=>$js_packages["dijit"])
		);
		if ($id==NULL) $id=$label;
        parent::__construct($label, $id, $reqPackage);
    }
	
	public function setMessage($msg) {
		$this->missatge = $msg;
	}

	protected function getPreContent(){
		$ret = "<div id='{$this->getId()}' data-dojo-type='dijit.layout.ContentPane' extractContent='false' preventCache='false'"; 
		$ret.= "preload='false' refreshOnShow='false' closable='false' doLayout='false'>";
		return $ret;
	}
	protected function getPostContent(){
		return "</div>\n";
	}
	protected function getContent(){
		return $this->missatge;
	}
}
?>
