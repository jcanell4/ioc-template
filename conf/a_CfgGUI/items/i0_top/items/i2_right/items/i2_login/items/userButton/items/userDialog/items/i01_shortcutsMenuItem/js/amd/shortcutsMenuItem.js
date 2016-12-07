//include "dijit/registry"
//include "ioc/wiki30/processor/ErrorMultiFunctionProcessor"
//include "ioc/wiki30/Request"

var shortcutsOption = registry.byId('cfgIdConstants::SHORTCUTS_MENU_ITEM');
if (shortcutsOption) {
   var processorUser = new ErrorMultiFunctionProcessor();
   var requestUser = new Request();
   processorUser.addErrorAction("7101", function () {
       requestUser.urlBase = "lib/plugins/ajaxcommand/ajax.php?call=new_page&template=template:shortcuts&user_id="+shortcutsOption.dispatcher.getGlobalState().userId;
       requestUser.sendRequest(shortcutsOption.getQuery());
   });
   shortcutsOption.addProcessor(processorUser.type, processorUser);
}

