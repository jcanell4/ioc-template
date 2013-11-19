<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WikiIocTpl
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */

if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', DOKU_TPLINC.'classes/');

require_once DOKU_TPL_CLASSES."WikiIocContentPage.php";

class WikiIocTpl {
    private $navigationComponent;
    private $loginname;
    private $lang;
    private $rev;
    private $contentComponent;
    private $scriptTemplateFile;
    private $replaceInTemplateFile;

//$tpl_view = "content";
//
//if (!empty($_REQUEST["view"])){
//    $tpl_view = (string)$_REQUEST["view"];
//}
//if (!empty($tpl_view) &&
//    $tpl_view !== "content" &&
//    $tpl_view !== "print" &&
//    $tpl_view !== "detail" &&
//    $tpl_view !== "discuss" &&
//    $tpl_view !== "cite"){
//    //ignore unknown values
//    $tpl_view = "content";
//}
//    
    
    /*SINGLETON CLASS*/
    public static function Instance(){
        static $inst = null;
        if ($inst === null) {
            $inst = new WikiIocTpl();
            $inst->initialize();
        }
        return $inst;
    }

    private function __construct(){
    }
    
    public function initialize(){
        global $INFO;
        
        $INFO['prependTOC'] = FALSE;
        $this->_storeLogin();
        $this->_setLanguange();
        $this->_storeRevision();
                
        //workaround for the "jumping textarea" IE bug. CSS only fix not possible cause
        //some DokuWiki JavaScript is triggering this bug, too. See the following for
        //info:
        //- <http://blog.andreas-haerter.com/2010/05/28/fix-msie-8-auto-scroll-textarea-css-width-percentage-bug>
        //- <http://msdn.microsoft.com/library/cc817574.aspx>
        if ($ACT === "edit" && !headers_sent()){
            header("X-UA-Compatible: IE=EmulateIE7");
        }
        
        $this->contentComponent = new WikiIocContentPage();
    }
    
    public function getTitle(){
        global $conf;
        return tpl_pagetitle($this->contentComponent->getId(), true)
                                                ." - ".hsc($conf["title"]); 
    }
    
    public function setScriptTemplateFile($fileName, $replace){
        $this->scriptTemplateFile=$fileName;
        $this->replaceInTemplateFile = $replace;
    }
    
    public function printHeaderTags(){
        echo "<meta charset='utf-8'/>\n";    
        echo "<meta http-equiv='X-UA-Compatible' content='IE=edge'/>\n";
        echo "<title>".$this->getTitle()."</title>\n";
        echo "<link rel='stylesheet' href='//ajax.googleapis.com/ajax/libs/dojo/1.8/dijit/themes/claro/claro.css' />\n";
        echo "<link rel='stylesheet' href='//ajax.googleapis.com/ajax/libs/dojo/1.8/dijit/themes/claro/document.css'/>\n";
        tpl_metaheaders();
        echo "<meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\n";

        //manually load needed CSS? this is a workaround for PHP Bug #49642. In some
        //version/os combinations PHP is not able to parse INI-file entries if there
        //are slashes "/" used for the keynames (see bugreport for more information:
        //<http://bugs.php.net/bug.php?id=49692>). to trigger this workaround, simply
        //delete/rename vector's style.ini.
        if (!file_exists(DOKU_TPLINC."style.ini")){
            echo  "<link rel=\"stylesheet\" media=\"all\" type=\"text/css\" href=\"".DOKU_TPL."bug49642.php".((!empty($lang["direction"]) && $lang["direction"] === "rtl") ? "?langdir=rtl" : "")."\" />\n"; //var comes from DokuWiki core
            echo '<link rel="stylesheet" href="css/app.css" />';
        }

        //include default or userdefined favicon
        //
        //note: since 2011-04-22 "Rincewind RC1", there is a core function named
        //      "tpl_getFavicon()". But its functionality is not really fitting the
        //      behaviour of this template, therefore I don't use it here.
        if (file_exists(DOKU_TPLINC."user/favicon.ico")){
            //user defined - you might find http://tools.dynamicdrive.com/favicon/
            //useful to generate one
            echo "\n<link rel=\"shortcut icon\" href=\"".DOKU_TPL."user/favicon.ico\" />\n";
        }elseif (file_exists(DOKU_TPLINC."user/favicon.png")){
            //note: I do NOT recommend PNG for favicons (cause it is not supported by
            //all browsers), but some users requested this feature.
            echo "\n<link rel=\"shortcut icon\" href=\"".DOKU_TPL."user/favicon.png\" />\n";
        }else{
            //default
            echo "\n<link rel=\"shortcut icon\" href=\"".DOKU_TPL."static/3rd/dokuwiki/favicon.ico\" />\n";
        }

        //include default or userdefined Apple Touch Icon (see <http://j.mp/sx3NMT> for
        //details)
        if (file_exists(DOKU_TPLINC."user/apple-touch-icon.png")){
            echo "<link rel=\"apple-touch-icon\" href=\"".DOKU_TPL."user/apple-touch-icon.png\" />\n";
        }else{
            //default
            echo "<link rel=\"apple-touch-icon\" href=\"".DOKU_TPL."static/3rd/dokuwiki/apple-touch-icon.png\" />\n";
        }
        
        ////load userdefined js?
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
        //}    

        //load language specific css hacks?
        if (file_exists(DOKU_TPLINC."lang/".$conf["lang"]."/style.css")){
          $interim = trim(file_get_contents(DOKU_TPLINC."lang/".$conf["lang"]."/style.css"));
          if (!empty($interim)){
              echo "<style type=\"text/css\" media=\"all\">\n".hsc($interim)."\n</style>\n";
          }
        }
        
        print '<!--[if lt IE 7]><style type="text/css">body{behavior:url("'
                .DOKU_TPL.'static/3rd/csshover.htc")}</style><![endif]-->'."\n";
        
        echo '<script>'."\n";
        echo "var dojoConfig = {\n";
        echo "    parseOnLoad:true,\n";
        echo "    async:true,\n";
        echo "    baseUrl: '/iocjslib/',\n";
        echo "    tlmSiblingOfDojo: false,\n";
        echo WikiIocBuilderManager::Instance()->getRenderingCodeForRequiredPackages();
        echo "\n};\n";
        echo '</script>'."\n";
        
        if(@file_exists($this->scriptTemplateFile)){
            $contentTemplateFile = file_get_contents($this->scriptTemplateFile);
            foreach ($this->replaceInTemplateFile as $key => $value) {
                $contentTemplateFile = preg_replace('/'.$key.'/', 
                                                    $value, 
                                                    $contentTemplateFile);
            }
            echo $contentTemplateFile;
        }

    }
    
    public function printContentPage(){
        $this->contentComponent->printRenderingCode();
    }
    
    public function setNavigationComponent(&$component){
        $this->navigationComponent=&$component;
    }
    
    private function _storeRevision(){
        //detect revision
        $this->rev = (int)$INFO["rev"]; //$INFO comes from the DokuWiki core
        if ($this->rev < 1){
            $this->rev = (int)$INFO["lastmod"];
        }
    }


    /**
     * Stores the name the current client used to login
     *
     * @var string
     * @author Andreas Haerter <development@andreas-haerter.com>
     */
    private function _storeLogin(){
        global $conf;
        $this->loginname = "";
        if (!empty($conf["useacl"])){
            if (isset($_SERVER["REMOTE_USER"]) && //no empty() but isset(): "0" may be a valid username...
                $_SERVER["REMOTE_USER"] !== ""){
                $this->loginname = $_SERVER["REMOTE_USER"]; //$INFO["client"] would not work here (-> e.g. if
                                                      //current IP differs from the one used to login)
            }
        }
    }
    
    private function _setLanguange(){
        global $conf;
        global $lang;
        //get needed language array
        include DOKU_TPLINC."lang/en/lang.php";
        //overwrite English language values with available translations
        if (!empty($conf["lang"]) &&
            $conf["lang"] !== "en" &&
            file_exists(DOKU_TPLINC."/lang/".$conf["lang"]."/lang.php")){
            //get language file (partially translated language files are no problem
            //cause non translated stuff is still existing as English array value)
            include DOKU_TPLINC."/lang/".$conf["lang"]."/lang.php";
        }
        $this->lang=&$lang;
    }

}

?>
