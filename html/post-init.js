require([
    'dojo/dom',
    'ioc/wiki30/dispatcherSingleton',
    'ioc/wiki30/Request',
    'dijit/registry',
    'dojo/ready',
    'dojo/dom-style',
    'ioc/wiki30/UpdateViewHandler',
    'ioc/wiki30/ReloadStateHandler',
    'dojo/_base/unload',
    'dojo/json',
    'ioc/wiki30/GlobalState',
    'ioc/gui/content/containerContentToolFactory',
    'ioc/wiki30/RequestControl',
    'ioc/wiki30/LocalUserConfig',
    'dojo/_base/unload',
    'dojo/cookie',
    'dojo/domReady!'
], function (dom, getDispatcher, Request, registry,
             ready, style, UpdateViewHandler,
             ReloadStateHandler, unload, JSON, globalState,
             containerContentToolFactory, RequestControl, LocalUserConfig,
             baseUnload, cookie) {

    var wikiIocDispatcher = getDispatcher();
    //almacenLocal: Gestiona la configuració GUI persistent de l'usuari
    wikiIocDispatcher.almacenLocal = new LocalUserConfig();

    //declaració de funcions
    ready(function () {

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
            /*disp.changeWidgetProperty('cfgIdConstants::PREVIEW_BUTTON', "visible", false);*/
            disp.changeWidgetProperty('cfgIdConstants::ED_PARC_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::CANCEL_PARC_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::SAVE_PARC_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::USER_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::NOTIFIER_BUTTON_INBOX', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::NOTIFIER_BUTTON_OUTBOX', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::MEDIA_DETAIL_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::MEDIA_TORNAR_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::DETAIL_SUPRESSIO_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::MEDIA_SUPRESSIO_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::MEDIA_UPLOAD_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::MEDIA_UPDATE_IMAGE_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::MEDIA_EDIT_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::SAVE_FORM_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::GENERATE_PROJECT_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::PRINT_BUTTON', "visible", false);
            disp.changeWidgetProperty('cfgIdConstants::REVERT_BUTTON', "visible", false);
            
            if (!disp.getGlobalState().login) {
                disp.changeWidgetProperty('cfgIdConstants::LOGIN_BUTTON', "visible", true);
            } else {

                //disp.changeWidgetProperty('cfgIdConstants::EXIT_BUTTON', "visible", true);
                // user is admin or manager => NEW_BUTTON visible
                var new_button_visible = false;
                if (Object.keys(disp.getGlobalState().permissions).length > 0) {
                    new_button_visible = (disp.getGlobalState().permissions['isadmin'] ||
                    disp.getGlobalState().permissions['ismanager'] ||
                    disp.getGlobalState().permissions['isprojectmanager']);
                }
                disp.changeWidgetProperty('cfgIdConstants::NEW_BUTTON', "visible", new_button_visible);
                disp.changeWidgetProperty('cfgIdConstants::USER_BUTTON', "visible", true);
                disp.changeWidgetProperty('cfgIdConstants::NOTIFIER_BUTTON_INBOX', "visible", true);
                disp.changeWidgetProperty('cfgIdConstants::NOTIFIER_BUTTON_OUTBOX', "visible", true);

                if (disp.getGlobalState().currentTabId) {

                    var page = disp.getGlobalState().getContent(disp.getGlobalState().currentTabId),
                        selectedSection = disp.getGlobalState().getCurrentElement(),
                        isRevision;


                    if (page.action === 'view') {

                        // ALERTA[Xavi] Compte! en el mode vista no es pot fer servir el mateix botó perqué no tenim la informació per desar a la wiki!
                        // isRevision = disp.getContentCache(cur).getMainContentTool().rev ? true : false;
                        //
                        // if (isRevision) {
                        //     disp.changeWidgetProperty('cfgIdConstants::REVERT_BUTTON', "visible", true);
                        // }


                        if (selectedSection.id) {
                            disp.changeWidgetProperty('cfgIdConstants::ED_PARC_BUTTON', "visible", true);
                        }
                        disp.changeWidgetProperty('cfgIdConstants::EDIT_BUTTON', "visible", true);
                        disp.changeWidgetProperty('cfgIdConstants::PRINT_BUTTON', "visible", true);

                    } else if (page.action === 'sec_edit') {
                        if (selectedSection.id) {

                            if (selectedSection.state) { // TODO[Xavi] per ara considerem qualsevol valor com a en edició
                                // La edició seleccionada està en edició
                                var ro = disp.getContentCache(cur).getMainContentTool().locked
                                    || disp.getContentCache(cur).getMainContentTool().readonly;

                                disp.changeWidgetProperty('cfgIdConstants::SAVE_PARC_BUTTON', "visible", !ro);
                                disp.changeWidgetProperty('cfgIdConstants::CANCEL_PARC_BUTTON', "visible", true);
                            } else {
                                // La edició seleccionada no està en edició
                                disp.changeWidgetProperty('cfgIdConstants::ED_PARC_BUTTON', "visible", true);
                            }
                        }
                        disp.changeWidgetProperty('cfgIdConstants::PRINT_BUTTON', "visible", true);
                    } else if (page.action === 'edit') {
                        var ro = disp.getContentCache(cur).getMainContentTool().locked
                            || disp.getContentCache(cur).getMainContentTool().readonly;

                        isRevision = disp.getContentCache(cur).getMainContentTool().rev ? true : false;

                        if (isRevision) {
                            disp.changeWidgetProperty('cfgIdConstants::REVERT_BUTTON', "visible", true);
                        }

                        disp.changeWidgetProperty('cfgIdConstants::SAVE_BUTTON', "visible", !ro);
                        disp.changeWidgetProperty('cfgIdConstants::CANCEL_BUTTON', "visible", true);



                        if (cur) {
                            style.set(cur, "overflow", "hidden");
                        }
                        disp.changeWidgetProperty('cfgIdConstants::PRINT_BUTTON', "visible", true);
                    } else if (page.action === 'form') {
                        disp.changeWidgetProperty('cfgIdConstants::SAVE_FORM_BUTTON', "visible", true);
                        disp.changeWidgetProperty('cfgIdConstants::GENERATE_PROJECT_BUTTON', "visible", true);

                    } else if (page.action === 'media') {
                        selectedSection = disp.getGlobalState().getCurrentElement();
                        if (selectedSection.id) {
                            disp.changeWidgetProperty('cfgIdConstants::MEDIA_SUPRESSIO_BUTTON', "visible", true);
                            disp.changeWidgetProperty('cfgIdConstants::MEDIA_DETAIL_BUTTON', "visible", true);
                        }
                        disp.changeWidgetProperty('cfgIdConstants::MEDIA_UPLOAD_BUTTON', "visible", true);
                    } else if (page.action === 'mediadetails') {
                        var pageDif = (page.mediado && page.mediado === "diff");
                        if (!pageDif) {
                            disp.changeWidgetProperty('cfgIdConstants::DETAIL_SUPRESSIO_BUTTON', "visible", true);
                            disp.changeWidgetProperty('cfgIdConstants::MEDIA_UPDATE_IMAGE_BUTTON', "visible", true);
                            if (disp.getGlobalState().pages["media"] && disp.getGlobalState().pages["media"][disp.getGlobalState().currentTabId]) {
                                disp.changeWidgetProperty('cfgIdConstants::MEDIA_EDIT_BUTTON', "visible", true);
                            }
                        } else {
                            disp.changeWidgetProperty('cfgIdConstants::MEDIA_TORNAR_BUTTON', "visible", true);
                        }
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
                    , "value": {
                        "loginRequest": true
                        , "loginResult": true
                        , "userId": state.userId
                    }
                });
                wikiIocDispatcher.processResponse({
                    "type": "command"
                    , "value": {
                        "type": "change_widget_property"
                        , "id": 'cfgIdConstants::USER_BUTTON'
                        , "propertyName": "label"
                        , "propertyValue": state.userId
                    }
                });
            }

            // Establim el panell d'informació actiu
            var currentNavigationPaneId = state.getCurrentNavigationId();
            wikiIocDispatcher.getGlobalState().setCurrentNavigationId(currentNavigationPaneId);

            var tbContainer = registry.byId(wikiIocDispatcher.navegacioNodeId);
            if (tbContainer) {
                // Seleccionem el tab si està creat
                if (currentNavigationPaneId) {
                    var childWidget = registry.byId(currentNavigationPaneId);
                    if (childWidget) {
                        tbContainer.selectChild(currentNavigationPaneId);
                    }
                }
            }

            if (state.permissions) {
                wikiIocDispatcher.processResponse({
                    "type": "jsinfo"
                    , "value": state.permissions
                });
                // Add admin_tab to the Navigation container
                if (state.permissions['isadmin'] | state.permissions['ismanager']) {
                    var requestTabContent = new Request();
                    requestTabContent.urlBase = "lib/plugins/ajaxcommand/ajax.php?call=admin_tab";
                    requestTabContent.sendRequest();
                }
            }

            // Add shortcut_tab
            if(state.extratabs['cfgIdConstants::TB_SHORTCUTS']){
                var requestTabContent = new Request();
                requestTabContent.urlBase = "lib/plugins/ajaxcommand/ajax.php?call=shortcuts_tab&user_id="+state.userId;
                requestTabContent.sendRequest();
            }

            if (state.sectok) {
                wikiIocDispatcher.processResponse({
                    "type": "sectok"
                    , "value": state.sectok
                });
            }

            if (state.title) {
                wikiIocDispatcher.processResponse({
                    "type": "title"
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

                console.log(state.pages);

                for (var id in state.pages) {
                    console.log("Reloading pàgina:", id);
                    var queryParams = '';

                    if (state.getContent(id).action === "view" || state.getContent(id).action === "edit") {
                        if (state.getContent(id).rev) {
                            queryParams += "rev=" + state.getContent(id).rev + "&";
                        }
                        queryParams += "call=page&id=";

                    } else if (state.getContent(id).action === "form") {

                        var ns = state.getContent(id).ns;
                        var projectType = state.getContent(id).projectType;
                        queryParams = "call=project&do=edit&ns=" + ns + "&projectType=" + projectType + "&id=";

                    } else if (state.getContent(id).action === "admin") {
                        queryParams = "call=admin_task&do=admin&page=";
                        // fix? ns empty, load with page name
                        state.getContent(id).ns = id.substring(6);

                    } else if (state.getContent(id).action === "recents") {
                        queryParams = "call=recent&id=";
                        // fix? ns empty, load with page name
                        state.getContent(id).ns = "";

                    } else if (state.getContent(id).action === "media") {
                        queryParams = "call=media";
                        var elid = state.getContent(id).ns;
                        queryParams += '&ns=' + elid + '&do=media&id=';

                    } else if (state.getContent(id).action === "mediadetails") {
                        queryParams = "call=mediadetails";
                        var elid = state.getContent(id).myid;
                        //_ret = 'id=' + elid + '&image=' + elid + '&img=' + elid + '&do=media';
                        queryParams += '&id=' + elid + '&image=' + elid + '&img=' + elid + '&do=media&id=';

                    } else if (state.getContent(id).action === "diff") {
                        console.log("Diff al globalstate", state.getContent(id));
                        var page = state.getContent(id);
                        queryParams = 'call=diff&id=' + page.ns;

                        if (page.rev2) {
                            queryParams += '&rev2[]=' + page.rev1;
                            queryParams += '&rev2[]=' + page.rev2;
                        } else {
                            queryParams += '&rev1=' + page.rev1;
                        }

                    } else {
                        queryParams = "call=page&id=";
                    }

                    requestState.sendRequest(queryParams + state.getContent(id).ns).always(function () {
                        np++;

                        if (np === length) {
                            if (state.info) {
                                wikiIocDispatcher.processResponse({
                                    "type": "info"
                                    ,"value": state.info
                                });
                            }

                            if (state.currentTabId) {
                                var tc = registry.byId(wikiIocDispatcher.containerNodeId);
                                var widget = registry.byId(state.currentTabId);
                                tc.selectChild(widget);
                            }

                            var debug = true;

                            if (state.infoStorage && !debug) {
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

        // Guardar los valores por defecto de las medidas de los paneles ajustables
        wikiIocDispatcher.almacenLocal.setUpUserDefaultPanelsSize(wikiIocDispatcher);

        var tbContainer = registry.byId(wikiIocDispatcher.navegacioNodeId);
        if (tbContainer) {
            var currentNavigationPaneId = tbContainer.getChildren()[0].id;
            wikiIocDispatcher.getGlobalState().setCurrentNavigationId(currentNavigationPaneId);
        }

        // cercar l'estat
        if (typeof(Storage) !== "undefined" && sessionStorage.globalState) {
            var state = globalState.newInstance(JSON.parse(sessionStorage.globalState));
            var extraState = wikiIocDispatcher.requestedState;

            wikiIocDispatcher.reloadFromState(state);
            if (extraState)
                wikiIocDispatcher.reloadFromState(extraState);
        }

        wikiIocDispatcher.updateFromState();

        var container = registry.byId(wikiIocDispatcher.metaInfoNodeId);
        containerContentToolFactory.generate(container, {dispatcher: wikiIocDispatcher});


        container = registry.byId(wikiIocDispatcher.containerNodeId);
        containerContentToolFactory.generate(container, {dispatcher: wikiIocDispatcher, isCentral: true});

        container = registry.byId(wikiIocDispatcher.navegacioNodeId);
        containerContentToolFactory.generate(container, {dispatcher: wikiIocDispatcher});

        window.addEventListener("beforeunload", function (event) {
            // ALERTA[Xavi] Si es detectan canvis retorna a "event.returnValue" el que fa que el navegador mostri un missatge (segons el navegador el missatge serà el propi i no el que es passa com a valor, Firefox i Chrome fan servir el missatge propi)
            if (wikiIocDispatcher.getChangesManager().thereAreChangedContents()) {
                event.returnValue = LANG.notsavedyet;
            }
        });

        var eventName = wikiIocDispatcher.getEventManager().eventName;

        // ALERTA[Xavi] Aquí es on es creen i es configuren els controladors de request
        new RequestControl(eventName.LOCK_DOCUMENT, 'lib/plugins/ajaxcommand/ajax.php?call=lock', true); // TODO[Xavi] Això no cal que sigui true, però s'ha de canviar com es genera el query per tot arreu si ho canviem
        new RequestControl(eventName.UNLOCK_DOCUMENT, 'lib/plugins/ajaxcommand/ajax.php?call=unlock', false);
        new RequestControl(eventName.CANCEL_DOCUMENT, 'lib/plugins/ajaxcommand/ajax.php?call=cancel', false, true);

        new RequestControl(eventName.CANCEL_PARTIAL, 'lib/plugins/ajaxcommand/ajax.php?call=cancel_partial', false, true);
        new RequestControl(eventName.EDIT_PARTIAL, 'lib/plugins/ajaxcommand/ajax.php?call=edit_partial', false, true);
        new RequestControl(eventName.SAVE_PARTIAL, 'lib/plugins/ajaxcommand/ajax.php?call=save_partial', true, true);
        new RequestControl(eventName.SAVE_PARTIAL_ALL, 'lib/plugins/ajaxcommand/ajax.php?call=save_partial&do=save_all', true, true);

        new RequestControl(eventName.CANCEL, 'lib/plugins/ajaxcommand/ajax.php?call=cancel', false, true);
        new RequestControl(eventName.SAVE, 'lib/plugins/ajaxcommand/ajax.php?call=save', true, true);
        new RequestControl(eventName.EDIT, 'lib/plugins/ajaxcommand/ajax.php?call=edit', false, true);

        new RequestControl(eventName.SAVE_FORM, 'lib/plugins/ajaxcommand/ajax.php?call=project&do=save', true, true);

        new RequestControl(eventName.SAVE_DRAFT, 'lib/plugins/ajaxcommand/ajax.php?call=draft&do=save', true);
        new RequestControl(eventName.REMOVE_DRAFT, 'lib/plugins/ajaxcommand/ajax.php?call=draft&do=remove', true);

        new RequestControl(eventName.NOTIFY, 'lib/plugins/ajaxcommand/ajax.php?call=notify', true);

        new RequestControl(eventName.MEDIA_DETAIL, 'lib/plugins/ajaxcommand/ajax.php?call=mediadetails', true);


        // ALERTA[Xavi] Si al carregar estem autenticats, s'ha de possar en marxa el motor de notificacions
        //console.log("Estem autenticats?", wikiIocDispatcher.getGlobalState().login);

        if (wikiIocDispatcher.getGlobalState().login === true) {
            wikiIocDispatcher.getEventManager().fireEvent('notify', {
                //id: null, // No cal
                dataToSend: {
                    do: 'init'
                }
            });
        }

        // Recuperem el contenidor de notificacions
        var inboxNotifierContainer = registry.byId('cfgIdConstants::NOTIFIER_CONTAINER_INBOX');
        var outboxNotifierContainer = registry.byId('cfgIdConstants::NOTIFIER_CONTAINER_OUTBOX');
        // wikiIocDispatcher.setNotifierContainer(notifierContainer);

        var warningContainer = registry.byId('cfgIdConstants::SYSTEM_WARNING_CONTAINER');
        // wikiIocDispatcher.setWarningContainer(warningContainer);

        var notifyManager = wikiIocDispatcher.getNotifyManager();

        notifyManager.addWarningContainer(warningContainer);
        notifyManager.addNotifyContainer('inbox',  inboxNotifierContainer);
        notifyManager.addNotifyContainer('outbox', outboxNotifierContainer);



        // Alerta[Xavi] TEST per carregar formularis
        //jQuery.ajax({
        //    url: '//iocwiki.dev/dokuwiki_30/lib/plugins/ajaxcommand/ajax.php?call=form&id=testforms',
        //    success: wikiIocDispatcher.processResponse.bind(wikiIocDispatcher)
        //});

        //var requestForm = new Request();
        //requestForm.urlBase = "lib/plugins/ajaxcommand/ajax.php?call=testform&id=testform";
        //requestForm.sendRequest();


        // TODO[Xavi] Canviar per codi de Dojo
        baseUnload.addOnUnload(function(){
            cookie("IOCForceScriptLoad", 1);
        });
    });
});

