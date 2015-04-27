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
    private $content;
    
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
    public function getContent() {
        $ret = (is_object($this->content)) ? $this->content->getRenderingCode() : $this->content;
        return $ret;
    }
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
    /*
    public function getHeight() {
        return $this->get('CSS','height');
    }
    public function getWidth() {
        return $this->get('CSS','width');
    }
    public function getPosition() {
        return $this->get('CSS','position');
    }
    public function getRegion() {
        return $this->get('region');
    }
    public function getTop() {
        return $this->get('CSS','top');
    }
    public function getLeft() {
        return $this->get('CSS','left');
    }
    public function getZindex() {
        return $this->get('CSS','z-index');
    }

    public function setHeight($v) {
        $this->set('CSS','height', $v);
    }
    public function setWidth($v) {
        $this->set('CSS','width', $v);
    }
    public function setPosition($v) {
        $this->set('CSS','position', $v);
    }
    public function setRegion($v) {
        $this->set('region', $v);
    }
    public function setTop($v) {
        $this->set('CSS','top', $v);
    }
    public function setLeft($v) {
        $this->set('CSS',left, $v);
    }
    public function setZindex($v) {
        $this->set('CSS','z-index', $v);
    }
    */
}

abstract class WikiIocItemsContainer extends WikiIocContainer {
    protected $items = array();

    function __construct($aParms = array(), $aItems = array(), $reqPackage = array()) {
        parent::__construct($aParms, $reqPackage);

        if ($aItems) {
            foreach($aItems as $item) {
                $ioc_class = $item['class'];
                $obj = new $ioc_class($item['parms'], $item['items']);
                $id = $obj->getId();
                $this->putItem($id, $obj);
                unset($obj);
            }
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
        $ret = "";
        foreach($this->items as $i) {
            $ret .= $i->getRenderingCode();
        }
        return $ret;
    }
}

class WikiIocBody extends WikiIocItemsContainer {

    function __construct($aParms = array(), $aItems = array()) {
        parent::__construct($aParms, $aItems, array());
    }

    protected function getPreContent() {
        return "<body {$this->getDOM()} class='claro'>\n";
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

    function __construct($aParms = array(), $aItems = array()) {
        parent::__construct($aParms, $aItems, array());
    }

    protected function getPreContent() {
        return "<div {$this->getDOM()} {$this->getCSS()}>\n";
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
        $ret = "<span {$this->getCSS()}>\n"
             . "<img alt='logo' {$this->getCSS(array('width','height','z-index'))} src='".DOKU_TPL."{$this->getPRP('src')}'></img>\n"
             . "</span>\n";
        return $ret;
    }
}

/**
 * class WikiIocBorderContainer
 *      Contenidor de items del tipus BorderContainer
 */
class WikiIocBorderContainer extends WikiIocItemsContainer {

    function __construct($aParms = array(), $aItems = array()) {
        parent::__construct($aParms, $aItems, array());
    }

    protected function getPreContent() {
        $ret = "<div {$this->getDOM()}>\n"
             . "<div data-dojo-type='dijit.layout.BorderContainer' design='sidebar' persist='false' gutters='true' "
             . "style='min-width:1em; min-height:1px; z-index:0; width:100%; height:100%;'>\n";
        return $ret;
    }

    protected function getPostContent() {
        return "</div>\n</div>\n";
    }
}

/**
 * class WikiIocItemsPanel
 *      Contenidor de tipus ContentPane allotjat en un BorderContainer
 *      Pot contenir items de qualsevol tipus
 */
class WikiIocItemsPanel extends WikiIocItemsContainer {
    // bloc esquerre: conté la #zona de navegació# i la #zona de propietats#
    // bloc dreta: conté la #zona de canvi#
    function __construct($aParms = array(), $aItems = array()) {
        global $js_packages;
        $reqPackage = array(
            array("name" => "dojo", "location" => $js_packages["dojo"]),
            array("name" => "dijit", "location" => $js_packages["dijit"])
        );
        parent::__construct($aParms, $aItems, $reqPackage);
    }

    protected function getPreContent() {
        $ret .= "<div {$this->getDOM()} {$this->getCSS()} data-dojo-type='dijit.layout.ContentPane' "
              . " extractContent='false' preventCache='false' preload='false' refreshOnShow='false' maxSize='Infinity'>\n";
        return $ret;
    }

    protected function getPostContent() {
        return "</div>\n";
    }
}

/**
 * class WikiIocItemsPanelDiv
 *      Contenidor de tipus ContentPane allotjat en un DIV allotjat en un BorderContainer
 *      Pot contenir items de qualsevol tipus
 */
class WikiIocItemsPanelDiv extends WikiIocItemsContainer {

    function __construct($aParms = array(), $aItems = array()) {
        global $js_packages;
        $reqPackage = array(
            array("name" => "dojo", "location" => $js_packages["dojo"]),
            array("name" => "dijit", "location" => $js_packages["dijit"])
        );
        parent::__construct($aParms, $aItems, $reqPackage);
    }

    protected function getPreContent() {
        $ret = "<div {$this->getDOM('id')}>\n";
        $ret .= "<div {$this->getNoDOM(array('id','label'))} data-dojo-type='dijit.layout.ContentPane' "
              . " extractContent='false' preventCache='false' preload='false' refreshOnShow='false' maxSize='Infinity'>\n";
        return $ret;
    }

    protected function getPostContent() {
        return "</div>\n</div>\n";
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

    public function __construct($aParms = array(), $aItems = array()) {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "ioc", "location" => $js_packages["ioc"])
                        ,array("name" => "dojo", "location" => $js_packages["dojo"])
                        ,array("name" => "dijit", "location" => $js_packages["dijit"])
                      );
        parent::__construct($aParms, $aItems, $reqPackage);
    }

    function putItem($id, &$tab) {
        if(!is_array($this->items)) {
            $this->set('DOM','tabSelected', $id);
            $tab->setSelected(TRUE);
        } else if($tab->isSelected()) {
            $this->selectTab($id);
        }
        $ret = parent::putItem($id, $tab);
        return $ret;
    }

    function selectTab($id) {
        if(array_key_exists($id, $this->items)) {
            if(array_key_exists($this->get('DOM','tabSelected'), $this->items)) {
                $this->items[$this->get('DOM','tabSelected')]->setSelected(FALSE);
            }
            $this->set('DOM','tabSelected', $id);
            $this->items[$id]->setSelected(TRUE);
        }
    }

    function getTab($id) {
        return $this->getItem($id);
    }
    function getTabType() {
        return $this->get('DOM','tabType');
    }
    function hasMenuButton() {
        return $this->get('DOM','useMenu');
    }
    function hasScrollingButtons() {
        return $this->get('DOM','useSlider');
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
        $this->set('DOM','useMenu', $value);
    }
    function setScrollingButtons($value) {
        $this->set('DOM','useSlider', $value);
    }

    protected function getPreContent() {
        $useMenu   = $this->get('DOM','useMenu') ? "true" : "false";
        $useSlider = $this->get('DOM','useSlider') ? "true" : "false";

        $ret = "<div {$this->getDOM('id')} data-dojo-type='dijit.layout.TabContainer' persist='false'";
        if($this->get('DOM','tabType') == 2) {
            $ret .= " controllerWidget='dijit.layout.ScrollingTabController'";
            $ret .= " useMenu='$useMenu'";
            $ret .= " useSlider='$useSlider'";
        } elseif($this->get('DOM','tabType') == 1) {
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
 *      Concentració de codi
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
        parent::__construct($aParms, NULL, $reqPackage);
    }

    protected function getPreContent() {
        /*
        $id = $this->get('id'); $id = $id ? "id='$id'" : "";
        $selected = $this->get('selected') ? " selected='true'" : "";
        
        $ret = "<div {$this->getDOM()} data-dojo-type='dijit.layout.ContentPane'"
            . " title='{$this->getDOM('label')}' tooltip='{$this->getDOM('toolTip')}'"
            . " extractContent='false' preventCache='false' preload='false' refreshOnShow='false'"
            . " $selected closable='false' doLayout='false'>\n";
        */
        $label = $this->get('DOM','label');
        $title = ($label) ? "title='$label'" : "";
        $tooltip = ($label) ? "tooltip='$label'" : "";
        $ret = "<div {$this->getDOM()} $title $tooltip data-dojo-type='dijit.layout.ContentPane'"
            . " extractContent='false' preventCache='false' preload='false' refreshOnShow='false'"
            . " closable='false' doLayout='false'>\n";
        return $ret;
    }

    protected function getPostContent() {
        return "</div>\n";
    }

    public function getContent() {
        $ret = "";
        $page = $this->get('PRP','page');
        if($page != NULL) {
            $ret .= "<div class='tb_container'>\n" . tpl_include_page($page, FALSE) . "\n</div>\n";
        }
        return $ret;
    }
}

class WikiIocContainerFromMenuPage extends WikiIocContentPane {

    function __construct($aParms = array()) {
        parent::__construct($aParms, array());
    }
}

class WikiIocContainerFromPage extends WikiIocContentPane {

    function __construct($aParms = array()) {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "ioc", "location" => $js_packages["ioc"])
                      );
        parent::__construct($aParms, $reqPackage);
    }

    protected function getPreContent() {
        /*
        $id = $this->get('id'); $id = $id ? "id='$id'" : "";
        $selected = $this->get('selected') ? " selected='true'" : "";

        $ret = "<div $id data-dojo-type='ioc.gui.ContentTabDokuwikiPage'"
            . " title='{$this->get('label')}' tooltip='{$this->get('toolTip')}'"
            . " extractContent='false' preventCache='false' preload='false' refreshOnShow='false'"
            . " $selected closable='false' doLayout='false'>\n";
        */
        $label = $this->get('DOM','label');
        $title = ($label) ? "title='$label'" : "";
        $tooltip = ($label) ? "tooltip='$label'" : "";
        $ret = "<div {$this->getDOM()} {$this->getDJO()} $title $tooltip data-dojo-type='ioc.gui.ContentTabDokuwikiPage'"
            . " extractContent='false' preventCache='false' preload='false' refreshOnShow='false'"
            . " closable='false' doLayout='false'>\n";
        return $ret;
    }
}

class WikiIocTreeContainer extends WikiIocContentPane {

    function __construct($aParms = array()) {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "ioc", "location" => $js_packages["ioc"])
                      );
        parent::__construct($aParms, $reqPackage);
    }
    /*
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
    */
    protected function getPreContent() {
        /*
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
        */
        $label = $this->get('DOM','label');
        $title = ($label) ? "title='$label'" : "";
        $tooltip = ($label) ? "tooltip='$label'" : "";
        $ret = "<div {$this->getDOM()} $title $tooltip data-dojo-type='ioc.gui.ContentTabDokuwikiNsTree'"
            . " extractContent='false' preventCache='false' preload='false' refreshOnShow='false'"
            . " {$this->getDJO()}"
            . " style='overflow:auto;' closable='false' doLayout='false'>\n";
        return $ret;
    }

    public function getContent() {
        return "";
    }
}

class WikiIocHiddenDialog extends WikiIocItemsContainer {
    /* Descripció:
     *		Crea un contenidor de la classe ioc.gui.ActionHiddenDialogDokuwiki
     *		que no és visible en el moment de la seva creació.
     *		Aquest contenidor està dissenyat per contenir items.
     */
    public function __construct($aParms = array(), $aItems = array()) {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "ioc", "location" => $js_packages["ioc"])
                        ,array("name" => "dojo", "location" => $js_packages["dojo"])
                        ,array("name" => "dijit", "location" => $js_packages["dijit"])
                      );
        parent::__construct($aParms, $aItems, $reqPackage);
    }

    protected function getPreContent() {
        $ret = "\n<div {$this->getDOM()} data-dojo-type='ioc.gui.ActionHiddenDialogDokuwiki'"
			. " {$this->getDJO()}"
			. ">";
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
    public function __construct($aParms = array(), $aItems = array()) {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "ioc", "location" => $js_packages["ioc"])
                        ,array("name" => "dojo", "location" => $js_packages["dojo"])
                        ,array("name" => "dijit", "location" => $js_packages["dijit"])
                      );
        parent::__construct($aParms, $aItems, $reqPackage);
    }
    /*
    public function setAction($action) {
        $this->set('action', $action);
    }
    public function setDisplay($display) {
        $this->set('display', $display);
    }
    public function setUrlBase($url) {
        $this->set('urlBase', $url);
    }
    public function getAction() {
        return $this->get('action');
    }
    public function getDisplay() {
        return $this->get('display');
    }
    public function getUrlBase() {
        return $this->get('urlBase');
    }
    */
    protected function getPreContent() {
        $id = $this->getDOM('id'); $id = $id ? "id='$id'" : "";
        $id_form = $id ? "id='{$id}_form'" : "";
        $action = $this->getDJO('action') ? "" : "<script>alert('No s\'ha definit l\'element action al formulari [{$this->getDOM('label')}].');</script>\n";
        //$visible = $this->get('visible') ? 'true' : 'false';
        
        $ret = "<span id='{$this->getDOM('id')}' title='{$this->getDOM('label')}' tooltip='{$this->getDOM('toolTip')}' {$this->getCSS()}'>\n"
            . " $action"
            . " <span $id_form data-dojo-type='ioc.gui.IocForm' {$this->getDJO()}>\n";
        return $ret;
    }

    protected function getPostContent() {
        $ret = "</span>\n</span>\n";
        return $ret;
    }
}

class WikiIocDropDownMenu extends WikiIocItemsContainer {
    public function __construct($aParms = array(), $aItems = array()) {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "dojo", "location" => $js_packages["dojo"])
                        ,array("name" => "dijit", "location" => $js_packages["dijit"])
                      );
        parent::__construct($aParms, $aItems, $reqPackage);
    }

    public function getPreContent() {
        $ret = "\n<div {$this->getDOM()} data-dojo-type='dijit.DropDownMenu'>";
        return $ret;
    }
    public function getPostContent() {
        return "\n</div>\n";
    }
}

class WikiIocDropDownButton extends WikiIocItemsContainer {
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
    public function __construct($aParms = array(), $aItems = array()) {
        global $js_packages;
        $reqPackage = array(
            array("name" => "ioc", "location" => $js_packages["ioc"]),
            array("name" => "dojo", "location" => $js_packages["dojo"]),
            array("name" => "dijit", "location" => $js_packages["dijit"])
        );
        parent::__construct($aParms, $aItems, $reqPackage);
    }
    /*
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
    */
    protected function getPreContent() {
        $ret = "\n<div {$this->getDOM(array('id','class'))} data-dojo-type='ioc.gui.IocDropDownButton'"
              ." {$this->getDJO()}"
              ." style='font-size:0.75em; margin-top:10px; margin-right:5px; float:right;'>"
              ."\n<span>{$this->get('DOM','label')}</span>";
        return $ret;
    }

    protected function getPostContent() {
        return "\n</div>\n";
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

    public function getRenderingCode() {
        $ret = "<input {$this->getDOM()} type='button' data-dojo-type='dijit.form.Button'"
            . " {$this->getDJO()} tabIndex='-1' intermediateChanges='false'"
            . " iconClass='dijitNoIcon' style='font-size:1em;'></input>\n";
        return $ret;
    }
}

class WikiIocButton extends WikiIocComponent {
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

    public function getRenderingCode() {
        $ret = "<input {$this->getDOM()} type='button' data-dojo-type='ioc.gui.IocButton'"
            . " {$this->getDJO()} tabIndex='-1' intermediateChanges='false'"
            . " iconClass='dijitNoIcon' style='font-size:0.75em;'></input>\n";
        return $ret;
    }
}

class WikiIocMenuItem extends WikiIocComponent {
    function __construct($aParms = array()) {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "dojo", "location" => $js_packages["dojo"])
                        ,array("name" => "dijit", "location" => $js_packages["dijit"])
                        ,array("name" => "ioc", "location" => $js_packages["ioc"])
                      );
        parent::__construct($aParms, $reqPackage);
    }

    public function getRenderingCode() {
        /*
        $id = $this->get('id'); $id = $id ? "id='$id'" : "";
        $autoSize = $this->get('autoSize') ? 'true' : 'false';
        $disabled = $this->get('disabled') ? 'true' : 'false';
        */
        $ret = "\n<div {$this->getDOM()} type='button' data-dojo-type='ioc.gui.IocMenuItem'"
             . " {$this->getDJO()} iconClass='dijitNoIcon'"
             . " style='font-size:1em;'></div>";
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
    /*
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
    */
    public function getRenderingCode() {
        $id = $this->get('DOM','id');
        $name = $this->get('DOM','name'); $name = "name='" . (($name == NULL) ? $id : $name) . "'";
        $type = $this->get('DOM','type'); $type = ($type == NULL) ? "" : "type='$type' ";
        $required = ($this->get('DOM','required')) ? "required=true" : "";
        $ret = "\n<label for='$id'>{$this->get('DOM','label')}</label><br />\n"
             . "<input data-dojo-type='dijit.form.TextBox' id='$id' {$name} {$type} {$required}/><br />";
        return $ret;
    }
}

class WikiDojoToolBar extends WikiIocItemsContainer {
    /* @author Rafael Claver <rclaver@xtec.cat>
     * Descripció:
     *        És un contenidor que construeix una barra de botons.
     *        Permet establir la seva posició i tamany.
     */

    function __construct($aParms = array(), $aItems = array()) {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "dojo", "location" => $js_packages["dojo"])
                        ,array("name" => "dijit", "location" => $js_packages["dijit"])
                        ,array("name" => "ioc", "location" => $js_packages["ioc"])
                      );
        parent::__construct($aParms, $aItems, $reqPackage);
    }

    protected function getPreContent() {
        if($this->get('CSS','position') == "absolute" && ($this->get('CSS','top') == NULL || $this->get('CSS','left') == NULL)) {
            $ret = "\n<span missatgeError='nombre incorrecte de paràmetres'>\n";
        } else {
            $ret = "\n<span {$this->getCSS()}>";
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
    function __construct($aParms = array(), $aItems = array()) {
        global $js_packages;
        $reqPackage = array(
                         array("name" => "dojo", "location" => $js_packages["dojo"])
                        ,array("name" => "dijit", "location" => $js_packages["dijit"])
                      );
        parent::__construct($aParms, $aItems, $reqPackage);
    }

    protected function getPreContent() {
        //$id = $this->get('id'); $id = $id ? "id='$id'" : "";
        $ret = "<span {$this->getDOM('id')} data-dojo-type='dijit.layout.AccordionContainer'"
             . " data-dojo-props='id:\"{$this->get('DOM','id')}\"' duration='200' persist='false'"
             . " style='min-width: 1em; min-height: 1em; width: 100%; height: 100%;'>\n";
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
    /*
    public function setTitle($title) {
        $this->set('title', $title);
    }
    public function getTitle() {
        return $this->get('title');
    }
    */
    public function getRenderingCode() {
        $selected = ($this->get('DOM','selected')) ? "selected='true'" : "";
        $ret = "<div data-dojo-type='dijit.layout.ContentPane' title='{$this->get('DOM','title')}' extractContent='false'"
             . " preventCache='false' preload='false' refreshOnShow='false' $selected closable='false' doLayout='false'></div>\n";
        return $ret;
    }
}

/**
 * class WikiIocTextContentPane
 *      Contenidor de tipus ContentPane per a text sense format
 */
class WikiIocTextContentPane extends WikiIocContainer {

    public function __construct($aParms = array(), $contingut = NULL) {
        global $js_packages;
        $reqPackage = array(
             array("name" => "dojo", "location" => $js_packages["dojo"])
            ,array("name" => "dijit", "location" => $js_packages["dijit"])
        );
        parent::__construct($aParms, $reqPackage);
        $this->content = $contingut;
    }

    public function setMessage($msg) {
        $this->set('PRP','missatge', $msg);
    }
    public function getMessage() {
        return $this->getPRP('missatge');
    }

    protected function getPreContent() {
        $ret = "<div {$this->getDOM()} data-dojo-type='dijit.layout.ContentPane' extractContent='false' preventCache='false'"
              ." preload='false' refreshOnShow='false' closable='false' doLayout='false'>";
        return $ret;
    }

    protected function getPostContent() {
        return "</div>\n";
    }
}
