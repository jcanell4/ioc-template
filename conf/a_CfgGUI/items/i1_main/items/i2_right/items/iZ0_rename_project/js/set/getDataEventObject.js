if (this.newname){
    var globalState=this.dispatcher.getGlobalState();
    var id=globalState.getCurrentId();
    var ns=globalState.getContent(globalState.currentTabId)["ns"];
    var pType = globalState.getContent(id).projectType;
    var project = (this.do) ? this.do : "rename_project";
    var action = (this.action) ? this.action : "";
    _ret = {
        id: id,
        name: 'rename_project',
        dataToSend: {id: ns,
                     projectType: pType,
                     newname: this.newname,
                     do: project,
                     action: action}
    };
}
