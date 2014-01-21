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
     ,"ioc/wiki30/UpdateViewHandler"
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
    ], function(dom, domStyle, win, wikiIocDispatcher, registry, ready, lang, UpdateViewHandler){
                
            var divMainContent = dom.byId("@@MAIN_CONTENT@@");
//            var tab_index = '@@TAB_INDEX@@';
//            var tab_docu = '@@TAB_DOCU@@';
//            var login_dialog = '@@LOGIN_DIALOG@@';
//            var login_button = '@@LOGIN_BUTTON@@';
//            var exit_button = '@@EXIT_BUTTON@@';
//            var edit_button = '@@EDIT_BUTTON@@';
//            var new_button = '@@NEW_BUTTON@@';
//            var save_button = '@@SAVE_BUTTON@@';
//            var ed_parc_button = '@@ED_PARC_BUTTON@@';
            var h = 100*(win.getBox().h-55)/win.getBox().h;
            domStyle.set(divMainContent, "height", h+"%");

//            var wikiIocDispatcher = new Dispatcher({containerNodeId: "@@BODY_CONTENT@@"});
            wikiIocDispatcher.containerNodeId = "@@BODY_CONTENT@@";
            wikiIocDispatcher.navegacioNodeId = "@@NAVEGACIO_NODE_ID@@";
            wikiIocDispatcher.metaInfoNodeId = "@@METAINFO_NODE_ID@@";	//dom node de la zona de meta-informació
            wikiIocDispatcher.infoNodeId = "@@INFO_NODE_ID@@";	//dom node de la zona de missatges
            wikiIocDispatcher.sectokManager.putSectok("%%ID%%", "%%SECTOK%%");
//            wikiIocDispatcher.loginButtonId = '@@LOGIN_BUTTON@@';
//            wikiIocDispatcher.exitButtonId = '@@EXIT_BUTTON@@';
//            wikiIocDispatcher.editButtonId = '@@EDIT_BUTTON@@';
            var updateHandler = new UpdateViewHandler(wikiIocDispatcher);
            
            updateHandler.update = function(){
                var disp = this.getDispatcher();
                if(!disp.globalState.login){
                    disp.changeWidgetProperty('@@LOGIN_BUTTON@@', "visible", true);
                    disp.changeWidgetProperty('@@EXIT_BUTTON@@', "visible", false);
                    disp.changeWidgetProperty('@@NEW_BUTTON@@', "visible", false);
                    disp.changeWidgetProperty('@@EDIT_BUTTON@@', "visible", false);
                    disp.changeWidgetProperty('@@SAVE_BUTTON@@', "visible", false);
                    disp.changeWidgetProperty('@@ED_PARC_BUTTON@@', "visible", false);
                }else{
                    disp.changeWidgetProperty('@@LOGIN_BUTTON@@', "visible", false);
                    disp.changeWidgetProperty('@@EXIT_BUTTON@@', "visible", true);
                    disp.changeWidgetProperty('@@NEW_BUTTON@@', "visible", true);
                    if(disp.globalState.currentTabId){
                        var page = disp.globalState.pages[disp.globalState.currentTabId];
                        if(page.action==='view'){
                            disp.changeWidgetProperty('@@EDIT_BUTTON@@', "visible", true);
                            disp.changeWidgetProperty('@@SAVE_BUTTON@@', "visible", false);
                            disp.changeWidgetProperty('@@ED_PARC_BUTTON@@', "visible", true);
                        }else if(page.action==='edit'){
                            disp.changeWidgetProperty('@@EDIT_BUTTON@@', "visible", false);
                            disp.changeWidgetProperty('@@SAVE_BUTTON@@', "visible", true);
                            disp.changeWidgetProperty('@@ED_PARC_BUTTON@@', "visible", false);
                        }
                    }
                }
            };
            wikiIocDispatcher.addUpdateView(updateHandler);
            

            ready(function(){
                var tbContainer = registry.byId(wikiIocDispatcher.navegacioNodeId);
                tbContainer.watch("selectedChildWidget", function(name,oldTab,newTab){
					if (newTab.updateRendering)
						newTab.updateRendering();
					});

                tbContainer = registry.byId('@@TAB_INDEX@@');
                tbContainer.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=page"); 
                tbContainer.set("standbyId", wikiIocDispatcher.containerNodeId);
                wikiIocDispatcher.toUpdateSectok.push(tbContainer);
                tbContainer.updateSectok();

                tbContainer = registry.byId('@@TAB_DOCU@@');
                tbContainer.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=page"); 
                tbContainer.set("standbyId", wikiIocDispatcher.containerNodeId);

                tbContainer = registry.byId('@@EXIT_BUTTON@@');
                tbContainer.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=login"); 
                //tbContainer.set("standbyId", "loginDialog_hidden_container");

                tbContainer = registry.byId('@@EDIT_BUTTON@@');
                tbContainer.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=edit"); 
                tbContainer.getQuery = function(){
                    var ns = wikiIocDispatcher.globalState.pages[wikiIocDispatcher.globalState.currentTabId]["ns"];
                    return this.query+"&id="+ns;
                };
                //tbContainer.set("standbyId", "loginDialog_hidden_container");

                var loginDialog = registry.byId('@@LOGIN_DIALOG@@');
                loginDialog.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=login"); 
                loginDialog.set("standbyId", "loginDialog_hidden_container");

                loginDialog.on('hide',function(){
                    loginDialog.reset(); 
                });

                var loginCancelButton = registry.byId('@@LOGIN_DIALOG@@'+'_CancelButton');
                loginCancelButton.on('click',function(){
                    var but = registry.byId('@@LOGIN_BUTTON@@');
                    but.closeDropDown(false);
                });

				//Prova per insertar un nou element a la zona de metainformació
				//var array = {'type':'metainfo', 'value':{'docId':'id_doc3', 'id':'meta3', 'title':'títol MetaInfo3', 'content':"contingut meta informació 3"}};
				//wikiIocDispatcher.processResponse(array);
            });
		
    });
</script>

