<?php

/**
 * Description of New_pageResponseHandler
 *
 * @author Eduardo Latorre Jarque <eduardo.latorre@gmail.com>
 */

if (!defined("DOKU_INC")) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(tpl_incdir().'cmd_response_handler/WikiIocResponseHandler.php');
require_once DOKU_PLUGIN.'ajaxcommand/JsonGenerator.php';

class New_pageResponseHandler extends WikiIocResponseHandler {
    function __construct() {
        parent::__construct(WikiIocResponseHandler::PAGE);
    }
    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $ajaxCmdResponseGenerator->addHtmlDoc($responseData['id'], 
                                                $responseData['ns'], 
                                                $responseData['title'], 
                                                $responseData['content']);
        
        $metaData = $this->getModelWrapper()->getMetaResponse($responseData['id']);
        $ajaxCmdResponseGenerator->addMetadata($metaData['id'],
                                                $metaData['meta']);


        $ajaxCmdResponseGenerator->addInfoDta($responseData["info"]);


        $id = $metaData['id'];
        $revs = $this->getModelWrapper()->getRevisions($id);
        $ajaxCmdResponseGenerator->addRevisionsTypeResponse($id, $revs);


        $ajaxCmdResponseGenerator->addProcessDomFromFunction(
            $responseData['id'],
            true,
            "ioc/dokuwiki/processContentPage",  //TODO configurable
            array(
                "ns" => $responseData['ns'], 
                "editCommand" => "lib/plugins/ajaxcommand/ajax.php?call=edit",
                "detailCommand" => "lib/plugins/ajaxcommand/ajax.php?call=get_image_detail",
            )
        );

    }    
}

?>
