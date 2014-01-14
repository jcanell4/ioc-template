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
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */

if (!defined("DOKU_INC")) die();	//check if we are running within the DokuWiki environment
if (!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', DOKU_TPLINC.'classes/');

require_once DOKU_TPLINC."conf/mainCfg.php";
require_once(DOKU_TPL_CLASSES.'WikiIocViewComponents.php');

$actionTabContainer = new WikiIocTabsContainer($cfgTabContainer);
$actionTabContainer->putTab($tb_index, new WikiIocTreeContainer($cfgTabIndex));
$actionTabContainer->putTab($tb_perfil, new WikiIocContentPane($cfgTabPerfil));
$actionTabContainer->putTab($tb_admin, new WikiIocContentPane($cfgTabAdmin));
$actionTabContainer->putTab($tb_docu, new WikiIocContainerFromPage($cfgTabDocu));

$blocMetaInfoContainer = new WikiIocMetaInfoContainer(new WikiIocCfgMetaInfoContainer($zonaMetaInfo));
//$blocMetaInfoContainer->putItem("project", new WikiIocProperty("pProject","pProject","PROJECT",true));
//$blocMetaInfoContainer->putItem("media", new WikiIocProperty("pMedia","pMedia","MEDIA"));
//$blocMetaInfoContainer->putItem("discussio", new WikiIocProperty("pDiscus","pDiscus","DISCUS"));
//$blocMetaInfoContainer->putItem("versions", new WikiIocProperty("pVersions","pVersions","VERSIONS"));

$actionItemDropDownComponent = new WikiIocHiddenDialog($cfgItemDropDownComponent);
$actionItemDropDownComponent->putItem("name", new WikiIocFormInputField($cfgItemDropDownName));
$actionItemDropDownComponent->putItem("pass", new WikiIocFormInputField($cfgItemDropDownPass));

$actionDropDownButtonLogin = new WikiIocDropDownButton($cfgDropDownButtonLogin);
$actionDropDownButtonLogin->setActionHidden($actionItemDropDownComponent);

$actionButtonNew = new WikiIocButton($cfgButtonNew);
$actionButtonSave = new WikiIocButton($cfgButtonSave);
$actionButtonEdit = new WikiIocButton($cfgButtonEdit);
$actionButtonEdparc = new WikiIocButton($cfgButtonEdparc);
$actionButtonExit = new WikiIocButton($cfgButtonExit);

$blocRightContainer = new WikiIocRightContainer($cfgRightContainer);
$blocRightContainer->putItem("bLogin", $actionDropDownButtonLogin);
$blocRightContainer->putItem("bNew", $actionButtonNew);
$blocRightContainer->putItem("bSave", $actionButtonSave);
$blocRightContainer->putItem("bEdit", $actionButtonEdit);
$blocRightContainer->putItem("bEditparc", $actionButtonEdparc);
$blocRightContainer->putItem("bExit", $actionButtonExit);

$blocMenuBarContainer = new WikiDojoToolBar($cfgMenuBarContainer);
$blocMenuBarContainer->setPosition("fixed");
$blocMenuBarContainer->setTopLeft(25,275);
$blocMenuBarContainer->putItem(barVista, new WikiDojoButton($cfgMenuBarButtonVista));
$blocMenuBarContainer->putItem(barEdicio, new WikiDojoButton($cfgMenuBarButtonEdicio));
$blocMenuBarContainer->putItem(barCorreccio, new WikiDojoButton($cfgMenuBarButtonCorreccio));

$blocHeadContainer = new WikiIocHeadContainer($cfgHeadContainer);
$blocHeadContainer->putItem("logo", new WikiIocHeadLogo($cfgHeadLogo));
$blocHeadContainer->putItem($blocMenuBarContainer->getId(), $blocMenuBarContainer);

$blocBottomContainer = new WikiIocBottomContainer($cfgBottomContainer);

$actionFormProva = new WikiDojoFormContainer($cfgFormProva);
$actionFormProva->putItem("frm_input1", new WikiIocFormInputField($cfgFormProvaInputField));
$actionFormProvaBotoSubmit = new WikiDojoButton($cfgFormProvaBotoSubmit);
$actionFormProva->putItem("frm_button1", $actionFormProvaBotoSubmit);

$blocCentralContainer = new WikiIocCentralTabsContainer($cfgCentralContainer);
//$blocCentralContainer->putTab("frm_prova", $actionFormProva);

if(!empty($_REQUEST["tb_container_sel"])){
    $actionTabContainer->selectTab($_REQUEST["tb_container_sel"]);
}

$tpl->setBlocSuperiorComponent($blocHeadContainer);
$tpl->setBlocCentralComponent($blocCentralContainer);
$tpl->setNavigationComponent($actionTabContainer);
$tpl->setMetaInfoComponent($blocMetaInfoContainer);
$tpl->setBlocRightComponent($blocRightContainer);
$tpl->setBlocInferiorComponent($blocBottomContainer);

$tpl->printPage();
?>
