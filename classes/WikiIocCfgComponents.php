<?php
/**
*/
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', DOKU_TPLINC.'classes/');
if (!defined('DOKU_TPL_CONF')) define('DOKU_TPL_CONF', DOKU_TPLINC.'conf/');

require_once(DOKU_TPL_CLASSES.'WikiIocCfgComponent.php');
require_once(DOKU_TPL_CONF.'js_packages.php');

abstract class WikiIocCfgItemsContainer extends WikiIocCfgComponent {
	protected $items;
	
    function __construct($label="", $id=NULL, $reqPackage=NULL){
        parent::__construct($label, $id, $reqPackage);
    }

	public function putItem($id, &$item){
        if($item->getId()==NULL){
            $item->setId($id);
        }
        $ret = $this->items[$id];
        $this->items[$id]=&$item;
        return $ret;
    }
    
    public function getItem($id){
        return $this->items[$id];
    }
    
    public function removeItem($id){
        $ret = $this->items[$id];
        unset($this->items[$id]);
        return $ret;
    }
    
    public function removeAllItems(){
        unset($this->items);
    }
}

class WikiIocCfgTabsContainer extends WikiIocCfgItemsContainer{
    const DEFAULT_TAB_TYPE=0;
    const RESIZING_TAB_TYPE=1;
    const SCROLLING_TAB_TYPE=2;
    private $tabSelected;
    private $tabType; //0= normal, 1=resizing, 2=scrolling
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
		if ($id == NULL) $id = $label;
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
    
    function selectTab($id){
        if(array_key_exists($id, $this->items)){
            if(array_key_exists($this->tabSelected, $this->items)){
                $this->items[$this->tabSelected]->setSelected(FALSE);
            }
            $this->tabSelected=$id;
            $this->items[$id]->setSelected(TRUE);
        }
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
}

class WikiIocCfgContentPane extends WikiIocCfgComponent{
	
    function __construct($label="", $id=NULL, $reqPackage=NULL){
        global $js_packages;
        if($reqPackage==NULL){
            $reqPackage=array(
                array("name"=>"dojo", "location"=>$js_packages["dojo"]),
                array("name"=>"dijit", "location"=>$js_packages["dijit"])
            );
        }
        parent::__construct($label, $id, $reqPackage);
    }
}

class WikiIocCfgContainerFromMenuPage extends WikiIocCfgContentPane{
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
}

class WikiIocCfgContainerFromPage extends WikiIocCfgContentPane{
   private $page;
   
	function __construct($label="", $page=NULL, $id=NULL){
        global $js_packages;
        $reqPackage=array(
            array("name"=>"ioc", "location"=>$js_packages["ioc"])
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
}

class WikiIocCfgTreeContainer extends WikiIocCfgContentPane{
   private $treeDataSource;
   private $rootValue;
   private $pageDataSource;
   
   function __construct($label="", $treeDataSource=NULL, $pageDataSource=NULL, $rootValue="", $id=NULL){
		global $js_packages;
		$reqPackage=array(
				array("name"=>"ioc", "location"=>$js_packages["ioc"])
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
}

class WikiIocCfgHiddenDialog extends WikiIocCfgItemsContainer {
	/* Descripció:
	 *		Defineix un contenidor de la classe ioc.gui.ActionHiddenDialogDokuwiki
	 *		que no serà visible en el moment de la seva creació.
	 *		Aquest contenidor està dissenyat per contenir items.
	 *		Els métodes de construcció els hereda de WikiIocCfgItemsContainer.
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
}

class WikiDojoCfgFormContainer extends WikiIocCfgItemsContainer {
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Defineix un contenidor de la classe ioc.gui.IocForm
	 *		dissenyat per contenir items de formulari.
	 *		Els métodes de construcció els hereda de WikiIocCfgItemsContainer.
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
}

class WikiIocCfgDropDownButton extends WikiIocCfgComponent{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Defineix un botó de la classe ioc.gui.IocDropDownButton
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
}	

class WikiDojoCfgButton extends WikiIocCfgComponent{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Defineix un botó de la classe dijit.form.Button
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
}	

class WikiIocCfgButton extends WikiDojoCfgButton{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Defineix un botó de la classe ioc.gui.IocButton
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
}	

class WikiIocCfgFormInputField extends WikiIocCfgComponent{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Defineix un item que pot ser albergat en un contenidor
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
}

class WikiDojoCfgToolBar extends WikiIocCfgItemsContainer{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Defineix un contenidor que construeix una barra de botons.
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
}	

class WikiIocCfgLeftContainer extends WikiIocCfgItemsContainer{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Defineix un contenidor per allotjar els items de la part esquerra.
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
}

class WikiIocCfgRightContainer extends WikiIocCfgItemsContainer{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Defineix un contenidor per allotjar els items de la #zona de canvi de mode# (part dreta).
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
}

class WikiIocCfgMetaInfoContainer extends WikiIocCfgItemsContainer{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Defineix un contenidor per allotjar els items de la #zona de propietats# (part esquerre).
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
}

class WikiIocCfgProperty extends WikiIocCfgComponent{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Defineix un contenidor per a la zona de propietats
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
}

class WikiIocCfgCentralTabsContainer extends WikiIocCfgItemsContainer{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Defineix un contenidor per allotjar els items centrals.
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
}

class WikiIocCfgHeadContainer extends WikiIocCfgItemsContainer{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Defineix un contenidor per allotjar els items de la capçalera.
	 * Mètodes:
	 *		putItem: afegeix un nou item al contenidor
	 */
    public function __construct($id=NULL, $label="", $reqPackage=NULL){
        parent::__construct($label, $id, $reqPackage);
    }
}

class WikiIocCfgBottomContainer extends WikiIocCfgComponent{
	/* @author Rafael Claver <rclaver@xtec.cat>
	 * Descripció:
	 *		Defineix un contenidor per escriure missatges d'informació per l'usuari.
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
}
?>
