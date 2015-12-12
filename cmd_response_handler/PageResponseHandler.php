<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of page_response_handler
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */

if (!defined("DOKU_INC")) {
    die();
}
if (!defined('DOKU_PLUGIN')) {
    define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
}
require_once(tpl_incdir() . 'cmd_response_handler/WikiIocResponseHandler.php');
require_once DOKU_PLUGIN . 'ajaxcommand/JsonGenerator.php';

class PageResponseHandler extends WikiIocResponseHandler
{
    function __construct()
    {
        parent::__construct(WikiIocResponseHandler::PAGE);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator)
    {
//		$ajaxCmdResponseGenerator->addHtmlDoc( $responseData['id'],
//		                                       $responseData['ns'],
//		                                       $responseData['title'],
//		                                       $responseData['content'],
//		                                       $responseData['rev'],
//		                                       $responseData['type']
////		                                       $responseData['action']
//
//		);
//
//

        $ajaxCmdResponseGenerator->addWikiCodeDocPartial(
            $responseData['structure']
        );

        // TODO[Xavi] Reactivar les metas i la info
//
//


        if (isset($responseData['meta'])) {
            $ajaxCmdResponseGenerator->addMetadata($responseData['meta']['id'], $responseData['meta']['meta']);
        }

        if (isset($responseData['info'])) {
            $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
        }

        if (isset($responseData['revs']) && count($responseData['revs']) > 0) {
            $ajaxCmdResponseGenerator->addRevisionsTypeResponse($responseData['structure']['id'], $responseData['revs']);
        } else {
            $ajaxCmdResponseGenerator->addExtraMetadata(
                $responseData['structure']['id'],
                $responseData['structure']['id'] . '_revisions',
                'No hi ha revisions',
                "<h2> Aquest document no té revisions </h2>" //TODO[Xavi] localització
            );
        }


        // TODO[Xavi] Això ha de ser reemplaçat per les funcionalitats dels ContenTools
		$ajaxCmdResponseGenerator->addProcessDomFromFunction(
			$responseData['structure']['id'],
			TRUE,
			"ioc/dokuwiki/processContentPage",  //TODO configurable
			array(
				"ns"            => $responseData['structure']['ns'],
				"editCommand"   => "lib/plugins/ajaxcommand/ajax.php?call=edit",
				"detailCommand" => "lib/plugins/ajaxcommand/ajax.php?call=get_image_detail",
			)
		);

    }
}
