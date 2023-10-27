//include dijit/registry; alias registry

var field = registry.byId('cfgIdConstants::LOGIN_NAME');
if (field) {
    field.on('keyup', function () {
        field.set("value", field.get("value").toLowerCase());
    });
}
