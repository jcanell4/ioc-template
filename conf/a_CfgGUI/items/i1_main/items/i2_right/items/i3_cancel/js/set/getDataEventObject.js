var globalState = this.dispatcher.getGlobalState();
var id = globalState.getCurrentId();

_ret = {
    id: id,
    name: 'cancel',
    dataToSend: {keep_draft: false},
    projectOwner: globalState.getContent(globalState.currentTabId).projectOwner,
    projectSourceType: globalState.getContent(globalState.currentTabId).projectSourceType
};
