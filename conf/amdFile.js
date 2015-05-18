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
,"ioc/wiki30/processor/ErrorMultiFunctionProcessor"
,"ioc/wiki30/Request"
], function (registry,ErrorMultiFunctionProcessor,Request) {
var userDialog = registry.byId('talkUserMenuItem');
if (userDialog) {
var processorTalk = new ErrorMultiFunctionProcessor();
var requestTalk = new Request();
requestTalk.urlBase = "lib/plugins/ajaxcommand/ajax.php?call=new_page";
processorTalk.addErrorAction("1001", function () {
requestTalk.sendRequest(userDialog.getQuery());
});
userDialog.addProcessor(processorTalk.type, processorTalk);
}
});
require([
"dijit/registry"
,"ioc/wiki30/GlobalState"
,"ioc/wiki30/dispatcherSingleton"
], function (registry,globalState,wikiIocDispatcher) {
var tbContainer = registry.byId(wikiIocDispatcher.navegacioNodeId);
if (tbContainer) {
tbContainer.watch("selectedChildWidget", function (name, oldTab, newTab) {
var documentId = globalState.getCurrentId();
var contentCache = wikiIocDispatcher.getContentCache(documentId);
if (contentCache) {
contentCache.setCurrentId("navigationPane", newTab.id);
}
if (newTab.updateRendering) {
newTab.updateRendering();
}
});
}
});
require([
"dijit/registry"
,"ioc/wiki30/dispatcherSingleton"
], function (registry,wikiIocDispatcher) {
var tab = registry.byId('tb_index');
if (tab) {
wikiIocDispatcher.toUpdateSectok.push(tab);
tab.updateSectok();
}
});
require([
"dijit/registry"
,"ioc/wiki30/dispatcherSingleton"
,"dojo/_base/lang"
], function (registry,wikiIocDispatcher,lang) {
var centralContainer = registry.byId(wikiIocDispatcher.containerNodeId);
if (centralContainer) {
centralContainer.watch("selectedChildWidget", lang.hitch(centralContainer, function (name, oldTab, newTab) {
if (wikiIocDispatcher.getContentCache(newTab.id)) {
newTab.setCurrentDocument(newTab.id);
wikiIocDispatcher.getInfoManager().refreshInfo(newTab.id);
}
if (oldTab && wikiIocDispatcher.getGlobalState()
.getContentAction(oldTab.id) == "edit") {
wikiIocDispatcher.getContentCache(oldTab.id).getEditor().unselect();
}
if (wikiIocDispatcher.getGlobalState()
.getContentAction(newTab.id) == "edit") {
wikiIocDispatcher.getContentCache(newTab.id).getEditor().select();
}
wikiIocDispatcher.updateFromState();
}));
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
var cpEsquerra = new ContentPane({
region: "left",
style: "width: 170px"
});
bc.addChild(cpEsquerra);
var cpDreta = new ContentPane({
region: "center"
});
bc.addChild(cpDreta);
bc.placeAt(dialog.containerNode);
var divesquerra = domConstruct.create('div', {
className: 'esquerra'
},cpEsquerra.containerNode);
var form = new Form().placeAt(divesquerra);
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
var divNouDocument = domConstruct.create('div', {
className: 'divNouDocument'
},form.containerNode);
domConstruct.create('label', {
innerHTML: '<br>' + newButton.NouDocumentlabel + '<br>'
}, divNouDocument);
var NouDocument = new TextBox({
placeHolder: newButton.NouDocumentplaceHolder
}).placeAt(divNouDocument);
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
onClick: function(){ dialog.hide();}
}).placeAt(botons);
form.startup();
}
dialog.show();
return false;
});
}
});
