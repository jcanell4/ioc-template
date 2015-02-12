var ret;
var q = dwPageUi.getFormQueryToEditSection(
           wikiIocDispatcher.getGlobalState().getCurrentSectionId()
        );
if (this.query) {
   ret = this.query + "&" + q;
}else {
   ret = q;
}
return ret;

