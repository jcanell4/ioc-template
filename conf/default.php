<?php

/**
 * Default options for the "ioc_template" DokuWiki template
 *
 * LICENSE: This file is open source software (OSS) and may be copied under
 *          certain conditions. See COPYING file for details or try to contact
 *          the author(s) of this file in doubt.
 *
 * @license GPLv2 (http://www.gnu.org/licenses/gpl2.html)
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */


//check if we are running within the DokuWiki environment
if (!defined("DOKU_INC")){
    die();
}

//user pages
$conf["ioc_template_userpage"]    = true; //TRUE: use/show user pages
$conf["ioc_template_userpage_ns"] = ":wiki:user:"; //namespace to use for user page storage

//discussion pages
$conf["ioc_template_discuss"]    = true; //TRUE: use/show discussion pages
$conf["ioc_template_discuss_ns"] = ":talk:"; //namespace to use for discussion page storage

//documentation
$conf["ioc_template_documentation"]  = true; //TRUE: use/show navigation
$conf["ioc_template_documentation_ns"]  = ":wiki:navigation"; //page/article used to store the navigation
$conf["ioc_template_documentation_translate"] = true; //TRUE: load translated navigation if translation plugin is available (see <http://www.dokuwiki.org/

//custom copyright notice
$conf["ioc_template_copyright"]    = true; //TRUE: use/show copyright notice
$conf["ioc_template_default"]      = true; //TRUE: use default copyright notice (if copyright notice is enabled at all)
$conf["ioc_template_copyright_ns"] = ":wiki:copyright"; //page/article used to store a custom copyright notice


