//include "dijit/registry"
//include "ioc/wiki30/dispatcherSingleton"; alias getDispatcher

var wikiIocDispatcher = getDispatcher();
var tab = registry.byId('cfgIdConstants::TB_INDEX');
var wikiIocDispatcher = getDispatcher();
if (tab) {
   wikiIocDispatcher.toUpdateSectok.push(tab);
   tab.updateSectok();
}

