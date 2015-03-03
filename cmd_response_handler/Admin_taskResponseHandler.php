<?php
/**
 * Description of admin_task_response_handler
 *
 * @author Eduard Latorre
 */

if (!defined("DOKU_INC")) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(tpl_incdir().'cmd_response_handler/WikiIocResponseHandler.php');
require_once DOKU_PLUGIN.'ajaxcommand/JsonGenerator.php';

class Admin_taskResponseHandler extends WikiIocResponseHandler {
    function __construct() {
        parent::__construct(WikiIocResponseHandler::ADMIN_TASK);
    }
    protected function response($requestParams,
                                $responseData,
                                &$ajaxCmdResponseGenerator) {
        if($requestParams['page']){
            // afegeix la pestanya al panell central
            $ajaxCmdResponseGenerator->addAdminTask($responseData['id'],
                                               $responseData['ns'],
                                               $responseData['title'],
                                               $responseData['content']);

            // missatge a mostrar al panell inferior
            $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);

            switch($requestParams['page']) {
                case "acl":
                    // Obté els Selectors Css dels forms del pluguin ACL
                    $params = array();
                    $this->getAclSelectors($params);

                    $ajaxCmdResponseGenerator->addProcessDomFromFunction(
                        $responseData['id'],
                        true,
                        "ioc/dokuwiki/processAclTask",
                        array(
                            "urlBase" => "lib/plugins/ajaxcommand/ajax.php?call=admin_task",
                            "saveSelector" => $params["saveSelector"],
                            "updateSelector" => $params["updateSelector"]
                        )
                    );
                break;
                case "plugin":
                    // Obté els Selectors Css dels forms del plugin PLUGIN
                    $params = array();
                    $this->getPluginSelectors($params);

                    $ajaxCmdResponseGenerator->addProcessDomFromFunction(
                        $responseData['id'],
                        true,
                        "ioc/dokuwiki/processPluginTask",
                        array(
                           "urlBase" => "lib/plugins/ajaxcommand/ajax.php?call=admin_task",
                           "commonSelector" => $params["commonSelector"],
                           "pluginsSelector" => $params["pluginsSelector"]
                        )
                    );
            break;
          }
      }
    }
}
