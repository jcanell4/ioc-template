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
require_once (DOKU_TPL_INCDIR . 'conf/mainCfg.php');
require_once (DOKU_TPL_INCDIR . 'classes/WikiIocBuilderManager.php');
require_once (DOKU_TPL_INCDIR . 'classes/WikiIocTpl.php');

//  $instIoc = WikiIocCfg::Instance();
//  $aIocCfg = $instIoc->getIocCfg();
//  print "<pre>"; print_r($aIocCfg); print "</pre>";
////  print $aIocCfg['parms']['main']['parms']['items']['left']['parms']['items']['zN']['parms']['items']['parms']['items']['0']['parms']['treeDataSource'];

$instIoc = WikiIocCfg::Instance();
$aIocCfg = $instIoc->getIocCfg();

$tpl = WikiIocTpl::Instance();
$tpl->setScriptTemplateFile(DOKU_TPL_INCDIR . "html/scriptsRef.tpl", $instIoc->getArrayTpl());
$tpl->setBodyIds($instIoc->getArrayMain());

WikiIocBuilderManager::Instance()->putRequiredPackage(array("name" => "ace", "location" => "/ace/lib/ace"));

$ioc_class = $aIocCfg['class'];
$tpl->setBody($ioc_class, $aIocCfg['parms']);
$tpl->printPage();
?>
