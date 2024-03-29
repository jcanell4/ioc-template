<?php
/**
 * SuppliesFormResponseHandler
 * @author Rafael
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', WikiGlobalConfig::tplIncDir());
require_once(DOKU_TPL_INCDIR . 'conf/cfgIdConstants.php');
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/PageResponseHandler.php');

class NewUserTeachersResponseHandler extends WikiIocResponseHandler {

    function __construct() {
        parent::__construct("new_user_teachers");
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $ajaxCmdResponseGenerator->addHtmlNewUserTeachersForm(
            $responseData[AjaxKeys::KEY_ID],
            $responseData[PageKeys::KEY_TITLE],
            $responseData[PageKeys::KEY_CONTENT]['list'],
            array(
                'urlBase' => "lib/exe/ioc_ajax.php?",
                'formId' => $responseData[PageKeys::KEY_CONTENT]['formId'],
                'query' => "call=${responseData[AjaxKeys::KEY_ID]}"
            )
        );


        $info = ($responseData['content']['info']) ? $responseData['content']['info'] : "new_user_teachers_loaded";
        $ajaxCmdResponseGenerator->addInfoDta(AjaxCmdResponseGenerator::generateInfo(
            RequestParameterKeys::KEY_INFO,
            WikiIocLangManager::getLang($info),
                
//======= marjose: codi conflictiu. He triat la versió que sembla que es més completa
//        $ajaxCmdResponseGenerator->addInfoDta(AjaxCmdResponseGenerator::generateInfo(
//            RequestParameterKeys::KEY_INFO,
//            WikiIocLangManager::getLang("new_user_teachers_loaded"),
//>>>>>>> master == end marjose ==
            $requestParams[AjaxKeys::KEY_ID]
        ));
    }

    protected function postResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $ajaxCmdResponseGenerator->addSetJsInfo($this->getJsInfo());
    }

}
