if (this.ok==true){
    var globalState=this.dispatcher.getGlobalState();
    var id=globalState.getCurrentId();
    var ns=globalState.getContent(globalState.currentTabId)["ns"];
    var pType=globalState.getContent(id).projectType;
    var userId=globalState.userId;
    _ret = {
        id: id,
        name: 'remove_project',
        dataToSend: {id: ns,
                     projectType: pType,
                     user_id: userId}
    };
}
