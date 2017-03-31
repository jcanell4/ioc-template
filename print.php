<?php

/**
 * Main file of the "vector" template for DokuWiki
 *
 *
 * LICENSE: This file is open source software (OSS) and may be copied under
 *          certain conditions. See COPYING file for details or try to contact
 *          the author(s) of this file in doubt.
 *
 * @license GPLv2 (http://www.gnu.org/licenses/gpl2.html)
 * @author Andreas Haerter <development@andreas-haerter.com>
 * @link http://andreas-haerter.com/projects/dokuwiki-template-vector
 * @link http://www.dokuwiki.org/template:vector
 * @link http://www.dokuwiki.org/devel:templates
 * @link http://www.dokuwiki.org/devel:coding_style
 * @link http://www.dokuwiki.org/devel:environment
 * @link http://www.dokuwiki.org/devel:action_modes
 */


//check if we are running within the DokuWiki environment
if (!defined("DOKU_INC")){
    die();
}


/**
 * Stores the template wide action
 *
 * Different DokuWiki actions requiring some template logic. Therefore the
 * template has to know, what we are doing right now - and that is what this
 * var is for.
 *
 * Please have a look at the "detail.php" file in the same folder, it is also
 * influencing the var's value.
 *
 * @var string
 * @author Andreas Haerter <development@andreas-haerter.com>
 */
$vector_action = "print";

/**
 * Stores the template wide context
 *
 * This template offers discussion pages via common articles, which should be
 * marked as "special". DokuWiki does not know any "special" articles, therefore
 * we have to take care about detecting if the current page is a discussion
 * page or not.
 *
 * @var string
 * @author Andreas Haerter <development@andreas-haerter.com>
 */
$vector_context = "article";
if (preg_match("/^".tpl_getConf("vector_discuss_ns")."?$|^".tpl_getConf("vector_discuss_ns").".*?$/i", ":".getNS(getID()))){
    $vector_context = "discuss";
}


/**
 * Stores the name the current client used to login
 *
 * @var string
 * @author Andreas Haerter <development@andreas-haerter.com>
 */
$loginname = "";
if (!empty($conf["useacl"])){
    if (isset($_SERVER["REMOTE_USER"]) && //no empty() but isset(): "0" may be a valid username...
        $_SERVER["REMOTE_USER"] !== ""){
        $loginname = $_SERVER["REMOTE_USER"]; //$INFO["client"] would not work here (-> e.g. if
                                              //current IP differs from the one used to login)
    }
}


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


//detect revision
$rev = (int)$INFO["rev"]; //$INFO comes from the DokuWiki core
if ($rev < 1){
    $rev = (int)$INFO["lastmod"];
}

/*
//get tab config
include DOKU_TPLINC."/conf/tabs.php";  //default
if (file_exists(DOKU_TPLINC."/user/tabs.php")){
    include DOKU_TPLINC."/user/tabs.php"; //add user defined
}


//get boxes config
include DOKU_TPLINC."/conf/boxes.php"; //default
if (file_exists(DOKU_TPLINC."/user/boxes.php")){
    include DOKU_TPLINC."/user/boxes.php"; //add user defined
}


//get button config
include DOKU_TPLINC."/conf/buttons.php"; //default
if (file_exists(DOKU_TPLINC."/user/buttons.php")){
    include DOKU_TPLINC."/user/buttons.php"; //add user defined
}


/**
 * Helper to render the tabs (like a dynamic XHTML snippet)
 *
 * @param array The tab data to render within the snippet. Each element
 *        is represented through a subarray:
 *        $array = array("tab1" => array("text"     => "hello world!",
 *                                       "href"     => "http://www.example.com"
 *                                       "nofollow" => true),
 *                       "tab2" => array("text"  => "I did it again",
 *                                       "href"  => DOKU_BASE."doku.php?id=foobar",
 *                                       "class" => "foobar-css"),
 *                       "tab3" => array("text"  => "I did it again and again",
 *                                       "href"  => wl("start", false, false, "&"),
 *                                       "class" => "foobar-css"),
 *                       "tab4" => array("text"      => "Home",
 *                                       "wiki"      => ":start"
 *                                       "accesskey" => "H"));
 *        Available keys within the subarrays:
 *        - "text" (mandatory)
 *          The text/label of the element.
 *        - "href" (optional)
 *          URL the element should point to (as link). Please submit raw,
 *          unencoded URLs, the encoding will be done by this function for
 *          security reasons. If the URL is not relative
 *          (= starts with http(s)://), the URL will be treated as external
 *          (=a special style will be used if "class" is not set).
 *        - "wiki" (optional)
 *          ID of a WikiPage to link (like ":start" or ":wiki:foobar").
 *        - "class" (optional)
 *          Name of an additional CSS class to use for the element content.
 *          Works only in combination with "text" or "href", NOT with "wiki"
 *          (will be ignored in this case).
 *        - "nofollow" (optional)
 *          If set to TRUE, rel="nofollow" will be added to the link if "href"
 *          is set (otherwise this flag will do nothing).
 *        - "accesskey" (optional)
 *          accesskey="<value>" will be added to the link if "href" is set
 *          (otherwise this option will do nothing).
 * @author Andreas Haerter <development@andreas-haerter.com>
 * @see _vector_renderButtons()
 * @see _vector_renderBoxes()
 * @link http://www.wikipedia.org/wiki/Nofollow
 * @link http://de.selfhtml.org/html/verweise/tastatur.htm#kuerzel
 * @link http://www.dokuwiki.org/devel:environment
 * @link http://www.dokuwiki.org/devel:coding_style
 *//*
function _vector_renderTabs($arr)
{
    //is there something useful?
    if (empty($arr) ||
        !is_array($arr)){
        return false; //nope, break operation
    }

    //array to store the created tabs into
    $elements = array();

    //handle the tab data
    foreach($arr as $li_id => $element){
        //basic check
        if (empty($element) ||
            !is_array($element) ||
            !isset($element["text"]) ||
            (empty($element["href"]) &&
             empty($element["wiki"]))){
            continue; //ignore invalid stuff and go on
        }
        $li_created = true; //flag to control if we created any list element
        $interim = "";
        //do we have an external link?
        if (!empty($element["href"])){
            //add URL
            $interim = "<a href=\"".hsc($element["href"])."\""; //@TODO: real URL encoding
            //add rel="nofollow" attribute to the link?
            if (!empty($element["nofollow"])){
                $interim .= " rel=\"nofollow\"";
            }
            //mark external link?
            if (substr($element["href"], 0, 4) === "http" ||
                substr($element["href"], 0, 3) === "ftp"){
                $interim .= " class=\"urlextern\"";
            }
            //add access key?
            if (!empty($element["accesskey"])){
                $interim .= " accesskey=\"".hsc($element["accesskey"])."\" title=\"[ALT+".hsc(strtoupper($element["accesskey"]))."]\"";
            }
            $interim .= "><span>".hsc($element["text"])."</span></a>";
        //internal wiki link
        }else if (!empty($element["wiki"])){
            $interim = "<a href=\"".hsc(wl(cleanID($element["wiki"])))."\"><span>".hsc($element["text"])."</span></a>";
        }
        //store it
        $elements[] = "\n        <li id=\"".hsc($li_id)."\"".(!empty($element["class"])
                                                             ? " class=\"".hsc($element["class"])."\""
                                                             : "").">".$interim."</li>";
    }

    //show everything created
    if (!empty($elements)){
        foreach ($elements as $element){
            echo $element;
        }
    }
    return true;
}


/**
 * Helper to render the boxes (like a dynamic XHTML snippet)
 *
 * @param array The box data to render within the snippet. Each box is
 *        represented through a subarray:
 *        $array = array("box-id1" => array("headline" => "hello world!",
 *                                          "xhtml"    => "I am <i>here</i>."));
 *        Available keys within the subarrays:
 *        - "xhtml" (mandatory)
 *          The content of the Box you want to show as XHTML. Attention: YOU
 *          HAVE TO TAKE CARE ABOUT FILTER EVENTUALLY USED INPUT/SECURITY. Be
 *          aware of XSS and stuff.
 *        - "headline" (optional)
 *          Headline to show above the box. Leave empty/do not set for none.
 * @author Andreas Haerter <development@andreas-haerter.com>
 * @see _vector_renderButtons()
 * @see _vector_renderTabs()
 * @link http://www.wikipedia.org/wiki/Nofollow
 * @link http://www.wikipedia.org/wiki/Cross-site_scripting
 * @link http://www.dokuwiki.org/devel:coding_style
 *//*
function _vector_renderBoxes($arr)
{
    //is there something useful?
    if (empty($arr) ||
        !is_array($arr)){
        return false; //nope, break operation
    }

    //array to store the created boxes into
    $boxes = array();

    //handle the box data
    foreach($arr as $div_id => $contents){
        //basic check
        if (empty($contents) ||
            !is_array($contents) ||
            !isset($contents["xhtml"])){
            continue; //ignore invalid stuff and go on
        }
        $interim  = "  <div id=\"".hsc($div_id)."\" class=\"portal\">\n";
        if (isset($contents["headline"])
            && $contents["headline"] !== ""){
            $interim .= "    <h5>".hsc($contents["headline"])."</h5>\n";
        }
        $interim .= "    <div class=\"body\">\n"
                   ."      <div class=\"dokuwiki\">\n" //dokuwiki CSS class needed cause we might have to show rendered page content
                   .$contents["xhtml"]."\n"
                   ."      </div>\n"
                   ."    </div>\n"
                   ."  </div>\n";
        //store it
        $boxes[] = $interim;
    }
    //show everything created
    if (!empty($boxes)){
        echo  "\n";
        foreach ($boxes as $box){
            echo $box;
        }
        echo  "\n";
    }

    return true;
}


/**
 * Helper to render the footer buttons (like a dynamic XHTML snippet)
 *
 * @param array The button data to render within the snippet. Each element
 *        is represented through a subarray:
 *        $array = array("btn1" => array("img"      => DOKU_TPL."static/img/button-vector.png",
 *                                       "href"     => "http://andreas-haerter.com/projects/dokuwiki-template-vector",
 *                                       "width"    => 80,
 *                                       "height"   => 15,
 *                                       "title"    => "vector for DokuWiki",
 *                                       "nofollow" => false),
 *                       "btn2" => array("img"   => DOKU_TPL."user/mybutton1.png",
 *                                       "href"  => wl("start", false, false, "&")),
 *                       "btn3" => array("img"   => DOKU_TPL."user/mybutton2.png",
 *                                       "href"  => "http://www.example.com");
 *        Available keys within the subarrays:
 *        - "img" (mandatory)
 *          The relative or full path of an image/button to show. Users may
 *          place own images within the /user/ dir of this template.
 *        - "href" (mandatory)
 *          URL the element should point to (as link). Please submit raw,
 *          unencoded URLs, the encoding will be done by this function for
 *          security reasons.
 *        - "width" (optional)
 *          width="<value>" will be added to the image tag if both "width" and
 *          "height" are set (otherwise, this will be ignored).
 *        - "height" (optional)
 *          height="<value>" will be added to the image tag if both "height" and
 *          "width" are set (otherwise, this will be ignored).
 *        - "nofollow" (optional)
 *          If set to TRUE, rel="nofollow" will be added to the link.
 *        - "title" (optional)
 *          title="<value>"  will be added to the link and image if "title"
 *          is set + alt="<value>".
 * @author Andreas Haerter <development@andreas-haerter.com>
 * @see _vector_renderButtons()
 * @see _vector_renderBoxes()
 * @link http://www.wikipedia.org/wiki/Nofollow
 * @link http://www.dokuwiki.org/devel:coding_style
 *//*
function _vector_renderButtons($arr)
{
    //array to store the created buttons into
    $elements = array();

    //handle the button data
    foreach($arr as $li_id => $element){
        //basic check
        if (empty($element) ||
            !is_array($element) ||
            !isset($element["img"]) ||
            !isset($element["href"])){
            continue; //ignore invalid stuff and go on
        }
        $interim = "";

        //add URL
        $interim = "<a href=\"".hsc($element["href"])."\""; //@TODO: real URL encoding
        //add rel="nofollow" attribute to the link?
        if (!empty($element["nofollow"])){
            $interim .= " rel=\"nofollow\"";
        }
        //add title attribute to the link?
        if (!empty($element["title"])){
            $interim .= " title=\"".hsc($element["title"])."\"";
        }
        $interim .= " target=\"_blank\"><img src=\"".hsc($element["img"])."\"";
        //add width and height attribute to the image?
        if (!empty($element["width"]) &&
            !empty($element["height"])){
            $interim .= " width=\"".(int)$element["width"]."\" height=\"".(int)$element["height"]."\"";
        }
        //add title and alt attribute to the image?
        if (!empty($element["title"])){
            $interim .= " title=\"".hsc($element["title"])."\" alt=\"".hsc($element["title"])."\"";
        } else {
            $interim .= " alt=\"\""; //alt is a mandatory attribute for images
        }
        $interim .= " border=\"0\" /></a>";

        //store it
        $elements[] = "      ".$interim."\n";
    }

    //show everything created
    if (!empty($elements)){
        echo  "\n";
        foreach ($elements as $element){
            echo $element;
        }
    }
    return true;
}
*/

//workaround for the "jumping textarea" IE bug. CSS only fix not possible cause
//some DokuWiki JavaScript is triggering this bug, too. See the following for
//info:
//- <http://blog.andreas-haerter.com/2010/05/28/fix-msie-8-auto-scroll-textarea-css-width-percentage-bug>
//- <http://msdn.microsoft.com/library/cc817574.aspx>
if ($ACT === "edit" &&
    !headers_sent()){
    header("X-UA-Compatible: IE=EmulateIE7");
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo hsc($conf["lang"]); ?>" lang="<?php echo hsc($conf["lang"]); ?>" dir="<?php echo hsc($lang["direction"]); ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php tpl_pagetitle(); echo " - ".hsc($conf["title"]); ?></title>
<?php
//show meta-tags
global $ACT;
$ACT = "show";

tpl_metaheaders();
echo "<meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />";

//manually load needed CSS? this is a workaround for PHP Bug #49642. In some
//version/os combinations PHP is not able to parse INI-file entries if there
//are slashes "/" used for the keynames (see bugreport for more information:
//<http://bugs.php.net/bug.php?id=49692>). to trigger this workaround, simply
//delete/rename vector's style.ini.
if (!file_exists(DOKU_TPLINC."style.ini")){
    echo  "<link rel=\"stylesheet\" media=\"all\" type=\"text/css\" href=\"".DOKU_TPL."bug49642.php".((!empty($lang["direction"]) && $lang["direction"] === "rtl") ? "?langdir=rtl" : "")."\" />\n"; //var comes from DokuWiki core
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

//load userdefined js?
if (tpl_getConf("vector_loaduserjs")){
    echo "<script type=\"text/javascript\" charset=\"utf-8\" src=\"".DOKU_TPL."user/user.js\"></script>\n";
}

//note: this is just a workaround for people searching for a print version.
//      don't forget to update the styles.ini, this is the really important
//      thing! BTW: good text about this: http://is.gd/5MyG5
echo  "<link rel=\"stylesheet\" media=\"all\" type=\"text/css\" href=\"".DOKU_TPL."css/print.css\" />\n"
     /*."<link rel=\"stylesheet\" media=\"all\" type=\"text/css\" href=\"".DOKU_TPL."static/css/print.css\" />\n"
     ."<link rel=\"stylesheet\" media=\"all\" type=\"text/css\" href=\"".DOKU_TPL."user/print.css\" />\n"*/;

//load language specific css hacks?
if (file_exists(DOKU_TPLINC."lang/".$conf["lang"]."/style.css")){
  $interim = trim(file_get_contents(DOKU_TPLINC."lang/".$conf["lang"]."/style.css"));
  if (!empty($interim)){
      echo "<style type=\"text/css\" media=\"all\">\n".hsc($interim)."\n</style>\n";
  }
}
?>
<!--[if lt IE 7]><style type="text/css">body{behavior:url("<?php echo DOKU_TPL; ?>static/3rd/vector/csshover.htc")}</style><![endif]-->
</head>
<body class="mediawiki ltr capitalize-all-nouns ns-0 ns-subject skin-vector">
<div id="page-container">
<div id="page-base" class="noprint"></div>
<div id="head-base" class="noprint"></div>

<!-- start div id=content -->
<div id="content">
  <a name="top" id="top"></a>
  <a name="dokuwiki__top" id="dokuwiki__top"></a>

  <!-- start main content area -->
  <?php
  //show messages (if there are any)
  html_msgarea();
  ?>

  <!-- start div id bodyContent -->
  <div id="bodyContent" class="dokuwiki">
    <!-- start rendered wiki content -->
    <?php
//    //flush the buffer for faster page rendering, heaviest content follows
//    if (function_exists("tpl_flush")) {
//        tpl_flush(); //exists since 2010-11-07 "Anteater"...
//    } else {
//        flush(); //...but I won't loose compatibility to 2009-12-25 "Lemming" right now.
//    }
    tpl_content(FALSE);
    ?>
    <!-- end rendered wiki content -->
    <div class="clearer"></div>
  </div>
  <!-- end div id bodyContent -->
</div>
<!-- end div id=content -->
<?php
//provide DokuWiki housekeeping, required in all templates
tpl_indexerWebBug();

//include web analytics software
if (file_exists(DOKU_TPLINC."/user/tracker.php")){
    include DOKU_TPLINC."/user/tracker.php";
}
?>
</body>
</html>
