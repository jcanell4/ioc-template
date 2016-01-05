_ret = {};
alert("test edparc triggered");
var eventManager = this.dispatcher.getEventManager(),
    id = this.dispatcher.getGlobalState().getCurrentId(),
    chunk = this.dispatcher.getGlobalState().getCurrentSectionId();
chunk = chunk.replace(id + "_", "");
chunk = chunk.replace("container_", "");
eventManager.dispatchEvent('edit_partial', {id: id, chunk: chunk});