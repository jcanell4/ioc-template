_ret="";
if (this.dispatcher.getGlobalState().currentTabId) {
   var ns=this.dispatcher.getGlobalState().getContent(
                    this.dispatcher.getGlobalState().currentTabId)["ns"];
   if(this.query){
      _ret=this.query + "&id=" + ns;
   }else{
      _ret="id=" + ns;
   }
}
