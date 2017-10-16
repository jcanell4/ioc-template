_ret="";
if (this.dispatcher.getGlobalState().currentTabId) {
    var globalState = this.dispatcher.getGlobalState();
    var ns=globalState.getContent(globalState.currentTabId).ns;
    var rev=globalState.getCurrentContent().rev;

    _ret=((this.query) ? this.query+"&" : "")+"id="+ns;
    _ret+=(rev) ? "&rev="+rev : "";
    _ret+=this.dispatcher.getDraftManager().generateLastLocalDraftTimesParam(globalState.currentTabId, ns)+"&amp;copy_remote=true";
}