_ret="";
var node = this.dispatcher.getGlobalState().getDwPageUi().getElementParentNodeId(this.dispatcher.getGlobalState().getCurrentElementId(), "DL");
if (node) {
    var elid = "";
    if (typeof node === "string") {
        elid = node;
    } else {
        elid = node.title;
    }
    _ret = 'id=' + elid + '&image=' + elid + '&img=' + elid + '&do=media';
}


