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

var newButton = registry.byId('cfgIdConstants::NEW_BUTTON');
if (newButton) {

    newButton.onClick = function () {
        var materials = 'defaultProject';
        var wikipages = 'wikipages';
        var path = [];
        var dialog = registry.byId("newDocumentDlg");

        if(!dialog){
            dialog = new Dialog({
                id: "newDocumentDlg",
                title: newButton.dialogTitle,
                style: "width: 590px; height: 350px;",
                newButton: newButton
            });

            dialog.on('hide', function () {
                dialog.destroyRecursive(false);
                domConstruct.destroy("newDocumentDlg");
            });
            
            dialog.on('show', function () {
                dialog.dialogTree.tree.set('path',path).then(function(){
                    dom.byId('textBoxNouDocument').focus();
                });
                dom.byId('textBoxEspaiNoms').value = path[path.length-1] || "";
                dom.byId('textBoxEspaiNoms').focus();
                dialog.switchBloc(null,null,wikipages);
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

            dialog.switchBloc = function(n,o,e) {
                if (e === wikipages || selectProjecte.value === wikipages) {
                    dom.byId('id_divNouProjecte').hidden = true;   //oculta el DIV que contiene el textBox de 'Nou Projecte'
                    dom.byId('id_divNumUnitats').hidden = true;    //oculta el DIV que contiene el textBox de 'Num. Unitats'
                    dom.byId('id_divBotoUnitats').hidden = true;   //oculta el DIV que contiene el Botón de 'Enviar Num. Unitats'
                    dom.byId('id_divTaulaUnitats').hidden = true;  //oculta el DIV que contiene la taula 'Unitats-Apartats'
                    dom.byId('id_divTemplate').hidden = false;     //muestra el DIV que contiene el combo de 'Plantilla'
                    dom.byId('id_divNouDocument').hidden = false;  //muestra el DIV que contiene el textBox de 'Nou Document'
                }else if (e === materials || selectProjecte.value === materials) {
                    dom.byId('id_divNouProjecte').hidden = true;   //oculta el DIV que contiene el textBox de 'Nou Projecte'
                    dom.byId('id_divNumUnitats').hidden = false;   //muestra el DIV que contiene el textBox de 'Num. Unitats'
                    dom.byId('id_divBotoUnitats').hidden = false;  //muestra el DIV que contiene el Botón de 'Enviar Num. Unitats'
                    dom.byId('id_divTaulaUnitats').hidden = false; //muestra el DIV que contiene la taula 'Unitats-Apartats'
                    dom.byId('id_divTemplate').hidden = true;      //oculta el DIV que contiene el combo de 'Plantilla'
                    dom.byId('id_divNouDocument').hidden = true;   //oculta el DIV que contiene el textBox de 'Nou Document'
                }else{
                    dom.byId('id_divNouProjecte').hidden = false;  //muestra el DIV que contiene el textBox de 'Nou Projecte'
                    dom.byId('id_divNumUnitats').hidden = true;    //oculta el DIV que contiene el textBox de 'Num. Unitats'
                    dom.byId('id_divBotoUnitats').hidden = true;   //oculta el DIV que contiene el Botón de 'Enviar Num. Unitats'
                    dom.byId('id_divTaulaUnitats').hidden = true;  //oculta el DIV que contiene la taula 'Unitats-Apartats'
                    dom.byId('id_divTemplate').hidden = true;      //oculta el DIV que contiene el combo de 'Plantilla'
                    dom.byId('id_divNouDocument').hidden = true;   //oculta el DIV que contiene el textBox de 'Nou Document'
                }
            };
            
            dialog.setDefaultDocumentName = function(n,o,e) {
                dom.byId('textBoxNouDocument').value = e;
                dom.byId('textBoxNouDocument').focus();
            };

            dialog.mostraTaula = function(divTaulaUnitats, num) {
                var td, i, unitat;
                var padding = "padding:2px 5px";
                var bold = "padding:2px 5px; font-weight:bold";
                
                var table = domConstruct.create("table", null, divTaulaUnitats);
                var tr = domConstruct.create("tr", null, table);
                domConstruct.create("td", {style:bold, innerHTML:"unitat"}, tr);
                domConstruct.create("td", {style:bold, innerHTML:"apartat"}, tr);
                
                for (i=1; i<=num; i++) {
                    unitat = 'u'+i;
                    tr = domConstruct.create("tr", {id:"tr_"+unitat}, table);
                    domConstruct.create("td", {id:"td_unitat_"+i, style:bold, innerHTML:unitat}, tr);
                    td = domConstruct.create("td", {id:"td_apartat_"+i, style:padding}, tr);
                    domConstruct.create("input", {id:"id_input_"+unitat, name:unitat, form:"formNewButton", type:"text", style:{width:"3em"}}, td);
                }
                dom.byId('id_divTaulaUnitats').hidden = false;
                dom.byId('id_input_u1').focus();
            };
            
            var bc = new BorderContainer({
                style: "height: 300px; width: 570px;"
            });

            // create a ContentPane as the left pane in the BorderContainer
            var cpEsquerra = new ContentPane({
                region: "left",
                style: "width: 220px"
            });
            bc.addChild(cpEsquerra);

            // create a ContentPane as the center pane in the BorderContainer
            var cpDreta = new ContentPane({
                region: "center",
                style: "width: 250px"
            });
            bc.addChild(cpDreta);

            // create a ContentPane as the right pane in the BorderContainer
            var cpTaula = new ContentPane({
                region: "right",
                style: "width: 100px"
            });
            bc.addChild(cpTaula);

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

            var form = new Form({id:"formNewButton"}).placeAt(divdreta);

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
                value: wikipages,
                searchAttr: 'name',
                store: new JsonRest({target: newButton.urlListProjects })
            }).placeAt(divProjecte);
            dialog.comboProjectes = selectProjecte;
            dialog.comboProjectes.startup();
            dialog.comboProjectes.watch('value', dialog.switchBloc );

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
            dialog.comboTemplates.watch('value', dialog.setDefaultDocumentName );

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

            //DIV NUM. UNITATS: Un camp de text per poder escriure el nombre d'unitats (hidden/visible)
            var divNumUnitats = domConstruct.create('div', {
                id: 'id_divNumUnitats',
                className: 'divNumUnitats'
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>' + newButton.NumUnitatslabel + '<br>'
            }, divNumUnitats);

            var NumUnitats = new TextBox({
                id: "textBoxNumUnitats",
                placeHolder: newButton.NumUnitatsplaceHolder
            }).placeAt(divNumUnitats);

            //DIV TAULA UNITATS: Un DIV per contenir la taula d'unitats i apartats (hidden/visible)
            var divTaulaUnitats = domConstruct.create('div', {
                id: 'id_divTaulaUnitats',
                className: 'divTaulaUnitats'
            },cpTaula.containerNode);
            
            // Botó enviar el nombre d'unitats
            var bunit = domConstruct.create('div', {
                id: 'id_divBotoUnitats',
                className: 'botons',
                style: "text-align:right;margin-top:10px;"
            },form.containerNode);

            var mostraTaulaClicked = false;
            
            new Button({
                label: newButton.labelButtonNumUnitats,
                onClick: function(){
                    if (NumUnitats.value !== undefined && NumUnitats.value > 0) {
                        if (mostraTaulaClicked === true) {
                            dom.byId('id_input_u1').focus();
                        }else {
                            dialog.mostraTaula(divTaulaUnitats, NumUnitats.value);
                            mostraTaulaClicked = true;
                        }
                    }else {
                        dom.byId('textBoxNumUnitats').focus();
                    }
                }
            }).placeAt(bunit);



            // ----- Botons generals del formulari ------
            var botons = domConstruct.create('div', {
                className: 'botons',
                style: "text-align:center;margin-top:20px;"
            },form.containerNode);

            domConstruct.create('label', {
                innerHTML: '<br>'
            }, botons);

            new Button({
                label: newButton.labelButtonAcceptar,
                
                onClick: function(){
                    var separacio = "";
                    if (EspaiNoms.value !== '' && EspaiNoms.value.slice(-1) !== ":"){
                         separacio = ':';
                    }
                    if (selectProjecte.value === wikipages) {
                        if (NouDocument.value !== '') {
                            var templatePar = selectTemplate.item?'&template=' + selectTemplate.item.path:'';
                            var query = 'call=new_page' + 
                                        '&do=new' + 
                                        '&id=' + normalitzaCaracters(EspaiNoms.value, true) + separacio + normalitzaCaracters(NouDocument.value) +
                                        templatePar;
                            newButton.sendRequest(query);
                            dialog.hide();
                        }
                    }else if (selectProjecte.value === materials) {
                        if (EspaiNoms.value !== '') {
                            var apartats = {};
                            for (var i=1; i<=NumUnitats.value; i++) {
                                apartats['u'+i] = formNewButton['id_input_u'+i].value;
                            }
                            var query = 'call=new_material' + 
                                        '&do=new' + 
                                        '&id=' + normalitzaCaracters(EspaiNoms.value, true) +
                                        '&unitats=' + JSON.stringify(apartats);
                            newButton.sendRequest(query);
                            dialog.hide();
                        }
                    }else {
                        if (NouProjecte.value !== '') {
                            var query = 'call=project' + 
                                        '&do=create_project' + 
                                        '&id=' + normalitzaCaracters(EspaiNoms.value, true) + separacio + normalitzaCaracters(NouProjecte.value) +
                                        '&cfgIdConstants::PROJECT_TYPE=' + selectProjecte.item.id;
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
    };
}