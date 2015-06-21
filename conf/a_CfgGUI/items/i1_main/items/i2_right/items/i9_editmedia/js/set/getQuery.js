_ret="";
if (this.dispatcher.getGlobalState().currentTabId) {
    var elid = this.dispatcher.getGlobalState().currentTabId;
    var ns = this.dispatcher.getGlobalState().getContent(
                    this.dispatcher.getGlobalState().currentTabId)["ns"];
    
                
    _ret='image=' + elid + '&ns=' + ns + '&do=media&tab_details=edit&tab_files=files';

}


