var requiredPages = this.dispatcher.getGlobalState().getAllRequiredPagesNS();
console.log(requiredPages.join(","));

_ret = "do=logoff&unlock=" + requiredPages.join(",") +"";