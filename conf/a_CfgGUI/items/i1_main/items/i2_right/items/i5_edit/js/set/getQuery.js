var ns=this.dispatcher.getGlobalState().pages[
                            this.dispatcher.getGlobalState().currentTabId]["ns"];

var rev = this.dispatcher.getGlobalState().getCurrentContent().rev;

if(this.query){
   _ret=this.query + "&id=" + ns;
}else{
   _ret="id=" + ns;
}

if (rev) {
    _ret+="&rev=" + rev;
}