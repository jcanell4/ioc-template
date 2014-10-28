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
    function __construct($aParms = array(), $reqPackage = array()) {
        parent::__construct($aParms, $reqPackage);
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
        return $this->get('height');
    }
    public function getWidth() {
        return $this->get('width');
    }
    public function getPosition() {
        return $this->get('position');
    }
    public function getRegion() {
        return $this->get('region');
    }
    public function getTop() {
        return $this->get('top');
    }
    public function getLeft() {
        return $this->get('left');
    }
    public function getZindex() {
        return $this->get('zindex');
    }

    public function setHeight($v) {
        $this->set('height', $v);
    }
    public function setWidth($v) {
        $this->set('width', $v);
    }
    public function setPosition($v) {
        $this->set('position', $v);
    }
    public function setRegion($v) {
        $this->set('region', $v);
    }
    public function setTop($v) {
        $this->set('top', $v);
    }
    public function setLeft($v) {
        $this->set(left, $v);
    }
    public function setZindex($v) {
        $this->set('zindex', $v);
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
        return "<body id='{$this->get('id')}' class='claro'>\n";
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
        $id = $this->get('id'); $id = $id ? "id='$id'" : "";
        $h = ($this->get('height')); $h = ($h) ? "height:{$h};" : "";
        $w = ($this->get('width'));  $w = ($w) ? "width:{$w};" : "";
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
    function __construct($aParms = array()) {
        parent::__construct($aParms, array());
    }

    function getRenderingCode() {
        $ret = "<span style='position:{$this->get('span-position')}; top:{$this->get('span-top')}; left:{$this->get('span-left')}; width:{$this->get('width')}; height:{$this->get('height')}; z-index:{$this->get('z-index')};'>\n"
            . "<img alt='logo' style='width:{$this->get('width')}; height:{$this->get('height')}; z-index:{$this->get('z-index')}' src='".DOKU_TPL."{$this->get('src')}'></img>\n"
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
        $id = $this->get('id'); $id = $id ? "id='$id'" : "";
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
 * class WikiIocItemsPanel
 *      Contenidor de tipus ContentPane. Pot contenir items de qualsevol tipus
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
                                 ,"div" => $aIocParms['div']
                                 ,"class" => $aIocParms['class']
                                 ,"doLayout" => $aIocParms['doLayout']
                                 ,"splitter" => $aIocParms['splitter']
                                 ,"minSize" => $aIocParms['minSize']
                                 ,"closable" => $aIocParms['closable']
                                 ,"style" => $aIocParms['style']
                               )
                   ,"items" => $aIocParms['items']
                 );
        parent::__construct($aParm, $reqPackage);
        
        $this->_div = $this->get('div');
        $this->_class = $this->get('class');
        $this->_doLayout = $this->get('doLayout');
        $this->_splitter = $this->get('splitter');
        $this->_minSize = $this->get('minSize');
        $this->_closable = $this->get('closable');
        $this->_style = $this->get('style');
    }

    protected function getPreContent() {
        $class = ($this->_class) ? "class='{$this->_class}'" : "";
        $doLayout = ($this->_doLayout) ? "doLayout='{$this->_doLayout}'" : "";
        $splitter = ($this->_splitter) ? "splitter='{$this->_splitter}'" : "";
        $minSize = ($this->_minSize) ? "minSize='{$this->_minSize}'" : "";
        $closable = ($this->_closable) ? "closable='{$this->_closable}'" : "";

        $s = ($this->_style) ? "{$this->_style};" : "";
        $h = ($this->get('height')); $h = ($h) ? "height:{$h};" : "";
        $w = ($this->get('width'));  $w = ($w) ? "width:{$w};" : "";
        $style = ($s || $h || $w) ? "style='$s$h$w'" : "";

        $ret = ($this->_div) ? "<div id='{$this->get('id')}'>\n" : "";
        $ret .= "<div $class data-dojo-type='dijit.layout.ContentPane' extractContent='false' preventCache='false' preload='false' refreshOnShow='false' "
              . "$doLayout region='{$this->get('region')}' $splitter $minSize maxSize='Infinity' $style $closable>\n";
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
//    private $tabSelected;
//    private $tabType = DEFAULT_TAB_TYPE;
//    private $bMenuButton = FALSE;
//    private $bScrollingButtons = FALSE;

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
                                 ,"tabType" => $aParms['tabType']
                                 ,"bMenuButton" => $aParms['bMenuButton']
                                 ,"bScrollingButtons" => $aParms['bScrollingButtons']
                               )
                   ,"items" => $aParms['items']
                 );
        parent::__construct($aParm, $reqPackage);

//        $this->setTabType($aParms['tabType']);
//        $this->setMenuButton($aParms['bMenuButton']);
//        $this->setScrollingButtons($aParms['bScrollingButtons']);
    }

    function putItem($id, &$tab) {
        if(!is_array($this->items)) {
            $this->set('tabSelected', $id);
            $tab->setSelected(TRUE);
        } else if($tab->isSelected()) {
            $this->selectTab($id);
        }
        $ret = parent::putItem($id, $tab);
        return $ret;
    }

    function selectTab($id) {
        if(array_key_exists($id, $this->items)) {
            if(array_key_exists($this->get('tabSelected'), $this->items)) {
                $this->items[$this->get('tabSelected')]->setSelected(FALSE);
            }
            $this->set('tabSelected', $id);
            $this->items[$id]->setSelected(TRUE);
        }
    }

    function getTab($id) {
        return $this->getItem($id);
    }
    function getTabType() {
        return $this->get('tabType');
    }
    function hasMenuButton() {
        return $this->get('bMenuButton');
    }
    function hasScrollingButtons() {
        return $this->get('bScrollingButtons');
    }

    function removeTab($id) {
        return $this->removeItem($id);
    }
    function removeAllTabs() {
        return $this->removeAllItems();
    }

    function setTabType($type) {
        $this->set('tabType', $type);
    }
    function setMenuButton($value) {
        $this->set('bMenuButton', $value);
    }
    function setScrollingButtons($value) {
        $this->set('bScrollingButtons', $value);
    }

    protected function getPreContent() {
        $id = $this->get('id'); $id = $id ? "id='$id'" : "";
        $useMenu   = $this->get('bMenuButton') ? "true" : "false";
        $useSlider = $this->get('bScrollingButtons') ? "true" : "false";

        $ret = "<div $id data-dojo-type='dijit.layout.TabContainer' persist='false'";
        if($this->get('tabType') == 2) {
            $ret .= " controllerWidget='dijit.layout.ScrollingTabController'";
            $ret .= " useMenu='$useMenu'";
            $ret .= " useSlider='$useSlider'";
        } elseif($this->get('tabType') == 1) {
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

    function __construct($aParms = array(), $reqPackage = array()) {
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
        $id = $this->get('id'); $id = $id ? "id='$id'" : "";
        $selected = $this->get('selected') ? " selected='true'" : "";
        
        $ret = "<div $id data-dojo-type='dijit.layout.ContentPane'"
            . " title='{$this->get('label')}' tooltip='{$this->get('toolTip')}'"
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

    function __construct($aParms = array("id" => NULL, "label" => "", "page" => NULL)) {
        parent::__construct($aParms, array());
    }

    function setPageName($value) {
        $this->set('page', $value);
    }
    function getPageName() {
        return $this->get('page');
    }

    protected function getContent() {
        $ret = "";
        $page = $this->get('page');
        if($page != NULL) {
            $ret .= "<div class='tb_container'>\n" . tpl_include_page($page, FALSE) . "\n</div>\n";
        }
        return $ret;
    }
}

class WikiIocContainerFromPage extends WikiIocContentPane {

    function __construct($aParms = array("id" => "", "label" => "", "page" => "")) {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "ioc", "location" => $js_packages["ioc"])
                      );
        parent::__construct($aParms, $reqPackage);
    }

    function setPageName($value) {
        $this->set('page', $value);
    }
    function getPageName() {
        return $this->get('page');
    }

    protected function getPreContent() {
        $id = $this->get('id'); $id = $id ? "id='$id'" : "";
        $selected = $this->get('selected') ? " selected='true'" : "";

        $ret = "<div $id data-dojo-type='ioc.gui.ContentTabDokuwikiPage'"
            . " title='{$this->get('label')}' tooltip='{$this->get('toolTip')}'"
            . " extractContent='false' preventCache='false' preload='false' refreshOnShow='false'"
            . " $selected closable='false' doLayout='false'>\n";
        return $ret;
    }

    protected function getContent() {
        $ret = "";
        $page = $this->get('page');
        if($page != NULL) {
            $ret .= "<div class=\"tb_container\">\n" . tpl_include_page($page, FALSE) . "\n</div>\n";
        }
        return $ret;
    }
}

class WikiIocTreeContainer extends WikiIocContentPane {

    function __construct($aParms = array("id" => "", "label" => "", "treeDataSource" => "")) {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "ioc", "location" => $js_packages["ioc"])
                      );
        parent::__construct($aParms, $reqPackage);
    }

    function setRootValue($value) {
        $this->set('rootValue', $value);
    }
    function setTreeDataSource($value) {
        $this->set('treeDataSource', $value);
    }
    function setPageDataSource($value) {
        $this->set('pageDataSource', $value);
    }

    function getRootValue() {
        return $this->get('rootValue');
    }
    function getTreeDataSource() {
        return $this->get('treeDataSource');
    }
    function getPageDataSource() {
        return $this->get('pageDataSource');
    }

    protected function getPreContent() {
        $id = $this->get('id'); $id = $id ? "id='$id'" : "";
        $treeDataSource = $this->get('treeDataSource') ? "treeDataSource:\"{$this->get('treeDataSource')}\"" : "";
        $coma = $treeDataSource ? "," : "";
        $rootValue = $this->get('rootValue') ? "$coma rootValue:\"{$this->get('rootValue')}\"" : "";
        $coma = $treeDataSource || $rootValue ? "," : "";
        $pageDataSource = $this->get('pageDataSource') ? "$coma urlBase:\"{$this->get('pageDataSource')}\"" : "";
        $selected = $this->get('selected') ? "selected='true'" : "";
        
        $ret = "<div $id data-dojo-type='ioc.gui.ContentTabDokuwikiNsTree'"
            . " title='{$this->get('label')}' tooltip='{$this->get('toolTip')}'"
            . " extractContent='false' preventCache='false' preload='false' refreshOnShow='false'"
            . " data-dojo-props='{$treeDataSource}{$rootValue}{$pageDataSource}'"
            . " $selected style='overflow:auto;' closable='false' doLayout='false'>\n";
        return $ret;
    }

//    protected function getContent() {
//        return "";
//    }
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
        $id = $this->get('id'); $id = $id ? "id='$id'" : "";
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
        $id = $this->get('id'); $id = $id ? "id='$id'" : "";
        $id_form = $id ? "id='{$id}_form'" : "";
        $action = $this->getAction() ? "" : "<script>alert('No s\'ha definit l\'element action al formulari [{$this->get('label')}].');</script>\n";
        $visible = $this->getDisplay() ? 'true' : 'false';
        
        $ret = "<span $id title='{$this->get('label')}' tooltip='{$this->get('toolTip')}'"
            . " style='position:{$this->get('position')}; top:{$this->get('top')}; left:{$this->get('left')}; z-index:{$this->get('zindex')};'>\n"
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
    public function __construct($aParms = array("id" => NULL, "label" => "", "autoSize" => FALSE, "display" => TRUE, "displayBlock" => TRUE, "actionHidden" => NULL)) {
        global $js_packages;
        $reqPackage = array(
            array("name" => "ioc", "location" => $js_packages["ioc"]),
            array("name" => "dojo", "location" => $js_packages["dojo"]),
            array("name" => "dijit", "location" => $js_packages["dijit"])
        );
        parent::__construct($aParms, $reqPackage);
        
        if ($aParms['actionHidden']) {
            $iocClass = $aParms['actionHidden']['class'];
            $this->set('actionHidden', new $iocClass($aParms['actionHidden']['parms']));
        }
    }

    public function setAutoSize($autoSize) {
        $this->set('autoSize', $autoSize);
    }
    public function setDisplay($display) {
        $this->set('display', $display);
    }
    public function setDisplayBlock($displayBlock) {
        $this->set('displayBlock', $displayBlock);
    }
    public function setActionHidden($actionHidden) {
        $this->set('actionHidden', $actionHidden);
    }

    public function getAutoSize() {
        return $this->get('autoSize');
    }
    public function getDisplay() {
        return $this->get('display');
    }
    public function getDisplayBlock() {
        return $this->get('displayBlock');
    }
    public function getActionHidden() {
        return $this->get('actionHidden');
    }

    protected function getPreContent() {
        $id = $this->get('id'); $id = $id ? "id='$id'" : "";
        $autoSize     = $this->get('autoSize') ? 'true' : 'false';
        $display      = $this->get('display') ? 'true' : 'false';
        $displayBlock = $this->get('displayBlock') ? "iocDisplayBlock" : "dijitInline";

        $ret = "\n<div $id data-dojo-type='ioc.gui.IocDropDownButton' class='$displayBlock' style='font-size:0.75em'"
            . " data-dojo-props=\"autoSize:$autoSize, visible:$display\">"
            . "\n<span>{$this->get('label')}</span>";
        return $ret;
    }

    protected function getPostContent() {
        return "\n</div>\n";
    }

    protected function getContent() {
        $_ActionHidden = $this->get('actionHidden');
        $ret = ($_ActionHidden) ? $_ActionHidden->getRenderingCode() : "";
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
    function __construct($aParms = array(), $reqPackage = array()) {
        global $js_packages;
        if ($reqPackage == NULL) {
            $reqPackage = array(
                array("name" => "dojo", "location" => $js_packages["dojo"]),
                array("name" => "dijit", "location" => $js_packages["dijit"])
            );
        }
        parent::__construct($aParms, $reqPackage);
    }

    public function setAction($action) {
        $this->set('action', $action);
    }
    public function setDisplay($display) {
        $this->set('display', $display);
    }
    public function setDisplayBlock($displayBlock) {
        $this->set('displayBlock', $displayBlock);
    }
    public function setFontSize($fontSize) {
        $this->set('fontSize', $fontSize);
    }
    public function setType($type) {
        $this->set('type', $type);
    }

    public function getAction() {
        return $this->get('action');
    }
    public function getDisplay() {
        return $this->get('display');
    }
    public function getDisplayBlock() {
        return $this->get('displayBlock');
    }
    public function getFontSize() {
        return $this->get('fontSize');
    }
    public function getType() {
        return $this->get('type');
    }

    public function getRenderingCode() {
        $id = $this->get('id'); $id = $id ? "id='$id'" : "";
        $visible      = $this->get('display') ? 'true' : 'false';
        $displayBlock = $this->get('displayBlock') ? "iocDisplayBlock" : "dijitInline";
        $fontSize     = $this->get('fontSize') ? $this->get('fontSize') : "1em";

        $ret = "<input $id class='$displayBlock' type='{$this->get('type')}' data-dojo-type='dijit.form.Button'"
            . " data-dojo-props=\"onClick: function(){{$this->get('action')}}, visible:$visible\""
            . " label='{$this->get('label')}' tabIndex='-1' intermediateChanges='false'"
            . " iconClass='dijitNoIcon' style='font-size:{$fontSize};'></input>\n";
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
    function __construct($aParms = array()) {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "dojo", "location" => $js_packages["dojo"])
                        ,array("name" => "dijit", "location" => $js_packages["dijit"])
                        ,array("name" => "ioc", "location" => $js_packages["ioc"])
                      );
        parent::__construct($aParms, $reqPackage);
    }

    public function setQuery($query) {
        $this->set('query', $query);
    }
    public function setAutoSize($autoSize) {
        $this->set('autoSize', $autoSize);
    }

    public function getQuery() {
        return $this->get('query');
    }
    public function getAutoSize() {
        return $this->get('autoSize');
    }

    public function getRenderingCode() {
        $id = $this->get('id'); $id = $id ? "id='$id'" : "";
        $autoSize     = $this->get('autoSize') ? 'true' : 'false';
        $display      = $this->get('display') ? 'true' : 'false';
        $displayBlock = $this->get('displayBlock') ? "iocDisplayBlock" : "dijitInline";

        $ret = "\n<input $id class='$displayBlock' type='button' data-dojo-type='ioc.gui.IocButton'"
            . " data-dojo-props=\"query:'{$this->get('query')}', autoSize:$autoSize, visible:$display\""
            . " label='{$this->get('label')}' tabIndex='-1' intermediateChanges='false'"
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
    function __construct($aParms = array()) {
        global $js_packages;
        $reqPackage = array(
            array("name" => "dojo", "location" => $js_packages["dojo"])
        );
        parent::__construct($aParms, $reqPackage);
    }

    public function setName($name) {
        $this->set('name', $name);
    }
    public function setType($type) {
        $this->set('type', $type);
    }

    public function getName() {
        return $this->get('name');
    }
    public function getType() {
        return $this->get('type');
    }

    public function getRenderingCode() {
        $id = $this->get('id');
        $name = $this->get('name'); $name = ($name == NULL) ? $this->get('id') : $name;
        $type = $this->get('type'); $type = ($type == NULL) ? "" : "type='$type' ";
        $ret = "<label for='$id'>{$this->get('label')}</label>"
             . "<input data-dojo-type='dijit.form.TextBox' id='$id' name='{$name}' {$type}/><br />";
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
        if($this->get('position') == "absolute" && ($this->get('top') == NULL || $this->get('left') == NULL)) {
            $ret = "\n<span missatgeError='nombre incorrecte de paràmetres'>\n";
        } else {
            $ret = "\n<span style='position:{$this->get('position')}; top:{$this->get('top')}; left:{$this->get('left')}; z-index:{$this->get('zindex')};'>";
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
        $id = $this->get('id'); $id = $id ? "id='$id'" : "";
        $ret = "<span $id data-dojo-type='dijit.layout.AccordionContainer' data-dojo-props='id:\"{$this->get('id')}\"' duration='200' persist='false' style='min-width: 1em; min-height: 1em; width: 100%; height: 100%;'>\n";
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
    function __construct($aParms = array()) {
        global $js_packages;
        $reqPackage = array(
             array("name" => "dojo", "location" => $js_packages["dojo"])
            ,array("name" => "dijit", "location" => $js_packages["dijit"])
        );
        parent::__construct($aParms, $reqPackage);
    }

    public function setTitle($title) {
        $this->set('title', $title);
    }
    public function getTitle() {
        return $this->get('title');
    }

    public function getRenderingCode() {
        $selected = ($this->get('selected')) ? "selected='true'" : "";
        $ret = "<div data-dojo-type='dijit.layout.ContentPane' title='{$this->get('title')}' extractContent='false'"
             . " preventCache='false' preload='false' refreshOnShow='false' $selected closable='false' doLayout='false'></div>\n";
        return $ret;
    }
}

/**
 * class WikiIocTextContentPane
 *      Contenidor de tipus ContentPane per a text sense format
 */
class WikiIocTextContentPane extends WikiIocContainer {

    public function __construct($aParms = array()) {
        global $js_packages;
        $reqPackage = array(
             array("name" => "dojo", "location" => $js_packages["dojo"])
            ,array("name" => "dijit", "location" => $js_packages["dijit"])
        );
        parent::__construct($aParms, $reqPackage);
    }

    public function setMessage($msg) {
        $this->set('missatge', $msg);
    }

    public function getMessage() {
        return $this->get('missatge');
    }

    protected function getPreContent() {
        $id = $this->get('id'); $id = $id ? "id='$id'" : "";
        $ret = "<div $id data-dojo-type='dijit.layout.ContentPane' extractContent='false' preventCache='false'"
              ." preload='false' refreshOnShow='false' closable='false' doLayout='false'>";
        return $ret;
    }

    protected function getContent() {
        return $this->get('missatge');
    }

    protected function getPostContent() {
        return "</div>\n";
    }
}
