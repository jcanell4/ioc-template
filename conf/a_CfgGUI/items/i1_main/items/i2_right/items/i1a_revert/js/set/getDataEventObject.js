var globalState = this.dispatcher.getGlobalState();
var id = globalState.getCurrentId();
var contentToolActual = this.dispatcher.getContentCache(id).getMainContentTool();
var ns = contentToolActual.ns;
_ret = {
    id: id,
    name: 'save',
    extraDataToSend: {do: 'save_rev'}
};
