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
        wikiIocDispatcher.navegacioNodeId = "@@NAVEGACIO_NODE_ID@@";
		wikiIocDispatcher.metaInfoNodeId = "@@METAINFO_NODE_ID@@";	//dom node de la zona de meta-informació
        wikiIocDispatcher.infoNodeId = "@@INFO_NODE_ID@@";	//dom node de la zona de missatges
		wikiIocDispatcher.sectokManager.putSectok("%%ID%%", "%%SECTOK%%");
		
		var tab_index = '@@TAB_INDEX@@';
		var tab_docu = '@@TAB_DOCU@@';
		var login_dialog = '@@LOGIN_DIALOG@@';
		var login_button = '@@LOGIN_BUTTON@@';
		
        ready(function(){
            var tbContainer = registry.byId(wikiIocDispatcher.navegacioNodeId);
			tbContainer.watch("selectedChildWidget", function(name,oldTab,newTab){
				if (newTab.updateRendering)
					newTab.updateRendering();
			});
			
            tbContainer = registry.byId(tab_index);
            //tbContainer.set("dispatcher", wikiIocDispatcher);
            tbContainer.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=page"); 
            tbContainer.set("standbyId", wikiIocDispatcher.containerNodeId);
            wikiIocDispatcher.toUpdateSectok.push(tbContainer);
            tbContainer.updateSectok();

            tbContainer = registry.byId(tab_docu);
            //tbContainer.set("dispatcher", wikiIocDispatcher);
            tbContainer.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=page"); 
            tbContainer.set("standbyId", wikiIocDispatcher.containerNodeId);
            
            tbContainer = registry.byId("exitButton");
            //tbContainer.set("dispatcher", wikiIocDispatcher);
            tbContainer.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=login"); 
			//tbContainer.set("standbyId", "loginDialog_hidden_container");
			
			var loginDialog = registry.byId(login_dialog);
            //loginDialog.set("dispatcher", wikiIocDispatcher);
            loginDialog.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=login"); 
            loginDialog.set("standbyId", "loginDialog_hidden_container");
            
            loginDialog.on('hide',function(){
                loginDialog.reset(); 
            });
            
            var loginCancelButton = registry.byId(login_dialog+'_CancelButton');
            loginCancelButton.on('click',function(){
                var but = registry.byId(login_button);
                but.closeDropDown(false);
            });

			//Prova per insertar un nou element a la zona de metainformació
			//var array = {'type':'metainfo', 'value':{'id':'meta3', 'title':'títol MetaInfo3', 'content':"contingut meta informació 3", 'docId':'id_doc3'}};
			//wikiIocDispatcher.processResponse(array);
		});
		
    });
</script>

