//include "dijit/registry"
//include "ioc/wiki30/dispatcherSingleton"; alias wikiIocDispatcher

var tab = registry.byId('cfgIdConstants::TB_INDEX');
if (tab) {
   wikiIocDispatcher.toUpdateSectok.push(tab);
   tab.updateSectok();
}

