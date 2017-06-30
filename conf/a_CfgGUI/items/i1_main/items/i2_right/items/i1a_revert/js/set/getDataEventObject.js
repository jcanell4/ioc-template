var id = this.dispatcher.getGlobalState().getCurrentId();

var globalState = this.dispatcher.getGlobalState();
var contentToolActual = this.dispatcher.getContentCache(id).getMainContentTool();
var ns = contentToolActual.ns;

    _ret = {
    id: id,
    name: 'save',
    extraDataToSend: {do: 'save_rev'}
};



