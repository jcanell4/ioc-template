//include "dijit/registry"
//include "dojo/dom"
//include "dojo/dom-construct"; alias "domConstruct"
//include "dojo/dom-style"; alias "domStyle"
//include "dijit/layout/BorderContainer"
//include "dijit/Dialog"
//include "dijit/layout/ContentPane"
//include "dijit/form/Form"
//include "dijit/form/Textarea"
//include "dijit/form/Button"
//include "ioc/widgets/WidgetFactory"

var sendlistButton = registry.byId('cfgIdConstants::SEND_LIST_TO_USERS_BUTTON');

if (sendlistButton) {
    sendlistButton.onClick = function () {
        var dialog = registry.byId("sendmessageDocumentDlg");
        var globalState = sendlistButton.dispatcher.getGlobalState();
        var grups = globalState.pages[sendlistButton.parent]['extra']['grups'];

        if (!dialog){
            dialog = new Dialog({
                id: "sendmessageDocumentDlg",
                title: sendlistButton.dialogTitle,
                style: "width:400px; height:350px;",
                sendlistButton: sendlistButton
            });

            dialog.on('hide', function () {
                dialog.destroyRecursive(false);
                domConstruct.destroy("sendmessageDocumentDlg");
            });
            
            dialog.on('show', function () {
                dom.byId('llistaUsuaris').value = "";
                dom.byId('textAreaMissatge').value = "";
                dom.byId('llistaUsuaris').focus();
            });

            dialog.creaWidget = function(id) {
                var data = {class: sendlistButton.widgetClass,
                            label: sendlistButton.widgetLabel,
                            type: sendlistButton.widgetType,
                            data: {
                                ns: globalState.getCurrentId(),
                                token: globalState.sectok,
                                searchDataUrl: sendlistButton.widgetSearchDataUrl,
                                dialogTitle: sendlistButton.widgetDialogTitle,
                                buttonLabel: sendlistButton.widgetButtonLabel,
                                dialogButtonLabel: sendlistButton.widgetDialogButtonLabel,
                                fieldName: sendlistButton.widgetFieldName,
                                fieldId: sendlistButton.widgetFieldId,
                                defaultEntryField: sendlistButton.widgetDefaultEntryField,
                                fields: {
                                    username: "Nom d'usuari",
                                    name: "Nom"
                                },
                                data: []
                            }
                };
                addWidgetToNode(data, id);
            };

            var bc = new BorderContainer({
                style: "width:380px; height:300px;"
            });

            // create a ContentPane as the center pane in the BorderContainer
            var cpCentre = new ContentPane({
                region: "center"
            });
            bc.addChild(cpCentre);

            // put the top level widget into the document, and then call startup()
            bc.placeAt(dialog.containerNode);

            // Un contenidor pel formulari
            var divcentre = domConstruct.create('div', {
                className: 'dreta'
            },cpCentre.containerNode);

            var form = new Form().placeAt(divcentre);

            //DIV LLISTA DE ROLS Un camp de text per contenir la llista d'Usuaris
            var divLlistaUsuaris = domConstruct.create('div', {
                className: 'divLlistaUsuaris'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>' + sendlistButton.labelLlistaUsuaris + '<br>'
            },divLlistaUsuaris);

            var llistaUsuaris = domConstruct.create('span', {
                id: 'llistaUsuaris',
                data: dialog.creaWidget('llistaUsuaris')
            },divLlistaUsuaris);

            //DIV MISSATGE Un camp de text per poder escriure el missatge
            var divMissatge = domConstruct.create('div', {
                className: 'divMissatge'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>' + sendlistButton.labelMissatge + '<br>'
            },divMissatge);

            var Missatge = new Textarea({
                id: 'textAreaMissatge',
                placeHolder: sendlistButton.placeholderMissatge
            }).placeAt(divMissatge);
            dialog.textAreaMissatge = Missatge;


            // botons
            var botons = domConstruct.create('div', {
                className: 'botons',
                style: "text-align:center;"
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br><br>'
            }, botons);

            new Button({
                label: sendlistButton.labelButtonAcceptar,
                
                onClick: function(){
                    if (llistaUsuaris.textContent !== '' && Missatge.value !== "") {
                        var u, usuaris = [];
                        var llista = llistaUsuaris.textContent;
                        llista = llista.replace(/[ \n●]/g, "");
                        u = llista.split("x");
                        for (var i=0; i<u.length; i++) {
                            if (u[i] !== "") {
                                usuaris[i] = u[i].replace(/.*<(.*)>/, "$1");
                            }
                        }
                        var query = 'call=' + sendlistButton.call +
                                    '&id=' + sendlistButton.parent +
                                    '&grups=' + grups +
                                    '&to=' + usuaris.toString() +
                                    '&message=' + Missatge.value +
                                    '&type=warning' +
                                    '&send_email=true';
                        sendlistButton.sendRequest(query);
                        dialog.hide();
                    }
                }
            }).placeAt(botons);

            // Botó cancel·lar
            new Button({
                label: sendlistButton.labelButtonCancellar,
                onClick: function(){dialog.hide();}
            }).placeAt(botons);

            form.startup();
        }
        dialog.show();
        return false;
    };
}
