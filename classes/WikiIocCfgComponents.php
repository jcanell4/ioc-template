<?php

if(!defined("DOKU_INC")) die();
if(!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', tpl_incdir() . 'classes/');
if(!defined('DOKU_TPL_CONF')) define('DOKU_TPL_CONF', tpl_incdir() . 'conf/');

require_once(DOKU_TPL_CLASSES . 'WikiIocCfgComponent.php');
require_once(DOKU_TPL_CONF . 'js_packages.php');

/**
 * Class WikiIocCfgItemsContainer
 * Superclasse dels components de tipus contenidor.
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
abstract class WikiIocCfgItemsContainer extends WikiIocCfgComponent {
    /** @var  WikiIocComponent[] */
    protected $items;

    /**
     * @param string $label etiqueta del contenidor
     * @param string $id    id del contenidor
     */
    function __construct($label = "", $id = NULL) {
        parent::__construct($label, $id);
    }

    /**
     * Afegeix un component a aquest contenidor i li estableix la id passada com argument si no en te cap.
     *
     * @param string           $id   que es farà servir com a index del component contingut per aquest contenidor
     * @param WikiIocComponent $item component per afegir al contenidor
     *
     * @return WikiIocComponent el mateix component passat com argument
     */
    public function putItem($id, &$item) { // TODO[Xavier] perquè es fa servir &?
        if($item->getId() == NULL) {
            $item->setId($id);
        }
        $ret              = $this->items[$id];
        $this->items[$id] =& $item;
        return $ret;
    }

    /**
     * Retorna el component contingut amb el id passat com argument.
     *
     * @param string $id index del component que volem obtenir
     *
     * @return WikiIocComponent component corresponent al id
     */
    public function getItem($id) {
        return $this->items[$id];
    }

    /**
     * Retorna tots els components continguts en aquest contenidor.
     *
     * @return WikiIocComponent[] array amb tots els components.
     */
    public function getAllItems() {
        $ret = array();
        foreach($this->items as $i) {
            $ret[] = $i;
        }
        return $ret;
    }

    /**
     * Elimina del contenidor el component corresponent a la id passada com argument i el retorna.
     *
     * @param string $id id del component a eliminar
     *
     * @return WikiIocComponent component eliminat
     */
    public function removeItem($id) {
        $ret = $this->items[$id];
        unset($this->items[$id]);
        return $ret;
    }

    public function removeAllItems() {
        unset($this->items);
    }
}

/**
 * Class WikiIocCfgContentPane
 * Aquesta classe permet afegir contingut com a pestanyes.
 */
class WikiIocCfgContentPane extends WikiIocCfgComponent {

    /**
     * @param string $label etiqueta del component
     * @param string $id    id del component
     */
    function __construct($label = "", $id = NULL) {
        parent::__construct($label, $id);
    }
}

/**
 * Class WikiIocCfgTabsContainer
 * Classe per configurar un contenidor de pestanyes que correspon a 'ioc.gui.ContentTabDokuwiki'
 */
class WikiIocCfgTabsContainer extends WikiIocCfgItemsContainer {
    const DEFAULT_TAB_TYPE   = 0;
    const RESIZING_TAB_TYPE  = 1;
    const SCROLLING_TAB_TYPE = 2;
    private $tabSelected;
    /** @var int 0 = normal, 1 = resizing, 2 = scrolling */
    private $tabType;
    private $bMenuButton;
    private $bScrollingButtons;

    /**
     * @param string $label   etiqueta del contenidor de pestanyes
     * @param int    $tabType tipus de contenidor de pestanyes corresponent a les constants definides a aquesta classe
     * @param string $id      id del contenidor de pestanyes
     */
    public function __construct($label = "", $tabType = 0, $id = NULL) {
        if($id == NULL)
            $id = $label;
        parent::__construct($label, $id);
        $this->tabType           = $tabType;
        $this->bMenuButton       = FALSE;
        $this->bScrollingButtons = FALSE;
    }

    /**
     * Afegeix una pestanya al contenidor. Si es la primera es selecciona automàticament. Si la pestanya està
     * seleccionada s'estableix el seu index com a pestanya seleccionada a aquest contenidor.
     *
     * @param string             $id  id de la pestanya
     * @param WikiIocContentPane $tab pestanya a afegir
     *
     * @return WikiIocContentPane la pestanya afegida
     */
    function putTab($id, &$tab) { // TODO[Xavier] perquè es fa servir &?
        if(!is_array($this->items)) {
            $this->tabSelected = $id;
            $tab->setSelected(TRUE);
        } else if($tab->isSelected()) {
            $this->selectTab($id);
        }
        return $this->putItem($id, $tab);
    }

    /**
     * Estableix la pestanya corresponent al id com a seleccionada.
     *
     * @param string $id index de la pestanya
     */
    function selectTab($id) {
        if(array_key_exists($id, $this->items)) {
            if(array_key_exists($this->tabSelected, $this->items)) {
                $this->items[$this->tabSelected]->setSelected(FALSE);
            }
            $this->tabSelected = $id;
            $this->items[$id]->setSelected(TRUE);
        }
    }

    /**
     * Retorna la pestanya corresponent al id.
     *
     * @param string $id de la pestanya
     *
     * @return WikiIocContentPane pestanya corresponent al id
     */
    function getTab($id) {
        return $this->getItem($id);
    }

    /**
     * Elimina la pestanya corresponent al id de aquest contenidor i la retorna.
     *
     * @param string $id id de la pestanya
     *
     * @return WikiIocContentPane pestanya eliminada
     */
    function removeTab($id) {
        return $this->removeItem($id);
    }

    /**
     * Elimina totes les pestanyes del contenidor.
     */
    function removeAllTabs() {
        //TODO[Xavier] Error, aquest métode no retorna res.
        return $this->removeAllItems();
    }

    /**
     * Retorna que tipus de contenidors de pestanyes es aquest, referit a les constants definides en aquesta classe
     *
     * @return int
     */
    function getTabType() {
        return $this->tabType;
    }

    /**
     * Estableix el tipus de contenidor de pestanyes per aquest contenidor, corresponent als valors de les constants
     * referits en aquesta classe.
     *
     * @param int $type
     */
    function setTabType($type) {
        $this->tabType = $type;
    }

    /**
     * Retorna true o false segons si té o no un botó de menú.
     *
     * @return bool
     */
    function hasMenuButton() {
        return $this->bMenuButton;
    }

    /**
     * Estableix si el contenidor te un botó de menú o no.
     *
     * @param bool $value
     */
    function setMenuButton($value) {
        $this->bMenuButton = $value;
    }

    /**
     * Retorna true o false segons si tés o no botó de scroll.
     *
     * @return bool
     */
    function hasScrollingButtons() {
        return $this->bScrollingButtons;
    }

    /**
     * Estableix si el contenidor te o no botons de scroll.
     *
     * @param bool $value
     */
    function setScrollingButtons($value) {
        $this->bScrollingButtons = $value;
    }
}

/**
 * Class WikiIocCfgContainerFromMenuPage
 *
 * @todo[Xavier] Aquesta classe no es fa servir? es idèntica a WikiIocCfgContainerFromPage
 */
class WikiIocCfgContainerFromMenuPage extends WikiIocCfgContentPane {
    /** @var string amb el format de dokuwiki ':wiki:navigation' */
    private $page;

    /**
     * @param string $label etiqueta
     * @param string $page  nom de la pàgina amb el format de dokuwiki ':wiki:navigation'
     * @param string $id    id
     */
    function __construct($label = "", $page = NULL, $id = NULL) {
        parent::__construct($label, $id);
        $this->page = $page;
    }

    /**
     * Retorna el nom de la pàgina.
     *
     * @return string nom de la pàgina
     */
    function getPageName() {
        return $this->page;
    }

    /**
     * Estableix el nom de la pàgina amb el format de dokuwiki ':wiki:navigation'.
     *
     * @param string $value
     */
    function setPageName($value) {
        $this->page = $value;
    }
}

/**
 * Class WikiIocCfgContainerFromPage
 * Panel de contingut que conté una pàgina de la dokuwiki corresponent a 'ioc.gui.ContentTabDokuwikiPage'
 */
class WikiIocCfgContainerFromPage extends WikiIocCfgContentPane {
    /** @var string amb el format de dokuwiki ':wiki:navigation' */
    private $page;

    /**
     * @param string $label
     * @param string $page
     * @param string $id
     */
    function __construct($label = "", $page = NULL, $id = NULL) {
        parent::__construct($label, $id);
        $this->page = $page;
    }

    /**
     * Retorna el nom de la pàgina.
     *
     * @return string nom de la pàgina
     */
    function getPageName() {
        return $this->page;
    }

    /**
     * Estableix el nom de la pàgina amb el format de dokuwiki ':wiki:navigation'.
     *
     * @param string $value
     */
    function setPageName($value) {
        $this->page = $value;
    }
}

/**
 * Class WikiIocCfgTreeContainer
 * Aquesta classe es un un contenidor que mostra una estructura d'arbre, corresponent a
 * 'ioc.gui.ContentTabDokuwikiNsTree'.
 */
class WikiIocCfgTreeContainer extends WikiIocCfgContentPane {
    private $treeDataSource;
    private $pageDataSource; // TODO[Xavier] No s'utilitza?
    private $rootValue; // TODO[Xavier] No s'utilitza?

    /**
     * L'origen de dades del arbre pot ser una crida ajax a traves de AjaxCommand, per exemple:
     * 'lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/'
     *
     * @param string $label          etiqueta del contenidor
     * @param string $treeDataSource origen de les dades del arbre
     * @param null   $pageDataSource ??
     * @param string $rootValue      ??
     * @param string $id             id del contenidor
     */
    function __construct($label = "", $treeDataSource = NULL, $pageDataSource = NULL, $rootValue = "", $id = NULL) {
        parent::__construct($label, $id);
        $this->treeDataSource = $treeDataSource;
        $this->pageDataSource = $pageDataSource;
        $this->rootValue      = $rootValue;
    }

    /**
     * @return string
     */
    function getRootValue() {
        return $this->rootValue;
    }

    /**
     * @param string $value
     */
    function setRootValue($value) {
        $this->rootValue = $value;
    }

    /**
     * Retorna la font de dades del arbre.
     *
     * @return string
     */
    function getTreeDataSource() {
        return $this->treeDataSource;
    }

    /**
     * Estableix la font de dades del arbre.
     *
     * @param $value
     */
    function setTreeDataSource($value) {
        $this->treeDataSource = $value;
    }

    function getPageDataSource() {
        return $this->pageDataSource;
    }

    function setPageDataSource($value) {
        $this->pageDataSource = $value;
    }
}

/**
 * Class WikiIocCfgHiddenDialog
 * Defineix un contenidor de la classe ioc.gui.ActionHiddenDialogDokuwiki que no serà visible en el moment de la seva
 * creació. Aquest contenidor està dissenyat per contenir items.
 *
 * Els mètodes de construcció els hereta de WikiIocCfgItemsContainer.
 *
 * @author Rafael Claver <rclaver@xtec.cat>
 */
class WikiIocCfgHiddenDialog extends WikiIocCfgItemsContainer {

    /**
     * @param string $id    id del contenidor
     * @param string $label etiqueta del contenidor
     */
    public function __construct($id, $label = "") {
        parent::__construct($label, $id);
    }
}

/**
 * Class WikiDojoCfgFormContainer
 * Defineix un contenidor de la classe ioc.gui.IocForm dissenyat per contenir items de formulari.
 *
 * Els mètodes de construcció els hereta de WikiIocCfgItemsContainer.
 *
 * @author Rafael Claver <rclaver@xtec.cat>
 */
class WikiDojoCfgFormContainer extends WikiIocCfgItemsContainer {
    private $action;
    private $urlBase;
    private $display;
    private $position;
    private $top;
    private $left;
    private $zindex;

    /**
     * @param string $label    etiqueta del formulari
     * @param string $id       id del formulari
     * @param string $action   acció que s'executarà amb les dades del formulari
     * @param string $position tipus de posicionament: 'static', 'relative', etc.
     * @param int    $top      distancia des de la part superior per col·locar el formulari
     * @param int    $left     distancia per la esquerra per col·locar el formulari
     * @param bool   $display  true o false segons si s'ha de mostrar o no
     * @param int    $zindex   zindex (propietat CSS)
     */
    public function __construct($label = "", $id = NULL, $action = NULL, $position = "static", $top = 0, $left = 0, $display = TRUE, $zindex = 900) {
        parent::__construct($label, $id);
        $this->action   = $action;
        $this->position = $position;
        $this->top      = $top;
        $this->left     = $left;
        $this->display  = $display;
        $this->zindex   = $zindex;
    }

    /**
     * Estableix el tipus de posicionament: 'static', 'relative', etc.
     *
     * @param string $position
     */
    public function setPosition($position) {
        $this->position = $position;
    }

    /**
     * Estableix el valor zindex (propietat CSS) del formulari.
     *
     * @param int $zindex
     */
    public function setZindex($zindex) {
        $this->zindex = $zindex;
    }

    /**
     * Estableix la distancia des de la part superior a la que es troba el formulari.
     *
     * @param int $top
     */
    public function setTop($top) {
        $this->top = $top;
    }

    /**
     * Estableix la distancia des de la esquerra a la que es troba el formulari.
     *
     * @param int $left
     */
    public function setLeft($left) {
        $this->left = $left;
    }

    /**
     * Estableix la cantonada superior esquerra del formulari.
     *
     * @param int $top
     * @param int $left
     */
    public function setTopLeft($top, $left) {
        $this->top  = $top;
        $this->left = $left;
    }

    /**
     * Estableix la acció que s'executarà amb les dades del formulari. Per exemple: 'commandreport'.
     *
     * @param string $action
     */
    public function setAction($action) {
        $this->action = $action;
    }

    /**
     * Estableix la url base a la que s'afegirà la acció per enviar el formulari. Per exemple:
     * 'lib/plugins/ajaxcommand/ajax.php?call='
     *
     * @param string $url url a la que s'afegirà la acció
     */
    public function setUrlBase($url) {
        $this->urlBase = $url;
    }

    /**
     * Estableix si el formulari s'ha de mostrar o no.
     *
     * @param bool $display true per mostrar-lo o false per amagar-lo
     */
    public function setDisplay($display) {
        $this->display = $display;
    }

    /**
     * Retorna el tipus de posicionament del formulari.
     *
     * @return string
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * Retorna la propietat zIndex del formulari.
     *
     * @return int
     */
    public function getZindex() {
        return $this->zindex;
    }

    /**
     * Retorna la distancia des de la part superior a la que es troba el formulari.
     *
     * @return int
     */
    public function getTop() {
        return $this->top;
    }

    /**
     * Retorna la distancia des de la esquerra a la que es troba el formulari.
     *
     * @return int
     */
    public function getLeft() {
        return $this->left;
    }

    /**
     * Retorna la acció que executarà el formulari.
     *
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * Retorna la url a la que s'afegirà la acció del formulari per executar-la.
     *
     * @return string
     */
    public function getUrlBase() {
        return $this->urlBase;
    }

    /**
     * Retorna si es mostra o no el formulari.
     *
     * @return bool true si es mostra o false si està amagat.
     */
    public function getDisplay() {
        return $this->display;
    }
}

/**
 * Class WikiIocCfgDropDownButton
 * Defineix un botó de la classe ioc.gui.IocDropDownButton
 *
 * Accepta n paràmetres que configuren l'aspecte del botó:
 * - autoSize: true/false
 *      true: indica que el seu tamany depen del tamany del contenidor pare
 *      false: el seu tamany és el tamany estàndar d'un boto, és a dir, té el tamany que ocupa el texte del botó
 * - display: true/false
 *      true: indica que és visible.
 *      false: indica que no és visible.
 * - displayBlock: true/false
 *      true: utilitzarà la classe CSS .iocDisplayBlock {display:block}.
 *      false: utilitzarà la classe CSS dijitInline.
 *
 * @author Rafael Claver <rclaver@xtec.cat>
 */
class WikiIocCfgDropDownButton extends WikiIocCfgComponent {
    private $autoSize;
    private $display;
    private $displayBlock;
    private $actionHidden; // TODO[Xavier] no s'utilitza?

    /**
     * @param string $id           id del component
     * @param string $label        etiqueta del component
     * @param bool   $autoSize     si es true indica que la mida depèn del contenidor pare, o de mida estàndard si es false
     * @param bool   $display      si es true indica que es visible, o amagat si es false
     * @param bool   $displayBlock si es cert farà servir la classe CSS '.iocDisplayBlock', si es false farà servir la
     *                             classe CSS 'dijitInline'
     * @param null   $actionHidden ??
     */
    function __construct($id = NULL, $label = "", $autoSize = FALSE, $display = TRUE, $displayBlock = TRUE, $actionHidden = NULL) {
        parent::__construct($label, $id);
        $this->autoSize     = $autoSize;
        $this->display      = $display;
        $this->displayBlock = $displayBlock;
        $this->actionHidden = $actionHidden;
    }

    /**
     * Estableix si el component ha de ajustar la seva mida a la del contenidor pare o no.
     *
     * @param bool $autoSize true per ajustar la mida, o false en cas contrari
     */
    public function setAutoSize($autoSize) {
        $this->autoSize = $autoSize;
    }

    /**
     * Estableix si el component s'ha de mostrar o amagar.
     *
     * @param bool $display true per mostrar-lo o false per amagar-lo
     */
    public function setDisplay($display) {
        $this->display = $display;
    }

    /**
     * Estableix si ha de fer servir la classe CSS '.iocDisplayBlock' o 'dijitInline'.
     *
     * @param bool $displayBlock si es true es farà servir '.iocDisplayBlock' i si es false 'dijitInline'
     */
    public function setDisplayBlock($displayBlock) {
        $this->displayBlock = $displayBlock;
    }

    public function setActionHidden($actionHidden) {
        $this->actionHidden = $actionHidden;
    }

    /**
     * Retorna si el component canvia de mida o es fixe.
     *
     * @return bool true si s'ajusta la mida al pare o false si es fixe
     */
    public function getAutoSize() {
        return $this->autoSize;
    }

    /**
     * Retorna si el component es visible o està amagat.
     *
     * @return bool true si es visible o false si està amagat
     */
    public function getDisplay() {
        return $this->display;
    }

    /**
     * Retorna si el component es mostra com a bloc o inline.
     *
     * @return bool true si es mostra com a bloc o false si es mostra inline.
     */
    public function getDisplayBlock() {
        return $this->displayBlock;
    }

    public function getActionHidden() {
        return $this->actionHidden;
    }
}

/**
 * Class WikiDojoCfgButton
 *
 *
 * Defineix un botó de la classe dijit.form.Button
 *
 * Accepta paràmetres que configuren l'aspecte del botó:
 * - display: true/false
 *      true: indica que és visible.
 *      false: indica que no és visible.
 * - displayBlock: true/false
 *      true: utilitzarà la classe CSS .iocDisplayBlock {display:block}.
 *      false: utilitzarà la classe CSS dijitInline.
 *
 * Accepta paràmetres que configuren l'acció del botó:
 * - action
 *
 * @author Rafael Claver <rclaver@xtec.cat>
 */
class WikiDojoCfgButton extends WikiIocCfgComponent {
    private $action;
    protected $display;
    protected $displayBlock;
    protected $fontSize;

    /**
     * @param string $label        etiqueta del botó
     * @param string $id           id del botó
     * @param null   $action       codi JavaScript a executar al clicar el botó
     * @param bool   $display      si es true indica que es visible, o amagat si es false
     * @param bool   $displayBlock si es cert farà servir la classe CSS '.iocDisplayBlock', si es false farà servir la
     * @param int    $fontSize     mida de la font
     * @param string $type         tipus de botó, per exemple 'button'
     */
    function __construct($label = "", $id = NULL, $action = NULL, $display = TRUE, $displayBlock = TRUE, $fontSize = 1, $type = 'button') {
        if($id == NULL)
            $id = $label;
        parent::__construct($label, $id);
        $this->action       = $action;
        $this->display      = $display;
        $this->displayBlock = $displayBlock;
        $this->fontSize     = $fontSize;
        $this->type         = $type;
    }

    /**
     * Estableix el codi a executar al clicar el botó.
     *
     * @param string $action codi JavaScript per executar
     */
    public function setAction($action) {
        $this->action = $action;
    }

    /**
     * Estableix si el botó s'ha de mostrar o amagar.
     *
     * @param bool $display true per mostrar-lo o false per amagar-lo
     */
    public function setDisplay($display) {
        $this->display = $display;
    }

    /**
     * Estableix si el botó es mostra com a bloc o inline.
     *
     * @param bool $displayBlock true per bloc o false per inline.
     */
    public function setDisplayBlock($displayBlock) {
        $this->displayBlock = $displayBlock;
    }

    /**
     * Estableix la mida de la font.
     *
     * @param int $fontSize
     */
    public function setFontSize($fontSize) {
        $this->fontSize = $fontSize;
    }

    /**
     * Estableix el tipus del botó, per exemple 'button'.
     *
     * @param string $type tipus de botó
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * Retorna el codi JavaScript a executar al clicar el botó.
     *
     * @return string codi a executar quan es clica el botó
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * Retorna si el botó es mostra o està amagat.
     *
     * @return bool true si es mostra o false si es amagat
     */
    public function getDisplay() {
        return $this->display;
    }

    /**
     * Retorna si el botó es mostra com a bloc o inline.
     *
     * @return bool true si es mostra com a bloc o false si es mostra inline.
     */
    public function getDisplayBlock() {
        return $this->displayBlock;
    }

    /**
     * Retorna la mida de la font del botó.
     *
     * @return int mida de la font
     */
    public function getFontSize() {
        return $this->fontSize;
    }

    /**
     * Retorna el tipus del botó.
     *
     * @return string amb el tipus de botó
     */
    public function getType() {
        return $this->type;
    }
}

/**
 * Class WikiIocCfgButton
 * Defineix un botó de la classe ioc.gui.IocButton
 *
 * Accepta paràmetres que configuren l'aspecte del botó:
 * - autoSize: true/false
 *      true: indica que el seu tamany depen del tamany del contenidor pare
 *      false: el seu tamany és el tamany estàndar d'un boto, és a dir, té el tamany que ocupa el texte del botó
 * - display: true/false
 *      true: indica que és visible.
 *      false: indica que no és visible.
 * - displayBlock: true/false
 *      true: utilitzarà la classe CSS .iocDisplayBlock {display:block}.
 *      false: utilitzarà la classe CSS dijitInline.
 *
 * Accepta paràmetres que configuren l'acció del botó:
 * - query
 *
 * @author Rafael Claver <rclaver@xtec.cat>
 *
 */
class WikiIocCfgButton extends WikiDojoCfgButton {
    private $query;
    private $autoSize;

    /**
     * @param string $label        etiqueta del botó
     * @param string $id           id del botó
     * @param string $query        acció a realitzar. Aquesta acció es una comanda del tipus 'do=save', 'do=edit',
     *                             'do=edparc', 'do=new', 'do=edit'
     * @param bool   $autoSize     si es true indica que la mida depèn del contenidor pare, o de mida estàndard si es false
     * @param bool   $display      si es true indica que es visible, o amagat si es false
     * @param bool   $displayBlock si es cert farà servir la classe CSS '.iocDisplayBlock', si es false farà servir la
     *                             classe CSS 'dijitInline'
     */
    function __construct($label = "", $id = NULL, $query = NULL, $autoSize = FALSE, $display = TRUE, $displayBlock = TRUE) {
        parent::__construct($label, $id, $query, $display, $displayBlock, 'button');
        $this->query    = $query;
        $this->autoSize = $autoSize;
    }

    /**
     * Estableix la acció a realitzar al clicar aquest botó. La comanda será del tipus:
     *      'do=save', 'do=edit', 'do=edparc', 'do=new', 'do=edit'
     *
     * @param string $query acció a realitzar
     */
    public function setQuery($query) {
        $this->query = $query;
    }

    /**
     * Estableix si el component ha de ajustar la seva mida a la del contenidor pare o no.
     *
     * @param bool $autoSize true per ajustar la mida, o false en cas contrari
     */
    public function setAutoSize($autoSize) {
        $this->autoSize = $autoSize;
    }

    /**
     * Retorna la comanda que s'executara al clicar el botó.
     *
     * @return string comanda que s'executa al clicar el botó
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * Retorna si el component canvia de mida o es fixe.
     *
     * @return bool true si s'ajusta la mida al pare o false si es fixe
     */
    public function getAutoSize() {
        return $this->autoSize;
    }
}

/**
 * Class WikiIocCfgFormInputField
 * Defineix un item que pot ser albergat en un contenidor
 * Aquest item és un input textbox de la classe dijit.form.TextBox
 *
 * @author Rafael Claver <rclaver@xtec.cat>
 */
class WikiIocCfgFormInputField extends WikiIocCfgComponent {
    private $name;
    private $type;

    /**
     * @param string $label etiqueta del component
     * @param string $id    id del component
     * @param string $name  propietat corresponent al 'name' del element html corresponent. En els casos del usuari i
     *                      el password corresponen a 'u' i 'p' respetcivament, valors requerits per dokuwiki
     * @param string $type  propietat type del element html, per exemple 'password'
     */
    function __construct($label = "", $id = NULL, $name = NULL, $type = NULL) {
        parent::__construct($label, $id);
        $this->name = ($name == NULL) ? $this->getId() : $name;
        $this->setType($type);
    }

    /**
     * Estableix la propietat type al element html, per exemple 'password', si el tipus es null el deixa buit ''.
     *
     * @param string $type type del element html
     */
    public function setType($type) {
        $this->type = ($type == NULL) ? "" : "type='$type' ";
    }

    /**
     * Retorna el type del element html.
     *
     * @return string type del element html
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Retorna el valor de la propietat name del element html.
     *
     * @return string propietat name del element html
     */
    public function getName() {
        return $this->name;
    }
}

/**
 * Class WikiDojoCfgToolBar
 * Defineix un contenidor que construeix una barra de botons.
 * Permet establir la seva posició i tamany.
 *
 * @author Rafael Claver <rclaver@xtec.cat>
 */
class WikiDojoCfgToolBar extends WikiIocCfgItemsContainer {
    private $position;
    private $zindex;
    private $top;
    private $left;

    /**
     * @param string $label    etiqueta de la toolbar
     * @param string $id       id del toolbar
     * @param string $position tipus de posicionament: 'static', 'relative', etc.
     * @param int    $top      distancia des de la part superior per col·locar el formulari
     * @param int    $left     distancia per la esquerra per col·locar el formulari
     * @param int    $zindex   zindex (propietat CSS)
     */
    function __construct($label = "", $id = NULL, $position = "static", $top = 0, $left = 0, $zindex = 900) {
        if($id == NULL) $id = $label;
        parent::__construct($label, $id);
        $this->position = $position;
        $this->zindex   = $zindex;
        $this->top      = $top;
        $this->left     = $left;
    }

    /**
     * Estableix el tipus de posicionament: 'static', 'relative', etc.
     *
     * @param string $position
     */
    public function setPosition($position) {
        $this->position = $position;
    }

    /**
     * Estableix el valor zindex (propietat CSS) del formulari.
     *
     * @param int $zindex
     */
    public function setZindex($zindex) {
        $this->zindex = $zindex;
    }

    /**
     * Estableix la distancia des de la part superior a la que es troba el formulari.
     *
     * @param int $top
     */
    public function setTop($top) {
        $this->top = $top;
    }

    /**
     * Estableix la distancia des de la esquerra a la que es troba el formulari.
     *
     * @param int $left
     */
    public function setLeft($left) {
        $this->left = $left;
    }

    /**
     * Estableix la cantonada superior esquerra del formulari.
     *
     * @param int $top
     * @param int $left
     */
    public function setTopLeft($top, $left) {
        $this->top  = $top;
        $this->left = $left;
    }

    /**
     * Retorna el tipus de posicionament del formulari.
     *
     * @return string
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * Retorna la propietat zIndex del formulari.
     *
     * @return int
     */
    public function getZindex() {
        return $this->zindex;
    }

    /**
     * Retorna la distancia des de la part superior a la que es troba el formulari.
     *
     * @return int
     */
    public function getTop() {
        return $this->top;
    }

    /**
     * Retorna la distancia des de la esquerra a la que es troba el formulari.
     *
     * @return int
     */
    public function getLeft() {
        return $this->left;
    }
}

/**
 * Class WikiIocCfgLeftContainer
 * Defineix un contenidor per allotjar els items de la part esquerra.
 * Està dissenyat per contenir blocs d'items.
 *
 * @deprecated
 * @todo   PENDENT DE SUPRIMIR (no es fa servir enlloc)
 * @author Rafael Claver <rclaver@xtec.cat>
 */
class WikiIocCfgLeftContainer extends WikiIocCfgItemsContainer {

    /**
     * @param string $label etiqueta del contenidor
     * @param string $id    id del contenidor
     */
    function __construct($label = "", $id = NULL) {
        if($id == NULL) $id = $label;
        parent::__construct($label, $id);
    }
}

/**
 * Class WikiIocCfgRightContainer
 * Defineix un contenidor per allotjar els items de la #zona de canvi de mode# (part dreta).
 * Està dissenyat per contenir els botons.
 *
 * @deprecated
 * @todo   PENDENT DE SUPRIMIR (COMPTE, AQUEST ES FA SERVIR)
 * @author Rafael Claver <rclaver@xtec.cat>
 */
class WikiIocCfgRightContainer extends WikiIocCfgItemsContainer {

    /**
     * @param string $label etiqueta del contenidor
     * @param string $id    id del contenidor
     */
    function __construct($label = "", $id = NULL) {
        if($id == NULL) $id = $label;
        parent::__construct($label, $id);
    }
}

/**
 * Class WikiIocCfgMetaInfoContainer
 * Defineix un contenidor per allotjar els items de la #zona de propietats# (part esquerre).
 * Està dissenyat per contenir els div de propietats.
 *
 * @deprecated
 * @todo   PENDENT DE SUPRIMIR (COMPTE, AQUEST ES FA SERVIR)
 * @author Rafael Claver <rclaver@xtec.cat>
 */
class WikiIocCfgMetaInfoContainer extends WikiIocCfgItemsContainer {
    /**
     * @param string $label etiqueta del contenidor
     * @param string $id    id del contenidor
     */
    function __construct($label = "", $id = NULL) {
        if($id == NULL) $id = $label;
        parent::__construct($label, $id);
    }
}

/**
 * Class WikiIocCfgProperty
 * Defineix un contenidor per a la zona de propietats
 *
 * @todo   [Xavier] no s'utilitza?
 * @author Rafael Claver <rclaver@xtec.cat>
 */
class WikiIocCfgProperty extends WikiIocCfgComponent {
    private $title; //és el mateix que LABEL ????? TODO[Xavier] Ho és? S'utilitza?

    /**
     * @param string $label    etiqueta del contenidor
     * @param string $id       id del contenidor
     * @param string $title    títol del contenidor
     * @param bool   $selected true si està seleccionat o false en cas contrari
     */
    function __construct($label = "", $id = NULL, $title = "", $selected = FALSE) {
        parent::__construct($label, $id);
        $this->title = $title;
        // TODO[Xavier] aquesta es la única crida que he trobat a setSelected(), i cap a isSelected(),
        // pensava que era de tipus bool, no sembla correcte que aquest mètode fasi servir un string
        $this->setSelected(($selected) ? "selected='true'" : "");
    }

    /**
     * @return string títol del contenidor
     */
    public function getTitle() {
        return $this->title;
    }
}

/**
 * Class WikiIocCfgCentralTabsContainer
 * Defineix un contenidor per allotjar els items centrals.
 *
 * Està dissenyat per contenir items com a pestanyes.
 *
 * @author Rafael Claver <rclaver@xtec.cat>
 */
class WikiIocCfgCentralTabsContainer extends WikiIocCfgItemsContainer {
    const DEFAULT_TAB_TYPE   = 0;
    const SCROLLING_TAB_TYPE = 1;

    /** @var int 0 = sense botons, amb botons per el scroll horitzontal de pestanyes */
    private $tabType;
    /** @var string conté el id de la pestanya seleccionada */
    private $tabSelected;
    private $bMenuButton;
    private $bScrollingButtons;

    /**
     * Si el valor del id es null s'utilitza el valor de la etiqueta com a id.
     *
     * @param string $label   etiqueta del contenidor
     * @param int    $tabType tipus de contenidor, referit per les constants definides en aquesta classe.
     * @param string $id      id del contenidor
     */
    public function __construct($label = "", $tabType = 0, $id = NULL) {
        if($id == NULL)
            $id = $label;
        parent::__construct($label, $id);
        $this->tabType           = $tabType;
        $this->bMenuButton       = FALSE;
        $this->bScrollingButtons = FALSE;
    }

    /**
     * Afegeix una pestanya al contenidor. Si es la primera es selecciona automàticament. Si la pestanya està
     * seleccionada s'estableix el seu index com a pestanya seleccionada a aquest contenidor.
     *
     * @param string             $id  id de la pestanya
     * @param WikiIocContentPane $tab pestanya a afegir
     *
     * @return WikiIocContentPane la pestanya afegida
     */
    function putTab($id, &$tab) { // TODO[Xavier] perquè es fa servir &?
        if(!is_array($this->items)) {
            $this->tabSelected = $id;
            $tab->setSelected(TRUE);
        } else if($tab->isSelected()) {
            $this->selectTab($id);
        }
        $ret = $this->putItem($id, $tab);
        return $ret;
    }

    /**
     * Retorna la pestanya corresponent al id.
     *
     * @param string $id de la pestanya
     *
     * @return WikiIocContentPane pestanya corresponent al id
     */
    function getTab($id) {
        return $this->getItem($id);
    }

    /**
     * Elimina la pestanya corresponent al id de aquest contenidor i la retorna.
     *
     * @param string $id id de la pestanaya
     *
     * @return WikiIocContentPane pestanya eliminada
     */
    function removeTab($id) {
        return $this->removeItem($id);
    }

    /**
     * Elimina totes les pestanyes del contenidor.
     */
    function removeAllTabs() {
        // TODO[Xavier] Error, aquest métode no retorna res.
        return $this->removeAllItems();
    }

    /**
     * Estableix el contenidor com a seleccionat.
     *
     * @param int $id id de la pestanya per establir com a seleccionada
     */
    function selectTab($id) {
        if(array_key_exists($id, $this->items)) {
            if(array_key_exists($this->tabSelected, $this->items)) {
                $this->items[$this->tabSelected]->setSelected(FALSE);
            }
            $this->tabSelected = $id;
            $this->items[$id]->setSelected(TRUE);
        }
    }

    /**
     * Retorna que tipus de contenidors de pestanyes es aquest, referit a les constants definides en aquesta classe
     *
     * @return int
     */
    function getTabType() {
        return $this->tabType;
    }

    /**
     * Estableix el tipus de contenidor de pestanyes per aquest contenidor, corresponent als valors de les constants
     * referits en aquesta classe.
     *
     * @param int $type
     */
    function setTabType($type) {
        $this->tabType = $type;
    }

    /**
     * Retorna true o false segons si té o no un botó de menú.
     *
     * @return bool
     */
    function hasMenuButton() {
        return $this->bMenuButton;
    }

    /**
     * Estableix si el contenidor te un botó de menú o no.
     *
     * @param bool $value
     */
    function setMenuButton($value) {
        $this->bMenuButton = $value;
    }

    /**
     * Retorna true o false segons si tés o no botó de scroll.
     *
     * @return bool
     */
    function hasScrollingButtons() {
        return $this->bScrollingButtons;
    }

    /**
     * Estableix si el contenidor te o no botons de scroll.
     *
     * @param bool $value
     */
    function setScrollingButtons($value) {
        $this->bScrollingButtons = $value;
    }
}

/**
 * Class WikiIocCfgHeadContainer
 *
 * @deprecated
 * @todo   PENDENT DE SUPRIMIR (COMPTE, AQUEST ES FA SERVIR)
 * @author Rafael Claver <rclaver@xtec.cat>
 */
class WikiIocCfgHeadContainer extends WikiIocCfgItemsContainer {
    /**
     * Compte, aquest mètode reb els arguments en ordre invers a la resta, primer el id i després la etiqueta.
     * TODO[Xavier] s'hauria de canviar la signatura per fer-lo concordant amb la resta de mètodes?
     *
     * @param string $id    id del contenidor
     * @param string $label etiqueta del contenidor
     */
    function __construct($id = NULL, $label = "") {
        parent::__construct($label, $id);
    }
}

/**
 * Class WikiIocCfgHeadLogo
 * Dibuixa el logo IOC.
 *
 * @author Rafael Claver <rclaver@xtec.cat>
 */
class WikiIocCfgHeadLogo extends WikiIocCfgComponent {

    /**
     * Compte, aquest mètode reb els arguments en ordre invers a la resta, primer el id i després la etiqueta.
     * TODO[Xavier] s'hauria de canviar la signatura per fer-lo concordant amb la resta de mètodes?
     *
     * @param string $id    id del component
     * @param string $label etiqueta del component
     */
    function __construct($id = NULL, $label = "") {
        parent::__construct($label, $id);
    }
}

/**
 * Class WikiIocCfgBottomContainer
 * Defineix un contenidor per escriure missatges d'informació per l'usuari.
 *
 * @author Rafael Claver <rclaver@xtec.cat>
 */
class WikiIocCfgBottomContainer extends WikiIocCfgComponent {
    private $missatge;

    /**
     * @param string $label etiqueta del contenidor
     * @param string $id    id del contenidor
     */
    public function __construct($label = "", $id = NULL) {
        if($id == NULL) $id = $label;
        parent::__construct($label, $id);
    }

    /**
     * Estableix el missatge a mostrar en el contenidor.
     *
     * @param string $msg missatge a mostrar
     */
    public function setMessage($msg) {
        $this->missatge = $msg;
    }

    /**
     * Retorna el missatge mostrat al contenidor.
     *
     * @return string missatge mostrat
     */
    public function getMessage() {
        return $this->missatge;
    }
}