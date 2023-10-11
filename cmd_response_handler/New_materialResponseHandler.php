<?php
/**
 * New_materialResponseHandler
 */
if (!defined("DOKU_INC")) die();
require_once(__DIR__ . "/New_pageResponseHandler.php");

class New_materialResponseHandler extends New_pageResponseHandler {

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        parent::response($requestParams, $responseData, $ajaxCmdResponseGenerator);
        if (isset($responseData['alert'])) {
            $ajaxCmdResponseGenerator->addAlert($responseData['alert']);
        }
    }
}
