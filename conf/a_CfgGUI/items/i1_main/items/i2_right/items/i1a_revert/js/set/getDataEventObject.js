var globalState = this.dispatcher.getGlobalState();
var id = globalState.getCurrentId();
var pType = globalState.getContent(id).projectType;
var contentCache = this.dispatcher.getContentCache(id);
var contentToolActual = contentCache.getMainContentTool();
var ns = contentToolActual.ns;
_ret = {
    id: id,
    name: 'save',
    extraDataToSend: {do:'save_rev',
                      ns:ns,
                      projectType:pType,
                      projectOwner:contentCache.projectOwner,
                      projectSourceType:contentCache.projectSourceType
                  }
};
