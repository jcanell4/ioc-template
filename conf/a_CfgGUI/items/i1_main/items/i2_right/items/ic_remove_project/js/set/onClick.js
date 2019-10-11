var globalState=this.dispatcher.getGlobalState();
if (globalState.currentTabId) {
    var id=globalState.getCurrentId();
    this.ok=confirm("Vols eliminar el projecte\n\t\'"+globalState.getContent(id).projectType+"\'?");
}
