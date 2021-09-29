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

var duplicateButton = registry.byId('cfgIdConstants::DUPLICATE_PROJECT_BUTTON');
if (duplicateButton) {

    duplicateButton.onClick = function () {
        var path = [];
        var projectType, old_path, old_project;
        var dialog = registry.byId("newDocumentDlg");

        if(!dialog){
            dialog = new Dialog({
                id: "newDocumentDlg",
                title: duplicateButton.dialogTitle,
                style: "width: 470px; height: 350px;",
                duplicateButton: duplicateButton
            });

            dialog.on('hide', function () {
                dialog.destroyRecursive(false);
                domConstruct.destroy("newDocumentDlg");
            });
            
            dialog.on('show', function () {
                dialog.dialogTree.tree.set('path',path).then(function(){
                    dom.byId('textBoxNouProject').focus();
                });
                dom.byId('textBoxNouProject').value = old_project;
                dom.byId('textBoxNouProject').focus();
                dom.byId('textBoxEspaiNoms').value = path[path.length-1] || "";
                dom.byId('textBoxEspaiNoms').focus();
            });

            dialog.nsActivePage = function (){
                path.length=0;
                var globalState = this.duplicateButton.dispatcher.getGlobalState();
                if (globalState && globalState.currentTabId) {
                    var stPath = "";
                    var aPath = globalState.getContent(globalState.currentTabId)['ns'] || '';
                    projectType = globalState.getContent(globalState.getCurrentId()).projectType;
                    aPath = aPath.split(':');
                    old_project = aPath.pop();
                    old_path = aPath.join(':');
                    aPath.unshift("");
                    for (var i=0; i<aPath.length; i++) {
                        if (i > 1) stPath += ":";
                        stPath = stPath + aPath[i];
                        path[i] = stPath;
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
                dom.byId('textBoxEspaiNoms').value= item.id;
                dom.byId('textBoxEspaiNoms').focus();
            };

            // Un formulari a la banda dreta contenint:
            var divdreta = domConstruct.create('div', {
                className: 'dreta'
            },cpDreta.containerNode);

            var form = new Form().placeAt(divdreta);

            //ESPAI DE NOMS Un camp de text per poder escriure l'espai de noms de destí
            var divEspaiNoms = domConstruct.create('div', {
                className: 'divEspaiNoms'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: duplicateButton.EspaideNomslabel + '<br>'
            },divEspaiNoms);

            var EspaiNoms = new TextBox({
                id: 'textBoxEspaiNoms',
                placeHolder: duplicateButton.EspaideNomsplaceHolder
            }).placeAt(divEspaiNoms);
            dialog.textBoxEspaiNoms = EspaiNoms;

            //DIV NOU NOM DEL PROJECTE: Un camp de text per poder escriure el nou nom del projecte
            var divNouProject = domConstruct.create('div', {
                id: 'id_divNouProject',
                className: 'divNouProject'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>' + duplicateButton.NouProjectlabel + '<br>'
            }, divNouProject);

            var NouProject = new TextBox({
                id: "textBoxNouProject",
                placeHolder: duplicateButton.NouProjectplaceHolder
            }).placeAt(divNouProject);


            // botons
            var botons = domConstruct.create('div', {
                className: 'botons',
                style: "text-align:center;"
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br><br>'
            }, botons);

            new Button({
                label: duplicateButton.labelButtonAcceptar,
                
                onClick: function(){
                    if (NouProject.value !== '' && EspaiNoms.value !== '') {
                        var query = 'call=project' +
                                    '&' + duplicateButton.query +
                                    '&id=' + old_path + ":" + old_project +
                                    '&projectType=' + projectType +
                                    '&new_path=' + normalitzaCaracters(EspaiNoms.value, true) +
                                    '&new_project=' + normalitzaCaracters(NouProject.value);
                        duplicateButton.sendRequest(query);
                        dialog.hide();
                    }
                }
            }).placeAt(botons);

            // Botó cancel·lar
            new Button({
                label: duplicateButton.labelButtonCancellar,
                onClick: function(){dialog.hide();}
            }).placeAt(botons);

            form.startup();
        }
        dialog.nsActivePage();
        dialog.show();
        return false;
    };
}
