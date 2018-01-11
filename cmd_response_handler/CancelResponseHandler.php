<?php
/**
 * Description of page_response_handler
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined("DOKU_INC")) die();
require_once(DOKU_INC . 'lib/plugins/ajaxcommand/defkeys/ResponseHandlerKeys.php');
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/PageResponseHandler.php');

class CancelResponseHandler extends PageResponseHandler {

    function __construct() {
        parent::__construct(ResponseHandlerKeys::CANCEL);
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
