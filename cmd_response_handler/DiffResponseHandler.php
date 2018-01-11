<?php
/**
 * DiffResponseHandler
 * @author Xavier García<xaviergaro.dev@gmail.com>
 */
if (!defined("DOKU_INC")) die();
require_once(DOKU_INC . 'lib/plugins/ajaxcommand/defkeys/ResponseHandlerKeys.php');
require_once(DOKU_TPL_INCDIR . "cmd_response_handler/WikiIocResponseHandler.php");

class DiffResponseHandler extends WikiIocResponseHandler {

    function __construct() {
        parent::__construct( ResponseHandlerKeys::PAGE );
    }

    protected function response( $requestParams, $responseData, &$ajaxCmdResponseGenerator ) {
        $ajaxCmdResponseGenerator->addDiffDoc( $responseData['id'],
            $responseData['ns'],
            $responseData['title'],
            $responseData['content'],
            $responseData['type'],
            $responseData['rev1'],
            $responseData['rev2']
        );

        $ajaxCmdResponseGenerator->addInfoDta( $responseData["info"] );
        $action = $this->getModelManager()->getActionInstance("RevisionsListAction");
        $revs = $action->get($requestParams);
        $revs['urlBase'] = "lib/exe/ioc_ajax.php?call=diff";

        $ajaxCmdResponseGenerator->addMetaDiff( $responseData['meta']['id'], $responseData['meta']['meta'] );
        $ajaxCmdResponseGenerator->addRevisionsTypeResponse( $responseData['id'], $revs );

        $ajaxCmdResponseGenerator->addProcessDomFromFunction(
            $responseData['id'],
            TRUE,
            "ioc/dokuwiki/processContentPage",  //TODO configurable
            array(
                "ns"            => $responseData['ns'],
                "editCommand"   => "lib/exe/ioc_ajax.php?call=edit",
                "pageCommand"   => "lib/exe/ioc_ajax.php?call=page",
                "detailCommand" => "lib/exe/ioc_ajax.php?call=get_image_detail",
            )
        );

    }
}

