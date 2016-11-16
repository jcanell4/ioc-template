_ret="";
var node = this.dispatcher.getGlobalState().getDwPageUi().getElementParentNodeId(this.dispatcher.getGlobalState().getCurrentElementId(), "DL");
var ns = this.dispatcher.getGlobalState().getContent(this.dispatcher.getGlobalState().currentTabId)["ns"];
if (node) {
    var elid = "";
    if (typeof node === "string") {
        elid = node;
    } else {
        elid = node.title;
    }
   var confirmar=confirm("Suprimiu aquesta entrada?"); 
   if (confirmar){        
        _ret = 'call=media&id=' + elid + '&image=' + elid + '&img=' + elid + '&delete=' + elid + '&ns=' + ns + '&do=media';
    }else{
        _ret = 'call=media&ns=' + ns + '&do=media';
    }
}
