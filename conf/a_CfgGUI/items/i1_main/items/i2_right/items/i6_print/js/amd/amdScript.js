//include "dijit/registry"
//include "dojo/cookie"

var button = registry.byId('cfgIdConstants::PRINT_BUTTON');
if (button) {

    button.onClick = function () {
            console.log("Creant la cookie");
            cookie("IOCForceScriptLoad", 1);
    };
}
