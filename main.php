<?php
/**
 * Main file of the "vector" template for DokuWiki
 *
 * @license GPLv2 (http://www.gnu.org/licenses/gpl2.html)
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */

if (!defined("DOKU_INC")) die();	//check if we are running within the DokuWiki environment
if (!defined('DOKU_TPL_CFG')) define('DOKU_TPL_CFG', tpl_incdir().'conf/');
if (!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', tpl_incdir().'classes/');

require_once DOKU_TPL_CFG."mainCfg.php";
require_once DOKU_TPL_CLASSES."WikiIocTpl.php";

$tpl = WikiIocTpl::Instance();

// Variables
$mainContent = "mainContent";
$bodyContent = "bodyContent";
$zonaAccions = "zonaAccions"; 
$zonaNavegacio = "zonaNavegacio"; //ojo, ojito, musho cuidadito, antes se llamaba "nav"
$zonaMetaInfo = "zonaMetaInfo";
$zonaMissatges = "zonaMissatges";
$zonaCanvi = "zonaCanvi";
$barraMenu = "barraMenu";
$tb_index = "tb_index";
$tb_perfil = "tb_perfil";
$tb_admin = "tb_admin";
$tb_docu = "tb_docu";
$loginDialog = "loginDialog";
$loginButton = "loginButton";
$exitButton = "exitButton";

require_once(DOKU_TPL_CLASSES.'WikiIocComponents.php');

$actionTabContainer = new WikiIocTabsContainer($zonaNavegacio, WikiIocTabsContainer::RESIZING_TAB_TYPE);
$actionTabContainer->putTab($tb_index, new WikiIocTreeContainer("Índex", "lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/"));
$actionTabContainer->putTab($tb_perfil, new WikiIocContentPane("Perfil"));
$actionTabContainer->putTab($tb_admin, new WikiIocContentPane("Admin"));
$actionTabContainer->putTab($tb_docu, new WikiIocContainerFromPage("documentació", ":wiki:navigation"));
$actionTabContainer->setMenuButton(TRUE);

$blocMetaInfoContainer = new WikiIocMetaInfoContainer($zonaMetaInfo);
//$blocMetaInfoContainer->putItem("project", new WikiIocProperty("pProject","pProject","PROJECT",true));
//$blocMetaInfoContainer->putItem("media", new WikiIocProperty("pMedia","pMedia","MEDIA"));
//$blocMetaInfoContainer->putItem("discussio", new WikiIocProperty("pDiscus","pDiscus","DISCUS"));
//$blocMetaInfoContainer->putItem("versions", new WikiIocProperty("pVersions","pVersions","VERSIONS"));

$actionButtonExit = new WikiIocButton("Sortir","exitButton","do=logoff",true,false,true);
$actionButtonNew = new WikiIocButton("Nou","newButton","do=new",true,true,true);
$actionButtonSave = new WikiIocButton("Desar","saveButton","do=save",true,true,true);
$actionButtonEdit = new WikiIocButton("Edició","editButton","do=edit",true,true,true);
$actionButtonEdparc = new WikiIocButton("Ed. Parc.","edparcButton","do=edparc",true,true,true);

$actionItemDropDownComponent = new WikiIocHiddenDialog($loginDialog,"login");
$actionItemDropDownComponent->putItem("name", new WikiIocFormInputField("Usuari:","name","u"));
$actionItemDropDownComponent->putItem("pass", new WikiIocFormInputField("Contrasenya:","pass","p","password"));

$actionDropDownButtonLogin = new WikiIocDropDownButton($loginButton,"Entrar");
$actionDropDownButtonLogin->setAutoSize(true);
$actionDropDownButtonLogin->setDisplay(true);
$actionDropDownButtonLogin->setDisplayBlock(true);
$actionDropDownButtonLogin->setActionHidden($actionItemDropDownComponent);

$blocRightContainer = new WikiIocRightContainer($zonaCanvi);
$blocRightContainer->putItem("bLogin", $actionDropDownButtonLogin);
$blocRightContainer->putItem("bNew", $actionButtonNew);
$blocRightContainer->putItem("bSave", $actionButtonSave);
$blocRightContainer->putItem("bEdit", $actionButtonEdit);
$blocRightContainer->putItem("bEditparc", $actionButtonEdparc);
$blocRightContainer->putItem("bExit", $actionButtonExit);

$blocBarraMenuContainer = new WikiDojoToolBar($barraMenu);
$blocBarraMenuContainer->setPosition("fixed");
$blocBarraMenuContainer->setTopLeft(25,275);
$blocBarraMenuContainer->putItem(barVista, new WikiDojoButton("VISTA","menu_vista","alert('VISTA')",true,false));
$blocBarraMenuContainer->putItem(barEdicio, new WikiDojoButton("EDICIÓ","menu_edicio","alert('EDICIO')",true,false));
$blocBarraMenuContainer->putItem(barCorreccio, new WikiDojoButton("CORRECCIÓ","menu_correccio","alert('CORRECCIO')",true,false));

$blocHeadContainer = new WikiIocHeadContainer();
$blocHeadContainer->putItem("logo", new WikiIocHeadLogo());
$blocHeadContainer->putItem($blocBarraMenuContainer->getId(), $blocBarraMenuContainer);

$blocBottomContainer = new WikiIocBottomContainer(new WikiIocCfgBottomContainer($zonaMissatges));
$blocBottomContainer->setMessage("àrea de missatges");

$actionFormProva = new WikiDojoFormContainer("formulari-prova",NULL,NULL,"relative",40,20);
$actionFormProva->setAction("commandreport");
$actionFormProva->setUrlBase("lib/plugins/ajaxcommand/ajax.php?call=");
$actionFormProva->putItem("frm_input1", new WikiIocFormInputField("input 1:", "input1"));
$botoSubmit = new WikiDojoButton("acceptar");
//$botoSubmit->setAction("alert('Es nota que amb el ratolí hi tens la mà trencada.')");
$botoSubmit->setType("submit");
$actionFormProva->putItem("frm_button1", $botoSubmit);

$blocCentralContainer = new WikiIocCentralTabsContainer($bodyContent, WikiIocCentralTabsContainer::SCROLLING_TAB_TYPE);
$blocCentralContainer->setMenuButton(TRUE);
$blocCentralContainer->setScrollingButtons(TRUE);
//$blocCentralContainer->putTab("frm_prova", $actionFormProva);

if(!empty($_REQUEST["tb_container_sel"])){
    $actionTabContainer->selectTab($_REQUEST["tb_container_sel"]);
}

//Definició de les variables a reemplaçar al fitxer descrit en aquesta funció
$tpl->setScriptTemplateFile(tpl_incdir()."html/scriptsRef.tpl", 
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
