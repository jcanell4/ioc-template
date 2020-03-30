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

var renameButton = registry.byId('cfgIdConstants::RENAME_FOLDER_BUTTON');
if (renameButton) {

    renameButton.onClick = function () {
        var defaultProject = 'defaultProject';
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
                dialog.dialogTree.tree.set('path',path).then(function(){
                    dom.byId('textBoxNouNom').focus();
                });
                dom.byId('textBoxEspaiNoms').value = path[path.length-1] || "";
                dom.byId('textBoxEspaiNoms').focus();
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
                dom.byId('textBoxNouNom').value = e;
                dom.byId('textBoxNouNom').focus();
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
                dom.byId('textBoxEspaiNoms').value= item.id;
                dom.byId('textBoxEspaiNoms').focus();
            };

            // Un formulari a la banda dreta contenint:
            var divdreta = domConstruct.create('div', {
                className: 'dreta'
            },cpDreta.containerNode);

            var form = new Form().placeAt(divdreta);

            //ESPAI DE NOMS Un camp de text per poder escriure l'espai de noms
            var divEspaiNoms = domConstruct.create('div', {
                className: 'divEspaiNoms'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: renameButton.EspaideNomslabel + '<br>'
            },divEspaiNoms);

            var EspaiNoms = new TextBox({
                id: 'textBoxEspaiNoms',
                placeHolder: renameButton.EspaideNomsplaceHolder
            }).placeAt(divEspaiNoms);
            dialog.textBoxEspaiNoms = EspaiNoms;

            //DIV NOU NOM: Un camp de text per poder escriure el nou nom del directori
            var divNouNom = domConstruct.create('div', {
                id: 'id_divNouNom',
                className: 'divNouNom'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>' + renameButton.NouNomlabel + '<br>'
            }, divNouNom);

            var NouNom = new TextBox({
                id: "textBoxNouNom",
                placeHolder: renameButton.NouNomplaceHolder
            }).placeAt(divNouNom);


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
                
                _normalitzaCaracters: function(cadena, preserveSep) {
                    cadena = cadena.toLowerCase();
                    cadena = cadena.replace(/[áäàâ]/gi,"a");
                    cadena = cadena.replace(/[éèëê]/gi,"e");
                    cadena = cadena.replace(/[íìïî]/gi,"i");
                    cadena = cadena.replace(/[óòöô]/gi,"o");
                    cadena = cadena.replace(/[úùüû]/gi,"u");
                    cadena = cadena.replace(/ç/gi,"c");
                    cadena = cadena.replace(/ñ/gi,"n");
                    if(preserveSep){
                        cadena = cadena.replace(/[^0-9a-z:_]/gi,"_");
                    }else{
                        cadena = cadena.replace(/[^0-9a-z_]/gi,"_");
                    }
                    cadena = cadena.replace(/_+/g,"_");
                    cadena = cadena.replace(/^_+|_+$/g,"");
                    return cadena;
                },

                onClick: function(){
                    if (NouNom.value !== '') {
                        var query = 'call=rename_folder' + 
                                    '&do=rename' + 
                                    '&old_name=' + this._normalitzaCaracters(EspaiNoms.value, true) + 
                                    '&new_name=' + this._normalitzaCaracters(NouNom.value);
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