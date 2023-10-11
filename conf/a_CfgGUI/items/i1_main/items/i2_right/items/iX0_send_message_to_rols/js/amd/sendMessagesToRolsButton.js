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

var sendmessageButton = registry.byId('cfgIdConstants::SEND_MESSAGE_TO_ROLS_BUTTON');

if (sendmessageButton) {
    sendmessageButton.onClick = function () {
        var dialog = registry.byId("sendmessageDocumentDlg");
        var checkedItems = [];
        var grups = sendmessageButton.dispatcher.getGlobalState().pages[sendmessageButton.parent]['extra']['grups'];

        if (!dialog){
            dialog = new Dialog({
                id: "sendmessageDocumentDlg",
                title: sendmessageButton.dialogTitle,
                style: "width:300px; height:350px;",
                sendmessageButton: sendmessageButton
            });

            dialog.on('hide', function () {
                dialog.destroyRecursive(false);
                domConstruct.destroy("sendmessageDocumentDlg");
            });
            
            dialog.on('show', function () {
                dom.byId('textAreaMissatge').value = "";
                dom.byId('textBoxLlistaRols').value = "";
                dom.byId('comboRols').focus();
                dialog.getCheckedItems();
            });

            dialog.getCheckedItems = function() {
                var $form = jQuery(dom.byId("dw__" + sendmessageButton.parent));
                var c = 0;
                for (var i=0; i<$form[0].length; i++) {
                    if ($form[0][i].type == "checkbox" && $form[0][i].checked) {
                        checkedItems[c++] = $form[0][i].alt;
                    }
                }
            };

            dialog.storeListRols = function(n,o,e) {
                dom.byId('textBoxLlistaRols').value += e + ",";
                LlistaRols.value = textBoxLlistaRols.value;
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

            //DIV ROLS DESTINATARIS Un div per contenir la selecció de Rols
            var divRols = domConstruct.create('div', {
                className: 'divRols'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: sendmessageButton.labelRols + '<br>'
            },divRols);

            //Un combo per seleccionar els rols dels destinataris
            var selectRols = new ComboBox({
                id: 'comboRols',
                placeHolder: sendmessageButton.placeholderRols,
                name: 'rols',
                value: '',
                store: new JsonRest({target: sendmessageButton.urlListRols})
            }).placeAt(divRols);
            dialog.comboRols = selectRols;
            dialog.comboRols.startup();
            dialog.comboRols.watch('value', dialog.storeListRols);

            //DIV LLISTA DE ROLS Un camp de text per contenir la llista de Rols
            var divLlistaRols = domConstruct.create('div', {
                className: 'divLlistaRols'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>' + sendmessageButton.labelLlista + '<br>'
            },divLlistaRols);

            var LlistaRols = new TextBox({
                id: 'textBoxLlistaRols'
            }).placeAt(divLlistaRols);
            dialog.textBoxLlistaRols = LlistaRols;

            //DIV MISSATGE Un camp de text per poder escriure el missatge
            var divMissatge = domConstruct.create('div', {
                className: 'divMissatge'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>' + sendmessageButton.labelMissatge + '<br>'
            },divMissatge);

            var Missatge = new Textarea({
                id: 'textAreaMissatge'
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
                label: sendmessageButton.labelButtonAcceptar,
                
                onClick: function(){
                    if (LlistaRols.value !== '') {
                        var query = 'call=' + sendmessageButton.call +
                                    '&grups=' + grups +
                                    '&rols=' + LlistaRols.value +
                                    '&message=' + Missatge.value +
                                    '&type=warning' +
                                    '&send_email=true' +
                                    '&checked_items=' + JSON.stringify(checkedItems);
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
