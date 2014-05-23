<?php
/**
**/
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', tpl_incdir().'classes/');
if (!defined('DOKU_TPL_CONF')) define('DOKU_TPL_CONF', tpl_incdir().'conf/');

require_once(DOKU_TPL_CLASSES.'WikiIocViewComponent.php');
require_once(DOKU_TPL_CLASSES.'WikiIocCfgComponents.php');
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
	
	private $cfg;
	
    function __construct($cfg=NULL, $reqPackage=NULL){
        parent::__construct($cfg, $reqPackage);
		$this->cfg = $cfg;
    }

	public function putItem($id, &$item){
        return $this->cfg->putItem($id, $item);
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
		$allItems = $this->cfg->getAllItems();
        foreach ($allItems as $i){
            $ret .= $i->getRenderingCode();
        }
        return $ret;
	}
}

class WikiIocContentPane extends WikiIocContainer{
	
	private $cfg;
	
    function __construct($cfg=NULL, $reqPackage=NULL){
        global $js_packages;
        if($reqPackage==NULL){
            $reqPackage=array(
                array("name"=>"dojo", "location"=>$js_packages["dojo"]),
                array("name"=>"dijit", "location"=>$js_packages["dijit"])
            );
        }
        parent::__construct($cfg, $reqPackage);
		$this->cfg = $cfg;
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
	private $cfg;
    
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
		$this->cfg = $cfg;
    }
    
    function getTab($id){
        return $this->cfg->getTab($id);
    }
    function putTab($id, &$tab){
        return $this->cfg->putTab($id, $tab);
    }
    function selectTab($id){
		$this->cfg->selectTab($id);		
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
        if($this->getTabType()==2 /*SCROLLING*/){
            $ret.=" controllerWidget='dijit.layout.ScrollingTabController'";
            $ret.=" useMenu='".($this->hasMenuButton()?'true" ':'false"');
            $ret.=' useSlider="'.($this->hasScrollingButtons()?'true" ':'false" ');
        }elseif ($this->getTabType()==1 /*RESIZING*/) {
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
   
	private $cfg;

	function __construct($cfg=NULL){
		parent::__construct($cfg);
		$this->cfg = $cfg;
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
   
	private $cfg;

	function __construct($cfg=NULL){
        global $js_packages;
        $reqPackage=array(
            array("name"=>"ioc", "location"=>$js_packages["ioc"])
        );
        parent::__construct($cfg, $reqPackage);
		$this->cfg = $cfg;
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
   
	private $cfg;

	function __construct($cfg=NULL){
		global $js_packages;
		$reqPackage=array(
				array("name"=>"ioc", "location"=>$js_packages["ioc"])
		);
		parent::__construct($cfg, $reqPackage);
		$this->cfg = $cfg;
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
    public function __construct($cfg=NULL){
        global $js_packages;
        $reqPackage=array(
                array("name"=>"ioc", "location"=>$js_packages["ioc"]),
                array("name"=>"dojo", "location"=>$js_packages["dojo"]),
                array("name"=>"dijit", "location"=>$js_packages["dijit"])
        );
        parent::__construct($cfg, $reqPackage);
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
	private $cfg;

    public function __construct($cfg=NULL){
        global $js_packages;
        $reqPackage=array(
                array("name"=>"ioc", "location"=>$js_packages["ioc"]),
                array("name"=>"dojo", "location"=>$js_packages["dojo"]),
                array("name"=>"dijit", "location"=>$js_packages["dijit"])
        );
        parent::__construct($cfg, $reqPackage);
		$this->cfg = $cfg;
    }

	public function setPosition($position) {
		$this->cfg->setPosition($position);
	}
	public function setZindex($zindex) {
		$this->cfg->setZindex($zindex);
	}
	public function setTop($top) {
		$this->cfg->setTop($top);
	}
	public function setLeft($left) {
		$this->cfg->setLeft($left);
	}
	public function setTopLeft($top, $left) {
		$this->cfg->setTopLeft($top, $left);
	}
	public function setAction($action) {
		$this->cfg->setAction($action);
	}
	public function setUrlBase($url) {
		$this->cfg->setUrlBase($url);
	}
	public function setDisplay($display) {
		$this->cfg->setDisplay($display);
	}
	public function getPosition() {
		return $this->cfg->getPosition();
	}
	public function getZindex() {
		return $this->cfg->getZindex();
	}
	public function getTop() {
		return $this->cfg->getTop();
	}
	public function getLeft() {
		return $this->cfg->getLeft();
	}
	public function getAction() {
		return $this->cfg->getAction();
	}
	public function getUrlBase() {
		return $this->cfg->getUrlBase();
	}
	public function getDisplay() {
		return $this->cfg->getDisplay();
	}
	
	protected function getPreContent(){
		$visible = $this->getDisplay() ? 'true' : 'false';
        $ret = "<span id='{$this->getId()}' title='{$this->getLabel()}' tooltip='{$this->getToolTip()}'"
				//." extractContent='false' preventCache='false' preload='false' refreshOnShow='false' closable='false' doLayout='false'"
				." style='position:{$this->getPosition()}; top:{$this->getTop()}px; left:{$this->getLeft()}px; z-index:{$this->getZindex()};'>\n";
		if ($this->cfg->getAction()==NULL)
			$ret.= "<script>alert('No s'ha definit l\'element \'action\' al formulari [{$this->getLabel()}].');</script>\n";
		$ret.= "<span id='{$this->getId()}_form' data-dojo-type='ioc.gui.IocForm'"
				." data-dojo-props=\"action:'{$this->getAction()}', urlBase:'{$this->getUrlBase()}', visible:$visible\">\n";
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
	private $cfg;

	function __construct($cfg=NULL){
		global $js_packages;
		$reqPackage=array(
			array("name"=>"ioc", "location"=>$js_packages["ioc"]),
			array("name"=>"dojo", "location"=>$js_packages["dojo"]),
			array("name"=>"dijit", "location"=>$js_packages["dijit"])
		);
        parent::__construct($cfg, $reqPackage);
		$this->cfg = $cfg;
	}
	
	public function setAutoSize($autoSize) {
		$this->cfg->setAutoSize($autoSize);
	}
	public function setDisplay($display) {
		$this->cfg->setDisplay($display);
	}
	public function setDisplayBlock($displayBlock) {
		$this->cfg->setDisplayBlock($displayBlock);
	}
	public function setActionHidden($actionHidden) {
		$this->cfg->setActionHidden($actionHidden);
	}
	public function getAutoSize() {
		return $this->cfg->getAutoSize();
	}
	public function getDisplay() {
		return $this->cfg->getDisplay();
	}
	public function getDisplayBlock() {
		return $this->cfg->getDisplayBlock();
	}
	public function getActionHidden() {
		return $this->cfg->getActionHidden();
	}
	
    protected function getPreContent(){
		$autoSize = $this->setAutoSize() ? 'true' : 'false';
		$display = $this->getDisplay() ? 'true' : 'false';
		$displayBlock = $this->getDisplayBlock() ? "iocDisplayBlock" : "dijitInline";
		
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
		if ($this->getActionHidden() !== NULL)
			$ret = $this->getActionHidden()->getRenderingCode();
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
	private $cfg;

	function __construct($cfg=NULL, $reqPackage=NULL){
		global $js_packages;
        if($reqPackage==NULL){
			$reqPackage=array(
				array("name"=>"dojo", "location"=>$js_packages["dojo"]),
				array("name"=>"dijit","location"=>$js_packages["dijit"])
			);
		}
        parent::__construct($cfg, $reqPackage);
		$this->cfg = $cfg;
	}

	public function setAction($action) {
		$this->cfg->setAction($action);
	}
	public function setDisplay($display) {
		$this->cfg->setDisplay($display);
	}
	public function setDisplayBlock($displayBlock) {
		$this->cfg->setDisplayBlock($displayBlock);
	}
	public function setFontSize($fontSize) {
		$this->cfg->setFontSize($fontSize);
	}
	public function setType($type) {
		$this->cfg->setType($type);
	}
	public function getAction() {
		return $this->cfg->getAction();
	}
	public function getDisplay() {
		return $this->cfg->getDisplay();
	}
	public function getDisplayBlock() {
		return $this->cfg->getDisplayBlock();
	}
	public function getFontSize() {
		return $this->cfg->getFontSize();
	}
	public function getType() {
		return $this->cfg->getType();
	}

	public function getRenderingCode() {
		$visible = $this->getDisplay() ? 'true' : 'false';
		$displayBlock = $this->getDisplayBlock() ? "iocDisplayBlock" : "dijitInline";
		
		$ret = "<input id='{$this->getId()}' class='$displayBlock' type='{$this->getType()}' data-dojo-type='dijit.form.Button'"
				." data-dojo-props=\"onClick: function(){{$this->getAction()}}, visible:$visible\"" 
				." label='{$this->getLabel()}' tabIndex='-1' intermediateChanges='false'"
				." iconClass='dijitNoIcon' style='font-size:{$this->getFontSize()}em;'></input>\n";
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
	private $cfg;

	function __construct($cfg=NULL){
		global $js_packages;
		$reqPackage=array(
			array("name"=>"ioc", "location"=>$js_packages["ioc"])
		);
        parent::__construct($cfg, $reqPackage);
		$this->cfg = $cfg;
	}

	public function setQuery($query) {
		$this->cfg->setQuery($query);
	}
	public function setAutoSize($autoSize) {
		$this->cfg->setAutoSize($autoSize);
	}
	public function getQuery() {
		return $this->cfg->getQuery();
	}
	public function getAutoSize() {
		return $this->cfg->getAutoSize();
	}

	public function getRenderingCode() {
		$autoSize = $this->getAutoSize() ? 'true' : 'false';
		$display = $this->getDisplay() ? 'true' : 'false';
		$displayBlock = $this->getDisplayBlock() ? "iocDisplayBlock" : "dijitInline";
		
		$ret = "\n<input id='{$this->getId()}' class='$displayBlock' type='button' data-dojo-type='ioc.gui.IocButton'"
				." data-dojo-props=\"query:'{$this->getQuery()}', autoSize:$autoSize, visible:$display\"" 
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
	private $cfg;

	function __construct($cfg=NULL){
		global $js_packages;
		$reqPackage=array(
			array("name"=>"dojo", "location"=>$js_packages["dojo"])
		);
        parent::__construct($cfg, $reqPackage);
		$this->cfg = $cfg;
	}
	
	public function setType($type){
		$this->cfg->setType($type);
	}
	public function getType(){
		return $this->cfg->getType();
	}
	public function getName(){
		return $this->cfg->getName();
	}
	
    public function getRenderingCode() {
        $ret = "<label for='{$this->getId()}'>{$this->getLabel()}</label>"
			  ."<input data-dojo-type='dijit.form.TextBox' id='{$this->getId()}' name='{$this->getName()}' {$this->getType()}/><br />";
		return $ret;
    }
}

class WikiDojoToolBar extends WikiIocItemsContainer{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		És un contenidor que construeix una barra de botons.
	 *		Permet establir la seva posició i tamany.
	 */
	private $cfg;

	function __construct($cfg=NULL){
		global $js_packages;
		$reqPackage=array(
			array("name"=>"dojo", "location"=>$js_packages["dojo"]),
			array("name"=>"dijit","location"=>$js_packages["dijit"])
		);
        parent::__construct($cfg, $reqPackage);
		$this->cfg = $cfg;
	}
	
	public function setPosition($position) {
		$this->cfg->setPosition($position);
	}
	public function setTop($top) {
		$this->cfg->setTop($top);
	}
	public function setLeft($left) {
		$this->cfg->setLeft($left);
	}
	public function setTopLeft($top, $left) {
		$this->cfg->setTopLeft($top, $left);
	}
	public function setZindex($zindex) {
		$this->cfg->setZindex($zindex);
	}
	public function getPosition() {
		return $this->cfg->getPosition();
	}
	public function getTop() {
		return $this->cfg->getTop();
	}
	public function getLeft() {
		return $this->cfg->getLeft();
	}
	public function getZindex() {
		return $this->cfg->getZindex();
	}
	
    protected function getPreContent(){
		if ($this->getPosition()=="absolute" && ($this->getTop()==NULL || $this->getLeft()==NULL)) {
			$ret = "\n<span missatgeError='nombre incorrecte de paràmetres'>\n";
		}else {
			$ret = "\n<span style='position:{$this->getPosition()}; top:{$this->getTop()}px; left:{$this->getLeft()}px; z-index:{$this->getZindex()};'>";
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
	function __construct($cfg=NULL){
		global $js_packages;
		$reqPackage=array(
			array("name"=>"dojo", "location"=>$js_packages["dojo"]),
			array("name"=>"dijit","location"=>$js_packages["dijit"])
		);
        parent::__construct($cfg, $reqPackage);
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
	function __construct($cfg=NULL){
		global $js_packages;
		$reqPackage=array(
			array("name"=>"dojo", "location"=>$js_packages["dojo"]),
			array("name"=>"dijit","location"=>$js_packages["dijit"])
		);
        parent::__construct($cfg, $reqPackage);
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
	function __construct($cfg=NULL){
		global $js_packages;
		$reqPackage=array(
			array("name"=>"dojo", "location"=>$js_packages["dojo"]),
			array("name"=>"dijit","location"=>$js_packages["dijit"])
		);
        parent::__construct($cfg, $reqPackage);
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
	private $cfg;

	function __construct($cfg=NULL){
		global $js_packages;
		$reqPackage=array(
			array("name"=>"dojo", "location"=>$js_packages["dojo"]),
			array("name"=>"dijit","location"=>$js_packages["dijit"])
		);
        parent::__construct($cfg, $reqPackage);
		$this->cfg = $cfg;
	}

	public function getTitle(){
        return $this->cfg->getTitle();
    }
	
	public function getRenderingCode() {
		$ret = "<div data-dojo-type='dijit.layout.ContentPane' title='{$this->getTitle()}' extractContent='false' preventCache='false' preload='false' refreshOnShow='false' {$this->isSelected()} closable='false' doLayout='false'></div>\n";
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
	private $cfg;

    public function __construct($cfg=NULL){
        global $js_packages;
		$reqPackage=array(
                array("name"=>"ioc", "location"=>$js_packages["ioc"]),
                array("name"=>"dojo", "location"=>$js_packages["dojo"]),
                array("name"=>"dijit", "location"=>$js_packages["dijit"])
        );
        parent::__construct($cfg, $reqPackage);
		$this->cfg = $cfg;
    }
    
    function putTab($id, &$tab){
		return $this->cfg->putTab($id, $tab);
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
    function selectTab($id){
		$this->cfg->selectTab($id);
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
		$useMenu = $this->hasMenuButton() ? "true" : "false";
		$useSlider = $this->hasScrollingButtons() ? "true" : "false";
		
        $ret = "<div id='{$this->getId()}' data-dojo-type='dijit.layout.TabContainer' persist='false'";
        if($this->getTabType()==1 /*SCROLLING*/){
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
    public function __construct($cfg=NULL){
        parent::__construct($cfg);
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
	private $cfg;

    public function __construct($cfg=NULL){
		global $js_packages;
		$reqPackage=array(
			array("name"=>"dojo", "location"=>$js_packages["dojo"]),
			array("name"=>"dijit","location"=>$js_packages["dijit"])
		);
        parent::__construct($cfg, $reqPackage);
		$this->cfg = $cfg;
    }
	
	public function setMessage($msg) {
		$this->cfg->setMessage($msg);
	}
	public function getMessage() {
		return $this->cfg->getMessage();
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
		return $this->getMessage();
	}
}
