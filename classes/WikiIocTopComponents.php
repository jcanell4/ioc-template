<?php
/**
 * Description of WikiIocTopComponents
 *      components de nivell superior
 * @author Rafael Claver
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', tpl_incdir() . 'classes/');
require_once(DOKU_TPL_CLASSES . 'WikiIocComponents.php');

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
