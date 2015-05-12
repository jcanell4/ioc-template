//include "dijit/registry"
//include "ioc/wiki30/GlobalState"; alias globalState
//include "ioc/wiki30/dispatcherSingleton"; alias wikiIocDispatcher

var tbContainer = registry.byId(wikiIocDispatcher.navegacioNodeId);
if (tbContainer) {
   tbContainer.watch("selectedChildWidget", function (name, oldTab, newTab) {
         var documentId = globalState.getCurrentId();
         var contentCache = wikiIocDispatcher.getContentCache(documentId);
         if (contentCache) {
            contentCache.setCurrentId("navigationPane", newTab.id);
         }
         if (newTab.updateRendering) {
            newTab.updateRendering();
         }
   });
}

