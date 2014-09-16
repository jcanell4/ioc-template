<?php

if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_INC')) define('DOKU_TPL_INC', tpl_incdir());
if (!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', DOKU_TPL_INC . 'classes/');
if (!defined('DOKU_TPL_CONF')) define('DOKU_TPL_CONF', DOKU_TPL_INC . 'conf/');

require_once DOKU_TPL_CLASSES . "WikiIocContentPage.php";
require_once DOKU_TPL_CONF . 'js_packages.php';
require_once DOKU_INC.'inc/common.php';

/**
 * Class WikiIocTpl
 * Aquesta classe es un Singleton, s'obté la instància amb WikiIocTpl::Instance().
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
class WikiIocTpl {
    /** @var  WikiIocHeadContainer */
    private $blocSuperiorComponent;
    /** @var  WikiIocCentralTabsContainer */
    private $blocCentralComponent;
    /** @var  WikiIocTabsContainer */
    private $navigationComponent;
    /** @var  WikiIocMetaInfoContainer */
    private $metaInfoComponent;
    /** @var  WikiIocRightContainer */
    private $blocRightComponent;
    /** @var  WikiIocBottomContainer */
    private $blocInferiorComponent;
    private $loginname;
    private $lang;
    private $rev;
    /** @var WikiIocContentPage */
    private $contentComponent;
    private $scriptTemplateFile;
    private $replaceInTemplateFile;
    private $mainID;
    private $mainContentID;
    private $tb_containerID;
    private $contentID;

    /*
    $tpl_view = "content";

    if (!empty($_REQUEST["view"])){
        $tpl_view = (string)$_REQUEST["view"];
    }
    if (!empty($tpl_view) &&
        $tpl_view !== "content" &&
        $tpl_view !== "print" &&
        $tpl_view !== "detail" &&
        $tpl_view !== "discuss" &&
        $tpl_view !== "cite"){
        //ignore unknown values
        $tpl_view = "content";
    }*/

    /**
     * Crea una nova instància o si ja existeix la retorna.
     *
     * @return WikiIocTpl
     */
    public static function Instance() {
        static $inst = NULL;
        if($inst === NULL) {
            $inst = new WikiIocTpl();
            $inst->initialize();
        }
        return $inst;
    }

    /**
     * El constructor es privat perquè es un Singleton, s'ha de fer servir WikiIocTpl::Instance()
     */
    private function __construct() {
    }

    /**
     * Inicialitza el template i estableix la pàgina que contindrà el template.
     */
    public function initialize() {
        global $INFO;
        global $ACT;

        $INFO["isDojoLoaded"] = TRUE;
        $INFO['prependTOC']   = FALSE;
        $this->_storeLogin();
        $this->_setLanguange();
        $this->_storeRevision();

        //workaround for the "jumping textarea" IE bug. CSS only fix not possible cause
        //some DokuWiki JavaScript is triggering this bug, too. See the following for
        //info:
        //- <http://blog.andreas-haerter.com/2010/05/28/fix-msie-8-auto-scroll-textarea-css-width-percentage-bug>
        //- <http://msdn.microsoft.com/library/cc817574.aspx>
        if($ACT === "edit" && !headers_sent()) {
            header("X-UA-Compatible: IE=EmulateIE7");
        }

        $this->contentComponent = new WikiIocContentPage();
    }

    /**
     * Retorna una cadena amb el títol establert a la pàgina principal concatenat amb el títol establert a la variable
     * de configuració '$conf'.
     *
     * @return string
     */
    public function getTitle() {
        global $conf;
        return tpl_pagetitle($this->contentComponent->getId(), TRUE) . " - " . hsc($conf["title"]);
    }

    /**
     * Estableix la ruta relativa al fitxer amb el script i un hash amb els valors amb els que es reemplaçaran totes
     * les coincidències del index del hash amb els continguts del fitxer pel valor del hash.
     *
     * Aquest reemplaço s'efectua al mètode WikiIocTpl::printPage().
     *
     * @param string $fileName  ruta relativa al fitxer amb el script
     * @param array  $replace   hash amb el que es substitueixen els valors del script, per exemple:
     *                          array(
     *                          '%%SECTOK%%' => getSecurityToken(),
     *                          '@@MAIN_CONTENT@@' => $mainContent)
     *                          )
     */
    public function setScriptTemplateFile($fileName, $replace) {
        $this->scriptTemplateFile    = $fileName;
        $this->replaceInTemplateFile = $replace;
    }

    /**
     * Envia a la pantalla la pàgina completa.
     */
    public function printPage() {
        global $conf, $lang;
        echo "<!DOCTYPE html>\n";
        echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='" . hsc($conf["lang"]) . "' lang='" . hsc($conf["lang"]) . "' dir='" . hsc($lang["direction"]) . "'>\n";
        $this->printHeaderTags();
        $this->printBody();
        echo "</html>";
    }

    /**
     * Envia a la pantalla les dades de la capçalera, incloent les fulles d'estíl CSS i el JavaScript.
     */
    public function printHeaderTags() {
        global $conf, $lang, $js_packages;
        echo "<head>\n";
        echo "<meta charset='utf-8'/>\n";
        echo "<meta http-equiv='X-UA-Compatible' content='IE=edge'/>\n";
        echo "<title>" . $this->getTitle() . "</title>\n";
        echo "<link rel='stylesheet' href='" . $js_packages["dijit"] . "/themes/claro/claro.css' />\n";
        echo "<link rel='stylesheet' href='" . $js_packages["dijit"] . "/themes/claro/document.css'/>\n";
        tpl_metaheaders();
        echo "<meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\n";

        //manually load needed CSS? this is a workaround for PHP Bug #49642. In some
        //version/os combinations PHP is not able to parse INI-file entries if there
        //are slashes "/" used for the keynames (see bugreport for more information:
        //<http://bugs.php.net/bug.php?id=49692>). to trigger this workaround, simply
        //delete/rename vector's style.ini.
        if(!file_exists(DOKU_TPL_INC . "style.ini")) {
            echo "<link rel=\"stylesheet\" media=\"all\" type=\"text/css\" href=\"" . DOKU_TPL . "bug49642.php" . ((!empty($lang["direction"]) && $lang["direction"] === "rtl") ? "?langdir=rtl" : "") . "\" />\n"; //var comes from DokuWiki core
            echo '<link rel="stylesheet" href="css/app.css" />';
        }

        $this->_userdefinedFavicon();
        $this->_userdefinedJs();

        //load language specific css hacks?
        if(file_exists(DOKU_TPL_INC . "lang/" . $conf["lang"] . "/style.css")) {
            $interim = trim(file_get_contents(DOKU_TPL_INC . "lang/" . $conf["lang"] . "/style.css"));
            if(!empty($interim)) {
                echo "<style type=\"text/css\" media=\"all\">\n" . hsc($interim) . "\n</style>\n";
            }
        }

        print "<!--[if lt IE 7]><style type='text/css'>body{behavior:url('" . DOKU_TPL . "static/3rd/csshover.htc')}</style><![endif]-->\n";
        echo "<script>\n";
        echo "var dojoConfig = {\n";
        echo "    parseOnLoad:true,\n";
        echo "    async:true,\n";
        echo "    baseUrl: '/iocjslib/',\n";
        echo "    tlmSiblingOfDojo: false,\n";
        echo "    locale: \"".hsc($conf["lang"])."\",\n";
        echo WikiIocBuilderManager::Instance()->getRenderingCodeForRequiredPackages();
        echo "};\n";
        echo "</script>\n";

        // Es reemplaça el contingut de la propietat scriptTemplateFile amb els valros del hash $replaceInTemplateFile
        if(@file_exists($this->scriptTemplateFile)) {
            $contentTemplateFile = file_get_contents($this->scriptTemplateFile);
            foreach($this->replaceInTemplateFile as $key => $value) {
                $contentTemplateFile = preg_replace('/' . $key . '/', $value, $contentTemplateFile);
            }
            echo $contentTemplateFile;
        }
        echo "</head>\n";
    }

    /**
     * Envia a la pantalla el cos de la pàgina.
     */
    public function printBody() {
        echo "<body id='{$this->mainID}' class='claro'>\n";

        // bloc superior: conté el logo i la #zona d'accions# (barra de menú)
        echo "<div style='height: 55px; width: 100%;'>";
        echo $this->blocSuperiorComponent->getRenderingCode();
        echo "</div>";

        echo "<div id='{$this->mainContentID}'>\n";
        echo "<div data-dojo-type='dijit.layout.BorderContainer' design='headline' persist='false' gutters='true' style='min-width:1em; min-height:1px; z-index:0; width:100%; height:100%;'>\n";

        // bloc esquerre: conté la #zona de navegació# i la #zona de propietats#
        echo "<div data-dojo-type='dijit.layout.ContentPane' extractContent='false' preventCache='false' preload='false' refreshOnShow='false' doLayout='true' region='left' splitter='true' minSize='150' maxSize='Infinity' style='width:190px;' closable='false'>\n";
        //#zona de navegació#
        echo "<div id='{$this->tb_containerID}' style='height: 40%;'>\n";
        echo $this->navigationComponent->getRenderingCode();
        echo "</div>\n";
        //#zona de meta-informació#
        echo "<div style='height: 60%;'>\n";
        echo $this->metaInfoComponent->getRenderingCode();
        echo "</div>\n";
        echo "</div>\n";

        // bloc central
        // Tant el contenidor div id='content' con la classe 'dokuwiki' del seu contenidor fill,
        // s'han creat per compatibilitat amb els continguts actuals de la wiki i els CSS assosciats
        // a aquests atributs, però no formen part original del contenidor central d'aquesta "Zona de Treball"
        echo "<div id='{$this->contentID}'>";
        echo "<div class='ioc_content dokuwiki' data-dojo-type='dijit.layout.ContentPane' extractContent='false' preventCache='false' preload='false' refreshOnShow='false' region='center' splitter='false' maxSize='Infinity' doLayout='false'>\n";
        echo $this->blocCentralComponent->getRenderingCode();
        echo "</div>\n</div>\n";

        // bloc dreta
        echo "<div data-dojo-type='dijit.layout.ContentPane' extractContent='false' preventCache='false' preload='false' refreshOnShow='false' doLayout='true' region='right' splitter='true' minSize='0' maxSize='Infinity' style='padding:0px; width:65px;' closable='true'>\n";
        echo $this->blocRightComponent->getRenderingCode();
        echo "</div>\n";

        // bloc inferior: mostra els missatges
        echo "<div data-dojo-type='dijit.layout.ContentPane' extractContent='false' preventCache='false' preload='false' refreshOnShow='false' region='bottom' splitter='true' maxSize='Infinity' style='height: 30px;' doLayout='false'>\n";
        echo $this->blocInferiorComponent->getRenderingCode();
        echo "</div>\n";

        echo "</div>\n</div>\n";
        echo "</body>\n";
    }

    /**
     * Estableix les ids del cos de la pàgina a partir del hash passat com argument.
     *
     * @param array $cfg hash amb les dades de configuració
     */
    public function setBodyIds($cfg) {
        $this->mainID         = $cfg["main"];
        $this->mainContentID  = $cfg["mainContent"];
        $this->tb_containerID = $cfg["tb_container"];
        $this->contentID      = $cfg["content"];

    }

    /**
     * Crida al mètode de la pàgina per renderitzar el codi, el que dispara l'event corresponent a dokuwiki.
     *
     * @todo[Xavier] no s'utilitza?
     */
    public function printContentPage() {
        $this->contentComponent->printRenderingCode();
    }

    /**
     * Estableix el contenidor del bloc superior.
     *
     * @param WikiIocHeadContainer $component
     */
    public function setBlocSuperiorComponent(&$component) {
        $this->blocSuperiorComponent = & $component;
    }

    /**
     * Estableix el contenidor central.
     *
     * @param WikiIocCentralTabsContainer $component
     */
    public function setBlocCentralComponent(&$component) {
        $this->blocCentralComponent = & $component;
    }

    /**
     * Estableix el contenidor amb pestanyes de navegació
     *
     * @param WikiIocTabsContainer $component
     */
    public function setNavigationComponent(&$component) {
        $this->navigationComponent = & $component;
    }

    /**
     * Estableix el contenidor amb la meta-informació del document actiu.
     *
     * @param WikiIocMetaInfoContainer $component
     */
    public function setMetaInfoComponent(&$component) {
        $this->metaInfoComponent = & $component;
    }

    /**
     * Estableix el contenidor de la dreta.
     *
     * @param WikiIocRightContainer $component
     */
    public function setBlocRightComponent(&$component) {
        $this->blocRightComponent = & $component;
    }

    /**
     * Estableix el contenidor inferior.
     *
     * @param WikiIocBottomContainer $component
     */
    public function setBlocInferiorComponent(&$component) {
        $this->blocInferiorComponent = & $component;
    }

    private function _storeRevision() {
        //detect revision
        $this->rev = (int) $INFO["rev"]; //$INFO comes from the DokuWiki core
        if($this->rev < 1) {
            $this->rev = (int) $INFO["lastmod"];
        }
    }

    private function _storeLogin() {
        /**
         * Stores the name the current client used to login
         *
         * @var string
         * @author Andreas Haerter <development@andreas-haerter.com>
         **/
        global $conf;
        $this->loginname = "";
        if(!empty($conf["useacl"])) {
            if(isset($_SERVER["REMOTE_USER"]) && //no empty() but isset(): "0" may be a valid username...
                $_SERVER["REMOTE_USER"] !== ""
            ) {
                $this->loginname = $_SERVER["REMOTE_USER"]; //$INFO["client"] would not work here (-> e.g. if current IP differs from the one used to login)
            }
        }
    }

    private function _setLanguange() {
        global $conf, $lang;
        //get needed language array
        include DOKU_TPL_INC . "lang/en/lang.php";
        //overwrite English language values with available translations
        if(!empty($conf["lang"]) &&
            $conf["lang"] !== "en" &&
            file_exists(DOKU_TPL_INC . "/lang/" . $conf["lang"] . "/lang.php")
        ) {
            //get language file (partially translated language files are no problem
            //cause non translated stuff is still existing as English array value)
            include DOKU_TPL_INC . "/lang/" . $conf["lang"] . "/lang.php";
        }
        $this->lang =& $lang;
    }

    private function _userdefinedFavicon() {
        //include default or userdefined favicon
        //
        //note: since 2011-04-22 "Rincewind RC1", there is a core function named "tpl_getFavicon()".
        //      But its functionality is not really fitting the behaviour of this template, therefore I don't use it here.
        if(file_exists(DOKU_TPL_INC . "user/favicon.ico")) {
            //user defined - you might find http://tools.dynamicdrive.com/favicon/ useful to generate one
            echo "\n<link rel=\"shortcut icon\" href=\"" . DOKU_TPL . "user/favicon.ico\" />\n";
        } elseif(file_exists(DOKU_TPL_INC . "user/favicon.png")) {
            //note: I do NOT recommend PNG for favicons (cause it is not supported by all browsers), but some users requested this feature.
            echo "\n<link rel=\"shortcut icon\" href=\"" . DOKU_TPL . "user/favicon.png\" />\n";
        } else {
            //default
            echo "\n<link rel=\"shortcut icon\" href=\"" . DOKU_TPL . "static/3rd/dokuwiki/favicon.ico\" />\n";
        }

        //include default or userdefined Apple Touch Icon (see <http://j.mp/sx3NMT> for details)
        if(file_exists(DOKU_TPL_INC . "user/apple-touch-icon.png")) {
            echo "<link rel=\"apple-touch-icon\" href=\"" . DOKU_TPL . "user/apple-touch-icon.png\" />\n";
        } else {
            //default
            echo "<link rel=\"apple-touch-icon\" href=\"" . DOKU_TPL . "static/3rd/dokuwiki/apple-touch-icon.png\" />\n";
        }
    }

    private function _userdefinedJs() {
        /*/load userdefined js?
        //if (tpl_getConf("vector_loaduserjs")){
        //    echo "<script type=\"text/javascript\" charset=\"utf-8\" src=\"".DOKU_TPL."user/user.js\"></script>\n";
        //}
        //
        ////show printable version?
        //if ($tpl_view === "print"){
        //  //note: this is just a workaround for people searching for a print version.
        //  //      don't forget to update the styles.ini, this is the really important
        //  //      thing! BTW: good text about this: http://is.gd/5MyG5
        //  echo  "<link rel=\"stylesheet\" media=\"all\" type=\"text/css\" href=\"".DOKU_TPL."static/3rd/dokuwiki/print.css\" />\n"
        //       ."<link rel=\"stylesheet\" media=\"all\" type=\"text/css\" href=\"".DOKU_TPL."static/css/print.css\" />\n"
        //       ."<link rel=\"stylesheet\" media=\"all\" type=\"text/css\" href=\"".DOKU_TPL."user/print.css\" />\n";
        //} */
    }
}