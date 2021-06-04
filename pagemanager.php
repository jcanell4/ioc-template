<?php
/**
 * DokuWiki Media Manager Popup
 * @author   Andreas Gohr <andi@splitbrain.org>
 * @license  GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */
if (!defined('DOKU_INC')) die();
header('X-UA-Compatible: IE=edge,chrome=1');

echo "<!DOCTYPE html>";
echo "<html lang=\"{$conf['lang']}\" dir=\"{$lang['direction']}\" class=\"popup no-js\">\n";
echo "<head>\n";
    echo "<meta charset=\"utf-8\" />\n";
    echo "<title>".hsc($lang['qb_link'])." [".strip_tags($conf['title'])."]</title>\n";
    echo "<script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>\n";
    tpl_metaheaders();
    echo "\n<meta name='viewport' content='width=device-width,initial-scale=1' />\n";
    echo tpl_favicon(array('favicon', 'mobile'));
    echo tpl_includeFile('meta.html');
echo "</head>\n";

echo "<body>\n";
    echo "<!--[if lte IE 7 ]><div id='IE7'><![endif]--><!--[if IE 8 ]><div id='IE8'><![endif]-->\n";
    echo "<div id='media__manager' class='dokuwiki'>\n";
        echo html_msgarea();
        echo "<div id='mediamgr__aside'><div class='pad'>\n";
            echo "<h1>".hsc($lang['mediaselect'])."</h1>\n";
            echo "<div id='media__opts'></div>\n";
            echo tpl_mediaTree();
        echo "</div></div>\n";

        echo "<div id='mediamgr__content'><div class='pad'>\n";
            echo tpl_pageContent();
        echo "</div></div>\n";
    echo "</div>\n";
    echo "<!--[if ( lte IE 7 | IE 8 ) ]></div><![endif]-->\n";
echo "</body>\n";
echo "</html>\n";

/**
 * prints the "main content" in the pagemanager popup
 *
 * Depending on the user's actions this may be a list of
 * files in a namespace, the meta editing dialog or
 * a message of referencing pages
 *
 * @triggers MEDIAMANAGER_CONTENT_OUTPUT
 * @param bool $fromajax - set true when calling this function via ajax
 * @param string $sort
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */
function tpl_pageContent($fromajax = false, $sort='natural') {
    global $AUTH;
    global $NS;
    global $JUMPTO;

    $do = 'filelist';

    // output the content pane, wrapped in an event.
    if (!$fromajax) ptln('<div id="media__content">');
    $data = array('do' => $do);
    $evt  = new Doku_Event('MEDIAMANAGER_CONTENT_OUTPUT', $data);
    if ($evt->advise_before()) {
        page_filelist($NS, $AUTH, $JUMPTO,false,$sort);
    }
    $evt->advise_after();
    unset($evt);
    if (!$fromajax) ptln('</div>');
}

/** List all files in a given namespace
 *
 * @param string      $ns             namespace
 * @param null|int    $auth           permission level
 * @param string      $jump           id
 * @param bool        $fullscreenview
 * @param bool|string $sort           sorting order, false skips sorting
 */
function page_filelist($ns, $auth=null, $jump='', $fullscreenview=false, $sort=false){
    global $conf;
    global $lang;
    $ns = cleanID($ns);

    if (!$fullscreenview) echo '<h1 id="page__ns">:'.hsc($ns).'</h1>'.NL;

    // check auth our self if not given (needed for ajax calls)
    if (is_null($auth)) $auth = auth_quickaclcheck("$ns:*");

    if ($auth < AUTH_READ){
        echo '<div class="nothing">'.$lang['nothingfound'].'</div>'.NL;
    }else{
        if (!$fullscreenview) {
            //Escriure un missatge
        }
        $dir = utf8_encodeFN(str_replace(':','/',$ns));
        $data = array();
        search($data, $conf['datadir'], 'search_index', array('showmsg'=>true, 'depth'=>1), $dir, 1, $sort);

        if (!count($data)){
            echo '<div class="nothing">'.$lang['nothingfound'].'</div>'.NL;
        }else {
            if ($fullscreenview) {
                echo '<ul class="' . _media_get_list_type() . '">';
            }
            foreach($data as $item){
                if (!$fullscreenview) {
                    page_printfile($item, $auth, $jump);
                }else {
                    media_printfile_thumbs($item,$auth,$jump);
                }
            }
            if ($fullscreenview) echo '</ul>'.NL;
        }
    }
}

/**
 * Formats and prints one file in the list
 *
 * @param array     $item
 * @param int       $auth              permission level
 * @param string    $jump              item id
 * @param bool      $display_namespace
 */
function page_printfile($item, $auth, $jump, $display_namespace=false){
    global $lang;

    // Prepare zebra coloring
    static $twibble = 1;
    $twibble *= -1;
    $zebra = ($twibble == -1) ? 'odd' : 'even';

    // Automatically jump to recent action
    if($jump == $item['id']) {
        $jump = ($jump == $item['id']) ? ' id="scroll__here" ' : '';
    }else{
        $jump = '';
    }

    // Prepare fileicons
    list($ext) = mimetype($item['file'],false);
    $class = preg_replace('/[^_\-a-z0-9]+/i','_',$ext);
    $class = 'select mediafile mf_'.$class;

    // Prepare filename
    $file = utf8_decodeFN($item['file']);

    // Prepare info
    $info = '';
    if ($item['isimg']){
        $info .= (int) $item['meta']->getField('File.Width');
        $info .= '&#215;';
        $info .= (int) $item['meta']->getField('File.Height');
        $info .= ' ';
    }
    $info .= hsc($item['id']);
    $info .= '<i>'.dformat($item['mtime']).'</i>';
    $info .= ' ';
    $info .= filesize_h($item['size']);

    // output
    echo '<div class="'.$zebra.'"'.$jump.' title="'.hsc($item['id']).'">'.NL;
    if (!$display_namespace) {
        echo '<a id="h_:'.$item['id'].'" class="'.$class.'">'.hsc($file).'</a> ';
    } else {
        echo '<a id="h_:'.$item['id'].'" class="'.$class.'">'.hsc($item['id']).'</a><br/>';
    }
    echo '<span class="info">('.$info.')</span>'.NL;

    // view button
    $link = ml($item['id'],'',true);
    echo ' <a href="'.$link.'" target="_blank"><img src="'.DOKU_BASE.'lib/images/magnifier.png" '.
        'alt="'.$lang['mediaview'].'" title="'.$lang['mediaview'].'" class="btn" /></a>';

//    // mediamanager button
//    $link = wl('',array('do'=>'media','image'=>$item['id'],'ns'=>getNS($item['id'])));
//    echo ' <a href="'.$link.'" target="_blank"><img src="'.DOKU_BASE.'lib/images/mediamanager.png" '.
//        'alt="'.$lang['btn_media'].'" title="'.$lang['btn_media'].'" class="btn" /></a>';
//
//    // delete button
//    if ($item['writable'] && $auth >= AUTH_DELETE){
//        $link = DOKU_BASE.'lib/exe/mediamanager.php?delete='.rawurlencode($item['id']).
//            '&amp;sectok='.getSecurityToken();
//        echo ' <a href="'.$link.'" class="btn_media_delete" title="'.$item['id'].'">'.
//            '<img src="'.DOKU_BASE.'lib/images/trash.png" alt="'.$lang['btn_delete'].'" '.
//            'title="'.$lang['btn_delete'].'" class="btn" /></a>';
//    }

//    echo '<div class="example" id="ex_'.str_replace(':','_',$item['id']).'">';
//    echo $lang['mediausage'].' <code>{{:'.$item['id'].'}}</code>';
//    echo '</div>';
//    if($item['isimg']) media_printimgdetail($item);
    echo '<div class="clearer"></div>'.NL.'</div>'.NL;
}