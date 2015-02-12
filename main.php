<?php
/**
 * Main file of the "vector" template for DokuWiki
 *
 * @license GPLv2 (http://www.gnu.org/licenses/gpl2.html)
 * @author  Josep Cañellas <jcanell4@ioc.cat>
 */

if (!defined("DOKU_INC")) die(); //check if we are running within the DokuWiki environment
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', tpl_incdir());

require_once (DOKU_INC . 'inc/common.php');
require_once (DOKU_TPL_INCDIR . 'classes/WikiIocBuilderManager.php');
require_once (DOKU_TPL_INCDIR . 'conf/cfgIdConstants.php');
require_once (DOKU_TPL_INCDIR . 'conf/mainCfg.php');
require_once (DOKU_TPL_INCDIR . 'classes/WikiIocTpl.php');

$instIoc = WikiIocCfg::Instance();
$aIocCfg = $instIoc->LeeFicheroArray();
$first_class = $aIocCfg['class'];

$instIocConst = new cfgIdConstants();
$tpl = WikiIocTpl::Instance();
//$tpl->setScriptTemplateFile(DOKU_TPL_INCDIR . "html/scriptsRef.tpl", $instIoc->getArrayTpl());
$tpl->setScriptTemplateFile(DOKU_TPL_INCDIR . "html/scriptsRef.tpl", $instIocConst->getConstantsIds());

/* TODO: la càrrega del package ACE hauria d'anar al fitxer conf/js_packages.js */
WikiIocBuilderManager::Instance()->putRequiredPackage(array("name" => "ace-builds", "location" => "/ace-builds/src-noconflict"));

$tpl->setBody($first_class, $aIocCfg['parms'], $aIocCfg['items']);
$tpl->printPage();
?>
