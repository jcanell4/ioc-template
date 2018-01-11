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
if (!defined('DOKU_INC')) die();
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', WikiGlobalConfig::tplIncDir());

$conf['dojo_base'] = "//ajax.googleapis.com/ajax/libs/dojo/1.8/";

//startpage
$conf["ioc_template_startpage"] = "start"; //namespace to use for user page storage

//user pages
$conf["ioc_template_userpage"]    = 1; //TRUE: use/show user pages
$conf["ioc_template_userpage_ns"] = ":wiki:user:"; //namespace to use for user page storage

//discussion pages
$conf["ioc_template_discuss"]    = 1; //TRUE: use/show discussion pages
$conf["ioc_template_discuss_ns"] = ":talk:"; //namespace to use for discussion page storage

//documentation
$conf["ioc_template_documentation"]  = 1; //TRUE: use/show navigation
$conf["ioc_template_documentation_ns"]  = ":wiki:navigation"; //page/article used to store the navigation
$conf["ioc_template_documentation_translate"] = 1; //TRUE: load translated navigation if translation plugin is available (see <http://www.dokuwiki.org/

//custom copyright notice
$conf["ioc_template_copyright"]    = 1; //TRUE: use/show copyright notice
$conf["ioc_template_default"]      = 1; //TRUE: use default copyright notice (if copyright notice is enabled at all)
$conf["ioc_template_copyright_ns"] = ":wiki:copyright"; //page/article used to store a custom copyright notice

//array de configuració de la GUI
$conf["ioc_path_cfg_gui"] = DOKU_TPL_INCDIR . "conf/a_CfgGUI"; //ruta de l'arbre directoris de l'array de configuració de la GUI
$conf["ioc_file_cfg_gui"] = DOKU_TPL_INCDIR . "conf/cfgArray.php"; //nom del fitxer que conté l'array de configuració de la GUI
$conf["ioc_file_amd_gui"] = DOKU_TPL_INCDIR . "conf/amdFile.js";   //nom del fitxer que conté el codi de les sentències AMD javascript
$conf["ioc_pre-init-js_file"]  = DOKU_TPL_INCDIR . "html/pre-init.js";     //nom del fitxer que conté el pre-codi estàtic de les sentències javascript
$conf["ioc_post-init-js_file"] = DOKU_TPL_INCDIR . "html/post-init.js";    //nom del fitxer que conté el post-codi estàtic de les sentències javascript
$conf['ioc_function_array_gui_needReset'] = 'iocNeedResetArrayGUI'; //nombre de la función del archivo cfgArray.php que indica si este archivo debe ser regenerado
$conf['ioc_function_array_gui'] = 'iocArrayGUI'; //nombre de la función del archivo cfgArray.php que retorna el array contructor de la GUI
