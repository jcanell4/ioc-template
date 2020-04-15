<?php
/**
 * Description of page_response_handler
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined("DOKU_INC")) die();
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/PageResponseHandler.php');

class CancelResponseHandler extends PageResponseHandler {

    function __construct() {
        parent::__construct(ResponseHandlerKeys::KEY_CANCEL);
    }
    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {

        if (isset($responseData[ResponseHandlerKeys::KEY_CODETYPE])) {
            $ajaxCmdResponseGenerator->addCodeTypeResponse($responseData[ResponseHandlerKeys::KEY_CODETYPE]);
        }
        elseif ($responseData[ResponseHandlerKeys::KEY_CLOSE]) {
            $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processCloseTab", $responseData['close']);
        }
        else {
            parent::response($requestParams, $responseData, $ajaxCmdResponseGenerator);
            $ajaxCmdResponseGenerator->addContenttoolTimerStop($responseData['structure']['id']);
        }
    }

}
