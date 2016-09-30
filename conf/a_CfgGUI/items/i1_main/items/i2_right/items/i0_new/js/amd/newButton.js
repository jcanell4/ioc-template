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


var newButton = registry.byId('cfgIdConstants::NEW_BUTTON');
if (newButton) {

    newButton.on('click', function () {                    
        var path=[];
        var dialog = registry.byId("newDocumentDlg");

        if(!dialog){
            dialog = new Dialog({
                id: "newDocumentDlg",
                title: newButton.dialogTitle,
                style: "width: 470px; height: 350px;",
                newButton: newButton
            });

            dialog.on('hide', function () {
                dialog.dialogTree.tree.collapseAll();
                dom.byId('textBoxNouProjecte').value="";
                dom.byId('textBoxNouDocument').value="";
            });
            dialog.on('show', function () {
                dialog.dialogTree.tree.set('path',path).then(function(){
                    dom.byId('textBoxNouDocument').focus();
                });
                dom.byId('textBoxEspaiNoms').value = path[path.length-1] || "";
                dom.byId('textBoxEspaiNoms').focus();
            });

            dialog.nsActivePage = function (){
                path.length=0;
                if (this.newButton.dispatcher.getGlobalState().currentTabId) {
                    var stPath = "";
                    var aPath = this.newButton.dispatcher.getGlobalState().getContent(this.newButton.dispatcher.getGlobalState().currentTabId)['ns'] || '';
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
                treeDataSource: 'lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/',
                onlyDirs:true,
                hiddenProjects:true
            }).placeAt(divizquierda);
            dialogTree.startup();

            dialog.dialogTree = dialogTree;

            dialogTree.tree.onClick=function(item) {
                dom.byId('textBoxEspaiNoms').value= item.id;
                dom.byId('textBoxEspaiNoms').focus();
            }

            // Un formulari a la banda dreta contenint:
            var divdreta = domConstruct.create('div', {
                className: 'dreta'
            },cpDreta.containerNode);

            var form = new Form().placeAt(divdreta);

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

            // Un combo per seleccionar el tipus de projecte (hidden/visible)
            var divProjecte = domConstruct.create('div', {
                className: 'divProjecte'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>' + newButton.Projecteslabel + '<br>'
            },divProjecte);

            var selectProjecte = new ComboBox({
                id: 'comboProjectes',
                placeHolder: newButton.ProjectesplaceHolder,
                name: 'projecte',
                //value: 'defaultProject',
                store: new JsonRest({target: newButton.urlListProjects })
            }).placeAt(divProjecte);
            dialog.comboProjectes = selectProjecte;
            //selectProjecte.startup();
            dialog.comboProjectes.startup();

            dialog.comboProjectes.onClick = function(item) {
                //dom.byId('textBoxNouDocument').value="";
                var box = registry.byId('textBoxNouDocument');
                domStyle.set(box.domNode, "visible", "hidden");
            }

            // Un camp de text per poder escriure el nom del nou projecte (hidden/visible)
            var divNouProjecte = domConstruct.create('div', {
                className: 'divNouProjecte'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>' + newButton.NouProjectelabel + '<br>'
            }, divNouProjecte);

            var NouProjecte = new TextBox({
                id: "textBoxNouProjecte",
                placeHolder: newButton.NouProjecteplaceHolder
            }).placeAt(divNouProjecte);

            // Un combo per seleccionar la plantilla del document (hidden/visible)
            var divTemplate = domConstruct.create('div', {
                className: 'divTemplate'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>' + newButton.Templateslabel + '<br>'
            },divTemplate);

            var selectTemplate = new ComboBox({
                id: 'comboTemplates',
                placeHolder: newButton.TemplatesplaceHolder,
                name: 'plantilla',
                value: 'plantilla 1',
                store: new JsonRest({target: newButton.urlListTemplates })
            }).placeAt(divTemplate);
            dialog.comboTemplates = selectTemplate;
            //selectTemplate.startup();
            dialog.comboTemplates.startup();

            // Un camp de text per poder escriure el nom del nou document (hidden/visible)
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