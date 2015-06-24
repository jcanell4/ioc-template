_ret="";
if (this.dispatcher.getGlobalState().currentTabId) {
    var elid = this.dispatcher.getGlobalState().currentTabId;
    var ns = this.dispatcher.getGlobalState().getContent(
                    this.dispatcher.getGlobalState().currentTabId)["ns"];
    if(dojo.query('input[type=radio][name=fileoptions]:checked')[0] === undefined){
        _ret='id=' + elid + '&ns=' + ns + "&do=media&versioupload=true";
    }else{
        var list = dojo.query('input[type=radio][name=fileoptions]:checked')[0].value;
        var sort = dojo.query('input[type=radio][name=filesort]:checked')[0].value;
        _ret='id=' + elid + '&ns=' + ns + '&do=media&list='+list+'&sort='+sort+"&versioupload=true";

    }        
}


