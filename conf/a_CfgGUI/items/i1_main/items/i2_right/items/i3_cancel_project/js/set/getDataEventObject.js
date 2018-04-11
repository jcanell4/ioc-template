var globalState = this.dispatcher.getGlobalState();
var id = globalState.getCurrentId();
var pType = globalState.getContent(id).projectType;
var kd = globalState.getContent(id).keep_draft;
_ret = {
    id: id,
    name: 'cancel_project',
    projectType: pType,
    dataToSend: {keep_draft: kd, projectType: pType}
};
