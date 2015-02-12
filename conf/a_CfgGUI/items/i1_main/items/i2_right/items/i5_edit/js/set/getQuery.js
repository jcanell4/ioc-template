var ns=wikiIocDispatcher.getGlobalState().pages[wikiIocDispatcher.getGlobalState().currentTabId]["ns"];
if(this.query){
   _ret=this.query + "&id=" + ns;
}else{
   _ret="id=" + ns;
}
