<?php
/**
 * Main file of the "vector" template for DokuWiki
 *
 * @license GPLv2 (http://www.gnu.org/licenses/gpl2.html)
 * @author  Josep Cañellas <jcanell4@ioc.cat>
 */

if(!defined("DOKU_INC")) die(); //check if we are running within the DokuWiki environment
if(!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', tpl_incdir() . 'classes/');

require_once tpl_incdir() . "conf/mainCfg.php";
require_once(DOKU_TPL_CLASSES . 'WikiIocBuilderManager.php');
require_once DOKU_TPL_CLASSES . "WikiIocTpl.php";

$cfg = WikiIocCfg::Instance();
$tpl = WikiIocTpl::Instance();

require_once(DOKU_TPL_CLASSES . "WikiIocComponents.php");

$actionTabContainer = new WikiIocTabsContainer($cfg->getConfig("zonaNavegacio"), WikiIocTabsContainer::RESIZING_TAB_TYPE);
$actionTabContainer->putTab($cfg->getConfig("zN_index_id"), new WikiIocTreeContainer("Índex", "lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/"));
$actionTabContainer->putTab($cfg->getConfig("zN_perfil_id"), new WikiIocContentPane("Perfil"));
$actionTabContainer->putTab($cfg->getConfig("zN_admin_id"), new WikiIocContentPane("Admin"));
$actionTabContainer->putTab($cfg->getConfig("zN_docum_id"), new WikiIocContainerFromPage("documentació", ":wiki:navigation"));
$actionTabContainer->setMenuButton(true);

$blocMetaInfoContainer = new WikiIocMetaInfoContainer($cfg->getConfig("zonaMetaInfo"));
//$blocMetaInfoContainer->putItem("project", new WikiIocProperty("pProject","pProject","PROJECT",true));
//$blocMetaInfoContainer->putItem("media", new WikiIocProperty("pMedia","pMedia","MEDIA"));
//$blocMetaInfoContainer->putItem("discussio", new WikiIocProperty("pDiscus","pDiscus","DISCUS"));
//$blocMetaInfoContainer->putItem("versions", new WikiIocProperty("pVersions","pVersions","VERSIONS"));

$actionButtonExit = new WikiIocButton("Sortir", $cfg->getConfig("exitButton"), "do=logoff", true, false, true);
$actionButtonNew = new WikiIocButton("Nou", $cfg->getConfig("newButton"), "do=new", true, false, true);
$actionButtonSave = new WikiIocButton("Desar", $cfg->getConfig("saveButton"), "do=save", true, false, true);
$actionButtonPre = new WikiIocButton("Previsualitza", $cfg->getConfig("previewButton"), "do=preview", true, false, true);
$actionButtonCancel = new WikiIocButton("Cancel·la", $cfg->getConfig("cancelButton"), "do=cancel", true, false, true);
$actionButtonEdit = new WikiIocButton("Edició", $cfg->getConfig("editButton"), "do=edit", true, false, true);
$actionButtonEdparc = new WikiIocButton("Ed. Parc.", $cfg->getConfig("edparcButton"), "", true, false, true);

$actionItemDropDownComponent = new WikiIocHiddenDialog($cfg->getConfig("loginDialog"), "login");
$actionItemDropDownComponent->putItem("name", new WikiIocFormInputField("Usuari:", "name", "u"));
$actionItemDropDownComponent->putItem("pass", new WikiIocFormInputField("Contrasenya:", "pass", "p", "password"));

$actionDropDownButtonLogin = new WikiIocDropDownButton($cfg->getConfig("loginButton"), "Entrar");
$actionDropDownButtonLogin->setAutoSize(true);
$actionDropDownButtonLogin->setDisplay(true);
$actionDropDownButtonLogin->setDisplayBlock(true);
$actionDropDownButtonLogin->setActionHidden($actionItemDropDownComponent);

$blocRightContainer = new WikiIocRightContainer($cfg->getConfig("zonaCanvi"));
$blocRightContainer->putItem("bLogin", $actionDropDownButtonLogin);
$blocRightContainer->putItem("bNew", $actionButtonNew);
$blocRightContainer->putItem("bSave", $actionButtonSave);
$blocRightContainer->putItem("bPreview", $actionButtonPre);
$blocRightContainer->putItem("bCancel", $actionButtonCancel);
$blocRightContainer->putItem("bEdit", $actionButtonEdit);
$blocRightContainer->putItem("bEditparc", $actionButtonEdparc);
$blocRightContainer->putItem("bExit", $actionButtonExit);

$blocBarraMenuContainer = new WikiDojoToolBar($cfg->getConfig("barraMenu"));
$blocBarraMenuContainer->setPosition("fixed");
$blocBarraMenuContainer->setTopLeft(28, 270);
$blocBarraMenuContainer->putItem(barVista, new WikiDojoButton("VISTA", "menu_vista", "alert('VISTA')", true, false));
$blocBarraMenuContainer->putItem(barEdicio, new WikiDojoButton("EDICIÓ", "menu_edicio", "alert('EDICIO')", true, false));
$blocBarraMenuContainer->putItem(barCorreccio, new WikiDojoButton("CORRECCIÓ", "menu_correccio", "alert('CORRECCIO')", true, false));

$blocHeadContainer = new WikiIocHeadContainer();
$blocHeadContainer->putItem("logo", new WikiIocHeadLogo());
$blocHeadContainer->putItem($blocBarraMenuContainer->getId(), $blocBarraMenuContainer);

$blocBottomContainer = new WikiIocBottomContainer($cfg->getConfig("zonaMissatges"));
$blocBottomContainer->setMessage("àrea de missatges");

$actionFormProva = new WikiDojoFormContainer("formulari-prova", null, null, "relative", 40, 20);
$actionFormProva->setAction("commandreport");
$actionFormProva->setUrlBase("lib/plugins/ajaxcommand/ajax.php?call=");
$actionFormProva->putItem("frm_input1", new WikiIocFormInputField("input 1:", "input1"));
$botoSubmit = new WikiDojoButton("acceptar");
$botoSubmit->setAction("alert('Es nota que amb el ratolí hi tens la mà trencada.')");
$botoSubmit->setType("submit");
$actionFormProva->putItem("frm_button1", $botoSubmit);

$blocCentralContainer = new WikiIocCentralTabsContainer($cfg->getConfig("bodyContent"), WikiIocCentralTabsContainer::SCROLLING_TAB_TYPE);
$blocCentralContainer->setMenuButton(true);
$blocCentralContainer->setScrollingButtons(true);
//$blocCentralContainer->putTab("frm_prova", $actionFormProva);

//Definició de les variables a reemplaçar al fitxer descrit en aquesta funció
$tpl->setScriptTemplateFile(tpl_incdir() . "html/scriptsRef.tpl", $cfg->getArrayTpl());
//$tpl->setScriptTemplateFile(tpl_incdir()."html/scriptsRef.tpl", 
//		array('%%ID%%' => "ajax"
//			, '%%SECTOK%%' => getSecurityToken()
//			, '@@MAIN_CONTENT@@' => $cfg->getConfig("mainContent")
//			, '@@BODY_CONTENT@@' => $cfg->getConfig("bodyContent")
//			, '@@NAVEGACIO_NODE_ID@@' => $cfg->getConfig("zonaNavegacio")
//			, '@@METAINFO_NODE_ID@@' => $cfg->getConfig("zonaMetaInfo")
//			, '@@INFO_NODE_ID@@' => $cfg->getConfig("zonaMissatges")
//			, '@@CANVI_NODE_ID@@' => $cfg->getConfig("zonaCanvi")
//			, '@@TAB_INDEX@@'    => $cfg->getConfig("zN_Index_id")
//			, '@@TAB_DOCU@@'     => $cfg->getConfig("zN_docum_id")
//			, '@@LOGIN_DIALOG@@' => $cfg->getConfig("loginDialog")
//			, '@@LOGIN_BUTTON@@' => $cfg->getConfig("loginButton")
//			, '@@EXIT_BUTTON@@' => $cfg->getConfig("exitButton")
//			, '@@EDIT_BUTTON@@' => $cfg->getConfig("editButton")
//		));

$tpl->setBodyIds($cfg->getArrayMain());
$tpl->setBlocSuperiorComponent($blocHeadContainer);
$tpl->setBlocCentralComponent($blocCentralContainer);
$tpl->setNavigationComponent($actionTabContainer);
$tpl->setMetaInfoComponent($blocMetaInfoContainer);
$tpl->setBlocRightComponent($blocRightContainer);
$tpl->setBlocInferiorComponent($blocBottomContainer);

/*TO DO*/
//Afegeixo manual el paquet ACE però s'ha d'automatitzar
WikiIocBuilderManager::Instance()->putRequiredPackage(
                     array("name" => "ace", "location" => "/ace/lib/ace")
);

$tpl->printPage();
?>
