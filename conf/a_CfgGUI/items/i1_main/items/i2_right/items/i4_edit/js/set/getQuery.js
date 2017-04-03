_ret="";
if (this.dispatcher.getGlobalState().currentTabId) {
    var globalState = this.dispatcher.getGlobalState();
   var ns=globalState.getContent(globalState.currentTabId).ns;

    var rev = globalState.getCurrentContent().rev;

    if(this.query){
       _ret=this.query + "&id=" + ns;
    }else{
       _ret="id=" + ns;
    }

    if (rev) {
        _ret+="&rev=" + rev;
    }

    _ret+=this.dispatcher.getDraftManager().generateLastLocalDraftTimesParam(globalState.currentTabId, ns)
}