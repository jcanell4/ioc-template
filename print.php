<?php
/**
 * Main file of the "vector" template for DokuWiki
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
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', tpl_incdir());

//detect revision
$rev = (int)$INFO["rev"]; //$INFO comes from the DokuWiki core
if ($rev < 1){
    $rev = (int)$INFO["lastmod"];
}

if ($ACT === "edit" &&
    !headers_sent()){
    header("X-UA-Compatible: IE=EmulateIE7");
}

include DOKU_TPL_INCDIR.'pre_print.php';
tpl_content(FALSE);
include DOKU_TPL_INCDIR.'post_print.php';

