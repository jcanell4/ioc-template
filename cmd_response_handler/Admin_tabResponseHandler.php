<?php
/**
 * Description of admin_tab_response_handler
 *
 * @author Eduard Latorre
 */

if (!defined("DOKU_INC")) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(tpl_incdir().'cmd_response_handler/PageResponseHandler.php');
require_once DOKU_PLUGIN.'ajaxcommand/JsonGenerator.php';

class Admin_tabResponseHandler extends PageResponseHandler {
    function __construct() {
        parent::__construct(WikiIocResponseHandler::CANCEL);
    }
    protected function response($requestParams,
                                $responseData,
                                &$ajaxCmdResponseGenerator) {
        //TODO La informació ha de venir de DokuModelAdapter. Cal fer el canvi
        $responseData["info"] = "ADMIN TAB ";
        error_log("Admin_tabResponseHandler\n", 3, "/var/www/php.log");

        parent::response($requestParams,
                        $responseData,
                        $ajaxCmdResponseGenerator);
      //$ajaxCmdResponseGenerator->addProcessFunction(true,
      //                                      "ioc/dokuwiki/processCancellation");
    }
}

?>
