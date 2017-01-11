<?php

/**
 * AdmintabResponseHandler add a admin tab  
 *
 * @author Eduardo latorre Jarque <eduardo.latorre@gmail.com>
 */

if (!defined("DOKU_INC")) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC.'lib/plugins/');
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', tpl_incdir());
require_once(DOKU_TPL_INCDIR . 'conf/cfgIdConstants.php');
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/WikiIocResponseHandler.php');
require_once(DOKU_PLUGIN . 'ajaxcommand/JsonGenerator.php');

class Admin_tabResponseHandler extends WikiIocResponseHandler {

    function __construct() {
        parent::__construct(WikiIocResponseHandler::ADMIN_TAB);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $urlBase = "lib/plugins/ajaxcommand/ajax.php?call=admin_task";

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
