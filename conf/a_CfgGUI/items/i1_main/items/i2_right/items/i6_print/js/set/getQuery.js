_ret="";
if (this.dispatcher.getGlobalState().currentTabId) {
   var ns=this.dispatcher.getGlobalState().getContent(
                    this.dispatcher.getGlobalState().currentTabId)["ns"];
   var rev = this.dispatcher.getGlobalState().getCurrentContent().rev;
   if(this.query){
      _ret=this.query + "&id=" + ns;
   }else{
      _ret="id=" + ns;
   }
   
   if (rev) {
        _ret+="&rev=" + rev;
    }
}
