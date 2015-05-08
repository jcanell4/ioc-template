require([
        "dojo/dom",
        "ioc/wiki30/dispatcherSingleton",
        "ioc/wiki30/Request",
        "dijit/registry",
        "dojo/ready",
        "dojo/dom-style",
        "ioc/wiki30/UpdateViewHandler",
        "ioc/wiki30/ReloadStateHandler",
        'dojo/_base/unload',
        "dojo/json",
        "ioc/wiki30/GlobalState",
        "ioc/gui/content/containerContentToolFactory",
        "dojo/domReady!"
    ], function (dom, wikiIocDispatcher, Request, registry,
                 ready, style, UpdateViewHandler,
                 ReloadStateHandler, unload, JSON, globalState,
                 containerContentToolFactory
    ) {
        //declaració de funcions

        var divMainContent = dom.byId("cfgIdConstants::MAIN_CONTENT");
        if (!divMainContent) {
            return;
        }
        var updateHandler = new UpdateViewHandler();

        updateHandler.update = function () {
            var disp = wikiIocDispatcher;
            var cur = disp.getGlobalState().currentTabId;
            if (cur) {
                style.set(cur, "overflow", "auto");
            }
            disp.changeWidgetProperty('cfgIdConstants::LOGIN_BUTTON', "visible", false);
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
                // user is admin or manager => NEW_BUTTON visible
                var new_button_visible =  false;
                if (Object.keys(disp.getGlobalState().permissions).length>0) {
                    new_button_visible = (disp.getGlobalState().permissions['isadmin'] | disp.getGlobalState().permissions['ismanager']);
                }
                disp.changeWidgetProperty('cfgIdConstants::NEW_BUTTON', "visible", new_button_visible);
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
                    }else if(page.action === 'media'){
                        disp.changeWidgetProperty('cfgIdConstants::MEDIA_DETAIL_BUTTON', "visible", true);
                    }
                }
            }
        };
        wikiIocDispatcher.addUpdateView(updateHandler);

        // Objecte que gestiona el refresc de la pàgina
        var reloadStateHandler = new ReloadStateHandler(function (state) {

            //actualitza l'estat a partir de les dades emmagatzemades en local
            if (state.login) {
                wikiIocDispatcher.processResponse({
                    "type": "login"
                    ,"value": {
                        "loginRequest": true
                        ,"loginResult": true
                        ,"userId":      state.userId
                    }
                });
                wikiIocDispatcher.processResponse({
                    "type": "command"
                    ,"value": {
                        "type":           "change_widget_property"
                        ,"id":            'cfgIdConstants::USER_BUTTON'
                        ,"propertyName":  "label"
                        ,"propertyValue": state.userId
                    }
                });

                // Add admin_tab to the Navigation container
                var requestTabContent = new Request();
                requestTabContent.urlBase = "lib/plugins/ajaxcommand/ajax.php?call=admin_tab";
                var data_tab = requestTabContent.sendRequest().always(function () {
                    var currentNavigationPaneId = state ? state.getCurrentNavigationId() : null;
                    if (currentNavigationPaneId === "tb_admin") {
                        var tbContainer = registry.byId(wikiIocDispatcher.navegacioNodeId);
                        tbContainer.selectChild(currentNavigationPaneId);
                    }
                });
            }

            if (state.permissions) {
                wikiIocDispatcher.processResponse({
                    "type":    "jsinfo"
                    , "value": state.permissions
                });
            }

            if (state.sectok) {
                wikiIocDispatcher.processResponse({
                    "type":   "sectok"
                    ,"value": state.sectok
                });
            }

            if (state.title) {
                wikiIocDispatcher.processResponse({
                    "type":   "title"
                    ,"value": state.title
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

                        if (np === length) {
                            if (state.info) {
                                wikiIocDispatcher.processResponse({
                                    "type":   "info"
                                    ,"value": state.info
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
                sessionStorage.globalState = JSON.stringify(wikiIocDispatcher.getGlobalState());
            }
        });

        ready(function () {


            // cercar l'estat
            if (typeof(Storage) !== "undefined" && sessionStorage.globalState) {
                var state = globalState.newInstance(JSON.parse(sessionStorage.globalState));

                wikiIocDispatcher.reloadFromState(state);
            }

            // Establim el panell d'informació actiu
            var currentNavigationPaneId = state ? state.getCurrentNavigationId() : null;
            var tbContainer = registry.byId(wikiIocDispatcher.navegacioNodeId);
            if (tbContainer) {
                if (!currentNavigationPaneId) {
                    currentNavigationPaneId = tbContainer.getChildren()[0].id;
                    wikiIocDispatcher.getGlobalState().setCurrentNavigationId(currentNavigationPaneId);
                }
                tbContainer.selectChild(currentNavigationPaneId);
            	// Seleccionem el tab si està creat
	        if (currentNavigationPaneId) {
        		var childWidget = registry.byId(currentNavigationPaneId);
                	if (childWidget) {
                    		tbContainer.selectChild(currentNavigationPaneId);
                	}
		}	
            }
            wikiIocDispatcher.updateFromState();
            
            var container = registry.byId(wikiIocDispatcher.metaInfoNodeId);
            containerContentToolFactory.generate(container, {dispatcher:wikiIocDispatcher});


            container = registry.byId(wikiIocDispatcher.containerNodeId);
            containerContentToolFactory.generate(container, {dispatcher:wikiIocDispatcher});

            container = registry.byId(wikiIocDispatcher.navegacioNodeId);
            containerContentToolFactory.generate(container, {dispatcher:wikiIocDispatcher});

        });

        // TODO[Xavi] Això hauria de activarse globalment, no només per un tipus concret de document
        window.addEventListener("beforeunload", function (event) {
            if (wikiIocDispatcher.getChangesManager().thereAreChangedContents()) {
                event.returnValue = LANG.notsavedyet;
            }

            deleteDraft();
        });
    });

