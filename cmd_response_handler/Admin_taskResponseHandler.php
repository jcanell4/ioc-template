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

        if ($responseData["needRefresh"]){
            $params = array(
                "urlBase" => $urlBase,
                "method" => "post",
                "query" => "page=".$requestParams["page"],
                "delay" => WikiGlobalConfig::getConf('delay_recall')*1000
            );
            $ajaxCmdResponseGenerator->addProcessFunction(TRUE, "ioc/dokuwiki/recallCommand", $params);
        }
        else if($requestParams['page']){
            // afegeix la pestanya al panell central
            $ajaxCmdResponseGenerator->addAdminTask($responseData['id'],
                                                    $responseData['ns'],
                                                    $responseData['title'],
                                                    $responseData['content']);

            // missatge a mostrar al panell inferior
            $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);

            switch($requestParams['page']) {
                case "acl":
                    $params = array('urlBase' => $urlBase);
                    $this->getAclSelectors($params);
                    $task = "ioc/dokuwiki/processAclTask";
                    break;
                case "plugin":
                    $params = array('urlBase' => $urlBase);
                    $this->getPluginSelectors($params);
                    $task = "ioc/dokuwiki/processPluginTask";
                    break;
                case "config":
                    $params = array('urlBase' => $urlBase);
                    $this->getConfigSelectors($params);
                    $task = "ioc/dokuwiki/processConfigTask";
                    break;
                case "usermanager":
                    $params = array('urlBase' => $urlBase);
                    $this->getUserManagerSelectors($params);
                    $task = "ioc/dokuwiki/processUserManagerTask";
                    break;
                case "revert":
                    $params = array('urlBase' => "lib/exe/ioc_ajax.php?");
                    $this->getRevertSelectors($params);
                    $task = "ioc/dokuwiki/processRevertTask";
                    break;
                case "latex":
                    $params = array('urlBase' => $urlBase);
                    $this->getLatexSelectors($params);
                    $task = "ioc/dokuwiki/processLatexTask";
                    break;
            }

            $ajaxCmdResponseGenerator->addProcessDomFromFunction($responseData['id'], true, $task, $params);

      }
    }
}
