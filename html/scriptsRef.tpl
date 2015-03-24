<script type="text/javascript" src="cfgIdConstants::DOJO_SOURCE/dojo.js"></script>
<script type="text/javascript">
    require([
        "dojo/dom",
        "dojo/dom-style",
        "dojo/dom-prop",
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
        "dojo/on",
        "dojo/query",
        "ioc/dokuwiki/guiSharedFunctions",
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
    ], function (dom, domStyle, domProp, win, wikiIocDispatcher, Request, registry,
                 ready, style, domForm, ContentPane, UpdateViewHandler, dwPageUi,
                 ReloadStateHandler, unload, JSON, lang, globalState,
                 ErrorMultiFunctionProcessor, on, dojoQuery, guiSharedFunctions) {
        //declaració de funcions

        var divMainContent = dom.byId("cfgIdConstants::MAIN_CONTENT");
        if (!divMainContent) {
            return;
        }

        var h = 100 * (win.getBox().h - 55) / win.getBox().h;
        domStyle.set(divMainContent, "height", h + "%");

        wikiIocDispatcher.containerNodeId     = "cfgIdConstants::BODY_CONTENT";
        wikiIocDispatcher.navegacioNodeId     = "cfgIdConstants::ZONA_NAVEGACIO";
        wikiIocDispatcher.metaInfoNodeId      = "cfgIdConstants::ZONA_METAINFO";
        wikiIocDispatcher.infoNodeId          = "cfgIdConstants::ZONA_MISSATGES";
        wikiIocDispatcher.sectokManager.putSectok("cfgIdConstants::SECTOK_ID", "cfgIdConstants::SECTOK");
        wikiIocDispatcher.loginButtonId       = 'cfgIdConstants::LOGIN_BUTTON';
        wikiIocDispatcher.exitButtonId        = 'cfgIdConstants::EXIT_BUTTON';
        wikiIocDispatcher.editButtonId        = 'cfgIdConstants::EDIT_BUTTON';
        wikiIocDispatcher.saveButtonId        = 'cfgIdConstants::SAVE_BUTTON';
        wikiIocDispatcher.cancelButtonId      = 'cfgIdConstants::CANCEL_BUTTON';
        wikiIocDispatcher.previewButtonId     = 'cfgIdConstants::PREVIEW_BUTTON';
        wikiIocDispatcher.edParcButtonId      = 'cfgIdConstants::ED_PARC_BUTTON';
        wikiIocDispatcher.userButtonId        = 'cfgIdConstants::USER_BUTTON';
        wikiIocDispatcher.mediaDetailButtonId = 'cfgIdConstants::MEDIA_DETAIL_BUTTON';


        // TODO[Xavi] es pot passar la funció següent com el constructor.
        var updateHandler = new UpdateViewHandler();

        updateHandler.update = function () {
            var disp = wikiIocDispatcher;
            var cur = disp.getGlobalState().currentTabId;
            if (cur) {
                style.set(cur, "overflow", "auto");
            }
            disp.changeWidgetProperty('cfgIdConstants::LOGIN_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::EXIT_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::NEW_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::EDIT_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::SAVE_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::CANCEL_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::PREVIEW_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::ED_PARC_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::USER_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::MEDIA_DETAIL_BUTTON', "visible", false);

            if (!disp.getGlobalState().login) {
                disp.changeWidgetProperty('cfgIdConstants::LOGIN_BUTTON', "visible", true);
            } else {
                //disp.changeWidgetProperty('cfgIdConstants::EXIT_BUTTON', "visible", true);
                disp.changeWidgetProperty('cfgIdConstants::NEW_BUTTON', "visible", true);
                disp.changeWidgetProperty('cfgIdConstants::USER_BUTTON', "visible", true);

                if (disp.getGlobalState().currentTabId) {
                    var page = disp.getGlobalState().pages[disp.getGlobalState().currentTabId];
                    if (page.action === 'view') {
                        disp.changeWidgetProperty('cfgIdConstants::EDIT_BUTTON', "visible", true);
                        disp.changeWidgetProperty('cfgIdConstants::ED_PARC_BUTTON', "visible", true);
                    } else if (page.action === 'edit') {
                        disp.changeWidgetProperty('cfgIdConstants::SAVE_BUTTON', "visible", true);
                        disp.changeWidgetProperty('cfgIdConstants::CANCEL_BUTTON', "visible", true);
                        disp.changeWidgetProperty('cfgIdConstants::PREVIEW_BUTTON', "visible", true);
                        if (cur) {
                            style.set(cur, "overflow", "hidden");
                        }
                    }else if(page.action==='media'){
                        disp.changeWidgetProperty('cfgIdConstants::MEDIA_DETAIL_BUTTON', "visible", true);
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
                    "type":    "login"
                    , "value": {
                        "loginRequest":  true
                        , "loginResult": true
                        , "userId":      state.userId
                    }
                });
                wikiIocDispatcher.processResponse({
                    "type":    "command"
                    , "value": {
                        "type":            "change_widget_property"
                        , "id":            'cfgIdConstants::USER_BUTTON'
                        , "propertyName":  "label"
                        , "propertyValue": state.userId
                    }
                });

                var requestTabContent = new Request();
                requestTabContent.urlBase = "lib/plugins/ajaxcommand/ajax.php?call=admin_tab";
                var data_tab = requestTabContent.sendRequest();

            }

            if (state.sectok) {
                wikiIocDispatcher.processResponse({
                    "type":    "sectok"
                    , "value": state.sectok
                });
            }

            if (state.title) {
                wikiIocDispatcher.processResponse({
                    "type":    "title"
                    , "value": state.title
                });
            }

            if (state.pages) {
                var np = 0;
                var length = state.pagesLength();
                var requestState = new Request();
                requestState.urlBase = "lib/plugins/ajaxcommand/ajax.php";


                var infoManager = wikiIocDispatcher.getInfoManager();
                if (length === 0) {
                    infoManager.loadInfoStorage(state.infoStorage);
                    infoManager.refreshInfo();
                }

                for (var id in state.pages) {
                    var queryParams;
                    if (state.pages[id].action === "view") {
                        queryParams = "call=page&id=";
                    } else if (state.pages[id].action === "edit") {
                        queryParams = "call=edit&reload=1&id=";
                    } else if (state.pages[id].action === "admin") {
                        queryParams = "call=admin_task&do=admin&page=";
                        // fix? ns empty, load with page name
                        state.pages[id].ns = id.substring(6);
                    } else {
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

                            if (state.infoStorage) {
                                infoManager.loadInfoStorage(state.infoStorage);
                                infoManager.refreshInfo(state.currentTabId);
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
                    wikiIocDispatcher.getGlobalState().setCurrentNavigationId(newTab.id);

                    if (newTab.updateRendering) {
                        newTab.updateRendering();
                    }
                });
            }

            var tab = registry.byId('cfgIdConstants::TB_INDEX');
            if (tab) {
                wikiIocDispatcher.toUpdateSectok.push(tab);
                tab.updateSectok();
            }

            var loginDialog = registry.byId('cfgIdConstants::LOGIN_DIALOG');
            if (loginDialog) {
                loginDialog.on('hide', function () {
                    loginDialog.reset();
                });
            }

            var loginCancelButton = registry.byId('cfgIdConstants::LOGIN_DIALOG' + '_CancelButton');
            if (loginCancelButton) {
                loginCancelButton.on('click', function () {
                    var bt = registry.byId('cfgIdConstants::LOGIN_BUTTON');
                    bt.closeDropDown(false);
                });
            }

            var userDialog = registry.byId('cfgIdConstants::USER_MENU_ITEM');
            if (userDialog) {
                var processorUser = new ErrorMultiFunctionProcessor();
                var requestUser = new Request();
                requestUser.urlBase = "lib/plugins/ajaxcommand/ajax.php?call=new_page";
                processorUser.addErrorAction("1001", function () {
                    requestUser.sendRequest(userDialog.getQuery);
                });
                userDialog.addProcessor(processorUser.type, processorUser);
            }

            userDialog = registry.byId('cfgIdConstants::TALK_USER_MENU_ITEM');
            if (userDialog) {
                var processorTalk = new ErrorMultiFunctionProcessor();
                var requestTalk = new Request();
                requestTalk.urlBase = "lib/plugins/ajaxcommand/ajax.php?call=new_page";
                processorTalk.addErrorAction("1001", function () {
                    requestTalk.sendRequest(userDialog.getQuery);
                });
                userDialog.addProcessor(processorTalk.type, processorTalk);
            }

            var centralContainer = registry.byId(wikiIocDispatcher.containerNodeId);
            if (centralContainer) {

                centralContainer.watch("selectedChildWidget", function (name, oldTab, newTab) {

                    // Aquest codi es crida només quan canviem de pestanya
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

                            guiSharedFunctions.addWatchToMetadataPane(cp, newTab.id, cp.id, wikiIocDispatcher);
                            guiSharedFunctions.addChangeListenersToMetadataPane(cp.id, wikiIocDispatcher);
                        }

                        // Restauració del panell de metadades
                        var currentMetadataPaneId = wikiIocDispatcher.getContentCache(newTab.id).getCurrentId("metadataPane");

                        if (currentMetadataPaneId) {
                            nodeMetaInfo.selectChild(currentMetadataPaneId);
                        }


                        wikiIocDispatcher.getGlobalState().currentTabId = newTab.id;

                        wikiIocDispatcher.getInfoManager().refreshInfo(newTab.id);
                    }

                    if (oldTab && wikiIocDispatcher.getGlobalState()
                                    .getContentAction(oldTab.id) == "edit") {
                        wikiIocDispatcher.getContentCache(oldTab.id).getEditor().unselect();
                    }
                    if (wikiIocDispatcher.getGlobalState()
                                    .getContentAction(newTab.id) == "edit") {
                        wikiIocDispatcher.getContentCache(newTab.id).getEditor().select();
                    }
                    wikiIocDispatcher.updateFromState();
                });
            }

            //cercar l'estat
            if (typeof(Storage) !== "undefined" && sessionStorage.globalState) {
                var state = globalState.newInstance(JSON.parse(sessionStorage.globalState));
                // var state = JSON.parse(sessionStorage.globalState);
                wikiIocDispatcher.reloadFromState(state);
            }


            // Establim el panell d'informació actiu
            var currentNavigationPaneId = state ? state.getCurrentNavigationId() : null;

            if (!currentNavigationPaneId) {
                currentNavigationPaneId = tbContainer.getChildren()[0].id;
                wikiIocDispatcher.getGlobalState().setCurrentNavigationId(currentNavigationPaneId);
            }

            tbContainer.selectChild(currentNavigationPaneId);


            wikiIocDispatcher.updateFromState();


        });
    });
</script>
