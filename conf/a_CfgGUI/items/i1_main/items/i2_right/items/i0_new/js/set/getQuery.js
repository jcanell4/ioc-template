var ret;
var ns = wikiIocDispatcher.getGlobalState().pages[wikiIocDispatcher.getGlobalState().currentTabId]["ns"];
if(this.query){
   ret = this.query + "&id=" + ns;
}else{
   ret = "id=" + ns;
}
return ret;
