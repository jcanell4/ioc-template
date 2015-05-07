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
                if (this.newButton.dispatcher.getGlobalState().currentTabId !== null) {
                    var nsActivePage = this.newButton.dispatcher.getGlobalState().pages[this.newButton.dispatcher.getGlobalState().currentTabId]['ns'] || '';
                    nsActivePage = nsActivePage.split(':')
                    nsActivePage.pop();
                    var len = nsActivePage.length;
                    if (len > 1) {
                        nsActivePage = nsActivePage.join(':');
                    }
                }
                return nsActivePage;
            };


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


