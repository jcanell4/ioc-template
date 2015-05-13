<?php
/**
 * Main file of the "vector" template for DokuWiki
 *
 * @license GPLv2 (http://www.gnu.org/licenses/gpl2.html)
 * @author  Josep Cañellas <jcanell4@ioc.cat>
 */

if (!defined("DOKU_INC")) die(); //check if we are running within the DokuWiki environment
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', tpl_incdir());

require_once (DOKU_TPL_INCDIR . 'conf/default.php');
require_once (DOKU_TPL_INCDIR . 'conf/cfgIdConstants.php');
require_once (DOKU_TPL_INCDIR . 'conf/mainCfg.php');
require_once (DOKU_TPL_INCDIR . 'classes/WikiIocTpl.php');

$aIocCfg = WikiIocCfg::Instance()->getArrayIocCfg();

$tpl = WikiIocTpl::Instance();
$tpl->setScriptTemplateFile($conf["ioc_pre-init-js_file"], $conf["ioc_post-init-js_file"], $aIocCfg['amd'], cfgIdConstants::getConstantsIds());
$tpl->setBody($aIocCfg['arrCfg']['class'], $aIocCfg['arrCfg']['parms'], $aIocCfg['arrCfg']['items']);
$tpl->printPage();
/*
require_once (DOKU_INC . 'lib/plugins/wikiiocmodel/DokuModelAdapter.php');
$inst = new DokuModelAdapter();
$inst->setPagePermission('fp:dam:m03', 'manolo', AUTH_DELETE, false);
*/
?>
