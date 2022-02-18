//include "dijit/registry"
//include "dojo/dom"
//include "dojo/dom-construct"; alias "domConstruct"
//include "dojo/dom-style"; alias "domStyle"
//include "dijit/layout/BorderContainer"
//include "dijit/Dialog"
//include "dijit/layout/ContentPane"
//include "dijit/form/Form"
//include "dijit/form/Textarea"
//include "dijit/form/TextBox"
//include "dijit/form/Button"
//include "dijit/form/ComboBox"
//include "dojo/store/JsonRest"
//include "ioc/functions/normalitzaCaracters"

var sendlistButton = registry.byId('cfgIdConstants::SEND_LIST_TO_USERS_BUTTON');

if (sendlistButton) {
    sendlistButton.onClick = function () {
        var dialog = registry.byId("sendmessageDocumentDlg");
        var grups = sendlistButton.dispatcher.getGlobalState().pages[sendlistButton.parent]['extra']['grups'];

        if (!dialog){
            dialog = new Dialog({
                id: "sendmessageDocumentDlg",
                title: sendlistButton.dialogTitle,
                style: "width:300px; height:350px;",
                sendlistButton: sendlistButton
            });

            dialog.on('hide', function () {
                dialog.destroyRecursive(false);
                domConstruct.destroy("sendmessageDocumentDlg");
            });
            
            dialog.on('show', function () {
                dom.byId('comboUsuaris').focus();
                dom.byId('textAreaMissatge').value = "";
                dom.byId('textBoxLlistaUsuaris').value = "";
            });

            dialog.storeListUsuaris = function(n,o,e) {
                dom.byId('textBoxLlistaUsuaris').value += e + ",";
                LlistaUsuaris.value = textBoxLlistaUsuaris.value;
            };

            var bc = new BorderContainer({
                style: "width:280px; height:300px;"
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

            //DIV ROLS DESTINATARIS Un div per contenir la selecció de Usuaris
            var divUsuaris = domConstruct.create('div', {
                className: 'divUsuaris'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: sendlistButton.labelUsuaris + '<br>'
            },divUsuaris);

            //Un combo per seleccionar els rols dels destinataris
            var selectUsuaris = new ComboBox({
                id: 'comboUsuaris',
                placeHolder: sendlistButton.placeholderUsuaris,
                name: 'users',
                value: '',
                store: new JsonRest({target: sendlistButton.urlListUsuaris})
            }).placeAt(divUsuaris);
            dialog.comboUsuaris = selectUsuaris;
            dialog.comboUsuaris.startup();
            dialog.comboUsuaris.watch('value', dialog.storeListUsuaris);

            //DIV LLISTA DE ROLS Un camp de text per contenir la llista d'Usuaris
            var divLlistaUsuaris = domConstruct.create('div', {
                className: 'divLlistaUsuaris'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>' + sendlistButton.labelLlista + '<br>'
            },divLlistaUsuaris);

            var LlistaUsuaris = new TextBox({
                id: 'textBoxLlistaUsuaris'
            }).placeAt(divLlistaUsuaris);
            dialog.textBoxLlistaUsuaris = LlistaUsuaris;

            //DIV MISSATGE Un camp de text per poder escriure el missatge
            var divMissatge = domConstruct.create('div', {
                className: 'divMissatge'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>' + sendlistButton.labelMissatge + '<br>'
            },divMissatge);

            var Missatge = new Textarea({
                id: 'textAreaMissatge',
                placeHolder: sendlistButton.placeholderMissatge,
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
                    if (LlistaUsuaris.value !== '') {
                        var query = 'call=' + sendlistButton.call +
                                    '&id=' + sendlistButton.parent +
                                    '&grups=' + grups +
                                    '&users=' + LlistaUsuaris.value +
                                    '&message=' + Missatge.value;
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
