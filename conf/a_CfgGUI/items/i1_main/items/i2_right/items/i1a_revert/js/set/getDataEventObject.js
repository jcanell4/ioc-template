var globalState = this.dispatcher.getGlobalState();
var id = globalState.getCurrentId();
var ns=globalState.getContent(id).ns;
var pType = globalState.getContent(id).projectType;
var projectOwner = globalState.getContent(id).projectOwner;
var projectSourceType = globalState.getContent(id).projectSourceType;
_ret = {
    id: id,
    name: 'save',
    extraDataToSend: {do:'save_rev',
                      ns:ns,
                      projectType:pType,
                      projectOwner:projectOwner,
                      projectSourceType:projectSourceType
                  }
};
