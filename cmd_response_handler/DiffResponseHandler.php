<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of diff_response_handler
 *
 * @author Xavier García<xaviergaro.dev@gmail.com>
 */

if ( ! defined( "DOKU_INC" ) ) {
	die();
}
if ( ! defined( 'DOKU_PLUGIN' ) ) {
	define( 'DOKU_PLUGIN', DOKU_INC . 'lib/plugins/' );
}
require_once( tpl_incdir() . 'cmd_response_handler/WikiIocResponseHandler.php' );
require_once DOKU_PLUGIN . 'ajaxcommand/JsonGenerator.php';

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

//		$metaData = $this->getModelWrapper()->getMetaResponse( $responseData['id'] );

//		if ($metaData['id']) {
//		$ajaxCmdResponseGenerator->addMetadata( $responseData['meta']['id'],
//		                                        $responseData['meta']['meta']);
////		}

		$ajaxCmdResponseGenerator->addInfoDta( $responseData["info"] );

//		$id   = $metaData['id'];
		$id = $responseData['id'];

		$revs = $this->getModelWrapper()->getRevisions( $id );
		$revs['urlBase'] = "lib/plugins/ajaxcommand/ajax.php?call=diff";


		$ajaxCmdResponseGenerator->addMetaDiff( $responseData['meta']['id'],
		                                                $responseData['meta']['meta'] );

		$ajaxCmdResponseGenerator->addRevisionsTypeResponse( $id, $revs );

		$ajaxCmdResponseGenerator->addProcessDomFromFunction(
			$responseData['id'],
			TRUE,
			"ioc/dokuwiki/processContentPage",  //TODO configurable
			array(
				"ns"            => $responseData['ns'],
				"editCommand"   => "lib/plugins/ajaxcommand/ajax.php?call=edit",
                                "pageCommand"   => "lib/plugins/ajaxcommand/ajax.php?call=page",
				"detailCommand" => "lib/plugins/ajaxcommand/ajax.php?call=get_image_detail",
			)
		);

	}
}

