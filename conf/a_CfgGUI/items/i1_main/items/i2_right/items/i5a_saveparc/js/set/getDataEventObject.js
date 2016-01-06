_ret = {};
var id = this.dispatcher.getGlobalState().getCurrentId(),
    chunk = this.dispatcher.getGlobalState().getCurrentSectionId();
chunk = chunk.replace(id + "_", "");
chunk = chunk.replace("container_", "");
_ret = {
    id: id,
    chunk: chunk
};
