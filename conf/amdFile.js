require([
"dijit/registry"
], function (registry) {
var loginCancelButton = registry.byId('loginDialog' + '_CancelButton');
if (loginCancelButton) {
loginCancelButton.on('click', function () {
var bt = registry.byId('loginButton');
bt.closeDropDown(false);
});
}
});
require([
"dijit/registry"
,"ioc/wiki30/processor/ErrorMultiFunctionProcessor"
,"ioc/wiki30/Request"
], function (registry,ErrorMultiFunctionProcessor,Request) {
var shortcutsOption = registry.byId('shortcutsMenuItem');
if (shortcutsOption) {
var processorUser = new ErrorMultiFunctionProcessor();
var requestUser = new Request();
processorUser.addErrorAction("7101", function () {
requestUser.urlBase = "lib/plugins/ajaxcommand/ajax.php?call=new_page&template=plantilles:user:dreceres&user_id="+shortcutsOption.dispatcher.getGlobalState().userId;
requestUser.sendRequest(shortcutsOption.getQuery());
});
shortcutsOption.addProcessor(processorUser.type, processorUser);
}
});
require([
"dijit/registry"
,"ioc/wiki30/processor/ErrorMultiFunctionProcessor"
,"ioc/wiki30/Request"
], function (registry,ErrorMultiFunctionProcessor,Request) {
var userDialog = registry.byId('userMenuItem');
if (userDialog) {
var processorUser = new ErrorMultiFunctionProcessor();
var requestUser = new Request();
requestUser.urlBase = "lib/plugins/ajaxcommand/ajax.php?call=new_page";
processorUser.addErrorAction("7101", function () {
requestUser.sendRequest(userDialog.getQuery());
});
userDialog.addProcessor(processorUser.type, processorUser);
}
});
require([
"dijit/registry"
,"ioc/wiki30/GlobalState"
,"ioc/wiki30/dispatcherSingleton"
], function (registry,globalState,getDispatcher) {
var wikiIocDispatcher = getDispatcher();
var tbContainer = registry.byId(wikiIocDispatcher.navegacioNodeId);
if (tbContainer) {
tbContainer.watch("selectedChildWidget", function (name, oldTab, newTab) {
wikiIocDispatcher.getGlobalState().setCurrentNavigationId(newTab.id);
if (newTab.updateRendering) {
newTab.updateRendering();
}
});
}
});
require([
"dijit/registry"
,"ioc/wiki30/dispatcherSingleton"
], function (registry,getDispatcher) {
var wikiIocDispatcher = getDispatcher();
var tab = registry.byId('tb_index');
if (tab) {
wikiIocDispatcher.toUpdateSectok.push(tab);
tab.updateSectok();
}
});
require([
"dijit/registry"
,"dojo/dom"
,"dojo/dom-construct"
,"dojo/dom-style"
,"dijit/layout/BorderContainer"
,"dijit/Dialog"
,"dijit/layout/ContentPane"
,"dijit/form/Form"
,"dijit/form/TextBox"
,"dijit/form/Button"
,"dijit/form/ComboBox"
,"dojo/store/JsonRest"
,"ioc/gui/NsTreeContainer"
], function (registry,dom,domConstruct,domStyle,BorderContainer,Dialog,ContentPane,Form,TextBox,Button,ComboBox,JsonRest,NsTreeContainer) {
var newButton = registry.byId('newButton');
if (newButton) {
newButton.onClick = function () {
var defaultProject = 'defaultProject';
var path = [];
var dialog = registry.byId("newDocumentDlg");
if(!dialog){
dialog = new Dialog({
id: "newDocumentDlg",
title: newButton.dialogTitle,
style: "width: 470px; height: 350px;",
newButton: newButton
});
dialog.on('hide', function () {
dialog.dialogTree.tree.collapseAll();   //contrae el árbol
dom.byId('textBoxEspaiNoms').value = "";
registry.byId('comboProjectes').set('value', defaultProject);
dom.byId('textBoxNouProjecte').value = "";
registry.byId('comboTemplates').value = null;
dom.byId('textBoxNouDocument').value = "";
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
dialog.switchBloc(null,null,defaultProject);
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
if (e === defaultProject || selectProjecte.value === defaultProject) {
dom.byId('id_divNouProjecte').hidden = true;    //oculta el DIV que contiene el textBox de 'Nou Projecte'
dom.byId('id_divTemplate').hidden = false;      //muestra el DIV que contiene el combo de 'Plantilla'
dom.byId('id_divNouDocument').hidden = false;   //muestra el DIV que contiene el textBox de 'Nou Document'
}else{
dom.byId('id_divNouProjecte').hidden = false;   //muestra el DIV que contiene el textBox de 'Nou Projecte'
dom.byId('id_divTemplate').hidden = true;       //oculta el DIV que contiene el combo de 'Plantilla'
dom.byId('id_divNouDocument').hidden = true;    //oculta el DIV que contiene el textBox de 'Nou Document'
}
};
dialog.setDefaultDocumentName = function(n,o,e) {
dom.byId('textBoxNouDocument').value = e;
dom.byId('textBoxNouDocument').focus();
}
var bc = new BorderContainer({
style: "height: 300px; width: 450px;"
});
var cpEsquerra = new ContentPane({
region: "left",
style: "width: 220px"
});
bc.addChild(cpEsquerra);
var cpDreta = new ContentPane({
region: "center"
});
bc.addChild(cpDreta);
bc.placeAt(dialog.containerNode);
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
var divdreta = domConstruct.create('div', {
className: 'dreta'
},cpDreta.containerNode);
var form = new Form().placeAt(divdreta);
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
value: defaultProject,
searchAttr: 'name',
store: new JsonRest({target: newButton.urlListProjects })
}).placeAt(divProjecte);
dialog.comboProjectes = selectProjecte;
dialog.comboProjectes.startup();
dialog.comboProjectes.watch('value', dialog.switchBloc );
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
var divTemplate = domConstruct.create('div', {
id: 'id_divTemplate',
className: 'divTemplate'
},form.containerNode);
domConstruct.create('label', {
innerHTML: '<br>' + newButton.Templateslabel + '<br>'
},divTemplate);
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
var botons = domConstruct.create('div', {
className: 'botons'
},form.containerNode);
domConstruct.create('label', {
innerHTML: '<br><br>'
}, botons);
new Button({
label: newButton.labelButtonAcceptar,
onClick: function(){
if (selectProjecte.value === defaultProject) {
if (NouDocument.value !== '') {
var separacio = (EspaiNoms.value !== '') ? ':' : '';
var templatePar = selectTemplate.item?'&template=' + selectTemplate.item.path:'';
var query = 'call=new_page' +
'&do=new' +
'&id=' + EspaiNoms.value + separacio + NouDocument.value +
templatePar;
newButton.sendRequest(query);
dialog.hide();
}
}else {
if (NouProjecte.value !== '') {
var separacio = (EspaiNoms.value !== '') ? ':' : '';
var query = 'call=project' +
'&do=create' +
'&id=' + EspaiNoms.value + separacio + NouProjecte.value +
'&projectType=' + selectProjecte.value;
newButton.sendRequest(query);
dialog.hide();
}
}
}
}).placeAt(botons);
new Button({
label: newButton.labelButtonCancellar,
onClick: function(){dialog.hide();}
}).placeAt(botons);
form.startup();
}
dialog.nsActivePage();
dialog.show();
return false;
}; //);
}
});
require([
"dijit/registry"
,"dojo/cookie"
,"ioc/wiki30/dispatcherSingleton"
], function (registry,cookie,dispatcherSingleton) {
var button = registry.byId('editButton');
if (button) {
button.onClick = function (e) {
var dispatcher = dispatcherSingleton();
var globalState = dispatcher.getGlobalState();
var ns= globalState.getContent(globalState.currentTabId).ns;
if (globalState.isPageRequired(ns)) {
e.stopPropagation();
e.preventDefault();
var errorMessage = {response: {text: 'No es pot enviar la petició'}};
dispatcher.processError(errorMessage); // TODO[Xavi] Localitzar
}
};
}
});
require([
"dijit/registry"
,"dojo/cookie"
], function (registry,cookie) {
var button = registry.byId('printButton');
if (button) {
button.onClick = function () {
console.log("Creant la cookie");
cookie("IOCForceScriptLoad", 1);
};
}
});
