var q=dwPageUi.getFormQueryToEditSection(wikiIocDispatcher.getGlobalState().getCurrentSectionId());
if(this.query){
   _ret=this.query + "&" + q;
}else{
   _ret=q;
}
