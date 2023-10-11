var globalState=this.dispatcher.getGlobalState();
var id = globalState.getCurrentId();
var metaDataSubSet=globalState.getContent(globalState.getCurrentId()).metaDataSubSet;
_ret = {
    id: id,
    metaDataSubSet: metaDataSubSet,
    name: 'save_project'
};
