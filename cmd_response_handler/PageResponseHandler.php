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
require_once DOKU_PLUGIN . 'ownInit/WikiGlobalConfig.php';

class PageResponseHandler extends WikiIocResponseHandler
{
    function __construct()
    {
        parent::__construct(WikiIocResponseHandler::PAGE);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator)
    {
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
				"editCommand"   => "lib/plugins/ajaxcommand/ajax.php?call=edit",
                                "pageCommand"   => "lib/plugins/ajaxcommand/ajax.php?call=page",
				"detailCommand" => "lib/plugins/ajaxcommand/ajax.php?call=get_image_detail",
			)
		);

    }
}
