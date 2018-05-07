_ret="";
if (this.dispatcher.getGlobalState().currentTabId) {
   var globalState=this.dispatcher.getGlobalState();
   var ns=globalState.getContent(globalState.currentTabId).ns;
   var rev=globalState.getCurrentContent().rev;
   var projectType=globalState.getContent(globalState.getCurrentId()).projectType;
   var hasDraft = !jQuery.isEmptyObject(this.dispatcher.getDraftManager().getContentLocalDraft(ns));
   
   if(this.query) _ret=this.query+"&";
   _ret+="id="+ns+"&projectType="+projectType;
   if(hasDraft) _ret+="&hasDraft=true";
   if(rev) _ret+="&rev="+rev;
   _ret+=this.dispatcher.getDraftManager().generateLastLocalDraftTimesParam(globalState.currentTabId, ns);
   _ret+="&contentFormat="+globalState.userState['editor'];
}
