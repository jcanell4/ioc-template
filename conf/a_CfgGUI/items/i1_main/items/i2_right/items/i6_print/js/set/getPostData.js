_ret="";
if (this.dispatcher.getGlobalState().currentTabId) {
   var id = this.dispatcher.getGlobalState().currentTabId;
   var ns=this.dispatcher.getGlobalState().getContent(id)["ns"];
   var rev = this.dispatcher.getGlobalState().getCurrentContent().rev;
   var hasChanges;
   
   if(this.dispatcher.getGlobalState().getCurrentContent().action==='edit' 
                && this.dispatcher.getChangesManager().isContentChanged(id)){
        hasChanges = 1;
   }else if(this.dispatcher.getGlobalState().getCurrentContent().action==='sec_edit'
                && this.dispatcher.getChangesManager().isChanged(id)){
       hasChanges = 2;
   }else{
       hasChanges = 0;
   }
   if(hasChanges==1){
        _ret={
            call:'preview',
            id: ns,
            wikitext:this.dispatcher.getWidget(id).getQuerySave().wikitext
        };
        /*console.log("call:preview, id:"+ns+"wikitext:" + _ret.wikitext);*/
   }else if(hasChanges==2){
       var editor  =this.dispatcher.getWidget(id);
       var queryValues = editor.getQuerySave(editor.getCurrentHeaderId());
        _ret={
            call:'preview',
            id: ns,
            wikitext:queryValues.prefix+queryValues.wikitext+queryValues.suffix
        };
        /*console.log("call:preview, id:"+ns+"wikitext:" + _ret.wikitext);*/
   }else{
        _ret={
            call:'print',
            id: ns,
        };
        /*console.log("call:print, id:"+ns);*/
   }
   if (rev) {
        _ret.rev = rev;
    }
}


