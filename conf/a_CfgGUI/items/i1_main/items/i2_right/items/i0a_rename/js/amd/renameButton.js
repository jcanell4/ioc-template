//include "dijit/registry"
//include "dojo/dom"
//include "dojo/dom-construct"; alias "domConstruct"
//include "dojo/dom-style"; alias "domStyle"
//include "dijit/layout/BorderContainer"
//include "dijit/Dialog"
//include "dijit/layout/ContentPane"
//include "dijit/form/Form"
//include "dijit/form/TextBox"
//include "dijit/form/Button"
//include "dijit/form/ComboBox"
//include "dojo/store/JsonRest"
//include "ioc/gui/NsTreeContainer"
//include "ioc/functions/normalitzaCaracters"

var renameButton = registry.byId('cfgIdConstants::RENAME_FOLDER_BUTTON');
if (renameButton) {

    renameButton.onClick = function () {
        var path = [];
        var dialog = registry.byId("newDocumentDlg");

        if(!dialog){
            dialog = new Dialog({
                id: "newDocumentDlg",
                title: renameButton.dialogTitle,
                style: "width: 470px; height: 350px;",
                renameButton: renameButton
            });

            dialog.on('hide', function () {
                dialog.destroyRecursive(false);
                domConstruct.destroy("newDocumentDlg");
            });
            
            dialog.on('show', function () {
                dom.byId('textBoxDirectoriOrigen').value = path[path.length-1] || "";
                dom.byId('textBoxDirectoriOrigen').focus();
            });

            dialog.nsActivePage = function (){
                path.length=0;
                if (this.renameButton.dispatcher.getGlobalState().currentTabId) {
                    var stPath = "";
                    var aPath = this.renameButton.dispatcher.getGlobalState().getContent(this.renameButton.dispatcher.getGlobalState().currentTabId)['ns'] || '';
                    aPath = aPath.split(':');
                    aPath.pop();
                    aPath.unshift("");
                    for (var i=0; i<aPath.length; i++) {
                        if (i > 1) {
                            stPath = stPath + ":";
                        }
                        stPath = stPath + aPath[i];
                        path[i]=stPath;
                    }
                }    
            };

            var bc = new BorderContainer({
                style: "height: 300px; width: 450px;"
            });

            // create a ContentPane as the left pane in the BorderContainer
            var cpEsquerra = new ContentPane({
                region: "left",
                style: "width: 220px"
            });
            bc.addChild(cpEsquerra);

            // create a ContentPane as the center pane in the BorderContainer
            var cpDreta = new ContentPane({
                region: "center"
            });
            bc.addChild(cpDreta);

            // put the top level widget into the document, and then call startup()
            bc.placeAt(dialog.containerNode);

            //L'arbre de navegació a la banda esquerra del quadre.
            var divizquierda = domConstruct.create('div', {
                className: 'izquierda'
            },cpEsquerra.containerNode);

            var dialogTree = new NsTreeContainer({
                treeDataSource: 'lib/exe/ioc_ajaxrest.php/ns_tree_rest/',
                onlyDirs:true,
                hiddenProjects:true
            }).placeAt(divizquierda);
            dialogTree.startup();

            dialog.dialogTree = dialogTree;

            dialogTree.tree.onClick=function(item) {
                dom.byId('textBoxDirectoriOrigen').value = item.id;
                dom.byId('textBoxDirectoriOrigen').focus();  //borra el placeholder
                dom.byId('textBoxDirectoriDesti').value = dialog.getNsDesti(item.id);
                dom.byId('textBoxDirectoriDesti').focus();
                dom.byId('textBoxNouNomCarpeta').focus();
            };

            dialog.getNsDesti = function(ns) {
                ns = ns.split(':');
                ns.pop();
                return ns.join(":");
            }

            // Un formulari a la banda dreta contenint:
            var divdreta = domConstruct.create('div', {
                className: 'dreta'
            },cpDreta.containerNode);

            var form = new Form().placeAt(divdreta);

            //ESPAI DE NOMS ORIGEN Un camp de text per poder escriure l'espai de noms origen
            var divDirectoriOrigen = domConstruct.create('div', {
                className: 'divDirectoriOrigen'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: renameButton.DirectoriOrigenlabel + '<br>'
            },divDirectoriOrigen);

            var DirectoriOrigen = new TextBox({
                id: 'textBoxDirectoriOrigen',
                placeHolder: renameButton.DirectoriOrigenplaceHolder
            }).placeAt(divDirectoriOrigen);
            dialog.textBoxDirectoriOrigen = DirectoriOrigen;

            //ESPAI DE NOMS DESTÍ Un camp de text per poder escriure l'espai de noms de destí
            var divDirectoriDesti = domConstruct.create('div', {
                className: 'divDirectoriDesti'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br><br>' + renameButton.DirectoriDestilabel + '<br>'
            },divDirectoriDesti);

            var DirectoriDesti = new TextBox({
                id: 'textBoxDirectoriDesti',
                placeHolder: renameButton.DirectoriDestiplaceHolder
            }).placeAt(divDirectoriDesti);
            dialog.textBoxDirectoriDesti = DirectoriDesti;

            //DIV NOU NOM: Un camp de text per poder escriure el nou nom de la carpeta
            var divNouNomCarpeta = domConstruct.create('div', {
                id: 'id_divNouNomCarpeta',
                className: 'divNouNomCarpeta'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>' + renameButton.NouNomCarpetalabel + '<br>'
            }, divNouNomCarpeta);

            var NouNomCarpeta = new TextBox({
                id: "textBoxNouNomCarpeta",
                placeHolder: renameButton.NouNomCarpetaplaceHolder
            }).placeAt(divNouNomCarpeta);


            // botons
            var botons = domConstruct.create('div', {
                className: 'botons',
                style: "text-align:center;"
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br><br>'
            }, botons);

            new Button({
                label: renameButton.labelButtonAcceptar,
                
                onClick: function(){
                    if (DirectoriOrigen.value !== '' && NouNomCarpeta.value !== '') {
                        var ns_desti = '';
                        if (DirectoriDesti.value !== '') {
                            ns_desti = normalitzaCaracters(DirectoriDesti.value, true) + ':';
                        }
                        var query = 'call=rename_folder' + 
                                    '&do=rename' + 
                                    '&old_folder_name=' + normalitzaCaracters(DirectoriOrigen.value, true) +
                                    '&new_folder_name=' + ns_desti + normalitzaCaracters(NouNomCarpeta.value);
                        renameButton.sendRequest(query);
                        dialog.hide();
                    }
                }
            }).placeAt(botons);

            // Botó cancel·lar
            new Button({
              label: renameButton.labelButtonCancellar,
              onClick: function(){dialog.hide();}
            }).placeAt(botons);

            form.startup();
        }
        dialog.nsActivePage();
        dialog.show();
        return false;
    };
}