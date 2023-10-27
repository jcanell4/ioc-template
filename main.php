<?php
/**
 * Main file of the "vector" template for DokuWiki
 * @license GPLv2 (http://www.gnu.org/licenses/gpl2.html)
 * @author  Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined('DOKU_INC')) die();
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', WikiGlobalConfig::tplIncDir());

require_once DOKU_TPL_INCDIR . 'directRequest.php';
require_once DOKU_TPL_INCDIR . 'conf/default.php';
require_once DOKU_TPL_INCDIR . 'conf/cfgIdConstants.php';
require_once DOKU_TPL_INCDIR . 'conf/mainCfg.php';
require_once DOKU_TPL_INCDIR . 'classes/WikiIocTpl.php';

$aIocCfg = WikiIocCfg::Instance()->getArrayIocCfg();

// Tratamiento de las peticiones explicitas de página a través de GET
$embebdedScript = directRequest::generateEmbebdedScript();

$tpl = WikiIocTpl::Instance();
$tpl->setEmbebdedScript($embebdedScript);
$tpl->setScriptTemplateFile($conf["ioc_pre-init-js_file"], $conf["ioc_post-init-js_file"], $aIocCfg['amd'], cfgIdConstants::getConstantsIds());
$tpl->setBody($aIocCfg['arrCfg']['class'], $aIocCfg['arrCfg']['parms'], $aIocCfg['arrCfg']['items']);
$tpl->printPage();