var self = this;
require(["ioc/dokuwiki/dwPageUi"], function(dwPageUi){
    var q=dwPageUi.getFormQueryToEditSection(
            self.dispatcher.getGlobalState().getCurrentSectionId());
    if(self.query){
       _ret=self.query + "&" + q;
    }else{
       _ret=q;
    }
});
