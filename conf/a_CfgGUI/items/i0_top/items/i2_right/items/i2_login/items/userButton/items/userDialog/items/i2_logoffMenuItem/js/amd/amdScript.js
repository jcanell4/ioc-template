//include "dijit/registry"
//include "ioc/wiki30/dispatcherSingleton"

var menuItem = registry.byId('cfgIdConstants::LOGOFF_MENU_ITEM');

if (menuItem) {

    jQuery(menuItem.domNode).on('click', function (e) {
        var dispatcher = dispatcherSingleton();
        var globalState = dispatcher.getGlobalState();


        var isAnyPageChanged = globalState.isAnyPageChanged();
        var discardChangesMessage = "Hi han documents en edició amb canvis, vols descartar-los i desconnectar?"; // TODO[Xavi] Localitzar

        //Si la pàgina es troba requerida s'atura la cadena d'events i no s'envia la petició d'edició
        if (isAnyPageChanged && !confirm(discardChangesMessage)) {
            console.log("Es cancel·la l'event");
            e.stopPropagation();
            e.preventDefault();
        }



    });
}