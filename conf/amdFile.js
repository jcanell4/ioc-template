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
], function (registry) {
var loginDialog = registry.byId('loginDialog');
if (loginDialog) {
loginDialog.on('hide', function () {
loginDialog.reset();
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
requestUser.urlBase = "lib/plugins/ajaxcommand/ajax.php?call=new_shortcuts_page&template=shortcuts&user_id="+shortcutsOption.dispatcher.getGlobalState().userId;
processorUser.addErrorAction("1001", function () {
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
processorUser.addErrorAction("1001", function () {
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
,"dijit/layout/BorderContainer"
,"dijit/Dialog"
,"dijit/layout/ContentPane"
,"dijit/form/Form"
,"dijit/form/TextBox"
,"dijit/form/Button"
,"ioc/gui/NsTreeContainer"
], function (registry,dom,domConstruct,BorderContainer,Dialog,ContentPane,Form,TextBox,Button,NsTreeContainer) {
var newButton = registry.byId('newButton');
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
var aPath = this.newButton.dispatcher.getGlobalState().getContent(this.newButton.dispatcher.getGlobalState().currentTabId)['ns'] || '';
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
var divesquerra = domConstruct.create('div', {
className: 'dreta'
},cpDreta.containerNode);
var form = new Form().placeAt(divesquerra);
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
});
