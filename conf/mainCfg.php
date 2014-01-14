<?php
/**
 * Main configuration file of the "vector" template for DokuWiki
 * @author Rafael Claver <rclaver@xtec.cat>
 */

if (!defined("DOKU_INC")) die();	//check if we are running within the DokuWiki environment
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
$tb_perfil = "tb_perfil";
$tb_admin = "tb_admin";
$tb_docu = "tb_docu";
$loginDialog = "loginDialog";
$loginButton = "loginButton";
$exitButton = "exitButton";

require_once(DOKU_TPL_CLASSES.'WikiIocCfgComponents.php');

$cfgTabContainer = new WikiIocCfgTabsContainer($zonaNavegacio, WikiIocCfgTabsContainer::RESIZING_TAB_TYPE);
$cfgTabContainer->setMenuButton(true);
$cfgTabIndex = new WikiIocCfgTreeContainer("Índex", "lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/");
$cfgTabPerfil = new WikiIocCfgContentPane("Perfil");
$cfgTabAdmin = new WikiIocCfgContentPane("Admin");
$cfgTabDocu = new WikiIocCfgContainerFromPage("documentació", ":wiki:navigation");

$cfgMetaInfoContainer = new WikiIocCfgMetaInfoContainer($zonaMetaInfo);

$cfgItemDropDownComponent = new WikiIocCfgHiddenDialog($loginDialog,"login");
$cfgItemDropDownName = new WikiIocCfgFormInputField("Usuari:","name","u");
$cfgItemDropDownPass = new WikiIocCfgFormInputField("Contrasenya:","pass","p","password");

$cfgDropDownButtonLogin = new WikiIocCfgDropDownButton($loginButton,"Entrar");
$cfgDropDownButtonLogin->setAutoSize(true);
$cfgDropDownButtonLogin->setDisplay(true);
$cfgDropDownButtonLogin->setDisplayBlock(true);

$cfgButtonNew = new WikiIocCfgButton("Nou","newButton","do=new",true,true,true);
$cfgButtonSave = new WikiIocCfgButton("Desar","saveButton","do=save",true,true,true);
$cfgButtonEdit = new WikiIocCfgButton("Edició","editButton","do=edit",true,true,true);
$cfgButtonEdparc = new WikiIocCfgButton("Ed. Parc.","edparcButton","do=edparc",true,true,true);
$cfgButtonExit = new WikiIocCfgButton("Sortir","exitButton","do=logoff",true,false,true);

$cfgRightContainer = new WikiIocCfgRightContainer($zonaCanvi);

$cfgMenuBarContainer = new WikiDojoCfgToolBar("barra_menu_superior");
$cfgMenuBarContainer->setPosition("fixed");
$cfgMenuBarContainer->setTopLeft(25,275);
$cfgMenuBarButtonVista = new WikiDojoCfgButton("VISTA","menu_vista","alert('VISTA')",true,false);
$cfgMenuBarButtonEdicio = new WikiDojoCfgButton("EDICIÓ","menu_edicio","alert('EDICIO')",true,false);
$cfgMenuBarButtonCorreccio = new WikiDojoCfgButton("CORRECCIÓ","menu_correccio","alert('CORRECCIO')",true,false);

$cfgHeadContainer = new WikiIocCfgHeadContainer();
$cfgHeadLogo = new WikiIocCfgHeadLogo();

$cfgBottomContainer = new WikiIocCfgBottomContainer($zonaMissatges);
$cfgBottomContainer->setMessage("àrea de missatges");

$cfgFormProva = new WikiDojoCfgFormContainer("formulari-prova",NULL,NULL,"relative",40,20);
$cfgFormProva->setAction("commandreport");
$cfgFormProva->setUrlBase("lib/plugins/ajaxcommand/ajax.php?call=");
$cfgFormProvaInputField = new WikiIocCfgFormInputField("input 1:", "input1");
$cfgFormProvaBotoSubmit = new WikiDojoCfgButton("acceptar");
$cfgFormProvaBotoSubmit->setAction("alert('Es nota que amb el ratolí hi tens la mà trencada.')");
$cfgFormProvaBotoSubmit->setType("submit");

$cfgCentralContainer = new WikiIocCfgCentralTabsContainer($bodyContent, WikiIocCfgCentralTabsContainer::SCROLLING_TAB_TYPE);
$cfgCentralContainer->setMenuButton(TRUE);
$cfgCentralContainer->setScrollingButtons(TRUE);

if(!empty($_REQUEST["tb_container_sel"])){
    $cfgTabContainer->selectTab($_REQUEST["tb_container_sel"]);
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
?>
