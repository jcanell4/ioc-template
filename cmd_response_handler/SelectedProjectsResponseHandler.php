<?php
/**
 * SelectedProjectsResponseHandler
 * @author Rafael
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', WikiGlobalConfig::tplIncDir());
require_once(DOKU_TPL_INCDIR . 'conf/cfgIdConstants.php');
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/PageResponseHandler.php');

class SelectedProjectsResponseHandler extends WikiIocResponseHandler {

    function __construct() {
        parent::__construct("selected_projects");
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $ajaxCmdResponseGenerator->addHtmlRsponseForm(
                $responseData[AjaxKeys::KEY_ID],
                $responseData[PageKeys::KEY_TITLE],
                $responseData[PageKeys::KEY_CONTENT],
                array(AjaxKeys::KEY_ID => $requestParams[AjaxKeys::KEY_ACTION_COMMAND],
                      AjaxKeys::PROJECT_TYPE => $requestParams[AjaxKeys::PROJECT_TYPE],
                      'consulta' => $requestParams['consulta'])
            );

        $ajaxCmdResponseGenerator->addInfoDta(AjaxCmdResponseGenerator::generateInfo(
                RequestParameterKeys::KEY_INFO,
                WikiIocLangManager::getLang("list_projects_showed"),
                $requestParams[AjaxKeys::KEY_ID]
            ));
    }

    protected function postResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $ajaxCmdResponseGenerator->addSetJsInfo($this->getJsInfo());
    }

}
