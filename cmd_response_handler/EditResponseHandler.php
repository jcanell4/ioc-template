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

class EditResponseHandler extends WikiIocResponseHandler {
    function __construct() {
        parent::__construct(WikiIocResponseHandler::EDIT);
    }
    protected function process($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $ajaxCmdResponseGenerator->addWikiCodeDoc($contentData['id'], 
                                    $contentData['ns'], $contentData['title'],
                                    $contentData['content']);
        $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processEditing", 
                                $this->getToolbarIds());        
    }    
}

?>
