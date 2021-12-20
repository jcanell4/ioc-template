<?php
/**
 * SelectProjectsResponseHandler
 * @author Rafael
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', WikiGlobalConfig::tplIncDir());
require_once(DOKU_TPL_INCDIR . 'conf/cfgIdConstants.php');
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/PageResponseHandler.php');

class SelectProjectsResponseHandler extends WikiIocResponseHandler {

    function __construct() {
        parent::__construct("select_projects");
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        if (isset($requestParams['projectType'])) {
//            $ajaxCmdResponseGenerator->addAddItemTree(cfgIdConstants::TB_INDEX, $requestParams[AjaxKeys::KEY_ID]);

            $ajaxCmdResponseGenerator->addHtmlForm(
                    $responseData[AjaxKeys::KEY_ID],
                    $responseData[PageKeys::KEY_TITLE],
                    $responseData[PageKeys::KEY_CONTENT],
                    array(
                        'urlBase' => "lib/exe/ioc_ajax.php?call=${requestParams[AjaxKeys::KEY_ID]}",
                        'id' => $responseData[AjaxKeys::KEY_ID],
                    ),
                    array(
                        'callAtt' => "call",
                        'urlBase' => "lib/exe/ioc_ajax.php",
                    )
           );

            $ajaxCmdResponseGenerator->addInfoDta(AjaxCmdResponseGenerator::generateInfo(
                    RequestParameterKeys::KEY_INFO,
                    WikiIocLangManager::getLang("list_projects_showed"),
                    $requestParams[AjaxKeys::KEY_ID]
            ));
        }
        else{
            $ajaxCmdResponseGenerator->addHtmlForm(
                    $responseData[AjaxKeys::KEY_ID],
                    $responseData[PageKeys::KEY_TITLE],
                    $responseData[PageKeys::KEY_CONTENT]['list'],
                    array(
                        'urlBase' => "lib/exe/ioc_ajax.php?call=${requestParams[AjaxKeys::KEY_ID]}",
                        'formId' => $responseData[PageKeys::KEY_CONTENT]['formId'],
                    ),
                    array(
                        'callAtt' => "call",
                        'urlBase' => "lib/exe/ioc_ajax.php",
                    )
            );

            $ajaxCmdResponseGenerator->addInfoDta(AjaxCmdResponseGenerator::generateInfo(
                    RequestParameterKeys::KEY_INFO,
                    WikiIocLangManager::getLang("select_projects_loaded"),
                    $requestParams[AjaxKeys::KEY_ID]
            ));
        }
    }

    protected function postResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $ajaxCmdResponseGenerator->addSetJsInfo($this->getJsInfo());
    }

}
