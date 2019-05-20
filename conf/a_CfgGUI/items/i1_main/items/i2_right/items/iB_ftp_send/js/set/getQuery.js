_ret="";
if (this.dispatcher.getGlobalState().currentTabId) {
    var globalState = this.dispatcher.getGlobalState();
    /*var id = globalState.currentTabId;*/
    var ns = globalState.getContent(globalState.currentTabId).ns;
    var pType = globalState.getContent(globalState.currentTabId).projectType;
    if (this.query) {
        _ret=this.query + "&id="+ns;
    }else {
        _ret="id="+ns;
    }
    _ret+="&do=ftpsend" + "&projectType="+pType;
}
