<?php
/**
 * SelectProjectsResponseHandler
 * @author Rafael
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', WikiGlobalConfig::tplIncDir());
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/WikiIocResponseHandler.php');

class SelectProjectsResponseHandler extends WikiIocResponseHandler {

    function __construct() {
        parent::__construct("select_projects");
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $ajaxCmdResponseGenerator->addRecents(
                $responseData['id'],
                $responseData['title'],
                $responseData['content']['list'],
                array(
                    'urlBase' => "lib/exe/ioc_ajax.php?call=select_projects",
                    'formId' => $responseData['content']['formId'],
                ),
                array(
                    'callAtt' => "call",
                    'urlBase' => "lib/exe/ioc_ajax.php",
                )
        );

        $ajaxCmdResponseGenerator->addInfoDta(AjaxCmdResponseGenerator::generateInfo(
                'info',
                WikiIocLangManager::getLang("select_projects_loaded"),
                'select_projects'
        ));
    }

}
