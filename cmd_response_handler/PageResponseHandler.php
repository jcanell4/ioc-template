<?php
/**
 * PageResponseHandler
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined("DOKU_INC")) die();
require_once(DOKU_TPL_INCDIR . "cmd_response_handler/WikiIocResponseHandler.php");

class PageResponseHandler extends WikiIocResponseHandler {

    function __construct($cmd = ResponseHandlerKeys::PAGE) {
        parent::__construct($cmd);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        self::staticResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
    }

    static function staticResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        //ALERTA[Xavi] En obrir el fitxer s'actualitzen els esborranys locals
        if ($responseData['drafts']) {
            $ajaxCmdResponseGenerator->addUpdateLocalDrafts($responseData['structure']['ns'], $responseData['drafts']);
        }

        $autosaveTimer = WikiGlobalConfig::getConf("autosaveTimer");
        if ($autosaveTimer) $autosaveTimer *= 1000;

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

        if (isset($responseData[PageKeys::KEY_REVISIONS]) && count($responseData[PageKeys::KEY_REVISIONS]) > 0) {
            $responseData[PageKeys::KEY_REVISIONS]['urlBase'] = "lib/exe/ioc_ajax.php?call=diff";
            if ($requestParams[RequestParameterKeys::PROJECT_OWNER]) {
                $responseData[PageKeys::KEY_REVISIONS]['call_view'] = "page&projectOwner={$requestParams[RequestParameterKeys::PROJECT_OWNER]}&projectSourceType={$requestParams[RequestParameterKeys::PROJECT_SOURCE_TYPE]}";
            }
            $ajaxCmdResponseGenerator->addRevisionsTypeResponse($responseData['structure']['id'], $responseData[PageKeys::KEY_REVISIONS]);
        }
        else if(isset($responseData['meta'])) {
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
