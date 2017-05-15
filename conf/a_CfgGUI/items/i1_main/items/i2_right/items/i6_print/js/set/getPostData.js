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
                && this.dispatcher.getChangesManager().isContentChanged(id)){
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
   }else if(hasChanges==2){
       var editor  =this.dispatcher.getWidget(id);
       var queryValues = editor.getQuerySave(editor.getCurrentSection());
        _ret={
            call:'preview',
            id: ns,
            wikitext:queryValues.prefix+queryValues.wikitext+queryValues.suffix
        };
   }else{
        _ret={
            call:'print',
            id: ns,
        };
   }
   if (rev) {
        _ret.rev = rev;
    }
}


