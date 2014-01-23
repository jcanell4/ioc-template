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
            var h = 100*(win.getBox().h-55)/win.getBox().h;
            domStyle.set(divMainContent, "height", h+"%");

            wikiIocDispatcher.containerNodeId = "@@BODY_CONTENT@@";
            wikiIocDispatcher.navegacioNodeId = "@@NAVEGACIO_NODE_ID@@";
            wikiIocDispatcher.metaInfoNodeId = "@@METAINFO_NODE_ID@@";	//dom node de la zona de meta-informació
            wikiIocDispatcher.infoNodeId = "@@INFO_NODE_ID@@";	//dom node de la zona de missatges
            wikiIocDispatcher.sectokManager.putSectok("%%ID%%", "%%SECTOK%%");
            wikiIocDispatcher.loginButtonId = '@@LOGIN_BUTTON@@';
            wikiIocDispatcher.exitButtonId = '@@EXIT_BUTTON@@';
            wikiIocDispatcher.editButtonId = '@@EDIT_BUTTON@@';
            wikiIocDispatcher.saveButtonId = '@@SAVE_BUTTON@@';
            wikiIocDispatcher.cancelButtonId = '@@CANCEL_BUTTON@@';
            wikiIocDispatcher.previewButtonId = '@@PREVIEW_BUTTON@@';
			
            var updateHandler = new UpdateViewHandler(wikiIocDispatcher);

            updateHandler.update = function(){
                var disp = this.getDispatcher();
                if(!disp.globalState.login){
                    disp.changeWidgetProperty('@@LOGIN_BUTTON@@', "visible", true);
                    disp.changeWidgetProperty('@@EXIT_BUTTON@@', "visible", false);
                    disp.changeWidgetProperty('@@NEW_BUTTON@@', "visible", false);
                    disp.changeWidgetProperty('@@EDIT_BUTTON@@', "visible", false);
                    disp.changeWidgetProperty('@@SAVE_BUTTON@@', "visible", false);
                    disp.changeWidgetProperty('@@CANCEL_BUTTON@@', "visible", false);
                    disp.changeWidgetProperty('@@PREVIEW_BUTTON@@', "visible", false);
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
                            disp.changeWidgetProperty('@@CANCEL_BUTTON@@', "visible", false);
                            disp.changeWidgetProperty('@@PREVIEW_BUTTON@@', "visible", false);
                            disp.changeWidgetProperty('@@ED_PARC_BUTTON@@', "visible", true);
                        }else if(page.action==='edit'){
                            disp.changeWidgetProperty('@@EDIT_BUTTON@@', "visible", false);
                            disp.changeWidgetProperty('@@SAVE_BUTTON@@', "visible", true);
                            disp.changeWidgetProperty('@@CANCEL_BUTTON@@', "visible", true);
                            disp.changeWidgetProperty('@@PREVIEW_BUTTON@@', "visible", true);
                            disp.changeWidgetProperty('@@ED_PARC_BUTTON@@', "visible", false);
                        }
                    }
                }
            };
            wikiIocDispatcher.addUpdateView(updateHandler);
            

            ready(function(){
                var tbContainer = registry.byId(wikiIocDispatcher.navegacioNodeId);
                tbContainer.watch("selectedChildWidget", function(name, oldTab, newTab){
					if (newTab.updateRendering)
						newTab.updateRendering();
					});

                var tab = registry.byId('@@TAB_INDEX@@');
                tab.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=page"); 
                tab.set("standbyId", wikiIocDispatcher.containerNodeId);
                wikiIocDispatcher.toUpdateSectok.push(tab);
                tab.updateSectok();

                tab = registry.byId('@@TAB_DOCU@@');
                tab.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=page"); 
                tab.set("standbyId", wikiIocDispatcher.containerNodeId);

                tab = registry.byId('@@EXIT_BUTTON@@');
                tab.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=login"); 
                //tab.set("standbyId", "loginDialog_hidden_container");

                tab = registry.byId('@@EDIT_BUTTON@@');
                tab.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=edit"); 
                tab.getQuery = function(){
                    var ns = wikiIocDispatcher.globalState.pages[wikiIocDispatcher.globalState.currentTabId]["ns"];
                    return this.query+"&id="+ns;
                };
                //tab.set("standbyId", "loginDialog_hidden_container");

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

                var centralContainer = registry.byId(wikiIocDispatcher.containerNodeId);
                centralContainer.watch("selectedChildWidget", function(name, oldTab, newTab){
                        //1. elimina els widgets corresponents a les metaInfo de la antiga pestanya
                        wikiIocDispatcher.removeAllChildrenWidgets(wikiIocDispatcher.metaInfoNodeId);
                        //2. crea els widgets corresponents a les MetaInfo de la nova pestanya seleccionada
                        var nodeMetaInfo = registry.byId(wikiIocDispatcher.metaInfoNodeId);
                        var metaContentCache = wikiIocDispatcher.getContentCache(newTab.id);
                        var m, cp;
                        /*NOTA el problema està aquí! revisa-ho*/
//                        for (m in metaContentCache) {
//                                cp = new ContentPane({
//                                                id: metaContentCache[m].id
//                                                ,title: metaContentCache[m].title
//                                                ,content: metaContentCache[m].content
//                                        });
//                                nodeMetaInfo.addChild(cp);
//                                nodeMetaInfo.resize();
//                        }
//                        wikiIocDispatcher.globalState.currentTabId=newTab.id;
                });
            });
		
    });
</script>

