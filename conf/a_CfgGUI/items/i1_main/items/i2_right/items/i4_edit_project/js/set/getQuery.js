_ret="";

if (this.dispatcher.getGlobalState().currentTabId) {
   var globalState=this.dispatcher.getGlobalState();
   var ns=globalState.getContent(globalState.currentTabId).ns;
   var rev=globalState.getCurrentContent().rev;
   var projectType=globalState.getContent(globalState.getCurrentId()).projectType;
   
   if(this.query){
      _ret=this.query+"&";
   }
   _ret+="id="+ns+"&projectType="+projectType;

   if(rev) {
      _ret+="&rev="+rev;
   }

   _ret+=this.dispatcher.getDraftManager().generateLastLocalDraftTimesParam(globalState.currentTabId, ns);
   _ret+="&contentFormat="+globalState.userState['editor'];
}
