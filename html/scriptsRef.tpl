<script type="text/javascript" src="@@DOJO_SOURCE@@/dojo.js"></script>
<script type="text/javascript">
require([
    "dojo/dom",
    "dojo/dom-style",
    "dojo/window",
    "ioc/wiki30/dispatcherSingleton",
    "ioc/wiki30/Request",
    "dijit/registry",
    "dojo/ready",
    "dojo/dom-style",
    "dojo/dom-form",
    "dijit/layout/ContentPane",
    "ioc/wiki30/UpdateViewHandler",
    "ioc/dokuwiki/dwPageUi",
    "ioc/wiki30/ReloadStateHandler",
    'dojo/_base/unload',
    "dojo/json",
    "dojo/_base/lang",
    "ioc/wiki30/GlobalState",
    "ioc/wiki30/processor/ErrorMultiFunctionProcessor",
    "dijit/form/Button",
    "dojo/parser",
    "dijit/layout/BorderContainer",
    "dijit/MenuBar",
    "dijit/PopupMenuBarItem",
    "dijit/MenuItem",
    "dijit/Menu",
    "dijit/TitlePane",
    "ioc/gui/ResizingTabController",
    "ioc/gui/IocButton",
    "ioc/gui/IocDropDownButton",
    "ioc/gui/IocMenuItem",
    "dijit/layout/TabContainer",
    "dijit/layout/AccordionContainer",
    "dijit/Toolbar",
    "dijit/layout/SplitContainer",
    "dijit/TooltipDialog",
    "dijit/form/TextBox",
    "ioc/gui/IocForm",
    "ioc/gui/ContentTabDokuwikiPage",
    "ioc/gui/ContentTabDokuwikiNsTree",
    "ioc/gui/ActionHiddenDialogDokuwiki",
    "dojo/domReady!"
], function (dom, domStyle, win, wikiIocDispatcher, Request, registry, ready, 
                style, domForm, ContentPane, UpdateViewHandler, dwPageUi, 
                ReloadStateHandler, unload, JSON, lang, globalState, 
                ErrorMultiFunctionProcessor) {
    //declaració de funcions

    var divMainContent = dom.byId("@@MAIN_CONTENT@@");
    if (!divMainContent) {
        return;
    }
    
    var h = 100 * (win.getBox().h - 55) / win.getBox().h;
    domStyle.set(divMainContent, "height", h + "%");

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
    wikiIocDispatcher.userButtonId = '@@USER_BUTTON@@';


    // TODO[Xavi] es pot passar la funció següent com el constructor.
    var updateHandler = new UpdateViewHandler();

    updateHandler.update = function () {
        var disp = wikiIocDispatcher;
        var cur = disp.getGlobalState().currentTabId;
        if (cur) {
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
        disp.changeWidgetProperty('@@USER_BUTTON@@', "visible", false);
        
        if (!disp.getGlobalState().login) {
            disp.changeWidgetProperty('@@LOGIN_BUTTON@@', "visible", true);
        } else {
            //disp.changeWidgetProperty('@@EXIT_BUTTON@@', "visible", true);
            disp.changeWidgetProperty('@@NEW_BUTTON@@', "visible", true);
            disp.changeWidgetProperty('@@USER_BUTTON@@', "visible", true);
            
            if (disp.getGlobalState().currentTabId) {
                var page = disp.getGlobalState().pages[disp.getGlobalState().currentTabId];
                if (page.action === 'view') {
                    disp.changeWidgetProperty('@@EDIT_BUTTON@@', "visible", true);
                    disp.changeWidgetProperty('@@ED_PARC_BUTTON@@', "visible", true);
                } else if (page.action === 'edit') {
                    disp.changeWidgetProperty('@@SAVE_BUTTON@@', "visible", true);
                    disp.changeWidgetProperty('@@CANCEL_BUTTON@@', "visible", true);
                    disp.changeWidgetProperty('@@PREVIEW_BUTTON@@', "visible", true);
                    if (cur) {
                        style.set(cur, "overflow", "hidden");
                    }
                }
            }
        }
    };
    wikiIocDispatcher.addUpdateView(updateHandler);

    //Objecte que gestiona el refresc de la pàgina
    var reloadStateHandler = new ReloadStateHandler(function (state) {
        //actualitza l'estat a partir de les dades emmagatzemades en local
        if (state.login) {
            wikiIocDispatcher.processResponse({
                    "type": "login"
                   ,"value": {
                        "loginRequest": true
                       ,"loginResult": true
                    }
            });
        }

        if (state.sectok) {
            wikiIocDispatcher.processResponse({
                "type":  "sectok"
               ,"value": state.sectok
            });
        }

        if (state.title) {
            wikiIocDispatcher.processResponse({
                "type": "title"
               ,"value": state.title
            });
        }

        if (state.pages) {
            var np = 0;
            var length = state.pagesLength();
            var requestState = new Request();
            requestState.urlBase = "lib/plugins/ajaxcommand/ajax.php";
            for (var id in state.pages) {
                var queryParams;
                if(state.pages[id].action==="view"){
                    queryParams = "call=page&id=";
                }else if(state.pages[id].action==="edit"){
                    queryParams = "call=edit&id=";
                }else{
                    queryParams = "call=page&id=";
                }
                requestState.sendRequest(queryParams + state.pages[id].ns).always(function () {
                    np++;
                    if (np == length) {
                        if (state.info) {
                            wikiIocDispatcher.processResponse({
                                "type": "info", "value": state.info
                            });
                        }

                        if (state.currentTabId) {
                            var tc = registry.byId(wikiIocDispatcher.containerNodeId);
                            var widget = registry.byId(state.currentTabId);
                            tc.selectChild(widget);
                        }
                    }
                });
            }
        }
    });
    wikiIocDispatcher.addReloadState(reloadStateHandler);

    unload.addOnWindowUnload(function () {
        if (typeof(Storage) !== "undefined") {
            sessionStorage.globalState = JSON.stringify(
                    wikiIocDispatcher.getGlobalState());
        }
    });

    ready(function () {
        var tbContainer = registry.byId(wikiIocDispatcher.navegacioNodeId);
        if (tbContainer) {
            tbContainer.watch("selectedChildWidget", function (name, oldTab, newTab) {
                if (newTab.updateRendering)
                    newTab.updateRendering();
            });
        }

        var tab = registry.byId('@@TAB_INDEX@@');
        if (tab) {
            tab.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=page");
            tab.set("standbyId", wikiIocDispatcher.containerNodeId);
            wikiIocDispatcher.toUpdateSectok.push(tab);
            tab.updateSectok();
        }

        tab = registry.byId('@@TAB_DOCU@@');
        if (tab) {
            tab.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=page");
            tab.set("standbyId", wikiIocDispatcher.containerNodeId);
        }

        tab = registry.byId('@@EXIT_BUTTON@@');
        if (tab) {
            tab.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=login");
            //tab.set("standbyId", "loginDialog_hidden_container");
        }

        var getQuery = function () {
            var ret;
            var ns = wikiIocDispatcher.getGlobalState().pages[
                    wikiIocDispatcher.getGlobalState().currentTabId]["ns"];
            if (this.query) {
                ret = this.query + "&id=" + ns;
            } else {
                ret = "id=" + ns;
            }
            return ret;
        };

        tab = registry.byId('@@EDIT_BUTTON@@');
        if (tab) {
            tab.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=edit");
            /** @override */
            tab.getQuery = getQuery;
        }

        tab = registry.byId('@@ED_PARC_BUTTON@@');
        if (tab) {
            tab.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=edit");
            tab.getQuery = function () {
                var ret;
                var q = dwPageUi.getFormQueryToEditSection(
                        wikiIocDispatcher.getGlobalState().getCurrentSectionId());
                if (this.query) {
                    ret = this.query + "&" + q;
                } else {
                    ret = q;
                }
                return ret;
            };
        }
        
        tab = registry.byId('@@CANCEL_BUTTON@@');
        if (tab) {
            tab.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=cancel");
            /** @override */
            tab.getQuery = getQuery;
        }
        
        tab = registry.byId('@@NEW_BUTTON@@');
        if (tab) {
            tab.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=new_page");
            /** @override */
            tab.getQuery = getQuery;
        }
        
        tab = registry.byId('@@SAVE_BUTTON@@');
        if (tab) {
            tab.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=save");
            /** @override */
            tab.getQuery = getQuery;
            /** @override */
            tab.getPostData = function () {
                return domForm.toObject("dw__editform");
            };
        }
        
        var loginDialog = registry.byId('@@LOGIN_DIALOG@@');
        if (loginDialog) {
            loginDialog.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=login");
            loginDialog.set("standbyId", "loginDialog_hidden_container");

            loginDialog.on('hide', function () {
                loginDialog.reset();
            });
        }
        
        var loginCancelButton = registry.byId('@@LOGIN_DIALOG@@' + '_CancelButton');
        if (loginCancelButton) {
            loginCancelButton.on('click', function () {
                var bt = registry.byId('@@LOGIN_BUTTON@@');
                bt.closeDropDown(false);
            });
        }

        var userDialog = registry.byId('@@USER_DIALOG@@');
        if (userDialog) {
            userDialog.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=page");
        }
        
        userDialog = registry.byId('@@USER_BUTTON@@');
        if (userDialog) {
            userDialog.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=page");
        }
        
        userDialog = registry.byId('@@USER_MENUITEM@@');
        if (userDialog) {
            var getQueryUser = function(){
                return "id=wiki:user:"+wikiIocDispatcher.getGlobalState().userId;
            };
            userDialog.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=page");
            userDialog.getQuery=getQueryUser;            
            var processorUser = new ErrorMultiFunctionProcessor();
            var requestUser = new Request();
            requestUser.urlBase="lib/plugins/ajaxcommand/ajax.php?call=new_page";
            processorUser.addErrorAction("1001", function(){
                requestUser.sendRequest(getQueryUser());
            });
            userDialog.addProcessor(processorUser.type, processorUser);
        }
        
        userDialog = registry.byId('@@TALKUSER_MENUITEM@@');
        if (userDialog) {
            var getQueryTalk = function(){
                return "id=talk:wiki:user:"+wikiIocDispatcher.getGlobalState().userId;
            };
            userDialog.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=page");            
            userDialog.getQuery=getQueryTalk;
            var processorTalk = new ErrorMultiFunctionProcessor();
            var requestTalk = new Request();
            requestTalk.urlBase="lib/plugins/ajaxcommand/ajax.php?call=new_page";
            processorTalk.addErrorAction("1001", function(){                
                requestTalk.sendRequest(getQueryTalk());
            });
            userDialog.addProcessor(processorTalk.type, processorTalk);
        }
        
        userDialog = registry.byId('@@LOGOFF_MENUITEM@@');
        if (userDialog) {
            userDialog.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=login");
        }
        
        var centralContainer = registry.byId(wikiIocDispatcher.containerNodeId);
        if (centralContainer) {
            centralContainer.watch("selectedChildWidget", function (name, oldTab, newTab) {
                if (wikiIocDispatcher.getContentCache(newTab.id)) {
                    var nodeMetaInfo = registry.byId(wikiIocDispatcher.metaInfoNodeId);
                    //1. elimina els widgets corresponents a les metaInfo de l'antiga pestanya
                    wikiIocDispatcher.removeAllChildrenWidgets(nodeMetaInfo);
                    //2. crea els widgets corresponents a les MetaInfo de la nova pestanya seleccionada
                    var metaContentCache = wikiIocDispatcher.getContentCache(newTab.id).getMetaData();
                    var m, cp;
                    for (m in metaContentCache) {
                        cp = new ContentPane({
                            id:      metaContentCache[m].id,
                            title:   metaContentCache[m].title,
                            content: metaContentCache[m].content
                        });
                        nodeMetaInfo.addChild(cp);
                        nodeMetaInfo.resize();
                    }
                    wikiIocDispatcher.getGlobalState().currentTabId = newTab.id;
                }
            });
        }
        //cercar l'estat
        if (typeof(Storage) !== "undefined" && sessionStorage.globalState) {
            var state = globalState.newInstance(JSON.parse(sessionStorage.globalState));
            // var state = JSON.parse(sessionStorage.globalState);
            wikiIocDispatcher.reloadFromState(state);
        }
        
        wikiIocDispatcher.updateFromState();
    });
});
</script>
