_ret="";
if (this.dispatcher.getGlobalState().currentTabId) {
   var eldelete = this.dispatcher.getGlobalState().currentTabId;
   var ns = this.dispatcher.getGlobalState().getContent(
                        this.dispatcher.getGlobalState().currentTabId)["ns"];
   var confirmar=confirm("Suprimiu aquesta entrada?"); 
   if (confirmar){ 
        _ret="delete="+eldelete+"&do=media&ns="+ns;
   }else{
       _ret="id="+eldelete+"&image="+eldelete+"&img="+eldelete+"&do=media";
   }
}

