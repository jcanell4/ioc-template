<?php
/**
 * Description of page_response_handler
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(tpl_incdir().'cmd_response_handler/PageResponseHandler.php');

class CancelResponseHandler extends PageResponseHandler {

    function __construct() {
        parent::__construct(WikiIocResponseHandler::CANCEL);
    }
    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {

        if(isset($responseData["codeType"])){
            $ajaxCmdResponseGenerator->addCodeTypeResponse($responseData["codeType"]);
        }else{
            parent::response($requestParams, $responseData, $ajaxCmdResponseGenerator);
        }

        if ($responseData["close"]) {
            $params = $responseData["close"];
            $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processCloseTab", $params);
        }else{
            $ajaxCmdResponseGenerator->addContenttoolTimerStop($responseData['structure']['id']);
        }
    }
}
