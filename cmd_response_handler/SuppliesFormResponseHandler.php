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
        if ($responseData[PageKeys::KEY_TYPE] == "html_supplies_form") {
            $ajaxCmdResponseGenerator->addHtmlSuppliesForm(
                $responseData[AjaxKeys::KEY_ID],
                $responseData[PageKeys::KEY_TITLE],
                $responseData[PageKeys::KEY_CONTENT]['list'],
                array(
                    'urlBase' => "lib/exe/ioc_ajax.php?",
                    'formId' => $responseData[PageKeys::KEY_CONTENT]['formId'],
                    'query' => "call=${responseData[AjaxKeys::KEY_ACTION_COMMAND]}"
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
        elseif ($responseData[PageKeys::KEY_TYPE] == "html_response_form") {
//            $responseData[ProjectKeys::KEY_ID] = $responseData[AjaxKeys::KEY_ACTION_COMMAND];
            $responseData[PageKeys::KEY_CONTENT]['list'] = '<h1 class="sectionedit1" id="id_'.$this->params[AjaxKeys::KEY_ACTION_COMMAND].'">Llistat de projectes seleccionats</h1>';
//            $responseData[ProjectKeys::KEY_EXTRA_STATE] = [ProjectKeys::KEY_EXTRA_STATE_ID => AjaxKeys::KEY_ACTION_COMMAND,
//                                                           ProjectKeys::KEY_EXTRA_STATE_VALUE => $responseData[PageKeys::KEY_CONTENT]['grups']];
            $ajaxCmdResponseGenerator->addHtmlRsponseForm(
                $responseData[AjaxKeys::KEY_ACTION_COMMAND],
                $responseData[PageKeys::KEY_TITLE],
                $responseData[PageKeys::KEY_CONTENT]['list'],
                array(AjaxKeys::KEY_ID => $responseData[AjaxKeys::KEY_ACTION_COMMAND],
                      'consulta' => $responseData[PageKeys::KEY_CONTENT]['grups'])
            );

            $ajaxCmdResponseGenerator->addInfoDta(AjaxCmdResponseGenerator::generateInfo(
                RequestParameterKeys::KEY_INFO,
                WikiIocLangManager::getLang("list_projects_showed"),
                $responseData[AjaxKeys::KEY_ACTION_COMMAND]
            ));
            
        }
    }

    protected function postResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $ajaxCmdResponseGenerator->addSetJsInfo($this->getJsInfo());
    }

}
