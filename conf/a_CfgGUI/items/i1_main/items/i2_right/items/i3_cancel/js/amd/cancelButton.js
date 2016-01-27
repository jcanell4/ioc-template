//include "dijit/registry"
//include "dojo/dom"
//include "dojo/dom-construct"; alias "domConstruct"
//include "dijit/layout/BorderContainer"
//include "dijit/Dialog"
//include "dijit/layout/ContentPane"
//include "dijit/form/Form"
//include  "dijit/form/TextBox"
//include  "dijit/form/Button"
//include  "ioc/gui/NsTreeContainer"
//include  "ioc/gui/NsTreeContainer"
//include  "ioc/wiki30/dispatcherSingleton"; alias "getDispatcher"


var dispatcher = getDispatcher();
var cancelButton = registry.byId('cfgIdConstants::CANCEL_BUTTON'),
    changesManager = dispatcher.getChangesManager();

if (cancelButton) {
    cancelButton.on('click', function () {
        var docId = dispatcher.getGlobalState().getCurrentId();
        if (changesManager.isContentChanged(docId) === false) {
            cancelButton.query = cancelButton.query.replace('cancel', 'page');
            cancelButton.urlBase = cancelButton.urlBase.replace('cancel', 'page');
        } else {
            cancelButton.query = cancelButton.query.replace('page', 'cancel');
            cancelButton.urlBase = cancelButton.urlBase.replace('page', 'cancel');
        }

    });
}
