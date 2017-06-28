//include "dijit/registry"
//include "ioc/wiki30/dispatcherSingleton"

var menuItem = registry.byId('cfgIdConstants::LOGOFF_MENU_ITEM');

if (menuItem) {

    jQuery(menuItem.domNode).on('click', function (e) {
        var dispatcher = dispatcherSingleton();
        var globalState = dispatcher.getGlobalState();


        var isAnyPageChanged = globalState.isAnyPageChanged();
        var discardChangesMessage = LANG.template['ioc-template'].confirm_logout_dialog;

        //Si la pàgina es troba requerida s'atura la cadena d'events i no s'envia la petició d'edició
        if (isAnyPageChanged && !confirm(discardChangesMessage)) {
            e.stopPropagation();
            e.preventDefault();
        }

    });
}