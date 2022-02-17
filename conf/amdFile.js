require([
"dijit/registry"
], function (registry) {
/*
var loginCancelButton = registry.byId('loginDialog' + '_CancelButton');
if (loginCancelButton) {
loginCancelButton.on('click', function () {
var bt = registry.byId('loginButton');
bt.closeDropDown(false);
});
}
*/
});
require([
"dijit/registry"
], function (registry) {
/*
var loginDialog = registry.byId('loginDialog');
if (loginDialog) {
loginDialog.on('hide', function () {
loginDialog.reset();
});
}
*/
});
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
requestUser.urlBase = "lib/exe/ioc_ajax.php?call=new_page&template=plantilles:user:dreceres&user_id="+shortcutsOption.dispatcher.getGlobalState().userId;
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
requestUser.urlBase = "lib/exe/ioc_ajax.php?call=new_page";
processorUser.addErrorAction("7101", function () {
requestUser.sendRequest(userDialog.getQuery());
});
userDialog.addProcessor(processorUser.type, processorUser);
}
});
require([
"dijit/registry"
,"ioc/wiki30/dispatcherSingleton"
], function (registry,dispatcherSingleton) {
var menuItem = registry.byId('logoffMenuItem');
if (menuItem) {
jQuery(menuItem.domNode).on('click', function (e) {
var dispatcher = dispatcherSingleton();
var globalState = dispatcher.getGlobalState();
var isAnyPageChanged = globalState.isAnyPageChanged();
var discardChangesMessage = LANG.template['ioc-template'].confirm_logout_dialog;
if (isAnyPageChanged && !confirm(discardChangesMessage)) {
e.stopPropagation();
e.preventDefault();
}
});
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
,"ioc/functions/normalitzaCaracters"
], function (registry,dom,domConstruct,domStyle,BorderContainer,Dialog,ContentPane,Form,TextBox,Button,ComboBox,JsonRest,NsTreeContainer,normalitzaCaracters) {
var newButton = registry.byId('newButton');
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
var cpEsquerra = new ContentPane({
region: "left",
style: "width: 220px"
});
bc.addChild(cpEsquerra);
var cpDreta = new ContentPane({
region: "center",
style: "width: 250px"
});
bc.addChild(cpDreta);
var cpTaula = new ContentPane({
region: "right",
style: "width: 100px"
});
bc.addChild(cpTaula);
bc.placeAt(dialog.containerNode);
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
var divdreta = domConstruct.create('div', {
className: 'dreta'
},cpDreta.containerNode);
var form = new Form({id:"formNewButton"}).placeAt(divdreta);
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
value: wikipages,
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
var divTaulaUnitats = domConstruct.create('div', {
id: 'id_divTaulaUnitats',
className: 'divTaulaUnitats'
},cpTaula.containerNode);
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
'&projectType=' + selectProjecte.item.id;
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
};
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
,"ioc/functions/normalitzaCaracters"
], function (registry,dom,domConstruct,domStyle,BorderContainer,Dialog,ContentPane,Form,TextBox,Button,ComboBox,JsonRest,NsTreeContainer,normalitzaCaracters) {
var renameButton = registry.byId('renameFolderButton');
if (renameButton) {
renameButton.onClick = function () {
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
dom.byId('textBoxDirectoriOrigen').value = path[path.length-1] || "";
dom.byId('textBoxDirectoriOrigen').focus();
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
treeDataSource: 'lib/exe/ioc_ajaxrest.php/ns_tree_rest/',
onlyDirs:true,
hiddenProjects:true
}).placeAt(divizquierda);
dialogTree.startup();
dialog.dialogTree = dialogTree;
dialogTree.tree.onClick=function(item) {
dom.byId('textBoxDirectoriOrigen').value = item.id;
dom.byId('textBoxDirectoriOrigen').focus();  //borra el placeholder
dom.byId('textBoxDirectoriDesti').value = dialog.getNsDesti(item.id);
dom.byId('textBoxDirectoriDesti').focus();
dom.byId('textBoxNouNomCarpeta').focus();
};
dialog.getNsDesti = function(ns) {
ns = ns.split(':');
ns.pop();
return ns.join(":");
}
var divdreta = domConstruct.create('div', {
className: 'dreta'
},cpDreta.containerNode);
var form = new Form().placeAt(divdreta);
var divDirectoriOrigen = domConstruct.create('div', {
className: 'divDirectoriOrigen'
},form.containerNode);
domConstruct.create('label', {
innerHTML: renameButton.DirectoriOrigenlabel + '<br>'
},divDirectoriOrigen);
var DirectoriOrigen = new TextBox({
id: 'textBoxDirectoriOrigen',
placeHolder: renameButton.DirectoriOrigenplaceHolder
}).placeAt(divDirectoriOrigen);
dialog.textBoxDirectoriOrigen = DirectoriOrigen;
var divDirectoriDesti = domConstruct.create('div', {
className: 'divDirectoriDesti'
},form.containerNode);
domConstruct.create('label', {
innerHTML: '<br><br>' + renameButton.DirectoriDestilabel + '<br>'
},divDirectoriDesti);
var DirectoriDesti = new TextBox({
id: 'textBoxDirectoriDesti',
placeHolder: renameButton.DirectoriDestiplaceHolder
}).placeAt(divDirectoriDesti);
dialog.textBoxDirectoriDesti = DirectoriDesti;
var divNouNomCarpeta = domConstruct.create('div', {
id: 'id_divNouNomCarpeta',
className: 'divNouNomCarpeta'
},form.containerNode);
domConstruct.create('label', {
innerHTML: '<br>' + renameButton.NouNomCarpetalabel + '<br>'
}, divNouNomCarpeta);
var NouNomCarpeta = new TextBox({
id: "textBoxNouNomCarpeta",
placeHolder: renameButton.NouNomCarpetaplaceHolder
}).placeAt(divNouNomCarpeta);
var botons = domConstruct.create('div', {
className: 'botons',
style: "text-align:center;"
},form.containerNode);
domConstruct.create('label', {
innerHTML: '<br><br>'
}, botons);
new Button({
label: renameButton.labelButtonAcceptar,
onClick: function(){
if (DirectoriOrigen.value !== '' && NouNomCarpeta.value !== '') {
var ns_desti = '';
if (DirectoriDesti.value !== '') {
ns_desti = normalitzaCaracters(DirectoriDesti.value, true) + ':';
}
var query = 'call=rename_folder' +
'&do=rename' +
'&old_folder_name=' + normalitzaCaracters(DirectoriOrigen.value, true) +
'&new_folder_name=' + ns_desti + normalitzaCaracters(NouNomCarpeta.value);
renameButton.sendRequest(query);
dialog.hide();
}
}
}).placeAt(botons);
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
});
require([
"dijit/registry"
,"ioc/functions/getValidator"
], function (registry,getValidator) {
var button = registry.byId('editButton');
button.setValidator(getValidator('PageNotRequired'));
});
require([
"dijit/registry"
,"ioc/functions/getValidator"
], function (registry,getValidator) {
var button = registry.byId('editProjectButton');
button.setValidator(getValidator('PageNotRequired'));
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
require([
"dijit/registry"
,"dojo/dom"
,"dojo/dom-construct"
,"dojo/dom-style"
,"dijit/layout/BorderContainer"
,"dijit/Dialog"
,"dijit/layout/ContentPane"
,"dijit/form/Form"
,"dijit/form/Textarea"
,"dijit/form/TextBox"
,"dijit/form/Button"
,"dijit/form/ComboBox"
,"dojo/store/JsonRest"
,"ioc/functions/normalitzaCaracters"
], function (registry,dom,domConstruct,domStyle,BorderContainer,Dialog,ContentPane,Form,Textarea,TextBox,Button,ComboBox,JsonRest,normalitzaCaracters) {
var sendmessageButton = registry.byId('sendMessageButton');
if (sendmessageButton) {
sendmessageButton.onClick = function () {
var dialog = registry.byId("sendmessageDocumentDlg");
var grups = sendmessageButton.dispatcher.getGlobalState().pages[sendmessageButton.parent]['extra']['grups'];
if (!dialog){
dialog = new Dialog({
id: "sendmessageDocumentDlg",
title: sendmessageButton.dialogTitle,
style: "width:300px; height:350px;",
sendmessageButton: sendmessageButton
});
dialog.on('hide', function () {
dialog.destroyRecursive(false);
domConstruct.destroy("sendmessageDocumentDlg");
});
dialog.on('show', function () {
dom.byId('comboRols').focus();
dom.byId('textAreaMissatge').value = "";
dom.byId('textBoxLlistaRols').value = "";
});
dialog.storeListRols = function(n,o,e) {
dom.byId('textBoxLlistaRols').value += e + ",";
LlistaRols.value = textBoxLlistaRols.value;
};
var bc = new BorderContainer({
style: "width:280px; height:300px;"
});
var cpCentre = new ContentPane({
region: "center"
});
bc.addChild(cpCentre);
bc.placeAt(dialog.containerNode);
var divcentre = domConstruct.create('div', {
className: 'dreta'
},cpCentre.containerNode);
var form = new Form().placeAt(divcentre);
var divRols = domConstruct.create('div', {
className: 'divRols'
},form.containerNode);
domConstruct.create('label', {
innerHTML: sendmessageButton.labelRols + '<br>'
},divRols);
var selectRols = new ComboBox({
id: 'comboRols',
placeHolder: sendmessageButton.placeholderRols,
name: 'rols',
value: '',
store: new JsonRest({target: sendmessageButton.urlListRols})
}).placeAt(divRols);
dialog.comboRols = selectRols;
dialog.comboRols.startup();
dialog.comboRols.watch('value', dialog.storeListRols);
var divLlistaRols = domConstruct.create('div', {
className: 'divLlistaRols'
},form.containerNode);
domConstruct.create('label', {
innerHTML: '<br>' + sendmessageButton.labelLlista + '<br>'
},divLlistaRols);
var LlistaRols = new TextBox({
id: 'textBoxLlistaRols'
}).placeAt(divLlistaRols);
dialog.textBoxLlistaRols = LlistaRols;
var divMissatge = domConstruct.create('div', {
className: 'divMissatge'
},form.containerNode);
domConstruct.create('label', {
innerHTML: '<br>' + sendmessageButton.labelMissatge + '<br>'
},divMissatge);
var Missatge = new Textarea({
id: 'textAreaMissatge',
placeHolder: sendmessageButton.placeholderMissatge,
}).placeAt(divMissatge);
dialog.textAreaMissatge = Missatge;
var botons = domConstruct.create('div', {
className: 'botons',
style: "text-align:center;"
},form.containerNode);
domConstruct.create('label', {
innerHTML: '<br><br>'
}, botons);
new Button({
label: sendmessageButton.labelButtonAcceptar,
onClick: function(){
if (LlistaRols.value !== '') {
var query = 'call=' + sendmessageButton.call +
'&grups=' + grups +
'&rols=' + LlistaRols.value +
'&message=' + Missatge.value;
sendmessageButton.sendRequest(query);
dialog.hide();
}
}
}).placeAt(botons);
new Button({
label: sendmessageButton.labelButtonCancellar,
onClick: function(){dialog.hide();}
}).placeAt(botons);
form.startup();
}
dialog.show();
return false;
};
}
});
require([
"dijit/registry"
,"ioc/wiki30/dispatcherSingleton"
], function (registry,dispatcherSingleton) {
var dispatcher = dispatcherSingleton();
var ftpSendButton = registry.byId('ftpSendButton');
var globalState = dispatcher.getGlobalState();
var ns = globalState.getContent(globalState.currentTabId).ns;
var fOnClick = function () {
var id = dispatcher.getGlobalState().getCurrentId();
registry.byId("zonaMetaInfo").selectChild(id + "_ftpsend");
this.setStandbyId(id + "_ftpsend");
};
if (ftpSendButton) {
ftpSendButton.onClick = fOnClick;
}
});
require([
"dijit/registry"
,"ioc/wiki30/dispatcherSingleton"
], function (registry,dispatcherSingleton) {
var dispatcher = dispatcherSingleton();
var ftpSendButton = registry.byId('ftpProjectButton');
var globalState = dispatcher.getGlobalState();
var fOnClick = function () {
var id = dispatcher.getGlobalState().getCurrentId();
registry.byId("zonaMetaInfo").selectChild(id + "_ftpsend");
this.setStandbyId(id + "_ftpsend");
};
if (ftpSendButton) {
ftpSendButton.onClick = fOnClick;
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
,"ioc/functions/normalitzaCaracters"
], function (registry,dom,domConstruct,domStyle,BorderContainer,Dialog,ContentPane,Form,TextBox,Button,ComboBox,JsonRest,NsTreeContainer,normalitzaCaracters) {
var duplicateButton = registry.byId('duplicateProjectButton');
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
var divdreta = domConstruct.create('div', {
className: 'dreta'
},cpDreta.containerNode);
var form = new Form().placeAt(divdreta);
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
});
