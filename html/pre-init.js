require([
        "dojo/dom",
        "dojo/dom-style",
        "dojo/window",
        "ioc/wiki30/dispatcherSingleton",
        "dojo/domReady!"
], function (dom, style, win, getDispatcher) {

        var wikiIocDispatcher = getDispatcher();
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
        wikiIocDispatcher.edParcButtonId      = 'cfgIdConstants::ED_PARC_BUTTON';
        wikiIocDispatcher.userButtonId        = 'cfgIdConstants::USER_BUTTON';
        wikiIocDispatcher.mediaDetailButtonId = 'cfgIdConstants::MEDIA_DETAIL_BUTTON';
        wikiIocDispatcher.mediaTornarButtonId = 'cfgIdConstants::MEDIA_TORNAR_BUTTON';
        wikiIocDispatcher.mediaSupressioButtonId = 'cfgIdConstants::MEDIA_SUPRESSIO_BUTTON';
        wikiIocDispatcher.mediaUploadButtonId = 'cfgIdConstants::MEDIA_UPLOAD_BUTTON';
        wikiIocDispatcher.mediaEditButtonId = 'cfgIdConstants::MEDIA_EDIT_BUTTON';
        wikiIocDispatcher.cancelParcButtonId      = 'cfgIdConstants::CANCEL_PARC_BUTTON';
        wikiIocDispatcher.saveParcButtonId      = 'cfgIdConstants::CANCEL_PARC_BUTTON';
});

