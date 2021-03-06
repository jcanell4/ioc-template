//include "dijit/registry"
//include "ioc/wiki30/processor/ErrorMultiFunctionProcessor"
//include "ioc/wiki30/Request"

var userDialog = registry.byId('cfgIdConstants::USER_MENU_ITEM');
if (userDialog) {
   var processorUser = new ErrorMultiFunctionProcessor();
   var requestUser = new Request();
   requestUser.urlBase = "lib/exe/ioc_ajax.php?call=new_page";
   processorUser.addErrorAction("7101", function () {
         requestUser.sendRequest(userDialog.getQuery());
   });
   userDialog.addProcessor(processorUser.type, processorUser);
}

