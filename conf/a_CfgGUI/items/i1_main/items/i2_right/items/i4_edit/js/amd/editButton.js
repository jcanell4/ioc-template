//include "dijit/registry"
//include "ioc/functions/getValidator"

var button = registry.byId('cfgIdConstants::EDIT_BUTTON');
button.setValidator(getValidator('PageNotRequired'));
