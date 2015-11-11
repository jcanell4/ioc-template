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
        $ajaxCmdResponseGenerator->addWikiCodeDocPartial(
            $responseData['structure'],
            true
        );


        // TODO: Que fem amb les metadades?
//        $meta = $responseData['meta']; // TODO[Xavi] en aquest cas no sembla que faci falta, al save_partial si que pots ser interessant
//        $ajaxCmdResponseGenerator->addMetadata($responseData['id'], $meta);

        // TODO: Mantenim la info? afegim una diferent?
//        $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);


    }
}
