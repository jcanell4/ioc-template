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

$blocRightContainer = new WikiIocRightContainer("zCanvi");
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
$blocBarraMenuContainer->putItem(barEdicio, new WikiDojoButton("EDICIÓ","e_dojoButton","alert('EDICIO')",true,false,1.3));
$blocBarraMenuContainer->putItem(barCorreccio, new WikiDojoButton("CORRECCIÓ","c_dojoButton","alert('CORRECCIO')",true,false));

$blocHeadContainer = new WikiIocHeadContainer();
$blocHeadContainer->putItem("logo", new WikiIocHeadLogo());
$blocHeadContainer->putItem($blocBarraMenuContainer->getId(), $blocBarraMenuContainer);

$blocBottomContainer = new WikiIocBottomContainer("area_missatges");
//$blocBottomContainer->setMessage("àrea de missateges");

//$actionFormProva = new WikiDojoFormContainer("form_proves","formproves","relative",40,0);
//$actionFormProva->putItem("idinput1", new WikiIocFormInputField("input 1:", "input_1", "input_1"));

$blocCentralContainer = new WikiIocCentralTabsContainer("bodyContent", WikiIocCentralTabsContainer::SCROLLING_TAB_TYPE);
//$blocCentralContainer->putTab("tb_cos_index", new WikiIocTreeContainer("índex", "lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/"));
//$blocCentralContainer->putTab("tb_cos_perfil", new WikiIocContentPane("perfil"));
//$blocCentralContainer->putTab("tb_cos_admin", new WikiIocContentPane("administració"));
//$blocCentralContainer->putTab("tb_cos_docu", new WikiIocContainerFromPage("documentació", ":wiki:navigation"));
$blocCentralContainer->setMenuButton(TRUE);
$blocCentralContainer->setScrollingButtons(TRUE);

if(!empty($_REQUEST["tb_container_sel"])){
    $actionTabContainer->selectTab($_REQUEST["tb_container_sel"]);
}
$tpl->setBlocSuperiorComponent($blocHeadContainer);
$tpl->setBlocCentralComponent($blocCentralContainer);
$tpl->setNavigationComponent($actionTabContainer);
$tpl->setBlocRightComponent($blocRightContainer);
$tpl->setBlocInferiorComponent($blocBottomContainer);

//Definició de les variables a reemplaçar al fitxer descrit en aquesta funció
$tpl->setScriptTemplateFile(DOKU_TPLINC."html/scriptsRef.tpl", 
		array('%%ID%%'=>"ajax"
			, '%%SECTOK%%'=>getSecurityToken()
			, '@@MAIN_CONTENT@@'=>"mainContent"
			, '@@BODY_CONTENT@@'=>"bodyContent"
		));

//? >
//<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
//<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo hsc($conf["lang"]);? >" lang="<?php echo hsc($conf["lang"]);? >" dir="<?php echo hsc($lang["direction"]);? >">
//<head>
//< ?php
//$tpl->printHeaderTags();
?>
<!--/head-->
<body id="main" class="claro">

<!-- Logo i Barra de Menú horitzontal	-->
<?php 
//echo $blocHeadContainer->getRenderingCode();
?>
	
<div id="mainContent">
	<div data-dojo-type="dijit.layout.BorderContainer" design="headline" persist="false" gutters="true" style="min-width: 1em; min-height: 1px; z-index: 0; width: 100%; height: 100%;">

		<!-- Contenidor de la part inferior -->
		<?php echo $blocBottomContainer->getRenderingCode();?>

		<!-- Bloc de contenidors de la part esquerra -->
		<div data-dojo-type="dijit.layout.ContentPane" extractContent="false" preventCache="false" preload="false" refreshOnShow="false" doLayout="true" region="left" splitter="true" minSize="150" maxSize="Infinity" style="width: 190px;" closable="false">
			<div id="tb_container" style="height: 40%;">
				<?php echo $actionTabContainer->getRenderingCode();?>
			</div>
		
			<div style="height: 60%;">
				<span data-dojo-type="dijit.layout.AccordionContainer" duration="200" persist="false" style="min-width: 1em; min-height: 1em; width: 100%; height: 100%;">
					<div data-dojo-type="dijit.layout.ContentPane" title="PROJECT" extractContent="false" preventCache="false" preload="false" refreshOnShow="false" selected="true" closable="false" doLayout="false"></div>
					<div data-dojo-type="dijit.layout.ContentPane" title="MEDIA" extractContent="false" preventCache="false" preload="false" refreshOnShow="false" closable="false" doLayout="false"></div>
					<div data-dojo-type="dijit.layout.ContentPane" title="DISCUS" extractContent="false" preventCache="false" preload="false" refreshOnShow="false" closable="false" doLayout="false"></div>
					<div data-dojo-type="dijit.layout.ContentPane" title="VERSIONS" extractContent="false" preventCache="false" preload="false" refreshOnShow="false" closable="false" doLayout="false"></div>
				</span>
			</div>
		</div>
	
		<!-- Bloc de contenidors de la part dreta -->
		<div data-dojo-type="dijit.layout.ContentPane" extractContent="false" preventCache="false" preload="false" refreshOnShow="false" doLayout="true" region="right" splitter="true" minSize="0" maxSize="Infinity" style="padding:0px; width: 80px;" closable="true">
			<?php
			echo $actionDropDownButtonLogin->getRenderingCode();
			echo $actionButtonExit->getRenderingCode();
			echo $actionButtonNew->getRenderingCode();
			echo $actionButtonSave->getRenderingCode();
			echo $actionButtonEdit->getRenderingCode();
			echo $actionButtonEdparc->getRenderingCode();
			?>
		</div>

		<!-- Bloc de contenidors central -->
		<!--<div class="ioc_content" data-dojo-type="dijit.layout.ContentPane" extractContent="false" preventCache="false" preload="false" refreshOnShow="false" region="center" splitter="false" maxSize="Infinity" doLayout="false">
			<div id="content">
				<div id="bodyContent" class="dokuwiki">
				</div>
			</div>
		</div>-->
		<?php 
		$blocCentralContainer->selectTab("tb_cos_perfil");
		echo $blocCentralContainer->getRenderingCode();
		?>

	</div>
</div>
</body>
</html>