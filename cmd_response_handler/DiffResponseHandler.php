<?php
/**
 * DiffResponseHandler
 * @author Xavier García<xaviergaro.dev@gmail.com>
 */
if (!defined('DOKU_INC')) die();
require_once( tpl_incdir() . 'cmd_response_handler/WikiIocResponseHandler.php' );

class DiffResponseHandler extends WikiIocResponseHandler {

    function __construct() {
        parent::__construct( WikiIocResponseHandler::PAGE );
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
        $revs = $this->getModelWrapper()->getRevisionsList( $requestParams );
        $revs['urlBase'] = "ajax.php?call=diff";

        $ajaxCmdResponseGenerator->addMetaDiff( $responseData['meta']['id'], $responseData['meta']['meta'] );
        $ajaxCmdResponseGenerator->addRevisionsTypeResponse( $responseData['id'], $revs );

        $ajaxCmdResponseGenerator->addProcessDomFromFunction(
            $responseData['id'],
            TRUE,
            "ioc/dokuwiki/processContentPage",  //TODO configurable
            array(
                "ns"            => $responseData['ns'],
                "editCommand"   => "ajax.php?call=edit",
                "pageCommand"   => "ajax.php?call=page",
                "detailCommand" => "ajax.php?call=get_image_detail",
            )
        );

    }
}

