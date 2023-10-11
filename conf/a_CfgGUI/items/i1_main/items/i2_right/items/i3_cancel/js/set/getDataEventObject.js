var globalState = this.dispatcher.getGlobalState();
var id = globalState.getCurrentId();


_ret = {
    id: id,
    name: 'cancel',
    dataToSend: {keep_draft: false},
};

var contentCache = globalState.getContent(globalState.currentTabId);

if (contentCache.projectOwner && contentCache.projectOwner !== 'undefined') {
    _ret['projectOwner'] = contentCache.projectOwner;
    _ret['projectSourceType'] = contentCache.projectSourceType;
};



