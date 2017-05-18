var id = this.dispatcher.getGlobalState().getCurrentId();

_ret = {
    id: id,
    name: 'cancel',
    dataToSend: {keep_draft: false}
};
