var ns=this.dispatcher.getGlobalState().pages[
                        this.dispatcher.getGlobalState().currentTabId]["ns"];
if(this.query){
   _ret=this.query + "&id=" + ns;
}else{
   _ret="id=" + ns;
}
