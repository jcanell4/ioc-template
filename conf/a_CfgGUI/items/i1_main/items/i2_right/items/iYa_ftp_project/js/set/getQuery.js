_ret="";
var globalState = this.dispatcher.getGlobalState();
if (globalState.currentTabId) {
    var ns = globalState.getContent(globalState.currentTabId).ns;
    _ret=(this.query) ? this.query + "&id="+ns : "id="+ns;
    var pType = globalState.getContent(globalState.currentTabId).projectType;
    if (pType && pType!=="" && pType!==undefined) 
        _ret+="&projectType="+pType;
    var pMoodleToken = globalState.getUserState("moodleToken");
    if (pMoodleToken && pMoodleToken!=="" && pMoodleToken!==undefined) 
        _ret+="&moodleToken="+pMoodleToken;
}
