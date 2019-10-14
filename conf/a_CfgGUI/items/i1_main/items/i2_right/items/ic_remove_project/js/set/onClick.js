var globalState=this.dispatcher.getGlobalState();
if (globalState.currentTabId) {
    var id=globalState.getCurrentId();
    this.ok=confirm("Vols eliminar el projecte\n\n\t\'"+globalState.getContent(id).ns+"\'?");
}
