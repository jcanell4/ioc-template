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

//check if we are running within the DokuWiki environment
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', DOKU_TPLINC.'classes/');

require_once DOKU_TPL_CLASSES."WikiIocTpl.php";

$tpl = WikiIocTpl::Instance();

// Variables
$mainContent = "mainContent";
$bodyContent = "bodyContent";
$zonaNavegacio = "zonaNavegacio"; //ojo, ojito, musho cuidadito, antes se llamaba "nav"
$zonaMetaInfo = "zonaMetaInfo";
$zonaMissatges = "zonaMissatges";
$zonaCanvi = "zonaCanvi";
$tb_index = "tb_index";
$tb_docu = "tb_docu";
$loginDialog = "loginDialog";
$loginButton = "loginButton";
$exitButton = "exitButton";

require_once(DOKU_TPL_CLASSES.'WikiIocViewComponents.php');

//$cfgTabContainer = new WikiIocCfgTabsContainer($zonaNavegacio, WikiIocTabsContainer::RESIZING_TAB_TYPE);
//$cfgTabContainer->putTab($tb_index, new WikiIocCfgTreeContainer("Índex", "lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/"));
//$cfgTabContainer->putTab("tb_perfil", new WikiIocCfgContentPane("Perfil"));
//$cfgTabContainer->putTab("tb_admin", new WikiIocCfgContentPane("Admin"));
//$cfgTabContainer->putTab($tb_docu, new WikiIocCfgContainerFromPage("documentació", ":wiki:navigation"));
//$actionTabContainer = new WikiIocTabsContainer($cfgTabContainer);

$actionTabContainer = new WikiIocTabsContainer(new WikiIocCfgTabsContainer($zonaNavegacio, WikiIocCfgTabsContainer::RESIZING_TAB_TYPE));
$actionTabContainer->putTab($tb_index, new WikiIocTreeContainer(new WikiIocCfgTreeContainer("Índex", "lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/")));
$actionTabContainer->putTab("tb_perfil", new WikiIocContentPane(new WikiIocCfgContentPane("Perfil")));
$actionTabContainer->putTab("tb_admin", new WikiIocContentPane(new WikiIocCfgContentPane("Admin")));
$actionTabContainer->putTab($tb_docu, new WikiIocContainerFromPage(new WikiIocCfgContainerFromPage("documentació", ":wiki:navigation")));
$actionTabContainer->setMenuButton(TRUE);

$blocMetaInfoContainer = new WikiIocMetaInfoContainer(new WikiIocCfgMetaInfoContainer($zonaMetaInfo));
//$blocMetaInfoContainer->putItem("project", new WikiIocProperty("pProject","pProject","PROJECT",true));
//$blocMetaInfoContainer->putItem("media", new WikiIocProperty("pMedia","pMedia","MEDIA"));
//$blocMetaInfoContainer->putItem("discussio", new WikiIocProperty("pDiscus","pDiscus","DISCUS"));
//$blocMetaInfoContainer->putItem("versions", new WikiIocProperty("pVersions","pVersions","VERSIONS"));

$cfgButtonExit = new WikiIocCfgButton("Sortir","exitButton","do=logoff",true,false,true);
$cfgButtonNew = new WikiIocCfgButton("Nou","newButton","do=new",true,true,true);
$cfgButtonSave = new WikiIocCfgButton("Desar","saveButton","do=save",true,true,true);
$cfgButtonEdit = new WikiIocCfgButton("Edició","editButton","do=edit",true,true,true);
$cfgButtonEdparc = new WikiIocCfgButton("Ed. Parc.","edparcButton","do=edparc",true,true,true);
$actionButtonExit = new WikiIocButton($cfgButtonExit);
$actionButtonNew = new WikiIocButton($cfgButtonNew);
$actionButtonSave = new WikiIocButton($cfgButtonSave);
$actionButtonEdit = new WikiIocButton($cfgButtonEdit);
$actionButtonEdparc = new WikiIocButton($cfgButtonEdparc);

$actionItemDropDownComponent = new WikiIocHiddenDialog(new WikiIocCfgHiddenDialog($loginDialog,"login"));
$actionItemDropDownComponent->putItem("name", new WikiIocFormInputField(new WikiIocCfgFormInputField("Usuari:","name","u")));
$actionItemDropDownComponent->putItem("pass", new WikiIocFormInputField(new WikiIocCfgFormInputField("Contrasenya:","pass","p","password")));

$actionDropDownButtonLogin = new WikiIocDropDownButton(new WikiIocCfgDropDownButton($loginButton,"Entrar"));
$actionDropDownButtonLogin->setAutoSize(true);
$actionDropDownButtonLogin->setDisplay(true);
$actionDropDownButtonLogin->setDisplayBlock(true);
$actionDropDownButtonLogin->setActionHidden($actionItemDropDownComponent);

$blocRightContainer = new WikiIocRightContainer(new WikiIocCfgRightContainer($zonaCanvi));
$blocRightContainer->putItem("bLogin", $actionDropDownButtonLogin);
$blocRightContainer->putItem("bNew", $actionButtonNew);
$blocRightContainer->putItem("bSave", $actionButtonSave);
$blocRightContainer->putItem("bEdit", $actionButtonEdit);
$blocRightContainer->putItem("bEditparc", $actionButtonEdparc);
$blocRightContainer->putItem("bExit", $actionButtonExit);

$blocBarraMenuContainer = new WikiDojoToolBar(new WikiDojoCfgToolBar("barra_menu_superior"));
$blocBarraMenuContainer->setPosition("fixed");
$blocBarraMenuContainer->setTopLeft(25,275);
$blocBarraMenuContainer->putItem(barVista, new WikiDojoButton(new WikiDojoCfgButton("VISTA","menu_vista","alert('VISTA')",true,false)));
$blocBarraMenuContainer->putItem(barEdicio, new WikiDojoButton(new WikiDojoCfgButton("EDICIÓ","menu_edicio","alert('EDICIO')",true,false)));
$blocBarraMenuContainer->putItem(barCorreccio, new WikiDojoButton(new WikiDojoCfgButton("CORRECCIÓ","menu_correccio","alert('CORRECCIO')",true,false)));

$blocHeadContainer = new WikiIocHeadContainer(new WikiIocCfgHeadContainer());
$blocHeadContainer->putItem("logo", new WikiIocHeadLogo(new WikiIocCfgHeadLogo()));
$blocHeadContainer->putItem($blocBarraMenuContainer->getId(), $blocBarraMenuContainer);

$blocBottomContainer = new WikiIocBottomContainer(new WikiIocCfgBottomContainer($zonaMissatges));
$blocBottomContainer->setMessage("àrea de missatges");

$actionFormProva = new WikiDojoFormContainer(new WikiDojoCfgFormContainer("formulari-prova",NULL,NULL,"relative",40,20));
$actionFormProva->setAction("commandreport");
$actionFormProva->setUrlBase("lib/plugins/ajaxcommand/ajax.php?call=");
$actionFormProva->putItem("frm_input1", new WikiIocFormInputField(new WikiIocCfgFormInputField("input 1:", "input1")));
$botoSubmit = new WikiDojoButton(new WikiDojoCfgButton("acceptar"));
//$botoSubmit->setAction("alert('Es nota que amb el ratolí hi tens la mà trencada.')");
$botoSubmit->setType("submit");
$actionFormProva->putItem("frm_button1", $botoSubmit);

$blocCentralContainer = new WikiIocCentralTabsContainer(new WikiIocCfgCentralTabsContainer($bodyContent, WikiIocCfgCentralTabsContainer::SCROLLING_TAB_TYPE));
$blocCentralContainer->setMenuButton(TRUE);
$blocCentralContainer->setScrollingButtons(TRUE);
//$blocCentralContainer->putTab("frm_prova", $actionFormProva);

if(!empty($_REQUEST["tb_container_sel"])){
    $actionTabContainer->selectTab($_REQUEST["tb_container_sel"]);
}

//Definició de les variables a reemplaçar al fitxer descrit en aquesta funció
$tpl->setScriptTemplateFile(DOKU_TPLINC."html/scriptsRef.tpl", 
		array('%%ID%%' => "ajax"
			, '%%SECTOK%%' => getSecurityToken()
			, '@@MAIN_CONTENT@@' => $mainContent
			, '@@BODY_CONTENT@@' => $bodyContent
			, '@@NAVEGACIO_NODE_ID@@' => $zonaNavegacio
			, '@@METAINFO_NODE_ID@@' => $zonaMetaInfo
			, '@@INFO_NODE_ID@@' => $zonaMissatges
			, '@@CANVI_NODE_ID@@' => $zonaCanvi
			, '@@TAB_INDEX@@'    => $tb_index
			, '@@TAB_DOCU@@'     => $tb_docu
			, '@@LOGIN_DIALOG@@' => $loginDialog
			, '@@LOGIN_BUTTON@@' => $loginButton
			, '@@EXIT_BUTTON@@' => $exitButton
		));

$tpl->setBlocSuperiorComponent($blocHeadContainer);
$tpl->setBlocCentralComponent($blocCentralContainer);
$tpl->setNavigationComponent($actionTabContainer);
$tpl->setMetaInfoComponent($blocMetaInfoContainer);
$tpl->setBlocRightComponent($blocRightContainer);
$tpl->setBlocInferiorComponent($blocBottomContainer);

$tpl->printPage();
?>
