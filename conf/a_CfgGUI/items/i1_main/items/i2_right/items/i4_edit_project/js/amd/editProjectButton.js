//include "dijit/registry"
//include "ioc/functions/getValidator"

var button = registry.byId('cfgIdConstants::EDIT_PROJECT_BUTTON');
button.setValidator(getValidator('PageNotRequired'));
