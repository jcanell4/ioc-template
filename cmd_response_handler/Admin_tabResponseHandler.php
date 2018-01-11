<?php
/**
 * Admin_tabResponseHandler add a admin tab
 * @author Eduardo latorre Jarque <eduardo.latorre@gmail.com>
 */
if (!defined('DOKU_INC')) die();
require_once(DOKU_INC . 'lib/plugins/ajaxcommand/defkeys/ResponseHandlerKeys.php');
require_once(DOKU_TPL_INCDIR . 'conf/cfgIdConstants.php');
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/WikiIocResponseHandler.php');

class Admin_tabResponseHandler extends WikiIocResponseHandler {

    function __construct() {
        parent::__construct(ResponseHandlerKeys::ADMIN_TAB);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $urlBase = "lib/exe/ioc_ajax.php?call=admin_task";

        $params = array(
            "id" => cfgIdConstants::TB_ADMIN,
            "title" =>  $responseData['title'],
            "standbyId" => cfgIdConstants::BODY_CONTENT,
            "urlBase" => $urlBase,
            "content" => $responseData["content"],
        );
        $ajaxCmdResponseGenerator->addAddTab(cfgIdConstants::ZONA_NAVEGACIO, $params);
    }
}
