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
                dom.byId('textBoxNomOrigen').value = path[path.length-1] || "";
                dom.byId('textBoxNomOrigen').focus();
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

            dialog.setDefaultDocumentName = function(n,o,e) {
                dom.byId('textBoxNomCarpeta').value = e;
                dom.byId('textBoxNomCarpeta').focus();
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
                dom.byId('textBoxNomOrigen').value= item.id;
                dom.byId('textBoxNomOrigen').focus();
            };

            // Un formulari a la banda dreta contenint:
            var divdreta = domConstruct.create('div', {
                className: 'dreta'
            },cpDreta.containerNode);

            var form = new Form().placeAt(divdreta);

            //ESPAI DE NOMS ORIGEN Un camp de text per poder escriure l'espai de noms origen
            var divNomOrigen = domConstruct.create('div', {
                className: 'divNomOrigen'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: renameButton.NomOrigenlabel + '<br>'
            },divNomOrigen);

            var NomOrigen = new TextBox({
                id: 'textBoxNomOrigen',
                placeHolder: renameButton.NomOrigenplaceHolder
            }).placeAt(divNomOrigen);
            dialog.textBoxNomOrigen = NomOrigen;

            //DIV NOU NOM: Un camp de text per poder escriure el nou nom de la carpeta
            var divNomCarpeta = domConstruct.create('div', {
                id: 'id_divNomCarpeta',
                className: 'divNomCarpeta'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>' + renameButton.NomCarpetalabel + '<br>'
            }, divNomCarpeta);

            var NomCarpeta = new TextBox({
                id: "textBoxNomCarpeta",
                placeHolder: renameButton.NomCarpetaplaceHolder
            }).placeAt(divNomCarpeta);


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
                    if (NomCarpeta.value !== '') {
                        var query = 'call=rename_folder' + 
                                    '&do=rename' + 
                                    '&old_folder_name=' + normalitzaCaracters(NomOrigen.value, true) +
                                    '&new_folder_name=' + normalitzaCaracters(NomCarpeta.value, true);
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