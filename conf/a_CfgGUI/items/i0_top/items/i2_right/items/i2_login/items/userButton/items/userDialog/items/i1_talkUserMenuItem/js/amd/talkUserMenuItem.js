//include "dijit/registry"; alias registry
//include "ioc/wiki30/processor/ErrorMultiFunctionProcessor"; alias ErrorMultiFunctionProcessor
//include "ioc/wiki30/Request"

var userDialog = registry.byId('cfgIdConstants::TALK_USER_MENU_ITEM');
if (userDialog) {
   var processorTalk = new ErrorMultiFunctionProcessor();
   var requestTalk = new Request();
   requestTalk.urlBase = "lib/exe/ioc_ajax.php?call=new_page";
   processorTalk.addErrorAction("7101", function () {
         requestTalk.sendRequest(userDialog.getQuery());
   });
   userDialog.addProcessor(processorTalk.type, processorTalk);
}

