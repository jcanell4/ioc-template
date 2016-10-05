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
                dom.byId('textBoxEspaiNoms').value="";
                dom.byId('textBoxNouProjecte').value="";
                dom.byId('textBoxNouDocument').value="";
                //muestra los DIV que contienen el textBox de 'Nou Projecte', el combo de las plantillas y TextBox de 'Nou Document'
                //dado que pueden haber sido ocultados y es necesario reestablecer su aspecto original
                //para la próxima vez que el botón 'Nou' solicite el diálogo
                dom.byId('id_divNouProjecte').hidden = false;
                dom.byId('id_divTemplate').hidden = false;
                dom.byId('id_divNouDocument').hidden = false;
            });
            dialog.on('show', function () {
                dialog.dialogTree.tree.set('path',path).then(function(){
                    dom.byId('textBoxNouDocument').focus();
                });
                dom.byId('textBoxEspaiNoms').value = path[path.length-1] || "";
                dom.byId('textBoxEspaiNoms').focus();
                dialog.switchBloc('defaultProject');
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

            dialog.switchBloc = function(e) {
                if (e === 'defaultProject') {
                    dom.byId('id_divNouProjecte').hidden = true;    //oculta el DIV que contiene el textBox de 'Nou Projecte'
                    dom.byId('id_divTemplate').hidden = false;      //muestra el DIV que contiene el combo de 'Plantilla'
                    dom.byId('id_divNouDocument').hidden = false;   //muestra el DIV que contiene el textBox de 'Nou Document'
                }else{
                    dom.byId('id_divNouProjecte').hidden = false;   //muestra el DIV que contiene el textBox de 'Nou Projecte'
                    dom.byId('id_divTemplate').hidden = true;       //oculta el DIV que contiene el combo de 'Plantilla'
                    dom.byId('id_divNouDocument').hidden = true;    //oculta el DIV que contiene el textBox de 'Nou Document'
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
                innerHTML: newButton.EspaideNomslabel + '<br>'
            },divEspaiNoms);

            var EspaiNoms = new TextBox({
                id: 'textBoxEspaiNoms',
                placeHolder: newButton.EspaideNomsplaceHolder
            }).placeAt(divEspaiNoms);
            dialog.textBoxEspaiNoms = EspaiNoms;

            //DIV PROJECTE Un div per contenir la selecció de Projectes
            var divProjecte = domConstruct.create('div', {
                //id: 'id_divProjecte',
                className: 'divProjecte'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>' + newButton.Projecteslabel + '<br>'
            },divProjecte);

            //Un combo per seleccionar el tipus de projecte
            var selectProjecte = new ComboBox({
                id: 'comboProjectes',
                placeHolder: newButton.ProjectesplaceHolder,
                name: 'projecte',
                value: 'defaultProject',
                store: new JsonRest({target: newButton.urlListProjects })
            }).placeAt(divProjecte);
            dialog.comboProjectes = selectProjecte;
            //dialog.comboProjectes.startup();

            //DIV NOU PROJECTE: Un camp de text per poder escriure el nom del nou projecte (hidden/visible)
            var divNouProjecte = domConstruct.create('div', {
                id: 'id_divNouProjecte',
                className: 'divNouProjecte'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>' + newButton.NouProjectelabel + '<br>'
            }, divNouProjecte);

            var NouProjecte = new TextBox({
                id: "textBoxNouProjecte",
                placeHolder: newButton.NouProjecteplaceHolder
            }).placeAt(divNouProjecte);

            //DIV PLANTILLA: Selecció de plantilla i nom del Nou Document (hidden/visible)
            var divTemplate = domConstruct.create('div', {
                id: 'id_divTemplate',
                className: 'divTemplate'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>' + newButton.Templateslabel + '<br>'
            },divTemplate);

            // Un combo per seleccionar la plantilla del document
            var selectTemplate = new ComboBox({
                id: 'comboTemplates',
                placeHolder: newButton.TemplatesplaceHolder,
                name: 'plantilla',
                value: '',
                store: new JsonRest({target: newButton.urlListTemplates })
            }).placeAt(divTemplate);
            dialog.comboTemplates = selectTemplate;
            dialog.comboTemplates.startup();

            //DIV NOU DOCUMENT: Un camp de text per poder escriure el nom del nou document (hidden/visible)
            var divNouDocument = domConstruct.create('div', {
                id: 'id_divNouDocument',
                className: 'divNouDocument'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>' + newButton.NouDocumentlabel + '<br>'
            }, divNouDocument);

            var NouDocument = new TextBox({
                id: "textBoxNouDocument",
                placeHolder: newButton.NouDocumentplaceHolder
            }).placeAt(divNouDocument);

            //la posición de ésta línea en el script es relevante
            dialog.comboProjectes.connect(dom.byId('comboProjectes'), "onChange", dialog.switchBloc(selectProjecte.value));
            //dialog.comboProjectes.on('change', dialog.switchBloc(selectProjecte.value));
            //dialog.comboProjectes.onChange = dialog.switchBloc(selectProjecte.value);

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
                    if (selectProjecte.value === 'defaultProject') {
                        if (NouDocument.value !== '') {
                            var separacio = (EspaiNoms.value !== '') ? ':' : '';
                            var query = 'do=new&id=' + EspaiNoms.value + separacio + NouDocument.value;
                            newButton.sendRequest(query);
                            dialog.hide();
                        }
                    }else {
                        if (NouProjecte.value !== '') {
                            var separacio = (EspaiNoms.value !== '') ? ':' : '';
                            var query = 'call=project&do=create&id=' + EspaiNoms.value + separacio + NouProjecte.value;
                            newButton.sendRequest(query);
                            dialog.hide();
                        }
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