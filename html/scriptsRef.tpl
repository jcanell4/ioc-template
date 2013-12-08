<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/dojo/1.8/dojo/dojo.js"></script>
<script type="text/javascript">
    require([
      "dojo/dom"
     ,"dojo/dom-style"
     ,"dojo/window"
     ,"ioc/wiki30/dispatcherSingleton"
     ,"dijit/registry"
     ,"dojo/ready"
     ,"dojo/_base/lang"
     ,"dijit/dijit"
     ,"dojo/parser"
     ,"dijit/layout/BorderContainer"
     ,"dijit/layout/ContentPane"
     ,"dijit/MenuBar"
     ,"dijit/PopupMenuBarItem"
     ,"dijit/MenuItem"
     ,"dijit/Menu"
     ,"dijit/TitlePane"
     ,"ioc/gui/ResizingTabController"
     ,"ioc/gui/IocButton"
     ,"ioc/gui/IocDropDownButton"
     ,"dijit/layout/TabContainer"
     ,"dijit/layout/AccordionContainer"
     ,"dijit/Toolbar"
     ,"dijit/layout/SplitContainer"
     ,"dijit/form/Button"
     ,"dijit/form/DropDownButton" 
     ,"dijit/TooltipDialog"
     ,"dijit/form/TextBox"
     ,"ioc/gui/IocForm"        
     ,"ioc/gui/ContentTabDokuwikiPage"
     ,"ioc/gui/ContentTabDokuwikiNsTree"
     ,"ioc/gui/ActionHiddenDialogDokuwiki"
     ,"dojo/domReady!"
    ], function(dom, domStyle, win, wikiIocDispatcher, registry, ready, lang){
        var h = 100*(win.getBox().h-55)/win.getBox().h;
        var divMainContent = dom.byId("@@MAIN_CONTENT@@");
        domStyle.set(divMainContent, "height", h+"%");
        /*
        var wikiIocDispatcher = new Dispatcher({
            containerNodeId: "bodyContent"
        });
        */
        wikiIocDispatcher.containerNodeId = "@@BODY_CONTENT@@";
        wikiIocDispatcher.sectokManager.putSectok("%%ID%%", "%%SECTOK%%");
        ready(function(){
            var tbContainer = registry.byId("nav");
			tbContainer.watch("selectedChildWidget", function(name,oldTab,newTab){
				if (newTab.updateRendering)
					newTab.updateRendering();
			});
			
            tbContainer = registry.byId("tb_docu");
            //tbContainer.set("dispatcher", wikiIocDispatcher);
            tbContainer.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=page"); 
            tbContainer.set("standbyId", "dijit_layout_ContentPane_7");
            
            tbContainer = registry.byId("tb_index");
            //tbContainer.set("dispatcher", wikiIocDispatcher);
            tbContainer.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=page"); 
            tbContainer.set("standbyId", "dijit_layout_ContentPane_7");
            wikiIocDispatcher.toUpdateSectok.push(tbContainer);
            tbContainer.updateSectok();

            tbContainer = registry.byId("loginDialog");
            //tbContainer.set("dispatcher", wikiIocDispatcher);
            tbContainer.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=login"); 
            tbContainer.set("standbyId", "loginDialog_hidden_container");
            
            tbContainer = registry.byId("exitButton");
            //tbContainer.set("dispatcher", wikiIocDispatcher);
            tbContainer.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=login"); 
//            tbContainer.set("standbyId", "loginDialog_hidden_container");
        });
    });
</script>

