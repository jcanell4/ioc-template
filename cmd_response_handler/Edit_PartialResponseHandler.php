<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of page_response_handler
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>, Xavier García <xaviergaro.dev@gmail.com>
 */

if (!defined("DOKU_INC")) {
    die();
}
if (!defined('DOKU_PLUGIN')) {
    define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
}
require_once(tpl_incdir() . 'cmd_response_handler/WikiIocResponseHandler.php');
require_once DOKU_PLUGIN . 'ajaxcommand/JsonGenerator.php';

class Edit_partialResponseHandler extends WikiIocResponseHandler
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

        // TODO[Xavi] tot això no s'està fent servir per el moment. Pendent d'esborrar

//        $params = [];
//        $this->getToolbarIds($params);
//        $params['id'] = $responseData['id'];
//        $params['licenseClass'] = "license";
//        $params['timeout'] = $conf['locktime'] - 60;
//        $params['draft'] = $conf['usedraft'] != 0; // TODO[Xavi] per evitar confusions caldria canviar-lo per usedraft aqui i al frontend
//        $params['locked'] = $responseData['locked']; // Nou, ho passem com a param -> true: està bloquejat

        $ajaxCmdResponseGenerator->addWikiCodeDocPartial(
            $responseData['id'],
            $responseData['ns'],
            $responseData['title'],
            $responseData['structure'],
            true
        );

//        $meta = $responseData['meta']; // TODO[Xavi] en aquest cas no sembla que faci falta, al save_partial si que pots ser interessant



        // TODO: Que fem amb les metadades?
//			$ajaxCmdResponseGenerator->addMetadata( $responseData['id'], $meta );

        // TODO: Mantenim la info? afegim una diferent?
        $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);

//		}

    }
}
