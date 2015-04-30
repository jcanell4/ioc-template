<script type="text/javascript" src="cfgIdConstants::DOJO_SOURCE/dojo.js"></script>
<script type="text/javascript">
    require([
        "dojo/dom",
        "dojo/dom-style",
        "dojo/dom-construct", //edu
        "dojo/window",
        "ioc/wiki30/dispatcherSingleton",
        "ioc/wiki30/Request",
        "dijit/registry",
        "dojo/ready",
        "dojo/dom-style",
        "dijit/layout/ContentPane", //edu
        "ioc/wiki30/UpdateViewHandler",
        "ioc/wiki30/ReloadStateHandler",
        'dojo/_base/unload',
        "dojo/json",
        "dojo/_base/lang",
        "ioc/wiki30/GlobalState",
        "ioc/wiki30/processor/ErrorMultiFunctionProcessor",
        "ioc/gui/content/containerContentToolFactory",
        "dijit/Dialog", //edu
        "dijit/form/Form",//edu
        "dijit/form/TextBox",//edu
        "dijit/form/Button",//edu
        "dijit/layout/BorderContainer",//edu
        "ioc/gui/NsTreeContainer",//edu
        

        "ioc/gui/IocForm",
        "ioc/gui/IocButton",
        "dojo/parser",
        "dijit/MenuBar",
        "dijit/PopupMenuBarItem",
        "dijit/MenuItem",
        "dijit/Menu",
        "dijit/TitlePane",
        "ioc/gui/ResizingTabController",
        "ioc/gui/IocDropDownButton",
        "ioc/gui/IocMenuItem",
        "dijit/layout/TabContainer",
        "dijit/layout/AccordionContainer",
        "dijit/Toolbar",
        "dijit/layout/SplitContainer",
        "dijit/TooltipDialog",
        "dijit/form/TextBox",
        "ioc/gui/ContentTabDokuwikiPage",
        "ioc/gui/ContentTabDokuwikiNsTree",
        "ioc/gui/ActionHiddenDialogDokuwiki",
        "dojo/domReady!"
    ], function (dom, domStyle, domConstruct, win, wikiIocDispatcher, Request, registry,
                 ready, style, ContentPane, UpdateViewHandler,
                 ReloadStateHandler, unload, JSON, lang, globalState,
                 ErrorMultiFunctionProcessor, containerContentToolFactory,
                 Dialog, Form, TextBox, Button, BorderContainer, NsTreeContainer
    ) {
        //declaració de funcions

        var divMainContent = dom.byId("cfgIdConstants::MAIN_CONTENT");
        if (!divMainContent) {
            return;
        }

        var h = 100 * (win.getBox().h - 55) / win.getBox().h;
        domStyle.set(divMainContent, "height", h + "%");

        wikiIocDispatcher.containerNodeId = "cfgIdConstants::BODY_CONTENT";
        wikiIocDispatcher.navegacioNodeId = "cfgIdConstants::ZONA_NAVEGACIO";
        wikiIocDispatcher.metaInfoNodeId = "cfgIdConstants::ZONA_METAINFO";
        wikiIocDispatcher.infoNodeId = "cfgIdConstants::ZONA_MISSATGES";
        wikiIocDispatcher.sectokManager.putSectok("cfgIdConstants::SECTOK_ID", "cfgIdConstants::SECTOK");
        wikiIocDispatcher.loginButtonId = 'cfgIdConstants::LOGIN_BUTTON';
        wikiIocDispatcher.exitButtonId = 'cfgIdConstants::EXIT_BUTTON';
        wikiIocDispatcher.editButtonId = 'cfgIdConstants::EDIT_BUTTON';
        wikiIocDispatcher.saveButtonId = 'cfgIdConstants::SAVE_BUTTON';
        wikiIocDispatcher.cancelButtonId = 'cfgIdConstants::CANCEL_BUTTON';
        wikiIocDispatcher.previewButtonId = 'cfgIdConstants::PREVIEW_BUTTON';
        wikiIocDispatcher.edParcButtonId = 'cfgIdConstants::ED_PARC_BUTTON';
        wikiIocDispatcher.userButtonId = 'cfgIdConstants::USER_BUTTON';
        wikiIocDispatcher.mediaDetailButtonId = 'cfgIdConstants::MEDIA_DETAIL_BUTTON';


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
                    } else if (page.action === 'media') {
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
                sessionStorage.globalState = JSON.stringify(wikiIocDispatcher.getGlobalState());
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

            //Revisar afegit al fer el merge des del master. Afegeix la creació 
            //d'un quadre de diàleg en clicar el botó nou
            var newButton = registry.byId('cfgIdConstants::NEW_BUTTON');
            if (newButton) {

                newButton.on('click', function () {

                    var dialog = registry.byId("newDocumentDlg");

                    if(!dialog){
                        dialog = new Dialog({
                            id:"newDocumentDlg",
                            title: newButton.dialogTitle,
                            style: "width: 470px; height: 250px;",
                            newButton: newButton
                        });

                        dialog.on('show', function () {
                            dom.byId('textBoxEspaiNoms').value=this.nsActivePageText();
                        });

                        dialog.nsActivePageText = function () {
                            var nsActivePage = '';
                            if (this.newButton.dispatcher.getGlobalState().currentTabId != null) {
                                var nsActivePage = this.newButton.dispatcher.getGlobalState().pages[this.newButton.dispatcher.getGlobalState().currentTabId]['ns'] || '';
                                nsActivePage = nsActivePage.split(':')
                                nsActivePage.pop();
                                var len = nsActivePage.length;
                                if (len > 1) {
                                    nsActivePage = nsActivePage.join(':');
                                }
                            }
                            return nsActivePage;
                        }


                        var bc = new BorderContainer({
                            style: "height: 200px; width: 450px;"
                        });

                        // create a ContentPane as the left pane in the BorderContainer
                        var cpEsquerra = new ContentPane({
                            region: "left",
                            style: "width: 170px"
                        });
                        bc.addChild(cpEsquerra);

                        // create a ContentPane as the center pane in the BorderContainer
                        var cpDreta = new ContentPane({
                            region: "center"
                        });
                        bc.addChild(cpDreta);

                        // put the top level widget into the document, and then call startup()
                        bc.placeAt(dialog.containerNode);

                        // Un formulari a la banda esquerre contenint:
                        var divesquerra = domConstruct.create('div', {
                            className: 'esquerra'
                        },cpEsquerra.containerNode);

                        var form = new Form().placeAt(divesquerra);

                        // Un camp de text per poder escriure l'espai de noms
                        var divEspaiNoms = domConstruct.create('div', {
                            className: 'divEspaiNoms'
                        },form.containerNode);

                        domConstruct.create('label', {
                            innerHTML: newButton.EspaideNomslabel + '<br>'
                        },divEspaiNoms);

                        var EspaiNoms = new TextBox({
                            id: 'textBoxEspaiNoms',
                            value: dialog.nsActivePageText(),
                            placeHolder: newButton.EspaideNomsplaceHolder
                        }).placeAt(divEspaiNoms);
                        dialog.textBoxEspaiNoms = EspaiNoms;

                        // Un camp de text per poder escriure el nom del nou document
                        var divNouDocument = domConstruct.create('div', {
                            className: 'divNouDocument'
                        },form.containerNode);

                        domConstruct.create('label', {
                            innerHTML: '<br>' + newButton.NouDocumentlabel + '<br>'
                        }, divNouDocument);

                        var NouDocument = new TextBox({
                            placeHolder: newButton.NouDocumentplaceHolder
                        }).placeAt(divNouDocument);

                        //L'arbre de navegació a la banda dreta del quadre.
                        var divdreta = domConstruct.create('div', {
                            className: 'dreta'
                        },cpDreta.containerNode);

                        var dialogTree = new NsTreeContainer({
                            treeDataSource: 'lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/',
                            onlyDirs:true
                        }).placeAt(divdreta);
                        dialogTree.startup();

                        dialogTree.tree.onClick=function(item) {
                            dom.byId('textBoxEspaiNoms').value= item.id;
                            dom.byId('textBoxEspaiNoms').focus();
                        }

                        // botons
                        var botons = domConstruct.create('div', {
                            className: 'botons'
                        },form.containerNode);

                        domConstruct.create('label', {
                            innerHTML: '<br><br>'
                        }, botons);

                        new Button({
                          label: newButton.labelButtonAcceptar,
                          onClick: function(){
                                if (NouDocument.value !== '') {
                                    var separacio = '';
                                    if (EspaiNoms.value !== '') {
                                        separacio = ':';
                                    }
                                    var query = 'do=new&id=' + EspaiNoms.value + separacio + NouDocument.value;
                                    newButton.sendRequest(query);
                                    dialog.hide();
                                }
                          }
                        }).placeAt(botons);

                        // El botó de cancel·lar
                        new Button({
                          label: newButton.labelButtonCancellar,
                          onClick: function(){ dialog.hide();}
                        }).placeAt(botons);

                        form.startup();
                    }
                    dialog.show();
                    return false;
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
                    requestUser.sendRequest(userDialog.getQuery());
                });
                userDialog.addProcessor(processorUser.type, processorUser);
            }

            userDialog = registry.byId('cfgIdConstants::TALK_USER_MENU_ITEM');
            if (userDialog) {
                var processorTalk = new ErrorMultiFunctionProcessor();
                var requestTalk = new Request();
                requestTalk.urlBase = "lib/plugins/ajaxcommand/ajax.php?call=new_page";
                processorTalk.addErrorAction("1001", function () {
                    requestTalk.sendRequest(userDialog.getQuery());
                });
                userDialog.addProcessor(processorTalk.type, processorTalk);
            }

            var centralContainer = registry.byId(wikiIocDispatcher.containerNodeId);


            if (centralContainer) {

                //TODO[Xavi] mirar si aquest bloc es pot moure al ContainerContentTool o EditorContentTool

                centralContainer.watch("selectedChildWidget", lang.hitch(centralContainer, function (name, oldTab, newTab) {
                    // Aquest codi es crida només quan canviem de pestanya

                    if (wikiIocDispatcher.getContentCache(newTab.id)) {
                        //wikiIocDispatcher.setCurrentDocument(newTab.id);
                        newTab.setCurrentDocument(newTab.id);
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

                }));
            }

            //cercar l'estat
            if (typeof(Storage) !== "undefined" && sessionStorage.globalState) {
                var state = globalState.newInstance(JSON.parse(sessionStorage.globalState));

                wikiIocDispatcher.reloadFromState(state);
            }

            // Establim el panell d'informació actiu
            var currentNavigationPaneId = state ? state.getCurrentNavigationId() : null;

            if (!currentNavigationPaneId && tbContainer.hasChildren()) {
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
</script>
