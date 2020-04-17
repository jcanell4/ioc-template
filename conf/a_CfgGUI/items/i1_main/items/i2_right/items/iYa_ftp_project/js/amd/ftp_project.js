//include "dijit/registry"
//include "ioc/wiki30/dispatcherSingleton"
var dispatcher = dispatcherSingleton();
var ftpSendButton = registry.byId('cfgIdConstants::FTP_PROJECT_BUTTON');
var globalState = dispatcher.getGlobalState();
var ns = globalState.getContent(globalState.currentTabId).ns;

var fOnClick = function () {
    var id = dispatcher.getGlobalState().getCurrentId();
    registry.byId("zonaMetaInfo").selectChild(id + "_ftpproject");
    this.setStandbyId(id + "_ftpproject");
};

if (ftpSendButton) {
    ftpSendButton.onClick = fOnClick;
}
