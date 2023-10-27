var normalitzaCaracters = dojo.require("ioc/functions/normalitzaCaracters");
if (this.dispatcher.getGlobalState().currentTabId) {
    this.newname = prompt("Escriu el nou nom pel projecte");
    this.newname = normalitzaCaracters(this.newname);
};
