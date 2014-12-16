<?php

if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', tpl_incdir());

require_once (DOKU_INC . 'inc/common.php');
require_once (DOKU_TPL_INCDIR . 'classes/WikiIocContentPage.php');
require_once (DOKU_TPL_INCDIR . 'conf/js_packages.php');

/**
 * Class WikiIocTpl
 * Aquesta classe es un Singleton, s'obté la instància amb WikiIocTpl::Instance().
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
class WikiIocTpl {
    private $aIocCfg;
    private $loginname;
    private $lang;
    private $rev;
    /** @var WikiIocContentPage */
    private $contentComponent;
    private $scriptTemplateFile;
    private $replaceInTemplateFile;

    public static function Instance() {
        static $inst = NULL;
        if($inst === NULL) {
            $inst = new WikiIocTpl();
            $inst->initialize();
        }
        return $inst;
    }

    private function __construct() {
    }

    public function initialize() {
        global $INFO;
        global $ACT;

        $INFO["isDojoLoaded"] = TRUE;
        $INFO['prependTOC']   = FALSE;
        $this->_storeLogin();
        $this->_setLanguange();
        $this->_storeRevision();

        if ($ACT === "edit" && !headers_sent()) {
            header("X-UA-Compatible: IE=EmulateIE7");
        }

        $this->contentComponent = new WikiIocContentPage();
    }

    public function getTitle() {
        global $conf;
        return tpl_pagetitle($this->contentComponent->getId(), TRUE) . " - " . hsc($conf["title"]);
    }

    public function setScriptTemplateFile($fileName, $replace) {
        $this->scriptTemplateFile    = $fileName;
        $this->replaceInTemplateFile = $replace;
        /*
         * Para incluir este trozo de código aquí y quitarlo de printHeaderTags()
         * hay que declarar la variable privada $contentTemplateFile
         * 
        if(@file_exists($this->scriptTemplateFile)) {
            $contentTemplateFile = file_get_contents($this->scriptTemplateFile);
            foreach($this->replaceInTemplateFile as $key => $value) {
                $contentTemplateFile = preg_replace('/' . $key . '/', $value, $contentTemplateFile);
            }
        }
        */
    }

    public function setBody($obj, $parms, $items) {
        $this->aIocCfg = new $obj($parms, $items);
    }

    public function printPage() {
        global $conf, $lang;
        echo "<!DOCTYPE html>\n";
        echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='" . hsc($conf["lang"]) . "' lang='" . hsc($conf["lang"]) . "' dir='" . hsc($lang["direction"]) . "'>\n";
        $this->printHeaderTags();
        echo $this->aIocCfg->getRenderingCode();
        echo "</html>";
    }

    public function printHeaderTags() {
        global $conf, $lang, $js_packages;
        echo "<head>\n";
        echo "<meta charset='utf-8'/>\n";
        echo "<meta http-equiv='X-UA-Compatible' content='IE=edge'/>\n";
        echo "<title>::" . $this->getTitle() . "::</title>\n";
        echo "<link rel='stylesheet' href='" . $js_packages["dijit"] . "/themes/claro/claro.css' />\n";
        echo "<link rel='stylesheet' href='" . $js_packages["dijit"] . "/themes/claro/document.css'/>\n";
        tpl_metaheaders();
        echo "<meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\n";

        if(!file_exists(DOKU_TPL_INCDIR . "style.ini")) {
            echo "<link rel=\"stylesheet\" media=\"all\" type=\"text/css\" href=\"" . DOKU_TPL . "bug49642.php" . ((!empty($lang["direction"]) && $lang["direction"] === "rtl") ? "?langdir=rtl" : "") . "\" />\n"; //var comes from DokuWiki core
            echo '<link rel="stylesheet" href="css/app.css" />';
        }

        $this->_userdefinedFavicon();
        $this->_userdefinedJs();

        if(file_exists(DOKU_TPL_INCDIR . "lang/" . $conf["lang"] . "/style.css")) {
            $interim = trim(file_get_contents(DOKU_TPL_INCDIR . "lang/" . $conf["lang"] . "/style.css"));
            if(!empty($interim)) {
                echo "<style type=\"text/css\" media=\"all\">\n" . hsc($interim) . "\n</style>\n";
            }
        }

        // TODO[Xavi] carreguem la llibrería ace
        echo "<script src=\"/ace-builds/src-noconflict/ace.js\"></script>";


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
     * Crida al mètode de la pàgina per renderitzar el codi, el que dispara l'event corresponent a dokuwiki.
     *
     * @todo[Xavier] no s'utilitza?
     */
    public function printContentPage() {
        $this->contentComponent->printRenderingCode();
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
        include DOKU_TPL_INCDIR . "lang/en/lang.php";
        //overwrite English language values with available translations
        if(!empty($conf["lang"]) &&
            $conf["lang"] !== "en" &&
            file_exists(DOKU_TPL_INCDIR . "/lang/" . $conf["lang"] . "/lang.php")
        ) {
            //get language file (partially translated language files are no problem
            //cause non translated stuff is still existing as English array value)
            include DOKU_TPL_INCDIR . "/lang/" . $conf["lang"] . "/lang.php";
        }
        $this->lang =& $lang;
    }

    private function _userdefinedFavicon() {
        //include default or userdefined favicon
        //
        //note: since 2011-04-22 "Rincewind RC1", there is a core function named "tpl_getFavicon()".
        //      But its functionality is not really fitting the behaviour of this template, therefore I don't use it here.
        if(file_exists(DOKU_TPL_INCDIR . "user/favicon.ico")) {
            //user defined - you might find http://tools.dynamicdrive.com/favicon/ useful to generate one
            echo "\n<link rel=\"shortcut icon\" href=\"" . DOKU_TPL . "user/favicon.ico\" />\n";
        } elseif(file_exists(DOKU_TPL_INCDIR . "user/favicon.png")) {
            //note: I do NOT recommend PNG for favicons (cause it is not supported by all browsers), but some users requested this feature.
            echo "\n<link rel=\"shortcut icon\" href=\"" . DOKU_TPL . "user/favicon.png\" />\n";
        } else {
            //default
            echo "\n<link rel=\"shortcut icon\" href=\"" . DOKU_TPL . "static/3rd/dokuwiki/favicon.ico\" />\n";
        }

        //include default or userdefined Apple Touch Icon (see <http://j.mp/sx3NMT> for details)
        if(file_exists(DOKU_TPL_INCDIR . "user/apple-touch-icon.png")) {
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
