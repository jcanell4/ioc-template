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
if (!defined("DOKU_INC")){
    die();
}
if(!define(DOKU_TPL_CLASSES)){
    define(DOKU_TPL_CLASSES, DOKU_TPLINC.'classes/');
}

require_once DOKU_TPL_CLASSES."WikiIocTpl.php";

$tpl = WikiIocTpl::Instance();

require_once(DOKU_TPL_CLASSES.'WikiIocActionComponents.php');
$actionTabContainer = new WikiIocActionTabContainer("nav", WikiIocActionTabContainer::RESIZING_TAB_TYPE);
$actionTabContainer->putTab("tb_index", new WikiIocActionTreeContainer("Índex", "lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/"));
$actionTabContainer->putTab("tb_perfil", new WikiIocActionContainer("Perfil"));
$actionTabContainer->putTab("tb_admin", new WikiIocActionContainer("Admin"));
$actionTabContainer->putTab("tb_docu", new WikiIocActionContainerFromPage("documentació", ":wiki:navigation"));
$actionTabContainer->setMenuButton(TRUE);
//$actionTabContainer->setScrollingButtons(TRUE);

$actionButtonExit = new WikiIocActionButton("Sortir","exitButton","do=logoff",true,false,true);
$actionButtonNew = new WikiIocActionButton("Nou","newButton","do=logoff",true,true,true);
$actionButtonSave = new WikiIocActionButton("Desar","saveButton","do=save",true,true,false);
$actionButtonEdit = new WikiIocActionButton("Edició","editButton","do=edit",true,true,true);
$actionButtonEdparc = new WikiIocActionButton("Ed. Parc.","edparcButton","do=edparc",true,true,true);

if(!empty($_REQUEST["tb_container_sel"])){
    $actionTabContainer->selectTab($_REQUEST["tb_container_sel"]);
}

$tpl->setNavigationComponent($actionTabContainer);

$tpl->setScriptTemplateFile(DOKU_TPLINC."html/scriptsRef.tpl",
array('%%ID%%' => "ajax", '%%SECTOK%%' => getSecurityToken()));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo hsc($conf["lang"]); ?>" lang="<?php echo hsc($conf["lang"]); ?>" dir="<?php echo hsc($lang["direction"]); ?>">
<head>
<?php
//show header tags
$tpl->printHeaderTags();
?>
</head>
<body id="main" class="claro">
 <div style="height: 55px; width: 100%;">
<span style="top: 2px; left: 0px; width: 240px; height: 50px; position: absolute; z-index: 900;">
    <?php
    echo '<img alt="logo" style="position: absolute; z-index: 900; top: 0px; left: 10px; height: 50px; width: 230px;" src="'.DOKU_TPL.'img/logo.png'.'"></img>';
    ?>
</span>
<span style="position: absolute; z-index: 900; top: 25px; left: 275px;">
    <span data-dojo-type="dijit.Toolbar">
        <input type="button" data-dojo-type="dijit.form.Button" tabIndex="-1" intermediateChanges="false" label="VISTA" iconClass="dijitNoIcon"></input>
        <input type="button" data-dojo-type="dijit.form.Button" tabIndex="-1" intermediateChanges="false" label="EDICIO" iconClass="dijitNoIcon"></input>
        <input type="button" data-dojo-type="dijit.form.Button" tabIndex="-1" intermediateChanges="false" label="CORREC" iconClass="dijitNoIcon"></input>
    </span>
</span>
</div>
 <div id="mainContent">
  <div data-dojo-type="dijit.layout.BorderContainer" design="headline" persist="false" gutters="true" style="min-width: 1em; min-height: 1px; z-index: 0; width: 100%; height: 100%;">
    <div data-dojo-type="dijit.layout.ContentPane" extractContent="false" preventCache="false" preload="false" refreshOnShow="false" region="bottom" splitter="true" maxSize="Infinity" style="height: 30px;" doLayout="false"></div>
    <div data-dojo-type="dijit.layout.ContentPane" extractContent="false" preventCache="false" preload="false" refreshOnShow="false" doLayout="true" region="left" splitter="true" minSize="150" maxSize="Infinity" style="width: 190px;" closable="false">
      <div id="tb_container" style="height: 40%;">
        <?php 
        echo  $actionTabContainer->getRenderingCode();   
        ?>
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
    <div data-dojo-type="dijit.layout.ContentPane" extractContent="false" preventCache="false" preload="false" refreshOnShow="false" doLayout="true" region="right" splitter="true" minSize="0" maxSize="Infinity" style="padding:0px; width: 60px;" closable="true">
        <!-- 
        <div dojoType="dijit.layout.SplitContainer" orientation="vertical"  layoutAlign="client" sizerWidth="7">-->
        <div class="wikiIocRotate">    
                <div id="loginButton" data-dojo-type="ioc.gui.IocDropDownButton" style="font-size:0.75em">
                    <span>Entrar</span>
                    <div id="loginDialog" data-dojo-type="ioc.gui.ActionHiddenDialogDokuwiki">
                    <!--
                    <div data-dojo-type="dijit.TooltipDialog">
                    -->
                        <label for="name">Usuari:</label> <input data-dojo-type="dijit.form.TextBox" id="name" name="u" /><br />
                        <label for="pass">Contrasenya:</label> <input type="password" data-dojo-type="dijit.form.TextBox" id="pass" name="p" /><br />
                        <!--
                        <button data-dojo-type="dijit.form.Button" type="submit">D'acord</button>
                        -->
                    </div>                       
                </div>
                </div>
		<?php
		echo $actionButtonExit->getRenderingCode();
        echo $actionButtonNew->getRenderingCode();
        echo $actionButtonSave->getRenderingCode();
        echo $actionButtonEdit->getRenderingCode();
        echo $actionButtonEdparc->getRenderingCode();
		?>
        <!-- </div>
        -->
    </div>
    <div class="ioc_content" data-dojo-type="dijit.layout.ContentPane" extractContent="false" preventCache="false" preload="false" refreshOnShow="false" region="center" splitter="false" maxSize="Infinity" doLayout="false">
        <div id="content">
        <div id="bodyContent" class="dokuwiki">
            <?php 
            //$tpl->printContentPage();
            ?>
            
        </div>
        </div>
    </div>
  </div>
</div>
</body>
</html>
