<?php
/**
 * Description of page_response_handler
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */

if (!defined("DOKU_INC")) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(tpl_incdir().'cmd_response_handler/PageResponseHandler.php');
require_once DOKU_PLUGIN.'ajaxcommand/JsonGenerator.php';

class CancelResponseHandler extends PageResponseHandler {
    function __construct() {
        parent::__construct(WikiIocResponseHandler::CANCEL);
    }
    protected function response($requestParams, 
                                $responseData, 
                                &$ajaxCmdResponseGenerator) {
        parent::response($requestParams, 
                        $responseData, 
                        $ajaxCmdResponseGenerator);
        $ajaxCmdResponseGenerator->addProcessFunction(true, 
                                            "ioc/dokuwiki/processCancellation");
        $ajaxCmdResponseGenerator->addInfoDta(' ');
    }
}

?>