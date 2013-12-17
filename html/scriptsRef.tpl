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
	 ,"dojo/_base/array"
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
     ,"dijit/TooltipDialog"
     ,"dijit/form/TextBox"
     ,"ioc/gui/IocForm"        
     ,"ioc/gui/ContentTabDokuwikiPage"
     ,"ioc/gui/ContentTabDokuwikiNsTree"
     ,"ioc/gui/ActionHiddenDialogDokuwiki"
     ,"dojo/domReady!"
    ], function(dom, domStyle, win, wikiIocDispatcher, registry, ready, lang, array){
        var h = 100*(win.getBox().h-55)/win.getBox().h;
        var divMainContent = dom.byId("@@MAIN_CONTENT@@");
        domStyle.set(divMainContent, "height", h+"%");
        /*
        var wikiIocDispatcher = new Dispatcher({
            containerNodeId: "bodyContent"
        });
        */
        wikiIocDispatcher.containerNodeId = "@@BODY_CONTENT@@";
        wikiIocDispatcher.infoNodeId = "@@INFO_NODE_ID@@";	//dom node de la zona de missatges
		wikiIocDispatcher.tab_index = '@@TAB_INDEX@@';
		wikiIocDispatcher.tab_docu = '@@TAB_DOCU@@';
		wikiIocDispatcher.login_dialog = '@@LOGIN_DIALOG@@';
		wikiIocDispatcher.login_button = '@@LOGIN_BUTTON@@';

		wikiIocDispatcher.sectokManager.putSectok("%%ID%%", "%%SECTOK%%");
		
        ready(function(){
            var tbContainer = registry.byId("nav");
			tbContainer.watch("selectedChildWidget", function(name,oldTab,newTab){
				if (newTab.updateRendering)
					newTab.updateRendering();
			});
			
            tbContainer = registry.byId(wikiIocDispatcher.tab_index);
            //tbContainer.set("dispatcher", wikiIocDispatcher);
            tbContainer.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=page"); 
            tbContainer.set("standbyId", wikiIocDispatcher.containerNodeId);
            wikiIocDispatcher.toUpdateSectok.push(tbContainer);
            tbContainer.updateSectok();

            tbContainer = registry.byId(wikiIocDispatcher.tab_docu);
            //tbContainer.set("dispatcher", wikiIocDispatcher);
            tbContainer.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=page"); 
            tbContainer.set("standbyId", wikiIocDispatcher.containerNodeId);
            
            tbContainer = registry.byId("exitButton");
            //tbContainer.set("dispatcher", wikiIocDispatcher);
            tbContainer.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=login"); 
			//tbContainer.set("standbyId", "loginDialog_hidden_container");
			
			var loginDialog = registry.byId(wikiIocDispatcher.login_dialog);
            //loginDialog.set("dispatcher", wikiIocDispatcher);
            loginDialog.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=login"); 
            loginDialog.set("standbyId", "loginDialog_hidden_container");
            
			loginDialog.on('hide',function(){
				var node = dom.byId(wikiIocDispatcher.login_dialog+'_form');
				node.reset();
			});

			var loginCancelButton = registry.byId(wikiIocDispatcher.login_dialog+'_CancelButton');
			loginCancelButton.on('click',function(){
				var node = dom.byId(wikiIocDispatcher.login_dialog);
	            domStyle.set(node, "display", "none");
			});

        });
    });
</script>

