//include "dijit/registry"
//include "dojo/cookie"
//include "ioc/wiki30/dispatcherSingleton"



var button = registry.byId('cfgIdConstants::EDIT_BUTTON');
if (button) {

    button.onClick = function (e) {
        var dispatcher = dispatcherSingleton();
        var globalState = dispatcher.getGlobalState();
        var ns= globalState.getContent(globalState.currentTabId).ns;


        // Si la pàgina es troba requerida s'atura la cadena d'events i no s'envia la petició d'edició
        if (globalState.isPageRequired(ns)) {
            e.stopPropagation();
            e.preventDefault();
        }

    };
}
