
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

    _ret+=this.dispatcher.getDraftManager().generateLastLocalDraftTimesParam(globalState.currentTabId, ns);
    _ret+="&editorType=" + globalState.userState['editor'];


    console.log("cache:", globalState.getContent(globalState.currentTabId));
    console.log("projectOwner?", globalState.getContent(globalState.currentTabId).projectOwner);
    console.log("projectSourceType?", globalState.getContent(globalState.currentTabId).projectSourceType);

    _ret+="&projectOwner=" + globalState.getContent(globalState.currentTabId).projectOwner;
    _ret+="&projectSourceType=" + globalState.getContent(globalState.currentTabId).projectSourceType;
}