<?php
/**
 * Description of admin_task_response_handler
 * @author Eduardo Latorre Jarque <eduardo.latorre@gmail.com>
 */
if (!defined("DOKU_INC")) die();
require_once(DOKU_INC . 'lib/plugins/ajaxcommand/defkeys/ResponseHandlerKeys.php');
require_once(DOKU_TPL_INCDIR.'cmd_response_handler/WikiIocResponseHandler.php');

class Admin_taskResponseHandler extends WikiIocResponseHandler {
    
    function __construct() {
        parent::__construct(ResponseHandlerKeys::ADMIN_TASK);
    }
    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $urlBase = "lib/exe/ioc_ajax.php?call=admin_task";

        if($responseData["needRefresh"]){
            $params = array(
                "urlBase" => $urlBase,
                "method" => "post",
                "query" => "page=".$requestParams["page"],
            );
            $ajaxCmdResponseGenerator->addProcessFunction(TRUE, "ioc/dokuwiki/recallCommand", $params);
        }else if($requestParams['page']){
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
                            "urlBase" => $urlBase,
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
                           "urlBase" => $urlBase,
                           "commonSelector" => $params["commonSelector"],
                           "pluginsSelector" => $params["pluginsSelector"]
                        )
                    );
                    break;
                case "config":
                    // Obté els Selectors Css dels forms del plugin PLUGIN
                    $params = array();
                    $this->getConfigSelectors($params);

                    $ajaxCmdResponseGenerator->addProcessDomFromFunction(
                        $responseData['id'],
                        true,
                        "ioc/dokuwiki/processConfigTask",
                        array(
                           "urlBase" => $urlBase,
                           "configSelector" => $params["configSelector"]
                        )
                    );
                    break;
                case "usermanager":
                    // Obté els Selectors Css dels forms del plugin USERMANAGER
                    $params = array();
                    $this->getUserManagerSelectors($params);

                    $ajaxCmdResponseGenerator->addProcessDomFromFunction(
                        $responseData['id'],
                        true,
                        "ioc/dokuwiki/processUserManagerTask",
                        array(
                           "urlBase" => $urlBase,
                           "formsSelector" => $params["formsSelector"],
                           "exportCsvName" => $params["exportCsvName"]
                        )
                    );
                    break;
                case "revert":
                    // Obté els Selectors Css dels forms del plugin REVERT
                    $params = array();
                    $this->getRevertSelectors($params);

                    $ajaxCmdResponseGenerator->addProcessDomFromFunction(
                        $responseData['id'],
                        true,
                        "ioc/dokuwiki/processRevertTask",
                        array(
                           "urlBase" => "lib/exe/ioc_ajax.php?",
                           "revertSelector" => $params["revertSelector"]
                        )
                    );
                    break;
                case "latex":
                    // Obté els Selectors Css dels forms del plugin LATEX
                    $params = array();
                    $this->getLatexSelectors($params);

                    $ajaxCmdResponseGenerator->addProcessDomFromFunction(
                        $responseData['id'],
                        true,
                        "ioc/dokuwiki/processLatexTask",
                        array(
                           "urlBase" => $urlBase,
                           "latexSelector" => $params["latexSelector"],
                           "latexpurge" => $params["latexpurge"],
                           "dotest" => $params["dotest"]
                        )
                    );
                    break;
          }
      }
    }
}
