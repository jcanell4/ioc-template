_ret="";
var globalState = this.dispatcher.getGlobalState();
if (globalState.currentTabId) {
    var ns = globalState.getContent(globalState.currentTabId).ns;
    var pType = globalState.getContent(globalState.currentTabId).projectType;
    var pMoodleToken = globalState.getUserState("moodleToken");
    _ret=(this.query) ? this.query + "&id="+ns : "id="+ns;
    if (pType && pType!=="" && pType!==undefined) 
        _ret+="&projectType="+pType;
    if (pMoodleToken && pMoodleToken!=="" && pMoodleToken!==undefined) 
        _ret+="&moodleToken="+pMoodleToken;
    
}
