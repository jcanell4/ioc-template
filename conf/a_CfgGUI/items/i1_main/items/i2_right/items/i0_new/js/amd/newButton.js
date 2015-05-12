//include "dijit/registry"
//include "dojo/dom"
//include "dojo/dom-construct"; alias "domConstruct"
//include "dijit/layout/BorderContainer"
//include "dijit/Dialog"
//include "dijit/layout/ContentPane"
//include "dijit/form/Form"
//include  "dijit/form/TextBox"
//include  "dijit/form/Button"
//include  "ioc/gui/NsTreeContainer"


var newButton = registry.byId('cfgIdConstants::NEW_BUTTON');
if (newButton) {

    newButton.on('click', function () {                    
        var path=[];
        var dialog = registry.byId("newDocumentDlg");

        if(!dialog){
            dialog = new Dialog({
                id:"newDocumentDlg",
                title: newButton.dialogTitle,
                style: "width: 470px; height: 250px;",
                newButton: newButton
            });

            dialog.on('hide', function () {
                dialog.dialogTree.tree.collapseAll();
                dom.byId('textBoxNouDocument').value="";
            });
            dialog.on('show', function () {
                dom.byId('textBoxEspaiNoms').value = path[path.length-1] || "";
                dom.byId('textBoxEspaiNoms').focus();
                dialog.dialogTree.tree.set('path',path).then(function(){
                    dom.byId('textBoxNouDocument').focus();
                });
            });

            dialog.nsActivePage = function (){
                path.length=0;
                if (this.newButton.dispatcher.getGlobalState().currentTabId) {
                    var stPath = "";
                    var aPath = this.newButton.dispatcher.getGlobalState().pages[this.newButton.dispatcher.getGlobalState().currentTabId]['ns'] || '';
                    aPath = aPath.split(':');
                    aPath.pop();
                    aPath.unshift("");
                    for (var i=0;i<aPath.length;i++) {
                        if (i > 1) {
                            stPath = stPath + ":";
                        }
                        stPath = stPath + aPath[i];
                        path[i]=stPath;
                    }
                }    
            };


            var bc = new BorderContainer({
                style: "height: 200px; width: 450px;"
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

            // Un formulari a la banda dreta contenint:
            var divesquerra = domConstruct.create('div', {
                className: 'dreta'
            },cpDreta.containerNode);

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
                id: "textBoxNouDocument",
                placeHolder: newButton.NouDocumentplaceHolder
            }).placeAt(divNouDocument);

            //L'arbre de navegació a la banda dreta del quadre.
            var divdreta = domConstruct.create('div', {
                className: 'dreta'
            },cpEsquerra.containerNode);

            var dialogTree = new NsTreeContainer({
                treeDataSource: 'lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/',
                onlyDirs:true
            }).placeAt(divdreta);
            dialogTree.startup();

            dialog.dialogTree = dialogTree;

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
              onClick: function(){dialog.hide();}
            }).placeAt(botons);

            form.startup();
        }
        dialog.nsActivePage();
        dialog.show();
        return false;
    });
}