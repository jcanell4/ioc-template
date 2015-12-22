_ret={};
if (this.dispatcher.getGlobalState().getCurrentId()) {
    _ret.urlBase = this.urlBase;
    _ret.currentId = this.dispatcher.getGlobalState().getCurrentId();
    _ret.editFormId = "dw__editform";
   var ns=this.dispatcher.getGlobalState().getContent(
                        this.dispatcher.getGlobalState().getCurrentId())["ns"];
   if(this.query){
          _ret.query = this.query + "&id=" + ns;
   }else{
      _ret="id=" + ns;
   }
}
