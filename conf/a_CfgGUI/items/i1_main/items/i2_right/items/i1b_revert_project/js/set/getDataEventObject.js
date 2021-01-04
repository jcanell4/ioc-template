var globalState = this.dispatcher.getGlobalState();
var id = globalState.getCurrentId();
var ns = globalState.getContent(globalState.currentTabId)["ns"];
var pType = globalState.getContent(id).projectType;
var metaDataSubSet = globalState.getContent(id).metaDataSubSet;
var rev = globalState.getContent(id).rev;
_ret = {
    id: id,
    name: 'revert_project',
    dataToSend: {id: ns,
                 projectType: pType,
                 metaDataSubSet: metaDataSubSet,
                 rev: rev}
};
