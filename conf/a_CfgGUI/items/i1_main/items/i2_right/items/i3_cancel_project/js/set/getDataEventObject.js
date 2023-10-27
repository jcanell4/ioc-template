var globalState = this.dispatcher.getGlobalState();
var id = globalState.getCurrentId();
var pType = globalState.getContent(id).projectType;
_ret = {
    id: id,
    name: 'cancel_project',
    projectType: pType,
    dataToSend: {keep_draft: false}
};
