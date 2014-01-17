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

if (!defined("DOKU_INC")) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(tpl_incdir().'cmd_response_handler/WikiIocResponseHandler.php');
require_once DOKU_PLUGIN.'ajaxcommand/JsonGenerator.php';

class PageResponseHandler extends WikiIocResponseHandler {
    function __construct() {
        parent::__construct(WikiIocResponseHandler::PAGE);
    }
    protected function process($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $ajaxCmdResponseGenerator->addSetJsInfo($this->getJsInfo());
        $ajaxCmdResponseGenerator->addHtmlDoc($responseData['id'], 
                                                $responseData['ns'], 
                                                $responseData['title'], 
                                                $responseData['content']);
        
        $metaData = $this->getMetaResponse($responseData['id']);
        $ajaxCmdResponseGenerator->addMetadata($metaData['docId'], 
                                                $metaData['meta']);
        $ajaxCmdResponseGenerator->addProcessDomFromFunction(
            $responseData['id'],
            true,
            "ioc/dokuwiki/processContentPage",  //TODO configurable
            array(
                "ns" => $responseData['ns'], 
                "command" => "lib/plugins/ajaxcommand/ajax.php?call=edit",
            )
        );
    }    
}

?>