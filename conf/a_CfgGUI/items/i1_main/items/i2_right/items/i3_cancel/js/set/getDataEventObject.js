var globalState = this.dispatcher.getGlobalState();
var id = globalState.getCurrentId();

// console.log("Cancel, quins params es pasen: projectOwner, projectSourceType", globalState.getContent(globalState.currentTabId).projectOwner, globalState.getContent(globalState.currentTabId).projectSourceType);
_ret = {
    id: id,
    name: 'cancel',
    dataToSend: {keep_draft: false},
    projectOwner: globalState.getContent(globalState.currentTabId).projectOwner,
    projectSourceType: globalState.getContent(globalState.currentTabId).projectSourceType
};


