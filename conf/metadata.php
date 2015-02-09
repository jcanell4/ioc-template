<?php

/**
 * Types of the different option values for the "vector" DokuWiki template
 *
 * Notes:
 * - In general, use the admin webinterface of DokuWiki to change config.
 * - To change/add configuration values to store, have a look at this file
 *   and the "default.php" in the same directory as this file.
 * - To change/translate the descriptions showed in the admin/configuration
 *   menu of DokuWiki, have a look at the file
 *   "/lib/tpl/vector/lang/<your lang>/settings.php". If it does not exists,
 *   copy and translate the English one. And don't forget to mail the
 *   translation to me, Andreas Haerter <development@andreas-haerter.com> :-D.
 * - To change the tab configuration, have a look at the "tabs.php" in the
 *   same directory as this file.
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
 * @link http://www.dokuwiki.org/devel:configuration
 */


//check if we are running within the DokuWiki environment
if (!defined("DOKU_INC")){
    die();
}

$meta["dojo_base"] =  array("string"); 

//startpage
$meta["ioc_template_startpage"] =  array("string"); 

//user pages
$meta["ioc_template_userpage"]    = array("onoff");
$meta["ioc_template_userpage_ns"] = array("string", "_pattern" => "/^:.{1,}:$/");

//discussion pages
$meta["ioc_template_discuss"]    = array("onoff");
$meta["ioc_template_discuss_ns"] = array("string", "_pattern" => "/^:.{1,}:$/");

//documentation
$meta["ioc_template_documentation"]  = array("onoff");
$meta["ioc_template_documentation_ns"]  = array("string", "_pattern" => "/^:.{1,}:$/");
$meta["ioc_template_documentation_translate"] = array("onoff");

//custom copyright notice
$meta["ioc_template_copyright"]    = array("onoff");
$meta["ioc_template_default"]      = array("onoff");
$meta["ioc_template_copyright_ns"] = array("string", "_pattern" => "/^:.{1,}:$/");

//array de configuració de la GUI
$meta["ioc_path_cfg_gui"] = array("string");
$meta["ioc_file_cfg_gui"] = array("string");
$meta['ioc_function_array_gui_needReset'] = array("string"); //nombre de la función del archivo cfgArray.php que indica si este archivo debe ser regenerado
$meta['ioc_function_array_gui'] = array("string"); //nombre de la función del archivo cfgArray.php que retorna el array contructor de la GUI
