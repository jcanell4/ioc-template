<?php
/**
 * Description of admin_task_response_handler
 *
 * @author Eduard Latorre
 */

if (!defined("DOKU_INC")) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(tpl_incdir().'cmd_response_handler/WikiIocResponseHandler.php');
//require_once(tpl_incdir().'cmd_response_handler/PageResponseHandler.php');
require_once DOKU_PLUGIN.'ajaxcommand/JsonGenerator.php';

class Admin_taskResponseHandler extends WikiIocResponseHandler {
    function __construct() {
        parent::__construct(WikiIocResponseHandler::ADMIN_TASK);
    }
    protected function response($requestParams,
                                $responseData,
                                &$ajaxCmdResponseGenerator) {
        //TODO La informació ha de venir de DokuModelAdapter. Cal fer el canvi
        $responseData["info"] = "ADMIN TASK ";
        //error_log("AAdmin_taskResponseHandler\n", 3, "/var/www/php.log");
        //error_log(print_r($requestParams,true), 3, "/var/www/php.log");
        if($requestParams['page']){
          $ajaxCmdResponseGenerator->addHtmlDoc($responseData['id'],
                                                $responseData['ns'],
                                                $responseData['title'],
                                                $responseData['content']);
        }
    }
}

?>
