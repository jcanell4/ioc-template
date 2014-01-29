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
     ,"dojo/dom-style"
     ,"dojo/dom-form"
     ,"dijit/layout/ContentPane"        
     ,"ioc/wiki30/UpdateViewHandler"
     ,"ioc/dokuwiki/dwPageUi"
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
    ], function(dom, domStyle, win, wikiIocDispatcher, registry, ready, lang, 
                    style, domForm, ContentPane, UpdateViewHandler, dwPageUi){
                
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
            wikiIocDispatcher.edParcButtonId = '@@ED_PARC_BUTTON@@';
			
            var updateHandler = new UpdateViewHandler(wikiIocDispatcher);

            updateHandler.update = function(){
                var disp = this.getDispatcher();
                var cur = wikiIocDispatcher.globalState.currentTabId; 
                if(cur){
                    style.set(cur, "overflow", "auto");
                }
                disp.changeWidgetProperty('@@LOGIN_BUTTON@@', "visible", false);
                disp.changeWidgetProperty('@@EXIT_BUTTON@@', "visible", false);
                disp.changeWidgetProperty('@@NEW_BUTTON@@', "visible", false);
                disp.changeWidgetProperty('@@EDIT_BUTTON@@', "visible", false);
                disp.changeWidgetProperty('@@SAVE_BUTTON@@', "visible", false);
                disp.changeWidgetProperty('@@CANCEL_BUTTON@@', "visible", false);
                disp.changeWidgetProperty('@@PREVIEW_BUTTON@@', "visible", false);
                disp.changeWidgetProperty('@@ED_PARC_BUTTON@@', "visible", false);

                if(!disp.globalState.login){
                    disp.changeWidgetProperty('@@LOGIN_BUTTON@@', "visible", true);
                }else{
                    disp.changeWidgetProperty('@@EXIT_BUTTON@@', "visible", true);
                    disp.changeWidgetProperty('@@NEW_BUTTON@@', "visible", true);
                    if(disp.globalState.currentTabId){
                        var page = disp.globalState.pages[disp.globalState.currentTabId];
                        if(page.action==='view'){
                            disp.changeWidgetProperty('@@EDIT_BUTTON@@', "visible", true);
                            disp.changeWidgetProperty('@@ED_PARC_BUTTON@@', "visible", true);
                        }else if(page.action==='edit'){
                            disp.changeWidgetProperty('@@SAVE_BUTTON@@', "visible", true);
                            disp.changeWidgetProperty('@@CANCEL_BUTTON@@', "visible", true);
                            disp.changeWidgetProperty('@@PREVIEW_BUTTON@@', "visible", true);
                            if(cur){
                                style.set(cur, "overflow", "hidden");
                            }
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
                var getQuery = function(){
                    var ret;
                    var ns = wikiIocDispatcher.globalState.pages[
                                wikiIocDispatcher.globalState.currentTabId]["ns"];
                    if(this.query){
                        ret = this.query+"&id="+ns;
                    }else{
                        ret = "id="+ns;
                    }
                    return ret;
                };
                tab.getQuery = getQuery;

                tab = registry.byId('@@ED_PARC_BUTTON@@');
                tab.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=edit"); 
                tab.getQuery = function(){
                    var ret;
                    var q = dwPageUi.getFormQueryToEditSection(
                            wikiIocDispatcher.globalState.getCurrentSectionId());
                    if(this.query){
                        ret = this.query+"&"+q;
                    }else{
                        ret = q;
                    }
                    return ret;
                };
                
                tab = registry.byId('@@CANCEL_BUTTON@@');
                tab.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=cancel"); 
                tab.getQuery = getQuery;

                tab = registry.byId('@@SAVE_BUTTON@@');
                tab.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=save"); 
                tab.getQuery = getQuery;
                tab.getPostData = function(){
                    return domForm.toObject("dw__editform");
                }

                var loginDialog = registry.byId('@@LOGIN_DIALOG@@');
                loginDialog.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=login"); 
                loginDialog.set("standbyId", "loginDialog_hidden_container");

                loginDialog.on('hide',function(){
                    loginDialog.reset(); 
                });

                var loginCancelButton = registry.byId('@@LOGIN_DIALOG@@'+'_CancelButton');
                loginCancelButton.on('click',function(){
                    var bt = registry.byId('@@LOGIN_BUTTON@@');
                    bt.closeDropDown(false);
                });

                var centralContainer = registry.byId(wikiIocDispatcher.containerNodeId);
                centralContainer.watch("selectedChildWidget", function(name, oldTab, newTab){
                    if (wikiIocDispatcher.getContentCache(newTab.id)){
                        var nodeMetaInfo = registry.byId(wikiIocDispatcher.metaInfoNodeId);
                        //1. elimina els widgets corresponents a les metaInfo de l'antiga pestanya
                        wikiIocDispatcher.removeAllChildrenWidgets(nodeMetaInfo);
                        //2. crea els widgets corresponents a les MetaInfo de la nova pestanya seleccionada
                        var metaContentCache = wikiIocDispatcher.getContentCache(newTab.id).getMetaData();
                        var m, cp;
                        for (m in metaContentCache) {
                            cp = new ContentPane({
                                        id: metaContentCache[m].id
                                        ,title: metaContentCache[m].title
                                        ,content: metaContentCache[m].content
                            });
                            nodeMetaInfo.addChild(cp);
                            nodeMetaInfo.resize();
                        }
                        wikiIocDispatcher.globalState.currentTabId = newTab.id;
                    }
                });
            });
    });
</script>

