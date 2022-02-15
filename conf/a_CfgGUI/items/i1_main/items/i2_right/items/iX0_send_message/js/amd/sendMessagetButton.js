//include "dijit/registry"
//include "dojo/dom"
//include "dojo/dom-construct"; alias "domConstruct"
//include "dojo/dom-style"; alias "domStyle"
//include "dijit/layout/BorderContainer"
//include "dijit/Dialog"
//include "dijit/layout/ContentPane"
//include "dijit/form/Form"
//include "dijit/form/Button"
//include "dojox/form/MultiComboBox"
//include "dojo/store/JsonRest"
//include "ioc/functions/normalitzaCaracters"

var sendmessageButton = registry.byId('cfgIdConstants::SEND_MESSAGE_BUTTON');
if (sendmessageButton) {

    sendmessageButton.onClick = function () {
        var dialog = registry.byId("newDocumentDlg");

        if(!dialog){
            dialog = new Dialog({
                id: "newDocumentDlg",
                title: sendmessageButton.dialogTitle,
                style: "width: 270px; height: 350px;",
                sendmessageButton: sendmessageButton
            });

            dialog.on('hide', function () {
                dialog.destroyRecursive(false);
                domConstruct.destroy("newDocumentDlg");
            });
            
            dialog.on('show', function () {
                dom.byId('comboRolsDestinataris').focus();
            });

            var bc = new BorderContainer({
                style: "width: 250px; height: 300px;"
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

            //DIV ROLS DESTINATARIS Un div per contenir la selecció de Rols
            var divRolsDestinataris = domConstruct.create('div', {
                className: 'divRolsDestinataris'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>' + sendmessageButton.RolsDestinatarislabel + '<br>'
            },divRolsDestinataris);

            //Un combo per seleccionar els rols dels destinataris
            var selectRols = new MultiComboBox({
                id: 'comboRolsDestinataris',
                placeHolder: sendmessageButton.RolsDestinatarisplaceHolder,
                name: 'rols',
                value: "",
                autoComplete: true,
                searchAttr: 'name',
                store: new JsonRest({target: sendmessageButton.urlListRols})
            }).placeAt(divRolsDestinataris);
            dialog.comboRols = selectRols;
            dialog.comboRols.startup();
            dialog.comboRols.watch('value', dialog.switchBloc );


            // botons
            var botons = domConstruct.create('div', {
                className: 'botons',
                style: "text-align:center;"
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br><br>'
            }, botons);

            new Button({
                label: sendmessageButton.labelButtonAcceptar,
                
                onClick: function(){
                    if (selectRols.value !== '') {
                        var query = 'call=send_message' +
                                    '&' + sendmessageButton.query +
                                    '&rolsdestinataris=' + normalitzaCaracters(selectRols.value, true);
                        sendmessageButton.sendRequest(query);
                        dialog.hide();
                    }
                }
            }).placeAt(botons);

            // Botó cancel·lar
            new Button({
                label: sendmessageButton.labelButtonCancellar,
                onClick: function(){dialog.hide();}
            }).placeAt(botons);

            form.startup();
        }
        dialog.show();
        return false;
    };
}
