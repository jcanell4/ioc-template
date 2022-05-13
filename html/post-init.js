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
    'dojo/cookie',
    'ioc/wiki30/manager/StorageManager',
    'ioc/functions/getValidator',
    'dojo/domReady!'
], function (dom, getDispatcher, Request, registry,
             ready, style, UpdateViewHandler,
             ReloadStateHandler, unload, JSON, globalState,
             containerContentToolFactory, RequestControl, LocalUserConfig,
             cookie, storageManager, getValidator) {

    var wikiIocDispatcher = getDispatcher();
    //almacenLocal: Gestiona la configuració GUI persistent de l'usuari
    wikiIocDispatcher.almacenLocal = new LocalUserConfig();


    // Recupera el valor d'un paràmetre de la URL, per exemple: el id per determinar si s'ha seguit un enllaç a un document o s'ha recarregat la pàgina
    var getParameterByName = function (name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    };

    var paramId = getParameterByName("id");

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
            disp.initUpdateWidgetProperty('cfgIdConstants::LOGIN_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::NEW_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::RENAME_FOLDER_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::EDIT_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::SAVE_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::CANCEL_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::ED_PARC_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::CANCEL_PARC_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::SAVE_PARC_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::USER_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::NOTIFIER_BUTTON_INBOX', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::NOTIFIER_BUTTON_OUTBOX', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::MEDIA_DETAIL_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::MEDIA_TORNAR_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::DETAIL_SUPRESSIO_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::MEDIA_SUPRESSIO_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::MEDIA_UPLOAD_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::MEDIA_UPDATE_IMAGE_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::MEDIA_EDIT_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::FTPSEND_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::FTP_PROJECT_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::EDIT_PROJECT_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::SAVE_PROJECT_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::GENERATE_PROJECT_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::CANCEL_PROJECT_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::PRINT_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::REVERT_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::DUPLICATE_PROJECT_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::RENAME_PROJECT_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::REMOVE_PROJECT_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::REVERT_PROJECT_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::SEND_MESSAGE_TO_ROLS_BUTTON', "visible", false);
            disp.initUpdateWidgetProperty('cfgIdConstants::SEND_LIST_TO_USERS_BUTTON', "visible", false);

            if (!disp.getGlobalState().login) {
                disp.changeWidgetProperty('cfgIdConstants::LOGIN_BUTTON', "visible", true);
            } else {
                var new_button_visible = false;
                if (Object.keys(disp.getGlobalState().permissions).length > 0) {
                    new_button_visible = (disp.getGlobalState().permissions['isadmin'] || 
                                          disp.getGlobalState().permissions['ismanager'] ||
                                          disp.getGlobalState().permissions['isprojectmanager']);
                }
                var send_button_visible = false;
                var ftpproject_button_visible = false;
                if (Object.keys(disp.getGlobalState().permissions).length > 0) {
                    send_button_visible = disp.getGlobalState().permissions['isadmin'];
                    ftpproject_button_visible = disp.getGlobalState().permissions['isadmin'] || disp.getGlobalState().permissions['ismanager'];
                }
                disp.changeWidgetProperty('cfgIdConstants::NEW_BUTTON', "visible", new_button_visible);
                disp.changeWidgetProperty('cfgIdConstants::RENAME_FOLDER_BUTTON', "visible", new_button_visible);
                disp.changeWidgetProperty('cfgIdConstants::USER_BUTTON', "visible", true);
                disp.changeWidgetProperty('cfgIdConstants::NOTIFIER_BUTTON_INBOX', "visible", true);
                disp.changeWidgetProperty('cfgIdConstants::NOTIFIER_BUTTON_OUTBOX', "visible", true);

                if (disp.getGlobalState().currentTabId) {

                    var page = disp.getGlobalState().getContent(disp.getGlobalState().currentTabId);
                    
                    if (page.action !== undefined) {
                        var selectedSection = disp.getGlobalState().getCurrentElement();
                        var isManager = disp.getGlobalState().getIsManager();
                        var isRevision;

                        if (!(page.action==='view' || page.action==="view_form" || page.action==="project_view")) {
                            new_button_visible = false;
                        }
                        disp.changeWidgetProperty('cfgIdConstants::NEW_BUTTON', "visible", new_button_visible);
                        disp.changeWidgetProperty('cfgIdConstants::RENAME_FOLDER_BUTTON', "visible", new_button_visible);

                        if (page.ftpSendButton === true) {
                            disp.changeWidgetProperty('cfgIdConstants::FTPSEND_BUTTON', "visible", send_button_visible);
                        }

                        if (page.ftpProjectButton === true) {
                            disp.changeWidgetProperty('cfgIdConstants::FTP_PROJECT_BUTTON', "visible", ftpproject_button_visible);
                        }

                        if (page.action === 'view') {
                            if (selectedSection.id) {
                                disp.changeWidgetProperty('cfgIdConstants::ED_PARC_BUTTON', "visible", true);
                            }
                            disp.changeWidgetProperty('cfgIdConstants::EDIT_BUTTON', "visible", true);
                            disp.changeWidgetProperty('cfgIdConstants::PRINT_BUTTON', "visible", true);
                        }
                        else if (page.action === 'sec_edit') {
                            if (selectedSection.id) {
                                if (selectedSection.state) { // TODO[Xavi] per ara considerem qualsevol valor com a en edició
                                    // La edició seleccionada està en edició
                                    var ro = disp.getContentCache(cur).getMainContentTool().locked ||
                                             disp.getContentCache(cur).getMainContentTool().readonly;
                                    disp.changeWidgetProperty('cfgIdConstants::SAVE_PARC_BUTTON', "visible", !ro);
                                    disp.changeWidgetProperty('cfgIdConstants::CANCEL_PARC_BUTTON', "visible", true);
                                } else {
                                    // La edició seleccionada no està en edició
                                    disp.changeWidgetProperty('cfgIdConstants::ED_PARC_BUTTON', "visible", true);
                                }
                            }
                            disp.changeWidgetProperty('cfgIdConstants::PRINT_BUTTON', "visible", true);
                        }
                        else if (page.action === 'edit') {
                            var ro = disp.getContentCache(cur).getMainContentTool().locked ||
                                     disp.getContentCache(cur).getMainContentTool().readonly;

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
                        }
                        else if (page.action === "form" || page.action === "project_edit" || page.action === "project_partial") {
                            disp.changeWidgetProperty('cfgIdConstants::SAVE_PROJECT_BUTTON', "visible", true);
                            disp.changeWidgetProperty('cfgIdConstants::CANCEL_PROJECT_BUTTON', "visible", true);
                        }
                        else if (page.action === "view_form" || page.action === "project_view") {
                            if (page.generated===false) {
                                disp.changeWidgetProperty('cfgIdConstants::GENERATE_PROJECT_BUTTON', "visible", true);
                            }
                            isRevision = disp.getContentCache(cur).getMainContentTool().get('isRevision');
                            if (!isRevision) {
                                disp.changeWidgetProperty('cfgIdConstants::EDIT_PROJECT_BUTTON', "visible", true);

                                if (page.projectType !== null && page.projectType !== "" && page.projectType !== undefined) {
                                    if (isManager || page.rol === "responsable") {
                                        disp.changeWidgetProperty('cfgIdConstants::DUPLICATE_PROJECT_BUTTON', "visible", true);
                                        disp.changeWidgetProperty('cfgIdConstants::RENAME_PROJECT_BUTTON', "visible", true);
                                    }
                                    if (isManager) {
                                        disp.changeWidgetProperty('cfgIdConstants::REMOVE_PROJECT_BUTTON', "visible", true);
                                    }
                                }
                            }else {
                                if (isManager || page.rol === "responsable") {
                                    disp.changeWidgetProperty('cfgIdConstants::REVERT_PROJECT_BUTTON', "visible", true);
                                }
                            }
                        }
                        else if (page.action === 'media') {
                            selectedSection = disp.getGlobalState().getCurrentElement();
                            if (selectedSection.id) {
                                disp.changeWidgetProperty('cfgIdConstants::MEDIA_SUPRESSIO_BUTTON', "visible", true);
                                disp.changeWidgetProperty('cfgIdConstants::MEDIA_DETAIL_BUTTON', "visible", true);
                            }
                            disp.changeWidgetProperty('cfgIdConstants::MEDIA_UPLOAD_BUTTON', "visible", true);
                        }
                        else if (page.action === 'mediadetails') {
                            var pageDif = (page.mediado && page.mediado === "diff");
                            isRevision = (page.rev && page.rev !== 0);
                            if (!pageDif) {
                                disp.changeWidgetProperty('cfgIdConstants::DETAIL_SUPRESSIO_BUTTON', "visible", !isRevision);
                                disp.changeWidgetProperty('cfgIdConstants::MEDIA_UPDATE_IMAGE_BUTTON', "visible", !isRevision);
                                if (disp.getGlobalState().pages["media"] && disp.getGlobalState().pages["media"][disp.getGlobalState().currentTabId]) {
                                    disp.changeWidgetProperty('cfgIdConstants::MEDIA_EDIT_BUTTON', "visible", !isRevision);
                                }
                            } else {
                                disp.changeWidgetProperty('cfgIdConstants::MEDIA_TORNAR_BUTTON', "visible", true);
                            }
                        }
                        else if (page.action === 'html_supplies_form') {
                        }
                        else if (page.action === 'html_response_form') {
                            disp.changeWidgetProperty('cfgIdConstants::SEND_MESSAGE_TO_ROLS_BUTTON', "visible", true);
                            disp.changeWidgetProperty('cfgIdConstants::SEND_LIST_TO_USERS_BUTTON', "visible", true);
                        }
                    }
                }
            }
        };
        wikiIocDispatcher.addUpdateView(updateHandler);


        // Gestió del relogin
        var relogin = function (userId, moodleToken) {
            var requestLogin = new Request();
            var moodleT = "";
            if (moodleToken && moodleToken!="" && moodleToken!=undefined && moodleToken!="null") {
                moodleT = "&moodleToken="+moodleToken;
            }
            requestLogin.urlBase = "lib/exe/ioc_ajax.php?call=login&do=relogin&userId=" + userId + moodleT;
            requestLogin.sendRequest().then(function() {
                // ALERTA[Xavi] Això només s'utilitza per depurar, per mostrar per consola quan s'ha rebut la resposta del login
                // console.log("---------- Ha arribat la resposta del login ------------");
            });
        };

        storageManager.on('change', 'login', function (e) {
            var request;
            var newState = JSON.parse(e.newValue),
                oldState = JSON.parse(e.oldValue);

            if (newState.login && (!oldState || !oldState.login) && newState.userId) {
                // No estava logejat i ara ho està
                relogin(newState.userId);
            }else if (!newState.login && oldState.login) {
                // Ara no està logejat però abans si ho estava
                request = new Request();
                request.urlBase = "lib/exe/ioc_ajax.php?call=login&do=logoff";
                request.sendRequest();

            }
            console.log("Detectats canvis al login", e);
        });

        var loginState = storageManager.findObject('login');
        if (loginState && loginState.login && !wikiIocDispatcher.getGlobalState().userId) {
            var globalState = storageManager.findObject('globalState');
            if(globalState && globalState.userState){
                relogin(loginState.userId, globalState.userState.moodleToken);
            }else{
                relogin(loginState.userId);
            }
        }

        // Objecte que gestiona el refresc de la pàgina
        var reloadStateHandler = new ReloadStateHandler(function (state) {
            // Recarreguem l'estat de l'usuari
            if (state.userState) {
                wikiIocDispatcher.getGlobalState().userState = state.userState;
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


            // Comprovem si s'ha seguit un enllaç (param id a l'URL)

            if (state.pages) {
                var np = 0;
                var length = state.pagesLength();
                var requestState = new Request();
                requestState.urlBase = "lib/exe/ioc_ajax.php";

                var infoManager = wikiIocDispatcher.getInfoManager();
                if (length === 0) {
                    infoManager.loadInfoStorage(state.infoStorage);
                    infoManager.refreshInfo();
                }

                var queryParams, page, ns, projectType, metaDataSubSet, elid;

                for (var id in state.pages) {

                    if (paramId && paramId !== state.pages[id].ns) {
                        // Si existeix el paràmetre només es carrega aquesta pàgina
                        console.log("S'està carregant un URL específic, ignorant la càrrega de ", state.pages[id].ns);
                        continue;
                    }

                    //Podriem ignorar les 'pages.undefined{}'
                    //if (typeof(state.pages.undefined) === "object") {
                    //    continue;
                    //}
                    
                    queryParams = '';

                    if (state.getContent(id).action === "view" || state.getContent(id).action === "edit") {
                        if (state.getContent(id).rev) {
                            queryParams += "rev=" + state.getContent(id).rev + "&";
                        }
                        if (state.getContent(id).projectOwner) {
                            queryParams += "projectOwner=" + state.getContent(id).projectOwner + "&";
                        }
                        if (state.getContent(id).projectSourceType) {
                            queryParams += "projectSourceType=" + state.getContent(id).projectSourceType + "&";
                        }
                        queryParams += "call=page&id=";

                    } else if (state.getContent(id).action === "form" || state.getContent(id).action === "project_edit" ) {
                        ns = state.getContent(id).ns;
                        projectType = state.getContent(id).projectType;
                        metaDataSubSet = state.getContent(id).metaDataSubSet;
                        if (metaDataSubSet === undefined) {
                            queryParams = "call=project&do=edit&ns="+ns + "&missingContentTool=true&projectType="+projectType + "&id=";
                        }else {
                            queryParams = "call=project&do=edit&ns="+ns + "&missingContentTool=true&projectType="+projectType + "&metaDataSubSet="+metaDataSubSet + "&id=";
                        }

                    } else if (state.getContent(id).action === "view_form" || state.getContent(id).action === "project_view" || state.getContent(id).action === "project_partial") {
                        queryParams = "call=project&do=";
                        if (state.getContent(id).workflowState) {
                            queryParams += "workflow&action=view";
                        }else {
                            queryParams += "view";
                        }
                        ns = state.getContent(id).ns;
                        projectType = state.getContent(id).projectType;
                        queryParams += "&ns="+ns + "&projectType="+projectType;
                        metaDataSubSet = state.getContent(id).metaDataSubSet;
                        if (metaDataSubSet !== undefined) {
                            queryParams += "&metaDataSubSet="+metaDataSubSet;
                        }
                        queryParams += "&id=";

                    } else if (state.getContent(id).action === "project_diff") {
                        page = state.getContent(id);
                        projectType = state.getContent(id).projectType;
                        queryParams = "call=project&do=diff&ns=" + page.ns + "&projectType=" + projectType;
                        if (page.rev2) {
                            queryParams += '&rev2[]=' + page.rev1;
                            queryParams += '&rev2[]=' + page.rev2;
                        } else {
                            queryParams += '&rev1=' + page.rev1;
                        }
                        queryParams += "&id=";

                    } else if (state.getContent(id).action === "admin") {
                        queryParams = "call=admin_task&do=admin&page=";
                        // fix? ns empty, load with page name
                        state.getContent(id).ns = id.substring(6);

                    } else if (state.getContent(id).action === "recents") {
                        queryParams = "call=recent&id=";
                        // fix? ns empty, load with page name
                        state.getContent(id).ns = "";

                    } else if (state.getContent(id).action === "html_supplies_form") {
                        queryParams = "call=" + id +
                                      state.getContent(id).parms +
                                      "&id=";
                        state.getContent(id).ns = id;

                    } else if (state.getContent(id).action === "html_response_form") {
                        queryParams = "call=" + id +
                                      "&grups=" + state.getContent(id).extra.grups +
                                      "&id=";
                        state.getContent(id).ns = id;

                    } else if (state.getContent(id).action === "media") {
                        queryParams = "call=media";
                        elid = state.getContent(id).ns;
                        queryParams += '&ns=' + elid + '&do=media&id=';

                    } else if (state.getContent(id).action === "mediadetails") {
                        queryParams = "call=mediadetails";
                        elid = state.getContent(id).myid;
                        queryParams += '&id=' + elid + '&image=' + elid + '&img=' + elid + '&do=media&id=';

                    } else if (state.getContent(id).action === "diff") {
                        page = state.getContent(id);
                        queryParams = 'call=diff&id=' + page.ns;

                        if (page.rev2) {
                            queryParams += '&rev2[]=' + page.rev1;
                            queryParams += '&rev2[]=' + page.rev2;
                        } else {
                            queryParams += '&rev1=' + page.rev1;
                        }

                    } else if (id === "user_profile" && state.currentTabId === "user_profile") {
                        user = state.userId;
                        queryParams = 'call=profile&page=usermanager&user='+user+'&fn[edit]['+user+']=1' + '&basura=';
                        
                    } else {
                        queryParams = "call=page&id=";
                    }
                    // console.log("Que hi ha al content state pel id?", id, state.getContent(id));

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
        // Reescrivint la URL si s'ha passat un id per paràmetre

        unload.addOnWindowUnload(function () {
            console.log("Unload");
            if (typeof(Storage) !== "undefined") {
                var state = wikiIocDispatcher.getGlobalState();
                state.freeAllPages();
                state.updateSessionStorage();
                // sessionStorage.globalState = JSON.stringify(state);
            }

            cookie("IOCForceScriptLoad", 1);

        }.bind(wikiIocDispatcher));

        // Guardar los valores por defecto de las medidas de los paneles ajustables
        wikiIocDispatcher.almacenLocal.setUpUserDefaultPanelsSize(wikiIocDispatcher);

        var tbContainer = registry.byId(wikiIocDispatcher.navegacioNodeId);
        if (tbContainer) {
            var currentNavigationPaneId = tbContainer.getChildren()[0].id;
            wikiIocDispatcher.getGlobalState().setCurrentNavigationId(currentNavigationPaneId);
        }

        var container = registry.byId(wikiIocDispatcher.metaInfoNodeId);
        containerContentToolFactory.generate(container, {dispatcher: wikiIocDispatcher});

        container = registry.byId(wikiIocDispatcher.containerNodeId);
        containerContentToolFactory.generate(container, {dispatcher: wikiIocDispatcher, isCentral: true});

        container = registry.byId(wikiIocDispatcher.navegacioNodeId);
        containerContentToolFactory.generate(container, {dispatcher: wikiIocDispatcher});

        window.addEventListener("beforeunload", function (event) {
            console.log("post-init:beforeunload"); // ALERTA! si es tanca la pestanya no es veurà aquest missatge, cal afegir un event.returnValue per mostrar el dialeg que aturarà la execució fins escollir una opció

            // es dispara al reload
            // es dispara quan es tanca la pestanya
            // es dispara quan es tanca el navegador

            // ALERTA[Xavi] Si es detectan canvis retorna a "event.returnValue" el que fa que el navegador mostri un missatge (segons el navegador el missatge serà el propi i no el que es passa com a valor, Firefox i Chrome fan servir el missatge propi)
            if (wikiIocDispatcher.getChangesManager().thereAreChangedContents()) {
                event.returnValue = LANG.notsavedyet;
            }


        });

        var ajax_call = "lib/exe/ioc_ajax.php?call=";
        var eventName = wikiIocDispatcher.getEventManager().eventName;

        // ALERTA[Xavi] Aquí es on es creen i es configuren els controladors de request
        new RequestControl(eventName.LOCK_DOCUMENT, ajax_call+"lock", true); // TODO[Xavi] Això no cal que sigui true, però s'ha de canviar com es genera el query per tot arreu si ho canviem
        new RequestControl(eventName.UNLOCK_DOCUMENT, ajax_call+"unlock", false);
        new RequestControl(eventName.CANCEL_DOCUMENT, ajax_call+"cancel", false, true);

        new RequestControl(eventName.CANCEL_PARTIAL, ajax_call+"cancel_partial", false, true);
        new RequestControl(eventName.EDIT_PARTIAL, ajax_call+"edit_partial", false, true, getValidator('PageNotRequired'));
        new RequestControl(eventName.SAVE_PARTIAL, ajax_call+"save_partial", true, true);
        new RequestControl(eventName.SAVE_PARTIAL_ALL, ajax_call+"save_partial&do=save_all", true, true);

        new RequestControl(eventName.CANCEL, ajax_call+"cancel", false, true);
        new RequestControl(eventName.SAVE, ajax_call+"save", true, true, getValidator('CanRevert'));
        new RequestControl(eventName.EDIT, ajax_call+"edit", false, true, getValidator('PageNotRequired'));

        new RequestControl(eventName.SAVE_DRAFT, ajax_call+"draft&do=save", true);
        new RequestControl(eventName.REMOVE_DRAFT, ajax_call+"draft&do=remove", true);

        //new RequestControl(eventName.VIEW_PROJECT, ajax_call+"project&do=view", true, true);
        new RequestControl(eventName.EDIT_PROJECT, ajax_call+"project&do=edit", true, true);
        new RequestControl(eventName.SAVE_PROJECT, ajax_call+"project", true, true, false, "cfgIdConstants::SAVE_PROJECT_BUTTON");//El paràmetre do i altres el posa el botó
        new RequestControl(eventName.CANCEL_PROJECT, ajax_call+"project", true, true, false, "cfgIdConstants::CANCEL_PROJECT_BUTTON");//El paràmetre do i altres el posa el botó
        new RequestControl(eventName.SAVE_PROJECT_DRAFT, ajax_call+"project&do=save_project_draft", true);
        new RequestControl(eventName.REMOVE_PROJECT_DRAFT, ajax_call+"project&do=remove_project_draft", true);

        new RequestControl(eventName.NOTIFY, ajax_call+"notify", true);
        new RequestControl(eventName.MEDIA_DETAIL, ajax_call+"mediadetails", true);
        new RequestControl(eventName.DUPLICATE_PROJECT, ajax_call+"project&do=duplicate_project", true);
        new RequestControl(eventName.RENAME_PROJECT, ajax_call+"project", true);
        new RequestControl(eventName.REMOVE_PROJECT, ajax_call+"project&do=remove_project", true);
        new RequestControl(eventName.REVERT_PROJECT, ajax_call+"project&do=revert_project", true);

        // Recuperem el contenidor de notificacions
        var inboxNotifierContainer = registry.byId('cfgIdConstants::NOTIFIER_CONTAINER_INBOX');
        var outboxNotifierContainer = registry.byId('cfgIdConstants::NOTIFIER_CONTAINER_OUTBOX');
        // wikiIocDispatcher.setNotifierContainer(notifierContainer);

        var warningContainer = registry.byId('cfgIdConstants::SYSTEM_WARNING_CONTAINER');
        // wikiIocDispatcher.setWarningContainer(warningContainer);

        var notifyManager = wikiIocDispatcher.getNotifyManager();

        notifyManager.addWarningContainer(warningContainer);
        notifyManager.addNotifyContainer('inbox', inboxNotifierContainer);
        notifyManager.addNotifyContainer('outbox', outboxNotifierContainer);

        var draftManager = wikiIocDispatcher.getDraftManager();
        draftManager.compactDrafts();
        
        var removeParamsFromURL = function () {
            if (window.location.href.indexOf('?') === -1) {
                return;
            }

            var domainPos = window.location.href.indexOf('/', window.location.href.indexOf('//')+2);
            var newURL =  window.location.href.substring(domainPos, window.location.href.lastIndexOf('?'));
            window.history.pushState(null, null, newURL);
        };
        
        // cercar l'estat
        if (typeof(Storage) !== "undefined" && sessionStorage.globalState) {
            var state = globalState.newInstance(JSON.parse(sessionStorage.globalState));
            var extraState = wikiIocDispatcher.getRequestedState();


            // Afegim un retard per assegurar-nos que els contenidors són llestos
            setTimeout(function() {
                wikiIocDispatcher.reloadFromState(state);
                if (extraState)
                    wikiIocDispatcher.reloadFromState(extraState);
            }, 100);



            
            removeParamsFromURL();
        }

        wikiIocDispatcher.updateFromState();        

    });
});

