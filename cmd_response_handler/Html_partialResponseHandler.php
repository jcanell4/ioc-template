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

class Html_partialResponseHandler extends WikiIocResponseHandler
{
    function __construct()
    {
        parent::__construct(WikiIocResponseHandler::EDIT);
    }

    /**
     * @param string[] $requestParams
     * @param mixed $responseData
     * @param AjaxCmdResponseGenerator $ajaxCmdResponseGenerator
     *
     * @return void
     */
    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator)
    {
        global $conf;
        global $INFO;

        // TODO: Xavi per determinar que fem amb els esborranys en el cas de les edicions parcials. Per el moment desactivat


//		if ( $responseData['show_draft_dialog'] ) {
//
//			// No s'envien les respostes convencionals
//
//			$ajaxCmdResponseGenerator->addDraftDialog(
//				$responseData['id'], $responseData['ns'],
//				$responseData['title'], $responseData['content'], $responseData['draft'],
//				$conf['locktime'] - 60, $this->getModelWrapper()->extractDateFromRevision($INFO['lastmod'])
//			);
//
//		} else {

        // TODO: això no es fa servir actualment, i quan ho fem servir serà different
        $params = [];
        $this->getToolbarIds($params);
        $params['id'] = $responseData['id'];
        $params['licenseClass'] = "license";
        $params['timeout'] = $conf['locktime'] - 60;
        $params['draft'] = $conf['usedraft'] != 0; // TODO[Xavi] per evitar confusions caldria canviar-lo per usedraft aqui i al frontend
        $params['locked'] = $responseData['locked']; // Nou, ho passem com a param -> true: està bloquejat

//        $ajaxCmdResponseGenerator->addWikiCodeDoc(
//            $responseData['id'], $responseData['ns'],
//            $responseData['title'], $responseData['content'], $responseData['draft'], $responseData['recover_draft'],
//            $params
//        );

        $ajaxCmdResponseGenerator->addWikiCodeDocPartial(
//            $responseData['id'],
//            $responseData['ns'],
//            $responseData['title'],
            $responseData['structure']
        );

//        $meta = $responseData['meta'];
//        if($requestParams["reload"]){
//            $respostaMeta = $this->getModelWrapper()->getMetaResponse($responseData['id'])['meta'];
//            $meta = array_merge($meta, $respostaMeta);
//        }


        // TODO: Que fem amb les metadades?
//			$ajaxCmdResponseGenerator->addMetadata( $responseData['id'], $meta );

        // TODO: Mantenim la info? afegim una diferent? <-- la info ha de venir del dokumodeladapter
//        $ajaxCmdResponseGenerator->addInfoDta(
//            'info', 'Activada edició parcial', $responseData['id']
//        );

//		}

//		$params = array();
//		$this->getToolbarIds( $params );
//		$params['id']           = $responseData['id'];
//		$params['licenseClass'] = "license";
//		$params['timeout']      = $conf['locktime'] - 60;
//		$params['draft']        = $conf['usedraft'] != 0;
//
//		$ajaxCmdResponseGenerator->addProcessFunction(
//			TRUE, "ioc/dokuwiki/processEditing",
//			$params
//		);

    }
}
