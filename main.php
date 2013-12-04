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

require_once(DOKU_TPL_CLASSES.'WikiIocComponents.php');
$actionTabContainer = new WikiIocTabsContainer("nav", WikiIocTabsContainer::RESIZING_TAB_TYPE);
$actionTabContainer->putTab("tb_index", new WikiIocTreeContainer("Índex", "lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/"));
$actionTabContainer->putTab("tb_perfil", new WikiIocContentPane("Perfil"));
$actionTabContainer->putTab("tb_admin", new WikiIocContentPane("Admin"));
$actionTabContainer->putTab("tb_docu", new WikiIocContainerFromPage("documentació", ":wiki:navigation"));
$actionTabContainer->setMenuButton(TRUE);

$blocPropertiesContainer = new WikiIocPropertiesContainer("zonaPropietats");
$blocPropertiesContainer->putItem("project", new WikiIocProperty("pProject","pProject","PROJECT",true));
$blocPropertiesContainer->putItem("media", new WikiIocProperty("pMedia","pMedia","MEDIA"));
$blocPropertiesContainer->putItem("discussio", new WikiIocProperty("pDiscus","pDiscus","DISCUS"));
$blocPropertiesContainer->putItem("versions", new WikiIocProperty("pVersions","pVersions","VERSIONS"));

$actionButtonExit = new WikiIocButton("Sortir","exitButton","do=logoff",true,false,true);
$actionButtonNew = new WikiIocButton("Nou","newButton","do=new",true,true,true);
$actionButtonSave = new WikiIocButton("Desar","saveButton","do=save",true,true,true);
$actionButtonEdit = new WikiIocButton("Edició","editButton","do=edit",true,true,true);
$actionButtonEdparc = new WikiIocButton("Ed. Parc.","edparcButton","do=edparc",true,true,true);

$actionItemDropDownComponent = new WikiIocHiddenDialog("login","login");
$actionItemDropDownComponent->putItem("name", new WikiIocFormInputField("Usuari:","name","u"));
$actionItemDropDownComponent->putItem("pass", new WikiIocFormInputField("Contrasenya:","pass","p"));

$actionDropDownButtonLogin = new WikiIocDropDownButton("Entrar","login");
$actionDropDownButtonLogin->setAutoSize(true);
$actionDropDownButtonLogin->setDisplay(true);
$actionDropDownButtonLogin->setDisplayBlock(true);
$actionDropDownButtonLogin->setActionHidden($actionItemDropDownComponent);

$blocRightContainer = new WikiIocRightContainer("zonaCanvi");
$blocRightContainer->putItem("bLogin", $actionDropDownButtonLogin);
$blocRightContainer->putItem("bExit", $actionButtonExit);
$blocRightContainer->putItem("bNew", $actionButtonNew);
$blocRightContainer->putItem("bSave", $actionButtonSave);
$blocRightContainer->putItem("bEdit", $actionButtonEdit);
$blocRightContainer->putItem("bEditparc", $actionButtonEdparc);

$blocBarraMenuContainer = new WikiDojoToolBar("barra_menu_superior");
$blocBarraMenuContainer->setPosition("fixed");
$blocBarraMenuContainer->setTopLeft(25,275);
$blocBarraMenuContainer->putItem(barVista, new WikiDojoButton("VISTA","v_dojoButton","alert('VISTA')",true,false));
$blocBarraMenuContainer->putItem(barEdicio, new WikiDojoButton("EDICIÓ","e_dojoButton","alert('EDICIO')",true,false));
$blocBarraMenuContainer->putItem(barCorreccio, new WikiDojoButton("CORRECCIÓ","c_dojoButton","alert('CORRECCIO')",true,false));

$blocHeadContainer = new WikiIocHeadContainer();
$blocHeadContainer->putItem("logo", new WikiIocHeadLogo());
$blocHeadContainer->putItem($blocBarraMenuContainer->getId(), $blocBarraMenuContainer);

$blocBottomContainer = new WikiIocBottomContainer("zonaMissatges");
$blocBottomContainer->setMessage("àrea de missatges");

$actionFormProva = new WikiDojoFormContainer("form_proves","formproves","relative",40,0);
$actionFormProva->putItem("frm_input1", new WikiIocFormInputField("input 1:", "input_1", "input_1"));
$actionFormProva->putItem("frm_button1", new WikiIocButton("acceptar","acceptar","do=info"));

$blocCentralContainer = new WikiIocCentralTabsContainer("bodyContent", WikiIocCentralTabsContainer::SCROLLING_TAB_TYPE);
$blocCentralContainer->setMenuButton(TRUE);
$blocCentralContainer->setScrollingButtons(TRUE);
$blocCentralContainer->putTab("frm_prova", $actionFormProva);

if(!empty($_REQUEST["tb_container_sel"])){
    $actionTabContainer->selectTab($_REQUEST["tb_container_sel"]);
}

//Definició de les variables a reemplaçar al fitxer descrit en aquesta funció
$tpl->setScriptTemplateFile(DOKU_TPLINC."html/scriptsRef.tpl", 
		array('%%ID%%'=>"ajax"
			, '%%SECTOK%%'=>getSecurityToken()
			, '@@MAIN_CONTENT@@'=>"mainContent"
			, '@@BODY_CONTENT@@'=>"bodyContent"
		));

$tpl->setBlocSuperiorComponent($blocHeadContainer);
$tpl->setBlocCentralComponent($blocCentralContainer);
$tpl->setNavigationComponent($actionTabContainer);
$tpl->setPropertiesComponent($blocPropertiesContainer);
$tpl->setBlocRightComponent($blocRightContainer);
$tpl->setBlocInferiorComponent($blocBottomContainer);

$tpl->printPage();
?>
