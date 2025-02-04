//include "dijit/registry"
//include "dojo/dom"
//include "dojo/dom-construct"; alias "domConstruct"
//include "dijit/layout/BorderContainer"
//include "dijit/Dialog"
//include "dijit/layout/ContentPane"
//include "dijit/form/Form"
//include "dijit/form/TextBox"
//include "dijit/form/Button"
//include "ioc/gui/NsTreeContainer"
//include "ioc/functions/normalitzaCaracters"

var duplicateFolderButton = registry.byId('cfgIdConstants::DUPLICATE_FOLDER_BUTTON');
if (duplicateFolderButton) {

    duplicateFolderButton.onClick = function () {
        var path = [];
        var dialog = registry.byId("newDocumentDlg");

        if(!dialog){
            dialog = new Dialog({
                id: "newDocumentDlg",
                title: duplicateFolderButton.dialogTitle,
                style: "width: 470px; height: 350px;",
                duplicateFolderButton: duplicateFolderButton
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
                if (this.duplicateFolderButton.dispatcher.getGlobalState().currentTabId) {
                    var stPath = "";
                    var aPath = this.duplicateFolderButton.dispatcher.getGlobalState().getContent(this.duplicateFolderButton.dispatcher.getGlobalState().currentTabId)['ns'] || '';
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
            };

            dialog.getNsDesti = function(ns) {
                //ns = ns.split(':');
                //ns.pop();
                //return ns.join(":");
                return ns+"_dup";
            };

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
                innerHTML: duplicateFolderButton.DirectoriOrigenlabel + '<br>'
            },divDirectoriOrigen);

            var DirectoriOrigen = new TextBox({
                id: 'textBoxDirectoriOrigen',
                placeHolder: duplicateFolderButton.DirectoriOrigenplaceHolder
            }).placeAt(divDirectoriOrigen);
            dialog.textBoxDirectoriOrigen = DirectoriOrigen;

            //ESPAI DE NOMS DESTÍ Un camp de text per poder escriure l'espai de noms de destí
            var divDirectoriDesti = domConstruct.create('div', {
                className: 'divDirectoriDesti'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br><br>' + duplicateFolderButton.DirectoriDestilabel + '<br>'
            },divDirectoriDesti);

            var DirectoriDesti = new TextBox({
                id: 'textBoxDirectoriDesti',
                placeHolder: duplicateFolderButton.DirectoriDestiplaceHolder
            }).placeAt(divDirectoriDesti);
            dialog.textBoxDirectoriDesti = DirectoriDesti;


            // botons
            var botons = domConstruct.create('div', {
                className: 'botons',
                style: "text-align:center;"
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br><br>'
            }, botons);

            new Button({
                label: duplicateFolderButton.labelButtonAcceptar,
                
                onClick: function(){
                    if (DirectoriOrigen.value !== '' && DirectoriDesti.value !== '') {
                        var query = 'call=duplicate_folder' + 
                                    '&do=copy' + 
                                    '&old_folder_name=' + normalitzaCaracters(DirectoriOrigen.value, true) +
                                    '&new_folder_name=' + normalitzaCaracters(DirectoriDesti.value, true);
                        duplicateFolderButton.sendRequest(query);
                        dialog.hide();
                    }
                }
            }).placeAt(botons);

            // Botó cancel·lar
            new Button({
              label: duplicateFolderButton.labelButtonCancellar,
              onClick: function(){dialog.hide();}
            }).placeAt(botons);

            form.startup();
        }
        dialog.nsActivePage();
        dialog.show();
        return false;
    };
}