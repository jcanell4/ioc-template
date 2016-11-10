_ret="";
var id=this.dispatcher.getGlobalState().getCurrentId();
var query=this.query;
require (["dijit/registry"], function(registry) {
    if (id) {
        var widget=registry.byId(id);
        var projectType=widget.getProjectType();
        if(query){
            _ret=query+"&id="+id+"&projectType="+projectType;
        }else{
            _ret="id="+id+"&projectType="+projectType;
        }
    }
}
);