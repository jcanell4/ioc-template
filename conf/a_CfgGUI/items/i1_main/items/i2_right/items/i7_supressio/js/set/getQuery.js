_ret="";
if (this.dispatcher.getGlobalState().currentTabId) {
   var eldelete = this.dispatcher.getGlobalState().currentTabId;
   var ns =this.dispatcher.getGlobalState().getContent(
                        this.dispatcher.getGlobalState().currentTabId)["ns"];
   _ret="call=mediadetails&delete="+eldelete+"&do=media&ns="+ns;
}


