<?php
/**
 * SuppliesFormResponseHandler
 * @author Rafael
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', WikiGlobalConfig::tplIncDir());
require_once(DOKU_TPL_INCDIR . 'conf/cfgIdConstants.php');
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/PageResponseHandler.php');

class SuppliesFormResponseHandler extends WikiIocResponseHandler {

    function __construct() {
        parent::__construct("supplies_form");
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $ajaxCmdResponseGenerator->addHtmlForm(
                $responseData[AjaxKeys::KEY_ID],
                $responseData[PageKeys::KEY_TITLE],
                $responseData[PageKeys::KEY_CONTENT]['list'],
                array(
                    'urlBase' => "lib/exe/ioc_ajax.php?call=${responseData[AjaxKeys::KEY_ACTION_COMMAND]}",
                    'formId' => $responseData[PageKeys::KEY_CONTENT]['formId'],
                ),
                array(
                    'callAtt' => "call",
                    'urlBase' => "lib/exe/ioc_ajax.php",
                    'do' => $responseData[AjaxKeys::KEY_ACTION_COMMAND]
                )
        );

        $ajaxCmdResponseGenerator->addInfoDta(AjaxCmdResponseGenerator::generateInfo(
                RequestParameterKeys::KEY_INFO,
                WikiIocLangManager::getLang("select_projects_loaded"),
                $requestParams[AjaxKeys::KEY_ID]
        ));
    }

    protected function postResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $ajaxCmdResponseGenerator->addSetJsInfo($this->getJsInfo());
    }

}
