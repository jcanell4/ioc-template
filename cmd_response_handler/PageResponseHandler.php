<?php
if (!defined("DOKU_INC")) die();
require_once(tpl_incdir() . 'cmd_response_handler/WikiIocResponseHandler.php');

/**
 * PageResponseHandler
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
class PageResponseHandler extends WikiIocResponseHandler {

    function __construct($cmd = WikiIocResponseHandler::PAGE) {
        parent::__construct($cmd);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        //ALERTA[Xavi] En obrir el fitxer s'actualitzen els esborranys locals
        if ($responseData['drafts']) {
            $ajaxCmdResponseGenerator->addUpdateLocalDrafts($responseData['structure']['ns'], $responseData['drafts']);
        }

        $autosaveTimer = NULL;
        if(WikiGlobalConfig::getConf("autosaveTimer")){
            $autosaveTimer = WikiGlobalConfig::getConf("autosaveTimer")*1000;
        }
        $ajaxCmdResponseGenerator->addWikiCodeDocPartial(
                $responseData['structure'],
                NULL,
                isset($responseData['draftType']),
                $autosaveTimer
        );

        if (isset($responseData['meta'])) {
            $ajaxCmdResponseGenerator->addMetadata($responseData['structure']['id'], $responseData['meta']);
        }

        if (isset($responseData['info'])) {
            $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
        }

        if (isset($responseData['revs']) && count($responseData['revs']) > 0) {

            $responseData['revs']['urlBase'] = "lib/exe/ioc_ajax.php?call=diff";
            $ajaxCmdResponseGenerator->addRevisionsTypeResponse($responseData['structure']['id'], $responseData['revs']);

        } else if(isset($responseData['meta'])) {
            $ajaxCmdResponseGenerator->addExtraMetadata(
                $responseData['structure']['id'],
                $responseData['structure']['id'] . '_revisions',
                'No hi ha revisions',
                "<h2> Aquest document no té revisions </h2>" //TODO[Xavi] localització
            );
        }

        $ajaxCmdResponseGenerator->addProcessDomFromFunction(
                $responseData['structure']['id'],
                TRUE,
                "ioc/dokuwiki/processContentPage",  //TODO configurable
                array(
                        "ns"            => $responseData['structure']['ns'],
                        "editCommand"   => "lib/exe/ioc_ajax.php?call=edit",
                        "pageCommand"   => "lib/exe/ioc_ajax.php?call=page",
                        "detailCommand" => "lib/exe/ioc_ajax.php?call=get_image_detail",
                )
        );

    }
}
