_ret="";
var gState=this.dispatcher.getGlobalState();
var id=gState.getCurrentId();
if (gState.currentTabId)
    var ns=gState.getContent(gState.currentTabId).ns;
var query=this.query;
require (["dijit/registry"], function(registry) {
    if (id) {
        if (!ns) ns=id;
        var widget=registry.byId(id);
        var projectType=widget.getProjectType();
        if(query){
            _ret=query+"&id="+ns+"&projectType="+projectType;
        }else{
            _ret="id="+ns+"&projectType="+projectType;
        }
    }
    console.log("_ret (dins require):" + _ret);
});
console.log("_ret (fora require):" + _ret);