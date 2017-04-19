var id = this.dispatcher.getGlobalState().getCurrentId();

var globalState = this.dispatcher.getGlobalState();
var contentToolActual = this.dispatcher.getContentCache(id).getMainContentTool();
var ns = contentToolActual.ns;

if (globalState.isPageRequired(ns)) {
    alert('No es pot restaurar la revisió perquè s\'ha detectat el document original en edició. Has de tancar-lo abans.');

    _ret = {
        _cancel: true
    }

} else {
    _ret = {
        id: id,
        name: 'save',
        extraDataToSend: {do: 'revert'}
    };
}



