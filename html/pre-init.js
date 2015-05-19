require([
        "dojo/dom",
        "dojo/dom-style",
        "dojo/window",
        "ioc/wiki30/dispatcherSingleton",
        "dojo/domReady!"
], function (dom, style, win, wikiIocDispatcher) {

        var divMainContent = dom.byId("cfgIdConstants::MAIN_CONTENT");
        if (!divMainContent) {
            return;
        }

        var h = 100 * (win.getBox().h - 55) / win.getBox().h;
        style.set(divMainContent, "height", h + "%");

        wikiIocDispatcher.containerNodeId     = "cfgIdConstants::BODY_CONTENT";
        wikiIocDispatcher.navegacioNodeId     = "cfgIdConstants::ZONA_NAVEGACIO";
        wikiIocDispatcher.metaInfoNodeId      = "cfgIdConstants::ZONA_METAINFO";
        wikiIocDispatcher.infoNodeId          = "cfgIdConstants::ZONA_MISSATGES";
        wikiIocDispatcher.sectokManager.putSectok("cfgIdConstants::SECTOK_ID", "cfgIdConstants::SECTOK");
        wikiIocDispatcher.loginButtonId       = 'cfgIdConstants::LOGIN_BUTTON';
        wikiIocDispatcher.exitButtonId        = 'cfgIdConstants::EXIT_BUTTON';
        wikiIocDispatcher.editButtonId        = 'cfgIdConstants::EDIT_BUTTON';
        wikiIocDispatcher.saveButtonId        = 'cfgIdConstants::SAVE_BUTTON';
        wikiIocDispatcher.cancelButtonId      = 'cfgIdConstants::CANCEL_BUTTON';
        wikiIocDispatcher.previewButtonId     = 'cfgIdConstants::PREVIEW_BUTTON';
        wikiIocDispatcher.edParcButtonId      = 'cfgIdConstants::ED_PARC_BUTTON';
        wikiIocDispatcher.userButtonId        = 'cfgIdConstants::USER_BUTTON';
        wikiIocDispatcher.mediaDetailButtonId = 'cfgIdConstants::MEDIA_DETAIL_BUTTON';
});
