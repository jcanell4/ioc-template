var id = this.dispatcher.getGlobalState().getCurrentId(),
    chunk = this.dispatcher.getGlobalState().getCurrentElementId();
chunk = chunk.replace(id + "_", "");
chunk = chunk.replace("container_", "");

_ret = {
    id: id,
    chunk: chunk,
    name: 'save_partial'
};
