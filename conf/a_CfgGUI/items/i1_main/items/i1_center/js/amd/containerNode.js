//include "dijit/registry"; alias "registry"
//include ioc/wiki30/dispatcherSingleton; alias "wikiIocDispatcher"
//include "dojo/_base/lang"

var centralContainer = registry.byId(wikiIocDispatcher.containerNodeId);


if (centralContainer) {

    //TODO[Xavi] mirar si aquest bloc es pot moure al ContainerContentTool o EditorContentTool

    centralContainer.watch("selectedChildWidget", lang.hitch(centralContainer, function (name, oldTab, newTab) {
        // Aquest codi es crida només quan canviem de pestanya
        //TODO [JOSEP] Diria que cal passar això al AbstractChangesManagerDecoration. Pots mirar-ho Xavi?
        if (oldTab && wikiIocDispatcher.getGlobalState()
                        .getContentAction(oldTab.id) == "edit") {
            wikiIocDispatcher.getContentCache(oldTab.id).getEditor().unselect();
        }

        if (wikiIocDispatcher.getGlobalState()
                        .getContentAction(newTab.id) == "edit") {
            wikiIocDispatcher.getContentCache(newTab.id).getEditor().select();
        }

        //wikiIocDispatcher.updateFromState();

    }));
}
