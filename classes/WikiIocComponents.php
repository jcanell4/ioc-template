<?php
/**
 * Description of IoctplControlSelector
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */

//check if we are running within the DokuWiki environment
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', tpl_incdir());

require_once(DOKU_TPL_INCDIR . 'classes/WikiIocComponent.php');
require_once(DOKU_TPL_INCDIR . 'conf/js_packages.php');

/**
 * Class WikiIocContainer
 */
abstract class WikiIocContainer extends WikiIocComponent {
    /**
     * @param array() $aParms hash amb paràmetres del contenidor ($label, $id) i altres
     * @param array() $requiredPackages hash amb els packages requerit amb el format:
     *                                  "array("name" => "ioc", "location" => $js_packages["ioc"])
     */
    private $height;
    private $width;
    private $position;
    private $region;
    private $top;
    private $left;
    private $zindex;

    function __construct($aParms = array(), $reqPackage = array()) {

        $aParm = array(
                   "id" => $aParms['id']
                  ,"label" => $aParms['label']
                );
        parent::__construct($aParm, $reqPackage);

        $this->setHeight($aParms['height']);
        $this->setWidth($aParms['width']);
        $this->setPosition($aParms['position']);
        $this->setRegion($aParms['region']);
        $this->setTop($aParms['top']);
        $this->setLeft($aParms['left']);
        $this->setZindex($aParms['zindex']);
    }

    /**
     * Codi a col·locar abans del contingut.
     *
     * @return string
     */
    abstract protected function getPreContent();

    /**
     * Codi a col·locar desprès del contingut.
     *
     * @return string
     */
    abstract protected function getPostContent();

    /**
     * Codi del contingut.
     *
     * @return string
     */
    abstract protected function getContent();

    /**
     * Crea el codi a mostrar afegint el contingut anterior, el contingut i el contingut posterior i el retorna.
     *
     * @return string
     */
    public function getRenderingCode() {
        $ret = $this->getPreContent()
            . $this->getContent()
            . $this->getPostContent();
        return $ret;
    }
    
    public function getHeight() {
        return $this->height;
    }
    public function getWidth() {
        return $this->width;
    }
    public function getPosition() {
        return $this->position;
    }
    public function getRegion() {
        return $this->region;
    }
    public function getTop() {
        return $this->top;
    }
    public function getLeft() {
        return $this->left;
    }
    public function getZindex() {
        return $this->zindex;
    }

    public function setHeight($v) {
        $this->height = $v;
    }
    public function setWidth($v) {
        $this->width = $v;
    }
    public function setPosition($v) {
        $this->position = $v;
    }
    public function setRegion($v) {
        $this->region = $v;
    }
    public function setTop($v) {
        $this->top = $v;
    }
    public function setLeft($v) {
        $this->left = $v;
    }
    public function setZindex($v) {
        $this->zindex = $v;
    }
}

abstract class WikiIocItemsContainer extends WikiIocContainer {
    protected $items = array();

    function __construct( $aIocCfg = array(
                                        "parms" => array()
                                       ,"items" => array()
                                     )
                         ,$reqPackage = array()
                        )
    {
        parent::__construct($aIocCfg['parms'], $reqPackage);

        foreach($aIocCfg['items'] as $aItem) {
            $ioc_class = $aItem['class'];
            $obj = new $ioc_class($aItem['parms']);
            $id = $obj->getId();
            $this->putItem($id, $obj);
            unset($obj);
        }
    }

    public function putItem($id, &$item) {
        if($item->getId() == NULL) {
            $item->setId($id);
        }
        $ret = $this->items[$id];
        $this->items[$id] =& $item;
        return $ret;
    }

    public function getItem($id) {
        return $this->items[$id];
    }

    public function removeItem($id) {
        $ret = $this->items[$id];
        unset($this->items[$id]);
        return $ret;
    }

    public function removeAllItems() {
        unset($this->items);
    }

    public function getContent() {
        $ret = '';
        foreach($this->items as $i) {
            $ret .= $i->getRenderingCode();
        }
        return $ret;
    }
}

class WikiIocBody extends WikiIocItemsContainer {

    function __construct($aIocParms = array("id" => NULL, "label" => NULL, "items" => array())) {
        //$aIocParms recibe $arrIocCfg['parms'] que contiene ['top'] y ['main']
        $aParm = array(
                    "parms" => array(
                                  "id" => $aIocParms['id']
                                 ,"label" => $aIocParms['label']
                               )
                   ,"items" => $aIocParms['items']
                 );
        parent::__construct($aParm, array());
    }

    protected function getPreContent() {
        return "<body id='{$this->getId()}' class='claro'>\n";
    }

    protected function getPostContent() {
        return "</body>\n";
    }
}

/**
 * class WikiIocDivBloc
 *      Contenidor de tipus DIV sense propietats dijit
*/
class WikiIocDivBloc extends WikiIocItemsContainer {
    /**
     * @param type $aIocParms
     */
    function __construct($aIocParms = array("id" => NULL, "label" => NULL, "height" => NULL, "width" => NULL, "items" => array())) {
        $aParm = array(
                    "parms" => array(
                                  "id" => $aIocParms['id']
                                 ,"label" => $aIocParms['label']
                                 ,"height" => $aIocParms['height']
                                 ,"width" => $aIocParms['width']
                               )
                   ,"items" => $aIocParms['items']
                 );
        parent::__construct($aParm, array());
    }

    protected function getPreContent() {
        $id = $this->getId(); $id = $id ? "id='$id'" : "";
        $h = ($this->getHeight()); $h = ($h) ? "height:{$h};" : "";
        $w = ($this->getWidth());  $w = ($w) ? "width:{$w};" : "";
        $style = ($h || $w) ? "style='$h$w'" : "";

        $ret = "<div $id $style>\n";
        return $ret;
    }

    protected function getPostContent() {
        return "</div>\n";
    }
}

class WikiIocImage extends WikiIocComponent {
    /* @author Rafael Claver <rclaver@xtec.cat>
     * Descripció: Dibuixa el logo IOC
    */
    function __construct($aIocParms = array("id" => NULL, "label" => NULL)) {
        $aParm = array("id" => $aIocParms['id'], "label" => $aIocParms['label']);
        parent::__construct($aParm, array());
    }

    function getRenderingCode() {
        $ret = "<span style='top: 2px; left: 0px; width: 240px; height: 50px; position: absolute; z-index: 900;'>\n"
            . "\t<img alt='logo' style='position: absolute; z-index: 900; top: 0px; left: 10px; height: 50px; width: 200px;' src='" . DOKU_TPL . "img/logo.png'></img>\n"
            . "</span>\n";
        return $ret;
    }
}

/**
 * class WikiIocBorderContainer
 *      Contenidor de items del tipus BorderContainer
 */
class WikiIocBorderContainer extends WikiIocItemsContainer {

    function __construct($aIocParms = array("id" => NULL, "label" => NULL, "items" => array())) {
        $aParm = array(
                    "parms" => array(
                                  "id" => $aIocParms['id']
                                 ,"label" => $aIocParms['label']
                               )
                   ,"items" => $aIocParms['items']
                 );
        parent::__construct($aParm, array());
    }

    protected function getPreContent() {
        $id = $this->getId(); $id = $id ? "id='$id'" : "";
        $ret = "<div $id>\n"
             . "<div data-dojo-type='dijit.layout.BorderContainer' design='headline' persist='false' gutters='true' "
             . "style='min-width:1em; min-height:1px; z-index:0; width:100%; height:100%;'>\n";
        return $ret;
    }

    protected function getPostContent() {
        return "</div>\n</div>\n";
    }
}

/**
 * class WikiIocItemsContentPane
 *      Contenidor de items del tipus ContentPane
 */
class WikiIocItemsPanel extends WikiIocItemsContainer {
    // bloc esquerre: conté la #zona de navegació# i la #zona de propietats#
    private $_div;
    private $_class;
    private $_doLayout;
    private $_splitter;
    private $_minSize;
    private $_closable;
    private $_style;
    
    function __construct($aIocParms = array("id" => NULL
                                            ,"label" => NULL
                                            ,"region" => NULL
                                            ,"height" => NULL
                                            ,"width" => NULL
                                            ,"div" => false
                                            ,"class" => NULL
                                            ,"doLayout" => NULL
                                            ,"splitter" => NULL
                                            ,"minSize" => NULL
                                            ,"closable" => NULL
                                            ,"style" => NULL
                                            ,"items" => array())
                                      ) {
        global $js_packages;
        $reqPackage = array(
            array("name" => "dojo", "location" => $js_packages["dojo"]),
            array("name" => "dijit", "location" => $js_packages["dijit"])
        );
        $aParm = array(
                    "parms" => array(
                                  "id" => $aIocParms['id']
                                 ,"label" => $aIocParms['label']
                                 ,"height" => $aIocParms['height']
                                 ,"width" => $aIocParms['width']
                                 ,"region" => $aIocParms['region']
                               )
                   ,"items" => $aIocParms['items']
                 );
        parent::__construct($aParm, $reqPackage);
        
        $this->_div = $aIocParms['div'];
        $this->_class = $aIocParms['class'];
        $this->_doLayout = $aIocParms['doLayout'];
        $this->_splitter = $aIocParms['splitter'];
        $this->_minSize = $aIocParms['minSize'];
        $this->_closable = $aIocParms['closable'];
        $this->_style = $aIocParms['style'];
    }

    protected function getPreContent() {
        $class = ($this->_class) ? "class='{$this->_class}'" : "";
        $doLayout = ($this->_doLayout) ? "doLayout='{$this->_doLayout}'" : "";
        $splitter = ($this->_splitter) ? "splitter='{$this->_splitter}'" : "";
        $minSize = ($this->_minSize) ? "minSize='{$this->_minSize}'" : "";
        $closable = ($this->_closable) ? "closable='{$this->_closable}'" : "";

        $s = ($this->_style) ? "{$this->_style};" : "";
        $h = ($this->getHeight()); $h = ($h) ? "height:{$h};" : "";
        $w = ($this->getWidth());  $w = ($w) ? "width:{$w};" : "";
        $style = ($s || $h || $w) ? "style='$s$h$w'" : "";

        $ret = ($this->_div) ? "<div id='{$this->getId()}'>\n" : "";
        $ret .= "<div $class data-dojo-type='dijit.layout.ContentPane' extractContent='false' preventCache='false' preload='false' refreshOnShow='false' "
              . "$doLayout region='{$this->getRegion()}' $splitter $minSize maxSize='Infinity' $style $closable>\n";
        return $ret;
    }

    protected function getPostContent() {
        $ret = ($this->_div) ? "</div>\n" : "";
        $ret .= "</div>\n";
        return $ret;
    }
}

class WikiIocTabsContainer extends WikiIocItemsContainer {
    /* Descripció:
     *        Crea un contenidor de tipus TabsContainer.
     *        Està dissenyat per contenir itmes com a pestanyes.
     * Mètodes:
     *        putTab: afegeix un nou item al contenidor
     * Propietats:
     *        tabType: 0=sin botones, 1=con botones para el scroll horizontal de pestañas
     *        tabSelected: conté el id de la pestanya seleccionada
     */
    const DEFAULT_TAB_TYPE   = 0;
    const RESIZING_TAB_TYPE  = 1;
    const SCROLLING_TAB_TYPE = 2;
    private $tabSelected;
    private $tabType = DEFAULT_TAB_TYPE;
    private $bMenuButton = FALSE;
    private $bScrollingButtons = FALSE;

    public function __construct($aParms = array( "id" => NULL
                                                ,"label" => NULL
                                                ,"tabType" => DEFAULT_TAB_TYPE
                                                ,"bMenuButton" => FALSE
                                                ,"bScrollingButtons" => FALSE
                                                ,"items" => array())
                               ) 
    {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "ioc", "location" => $js_packages["ioc"])
                        ,array("name" => "dojo", "location" => $js_packages["dojo"])
                        ,array("name" => "dijit", "location" => $js_packages["dijit"])
                      );
        $aParm = array(
                    "parms" => array(
                                  "id" => $aParms['id']
                                 ,"label" => $aParms['label']
                               )
                   ,"items" => $aParms['items']
                 );
        parent::__construct($aParm, $reqPackage);
        //$aParms recibe $arrIocCfg['parms'] que contiene ['id'], ['tabType'], ['bMenuButton'] y ['items']
        $this->setTabType($aParms['tabType']);
        $this->setMenuButton($aParms['bMenuButton']);
        $this->setScrollingButtons($aParms['bScrollingButtons']);
    }

    function putTab($id, &$tab) {
        if(!is_array($this->items)) {
            $this->tabSelected = $id;
            $tab->setSelected(TRUE);
        } else if($tab->isSelected()) {
            $this->selectTab($id);
        }
        $ret = $this->putItem($id, $tab);
        return $ret;
    }

    function selectTab($id) {
        if(array_key_exists($id, $this->items)) {
            if(array_key_exists($this->tabSelected, $this->items)) {
                $this->items[$this->tabSelected]->setSelected(FALSE);
            }
            $this->tabSelected = $id;
            $this->items[$id]->setSelected(TRUE);
        }
    }

    function getTab($id) {
        return $this->getItem($id);
    }
    function getTabType() {
        return $this->tabType;
    }
    function hasMenuButton() {
        return $this->bMenuButton;
    }
    function hasScrollingButtons() {
        return $this->bScrollingButtons;
    }

    function removeTab($id) {
        return $this->removeItem($id);
    }
    function removeAllTabs() {
        return $this->removeAllItems();
    }

    function setTabType( $type) {
        $this->tabType = $type;
    }
    function setMenuButton( $value) {
        $this->bMenuButton = $value;
    }
    function setScrollingButtons( $value) {
        $this->bScrollingButtons = $value;
    }

    protected function getPreContent() {
        $id = $this->getId(); $id = $id ? "id='$id'" : "";
        $useMenu   = $this->hasMenuButton() ? "true" : "false";
        $useSlider = $this->hasScrollingButtons() ? "true" : "false";

        $ret = "<div $id data-dojo-type='dijit.layout.TabContainer' persist='false'";
        if($this->getTabType() == 2) {
            $ret .= " controllerWidget='dijit.layout.ScrollingTabController'";
            $ret .= " useMenu='$useMenu'";
            $ret .= " useSlider='$useSlider'";
        } elseif($this->getTabType() == 1) {
            $ret .= " controllerWidget='ioc.gui.ResizingTabController'";
            $ret .= " useMenu='$useMenu'";
        } else {
            $ret .= " controllerWidget='dijit.layout.TabController'";
        }
        $ret .= ' style="min-width: 1em; min-height: 1em; width: 100%; height: 100%;">';
        return $ret;
    }

    protected function getPostContent() {
        $ret = "</div>\n";
        return $ret;
    }
}

/**
 * class WikiIocContentPane
 *      Contenidor què, per defecte, no conté res.
 *      És un element intermig de la jerarquia de classes per permetre que d'altres
 *      estenguin el "getContent"
 */
class WikiIocContentPane extends WikiIocContainer {

    function __construct($aParms = array("id" => NULL, "label" => ""), $reqPackage = array()) {
        global $js_packages;
        if ($reqPackage == NULL) {
            $reqPackage = array(
                             array("name" => "dojo", "location" => $js_packages["dojo"])
                            ,array("name" => "dijit", "location" => $js_packages["dijit"])
                          );
        }
        parent::__construct($aParms, $reqPackage);
    }

    protected function getPreContent() {
        $id = $this->getId(); $id = $id ? "id='$id'" : "";
        $selected = $this->isSelected() ? " selected='true'" : "";
        
        $ret = "<div $id data-dojo-type='dijit.layout.ContentPane'"
            . " title='{$this->getLabel()}' tooltip='{$this->getToolTip()}'"
            . " extractContent='false' preventCache='false'"
            . " preload='false' refreshOnShow='false'"
            . " $selected closable='false' doLayout='false'>\n";
        return $ret;
    }

    protected function getPostContent() {
        return "</div>\n";
    }

    protected function getContent() {
        return "";
    }

}

class WikiIocContainerFromMenuPage extends WikiIocContentPane {
    private $page;

    function __construct($aParms = array("id" => NULL, "label" => "", "page" => NULL)) {
        $aParm = array(
                    "id" => $aParms['id']
                   ,"label" => $aParms['label']
                 );
        parent::__construct($aParm, array());
        $this->setPageName($aParms['page']);
    }

    function setPageName($value) {
        $this->page = $value;
    }

    function getPageName() {
        return $this->page;
    }

    protected function getContent() {
        $ret = "";
        if($this->page != NULL) {
            $ret .= "<div class='tb_container'>\n" . tpl_include_page($this->getPageName(), FALSE) . "\n</div>\n";
        }
        return $ret;
    }
}

class WikiIocContainerFromPage extends WikiIocContentPane {
    private $page;

    function __construct($aParms = array("id" => "", "label" => "", "page" => "")) {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "ioc", "location" => $js_packages["ioc"])
                      );
        $aParm = array(
                    "id" => $aParms['id']
                   ,"label" => $aParms['label']
                 );
        parent::__construct($aParm, $reqPackage);
        
        $this->setPageName($aParms['page']);
    }

    function setPageName($value) {
        $this->page = $value;
    }

    function getPageName() {
        return $this->page;
    }

    protected function getPreContent() {
        $id = $this->getId(); $id = $id ? "id='$id'" : "";
        $selected = $this->isSelected() ? " selected='true'" : "";

        $ret = "<div $id data-dojo-type='ioc.gui.ContentTabDokuwikiPage'"
            . " title='{$this->getLabel()}' tooltip='{$this->getToolTip()}'"
            . " extractContent='false' preventCache='false' preload='false' refreshOnShow='false'"
            . " $selected closable='false' doLayout='false'>\n";
        return $ret;
    }

    protected function getContent() {
        $ret = "";
        if($this->getPageName() != NULL) {
            $ret .= "<div class=\"tb_container\">\n" . tpl_include_page($this->getPageName(), FALSE) . "\n</div>\n";
        }
        return $ret;
    }
}

class WikiIocTreeContainer extends WikiIocContentPane {
    private $treeDataSource;
    private $rootValue;
    private $pageDataSource;

    function __construct($aParms = array("id" => "", "label" => "", "treeDataSource" => "")) {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "ioc", "location" => $js_packages["ioc"])
                      );
        $aParm = array(
                    "id" => $aParms['id']
                   ,"label" => $aParms['label']
                 );
        parent::__construct($aParm, $reqPackage);
        
        $this->setTreeDataSource($aParms['treeDataSource']);
    }

    function setRootValue($value) {
        $this->rootValue = $value;
    }
    function setTreeDataSource($value) {
        $this->treeDataSource = $value;
    }
    function setPageDataSource($value) {
        $this->pageDataSource = $value;
    }

    function getRootValue() {
        return $this->rootValue;
    }
    function getTreeDataSource() {
        return $this->treeDataSource;
    }
    function getPageDataSource() {
        return $this->pageDataSource;
    }

    protected function getPreContent() {
        $id = $this->getId(); $id = $id ? "id='$id'" : "";
        $treeDataSource = $this->getTreeDataSource() ? "treeDataSource:\"{$this->getTreeDataSource()}\"" : "";
        $coma = $treeDataSource ? "," : "";
        $rootValue = $this->getRootValue() ? "$coma rootValue:\"{$this->getRootValue()}\"" : "";
        $coma = $treeDataSource || $rootValue ? "," : "";
        $pageDataSource = $this->getPageDataSource() ? "$coma urlBase:\"{$this->getPageDataSource()}\"" : "";
        $selected = $this->isSelected() ? "selected='true'" : "";
        
        $ret = "<div $id data-dojo-type='ioc.gui.ContentTabDokuwikiNsTree'"
            . " title='{$this->getLabel()}' tooltip='{$this->getToolTip()}'"
            . " extractContent='false' preventCache='false' preload='false' refreshOnShow='false'"
            . " data-dojo-props='{$treeDataSource}{$rootValue}{$pageDataSource}'"
            . " $selected style='overflow:auto;' closable='false' doLayout='false'>\n";
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
     */
    public function __construct($aParms = array("id" => NULL, "label" => NULL, "items" => array())) {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "ioc", "location" => $js_packages["ioc"])
                        ,array("name" => "dojo", "location" => $js_packages["dojo"])
                        ,array("name" => "dijit", "location" => $js_packages["dijit"])
                      );
        $aParm = array(
                    "parms" => array(
                                  "id" => $aParms['id']
                                 ,"label" => $aParms['label']
                               )
                   ,"items" => $aParms['items']
                 );
        parent::__construct($aParm, $reqPackage);
    }

    protected function getPreContent() {
        $id = $this->getId(); $id = $id ? "id='$id'" : "";
        $ret = "\n<div $id data-dojo-type='ioc.gui.ActionHiddenDialogDokuwiki'>";
        return $ret;
    }

    protected function getPostContent() {
        return "</div>\n";
    }
}

class WikiDojoFormContainer extends WikiIocItemsContainer {
    /* @author Rafael Claver <rclaver@xtec.cat>
     * Descripció:
     *        Crea un contenidor de la classe ioc.gui.IocForm
     *        dissenyat per contenir items de formulari.
     */
    private $action;
    private $urlBase;
    private $display = TRUE;

    public function __construct($aParms = array("id"=>NULL, "label"=>NULL, "action"=>NULL, "position"=>"", "top"=>0, "left"=>0, "display"=>TRUE, "zindex"=>NULL, "items" => array())) {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "ioc", "location" => $js_packages["ioc"])
                        ,array("name" => "dojo", "location" => $js_packages["dojo"])
                        ,array("name" => "dijit", "location" => $js_packages["dijit"])
                      );
        $aParm = array(
                    "parms" => array(
                                  "id" => $aParms['id']
                                 ,"label" => $aParms['label']
                                 ,"position" => $aParms['position']
                                 ,"top" => $aParms['top']
                                 ,"left" => $aParms['left']
                                 ,"zindex" => $aParms['zindex']
                               )
                   ,"items" => array($aParms['items'])
                 );
        parent::__construct($aParm, $reqPackage);
        
        $this->setAction($aParms['action']);
        $this->setDisplay($aParms['display']);
    }

    public function setAction($action) {
        $this->action = $action;
    }
    public function setDisplay($display) {
        $this->display = $display;
    }
    public function setUrlBase($url) {
        $this->urlBase = $url;
    }
    public function getAction() {
        return $this->action;
    }
    public function getDisplay() {
        return $this->display;
    }
    public function getUrlBase() {
        return $this->urlBase;
    }

    protected function getPreContent() {
        $id = $this->getId(); $id = $id ? "id='$id'" : "";
        $id_form = $id ? "id='{$id}_form'" : "";
        $action = $this->getAction() ? "" : "<script>alert('No s\'ha definit l\'element action al formulari [{$this->getLabel()}].');</script>\n";
        $visible = $this->getDisplay() ? 'true' : 'false';
        
        $ret = "<span $id title='{$this->getLabel()}' tooltip='{$this->getToolTip()}'"
            . " style='position:{$this->getPosition()}; top:{$this->getTop()}; left:{$this->getLeft()}; z-index:{$this->getZindex()};'>\n"
            . " $action <span $id_form data-dojo-type='ioc.gui.IocForm'"
            . " data-dojo-props=\"action:'{$this->getAction()}', urlBase:'{$this->getUrlBase()}', visible:$visible\">\n";
        return $ret;
    }

    protected function getPostContent() {
        $ret = "</span>\n</span>\n";
        return $ret;
    }
}

class WikiIocDropDownButton extends WikiIocContainer {
    /* @author Rafael Claver <rclaver@xtec.cat>
     * Descripció:
     *        Dibuixa un botó de la classe ioc.gui.IocDropDownButton
     * Propietats:
     *        Accepta n paràmetres que configuren l'aspecte del botó:
     *        - autoSize: true/false
     *                true: indica que el seu tamany depen del tamany del contenidor pare
     *                false: el seu tamany és el tamany estàndar d'un boto, és a dir, té el tamany que ocupa el texte del botó
     *        - display: true/false
     *                true: indica que és visible.
     *                false: indica que no és visible.
     *        - displayBlock: true/false
     *                true: utilitzarà la classe CSS .iocDisplayBlock {display:block}.
     *                false: utilitzarà la classe CSS dijitInline.
     */
    private $autoSize;
    private $display;
    private $displayBlock;
    private $actionHidden;

    public function __construct($aParms = array("id" => NULL, "label" => "", "autoSize" => FALSE, "display" => TRUE, "displayBlock" => TRUE, "actionHidden" => NULL)) {
        global $js_packages;
        $reqPackage = array(
            array("name" => "ioc", "location" => $js_packages["ioc"]),
            array("name" => "dojo", "location" => $js_packages["dojo"]),
            array("name" => "dijit", "location" => $js_packages["dijit"])
        );
        $aParm = array(
             "id" => $aParms['id']
            ,"label" => $aParms['label']
        );
        parent::__construct($aParm, $reqPackage);
        
        $this->setAutoSize($aParms['autoSize']);
        $this->setDisplay($aParms['display']);
        $this->setDisplayBlock($aParms['displayBlock']);
        if ($aParms['actionHidden']) {
            $iocClass = $aParms['actionHidden']['class'];
            $this->setActionHidden(new $iocClass($aParms['actionHidden']['parms']));
        }
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

    public function getAutoSize() {
        return $this->autoSize;
    }
    public function getDisplay() {
        return $this->display;
    }
    public function getDisplayBlock() {
        return $this->displayBlock;
    }
    public function getActionHidden() {
        return $this->actionHidden;
    }

    protected function getPreContent() {
        $id = $this->getId(); $id = $id ? "id='$id'" : "";
        $autoSize     = $this->getAutoSize() ? 'true' : 'false';
        $display      = $this->getDisplay() ? 'true' : 'false';
        $displayBlock = $this->getDisplayBlock() ? "iocDisplayBlock" : "dijitInline";

        $ret = "\n<div $id data-dojo-type='ioc.gui.IocDropDownButton' class='$displayBlock' style='font-size:0.75em'"
            . " data-dojo-props=\"autoSize:$autoSize, visible:$display\">"
            . "\n<span>{$this->getLabel()}</span>";
        return $ret;
    }

    protected function getPostContent() {
        return "\n</div>\n";
    }

    protected function getContent() {
        $_ActionHidden = $this->getActionHidden();
        $ret = $_ActionHidden ? $_ActionHidden->getRenderingCode() : "";
        return $ret;
    }
}

class WikiDojoButton extends WikiIocComponent {
    /* @author Rafael Claver <rclaver@xtec.cat>
     * Descripció:
     *        Dibuixa un botó de la classe dijit.form.Button
     * Propietats:
     *        Accepta paràmetres que configuren l'aspecte del botó:
     *        - display: true/false
     *                true: indica que és visible.
     *                false: indica que no és visible.
     *        - displayBlock: true/false
     *                true: utilitzarà la classe CSS .iocDisplayBlock {display:block}.
     *                false: utilitzarà la classe CSS dijitInline.
     *
     *        Accepta paràmetres que configuren l'acció del botó:
     *        - action
     */
    private $action;
    private $display;
    private $displayBlock;
    private $fontSize = 1;
    private $type = "button";

    function __construct($aParms = array("id"=> NULL, "label"=> "", "action"=> NULL, "display"=> false, "displayBlock"=> false), $reqPackage = array()) {
        global $js_packages;
        if ($reqPackage == NULL) {
            $reqPackage = array(
                array("name" => "dojo", "location" => $js_packages["dojo"]),
                array("name" => "dijit", "location" => $js_packages["dijit"])
            );
        }
        $aParm = array(
                   "id" => $aParms['id']
                  ,"label" => $aParms['label']
                );
        parent::__construct($aParm, $reqPackage);
        
        $this->setAction($aParms['action']);
        $this->setDisplay($aParms['display']);
        $this->setDisplayBlock($aParms['displayBlock']);
        //$this->setType($aParms['type']);
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

    public function getAction() {
        return $this->action;
    }
    public function getDisplay() {
        return $this->display;
    }
    public function getDisplayBlock() {
        return $this->displayBlock;
    }
    public function getFontSize() {
        return $this->fontSize;
    }
    public function getType() {
        return $this->type;
    }

    public function getRenderingCode() {
        $id = $this->getId(); $id = $id ? "id='$id'" : "";
        $visible      = $this->getDisplay() ? 'true' : 'false';
        $displayBlock = $this->getDisplayBlock() ? "iocDisplayBlock" : "dijitInline";

        $ret = "<input $id class='$displayBlock' type='{$this->getType()}' data-dojo-type='dijit.form.Button'"
            . " data-dojo-props=\"onClick: function(){{$this->getAction()}}, visible:$visible\""
            . " label='{$this->getLabel()}' tabIndex='-1' intermediateChanges='false'"
            . " iconClass='dijitNoIcon' style='font-size:{$this->getFontSize()}em;'></input>\n";
        return $ret;
    }
}

class WikiIocButton extends WikiDojoButton {
    /* @author Rafael Claver <rclaver@xtec.cat>
     * Descripció:
     *        Dibuixa un botó de la classe ioc.gui.IocButton
     * Propietats:
     *        Accepta paràmetres que configuren l'aspecte del botó:
     *        - autoSize: true/false
     *                true: indica que el seu tamany depen del tamany del contenidor pare
     *                false: el seu tamany és el tamany estàndar d'un boto, és a dir, té el tamany que ocupa el texte del botó
     *        - display: true/false
     *                true: indica que és visible.
     *                false: indica que no és visible.
     *        - displayBlock: true/false
     *                true: utilitzarà la classe CSS .iocDisplayBlock {display:block}.
     *                false: utilitzarà la classe CSS dijitInline.
     *
     *        Accepta paràmetres que configuren l'acció del botó:
     *        - query
     */
    private $query;
    private $autoSize;

    function __construct($aParms = array("id"=> NULL, "label"=> "", "query"=> NULL, "autoSize"=> FALSE, "display"=> TRUE, "displayBlock"=> TRUE)) {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "dojo", "location" => $js_packages["dojo"])
                        ,array("name" => "dijit", "location" => $js_packages["dijit"])
                        ,array("name" => "ioc", "location" => $js_packages["ioc"])
                      );
        $aParm = array(
                    "id" => $aParms['id']
                   ,"label" => $aParms['label']
                   ,"action" => $aParms['query']
                   ,"display" => $aParms['display']
                   ,"displayBlock" => $aParms['displayBlock']
                   ,"type" => "button"
                 );
        parent::__construct($aParm, $reqPackage);
        
        $this->setQuery($aParms['query']);
        $this->setAutoSize($aParms['autoSize']);
    }

    public function setQuery($query) {
        $this->query = $query;
    }
    public function setAutoSize($autoSize) {
        $this->autoSize = $autoSize;
    }

    public function getQuery() {
        return $this->query;
    }
    public function getAutoSize() {
        return $this->autoSize;
    }

    public function getRenderingCode() {
        $id = $this->getId(); $id = $id ? "id='$id'" : "";
        $autoSize     = $this->getAutoSize() ? 'true' : 'false';
        $display      = $this->getDisplay() ? 'true' : 'false';
        $displayBlock = $this->getDisplayBlock() ? "iocDisplayBlock" : "dijitInline";

        $ret = "\n<input $id class='$displayBlock' type='button' data-dojo-type='ioc.gui.IocButton'"
            . " data-dojo-props=\"query:'{$this->getQuery()}', autoSize:$autoSize, visible:$display\""
            . " label='{$this->getLabel()}' tabIndex='-1' intermediateChanges='false'"
            . " iconClass='dijitNoIcon' style='font-size:0.75em;'></input>\n";
        return $ret;
    }
}

class WikiIocFormInputField extends WikiIocComponent {
    /* @author Rafael Claver <rclaver@xtec.cat>
     * Descripció:
     *        Dibuixa un item que pot ser albergat en un contenidor
     *        Aquest item és un input textbox de la classe dijit.form.TextBox
     */
    private $name;
    private $type;

    function __construct($aParms = array("id" => NULL, "label" => "", "name" => NULL, "type" => NULL)) {
        global $js_packages;
        $reqPackage = array(
            array("name" => "dojo", "location" => $js_packages["dojo"])
        );
        $aParm = array(
                   "id" => $aParms['id']
                  ,"label" => $aParms['label']
                );
        parent::__construct($aParm, $reqPackage);
        
        $this->setName($aParms['name']);
        $this->setType($aParms['type']);
    }

    public function setName($name) {
        $this->name = ($name == NULL) ? $this->getId() : $name;
    }
    public function setType($type) {
        $this->type = ($type == NULL) ? "" : "type='$type' ";
    }

    public function getName() {
        return $this->name;
    }
    public function getType() {
        return $this->type;
    }

    public function getRenderingCode() {
        $id = $this->getId();
        $ret = "<label for='$id'>{$this->getLabel()}</label>"
             . "<input data-dojo-type='dijit.form.TextBox' id='$id' name='{$this->getName()}' {$this->getType()}/><br />";
        return $ret;
    }
}

class WikiDojoToolBar extends WikiIocItemsContainer {
    /* @author Rafael Claver <rclaver@xtec.cat>
     * Descripció:
     *        És un contenidor que construeix una barra de botons.
     *        Permet establir la seva posició i tamany.
     */

    function __construct($aParms = array("id" => NULL, "label" => NULL, "position" => "static", "top" => 0, "left" => 0, "zindex" => 900, "items" => array())) {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "dojo", "location" => $js_packages["dojo"])
                        ,array("name" => "dijit", "location" => $js_packages["dijit"])
                        ,array("name" => "ioc", "location" => $js_packages["ioc"])
                      );
        $aParm = array(
                    "parms" => array(
                                  "id" => $aParms['id']
                                 ,"label" => $aParms['label']
                                 ,"position" => $aParms['position']
                                 ,"top" => $aParms['top']
                                 ,"left" => $aParms['left']
                                 ,"zindex" => $aParms['zindex']
                               )
                   ,"items" => $aParms['items']
                 );
        parent::__construct($aParm, $reqPackage);
    }

    protected function getPreContent() {
        if($this->getPosition() == "absolute" && ($this->getTop() == NULL || $this->getLeft() == NULL)) {
            $ret = "\n<span missatgeError='nombre incorrecte de paràmetres'>\n";
        } else {
            $ret = "\n<span style='position:{$this->getPosition()}; top:{$this->getTop()}; left:{$this->getLeft()}; z-index:{$this->getZindex()};'>";
        }
        $ret .= "\n<span data-dojo-type='dijit.Toolbar'>\n";
        return $ret;
    }

    protected function getPostContent() {
        return "</span>\n</span>\n";
    }
}

/**
 * class WikiIocAccordionContainer
 */
class WikiIocAccordionContainer extends WikiIocItemsContainer {
    /* @author Rafael Claver <rclaver@xtec.cat>
     * Descripció:
     *        Crea un contenidor per allotjar els items de la #zona de propietats# (part esquerre).
     *        Està dissenyat per contenir els div de propietats.
     * Mètodes:
     *        putItem: afegeix un nou item al contenidor
     */
    function __construct($aParms = array("id" => NULL, "label" => NULL, "items" => array())) {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "dojo", "location" => $js_packages["dojo"])
                        ,array("name" => "dijit", "location" => $js_packages["dijit"])
                      );
        $aParm = array(
                    "parms" => array(
                                  "id" => $aParms['id']
                                 ,"label" => $aParms['label']
                               )
                   ,"items" => $aParms['items']
                 );
        parent::__construct($aParm, $reqPackage);
    }

    protected function getPreContent() {
        $id = $this->getId(); $id = $id ? "id='$id'" : "";
        $ret = "<span $id data-dojo-type='dijit.layout.AccordionContainer' data-dojo-props='id:\"{$this->getId()}\"' duration='200' persist='false' style='min-width: 1em; min-height: 1em; width: 100%; height: 100%;'>\n";
        return $ret;
    }

    protected function getPostContent() {
        return "</span>";
    }
}

class WikiIocProperty extends WikiIocComponent {
    /* @author Rafael Claver <rclaver@xtec.cat>
     * Descripció:
     *        Crea un contenidor per a la zona de propietats
     */
    private $title = "";
    
    function __construct($aParms = array("id" => NULL, "label" => "", "title" => NULL, "selected" => FALSE)) {
        global $js_packages;
        $reqPackage = array(
             array("name" => "dojo", "location" => $js_packages["dojo"])
            ,array("name" => "dijit", "location" => $js_packages["dijit"])
        );
        $aParm = array(
                   "id" => $aParms['id']
                  ,"label" => $aParms['label']
                );
        parent::__construct($aParm, $reqPackage);
        
        $this->setTitle($aParms['title']);
        $this->setSelected($aParms['selected']);
    }

    public function setTitle($title) {
        $this->title = ($title == NULL) ? "" : $title;
    }
    public function getTitle() {
        return $this->title;
    }

    public function getRenderingCode() {
        $selected = ($this->isSelected()) ? "selected='true'" : "";
        $ret = "<div data-dojo-type='dijit.layout.ContentPane' title='{$this->getTitle()}' extractContent='false'"
             . " preventCache='false' preload='false' refreshOnShow='false' $selected closable='false' doLayout='false'></div>\n";
        return $ret;
    }
}

/**
 * class WikiIocTextContentPane
 *      Contenidor de tipus ContentPane per a text sense format
 */
class WikiIocTextContentPane extends WikiIocContainer {

    private $missatge = "àrea de missatges";

    public function __construct($aParms = array("id" => NULL)) {
        global $js_packages;
        $reqPackage = array(
             array("name" => "dojo", "location" => $js_packages["dojo"])
            ,array("name" => "dijit", "location" => $js_packages["dijit"])
        );
        $aParm = array(
             "id" => $aParms['id']
            ,"label" => $aParms['id']
        );
        parent::__construct($aParm, $reqPackage);
    }

    public function setMessage($msg) {
        $this->missatge = $msg;
    }

    public function getMessage() {
        return $this->missatge;
    }

    protected function getPreContent() {
        $id = $this->getId(); $id = $id ? "id='$id'" : "";
        $ret = "<div $id data-dojo-type='dijit.layout.ContentPane' extractContent='false' preventCache='false'"
              ." preload='false' refreshOnShow='false' closable='false' doLayout='false'>";
        return $ret;
    }

    protected function getContent() {
        return $this->getMessage();
    }

    protected function getPostContent() {
        return "</div>\n";
    }
}
