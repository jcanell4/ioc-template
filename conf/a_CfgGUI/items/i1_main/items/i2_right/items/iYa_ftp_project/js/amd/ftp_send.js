//include "dijit/registry"
//include "ioc/wiki30/dispatcherSingleton"
var dispatcher = dispatcherSingleton();
var ftpSendButton = registry.byId('cfgIdConstants::FTP_PROJECT_BUTTON');
var globalState = dispatcher.getGlobalState();

var fOnClick = function () {
    var id = dispatcher.getGlobalState().getCurrentId();
    registry.byId("zonaMetaInfo").selectChild(id + "_ftpsend");
    this.setStandbyId(id + "_ftpsend");
};
if (ftpSendButton) {
    ftpSendButton.onClick = fOnClick;
}
