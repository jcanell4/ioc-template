<?php
/**
 * Description of LoginResponseHandler
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC.'lib/plugins/');
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', WikiGlobalConfig::tplIncDir());

require_once(DOKU_PLUGIN . 'ajaxcommand/defkeys/ResponseHandlerKeys.php');
require_once(DOKU_TPL_INCDIR . 'conf/cfgIdConstants.php');
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/WikiIocResponseHandler.php');

class LoginResponseHandler extends WikiIocResponseHandler {

    const NEED_PERSISTENCE_ENGINE = TRUE;

    function __construct() {
        parent::__construct(ResponseHandlerKeys::LOGIN);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $this->login($requestParams, $responseData, $ajaxCmdResponseGenerator);
    }

    private function login ($requestParams, $responseData, &$ajaxCmdResponseGenerator){

        $ajaxCmdResponseGenerator->addLoginInfo($responseData["loginRequest"],
                                                $responseData['loginResult'],
                                                $responseData['userId']);

        if ($responseData['user_state']) {
            $ajaxCmdResponseGenerator->addUserState($responseData['user_state']);
        }

        $ajaxCmdResponseGenerator->addSectokData(getSecurityToken());

        if($responseData["loginResult"]){
            $ajaxCmdResponseGenerator->addReloadWidgetContent(cfgIdConstants::TB_INDEX);
            $ajaxCmdResponseGenerator->addChangeWidgetProperty(
                                                    cfgIdConstants::USER_BUTTON,
                                                    "label",
                                                    $responseData["userId"]);
            $modelManager = $this->getModelManager();

            if ($this->getPermission()->isAdminOrManager()){
//                $dades = $this->getModelAdapter()->getAdminTaskList();
                $action = $modelManager->getActionInstance("ShortcutsTaskListAction", self::NEED_PERSISTENCE_ENGINE);
                $dades = $action->get($params);
                $urlBase = "lib/exe/ioc_ajax.php?call=admin_task";

                $params = array(
                    "id" => cfgIdConstants::TB_ADMIN,
                    "title" =>  $dades['title'],
                    "standbyId" => cfgIdConstants::BODY_CONTENT,
                    "urlBase" => $urlBase,
                    "content" => $dades["content"],
                );
                $ajaxCmdResponseGenerator->addAddTab(cfgIdConstants::ZONA_NAVEGACIO, $params);
            }

//            $dades = $this->getModelAdapter()->getShortcutsTaskList($responseData['userId']);
            $action = $modelManager->getActionInstance("ShortcutsTaskListAction", self::NEED_PERSISTENCE_ENGINE);
            $dades = $action->get($params);
            if ($dades["content"]){
                $containerClass = "ioc/gui/ContentTabNsTreeListFromPage";
                $urlBase = "lib/exe/ioc_ajax.php?call=page";
                $urlTree = "lib/exe/ioc_ajaxrest.php/ns_tree_rest/";

                $contentParams = array(
                    "id" => cfgIdConstants::TB_SHORTCUTS,
                    "title" =>  $dades['title'],
                    "standbyId" => cfgIdConstants::BODY_CONTENT,
                    "urlBase" => $urlBase,
                    "data" => $dades["content"],
                    "treeDataSource" => $urlTree,
                    'typeDictionary' => array (
                                            'p' => array (
                                                      'urlBase' => 'lib/exe/ioc_ajax.php?call=project',
                                                      'params' => array (0 => 'projectType')
                                                   ),
                                        ),
                );
                $ajaxCmdResponseGenerator->addAddTab(cfgIdConstants::ZONA_NAVEGACIO,
                                                $contentParams,
                                                ResponseHandlerKeys::FIRST_POSITION,
                                                TRUE,
                                                $containerClass);
            }
            $title = $_SERVER['REMOTE_USER'];
            $sig = toolbar_signature();
        }
        else{
            $ajaxCmdResponseGenerator->addReloadWidgetContent(cfgIdConstants::TB_INDEX);
            $ajaxCmdResponseGenerator->addRemoveAllContentTab();
            $title = '';
            $sig = '';
        }

        $ajaxCmdResponseGenerator->addTitle($title);
        $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/setSignature", $sig);

        $info = array('timestamp' => date('d-m-Y H:i:s'));

        if ($responseData['loginRequest'] && !$responseData['loginResult']) {
            $info['type'] = 'error';
            $info['message'] = 'Error d\'autenticació';

        } else if (!$responseData['loginRequest'] && !$responseData['loginResult']) {
            $info['type'] = 'info';
            $info['message'] = 'Usuari desconnectat';

            $ajaxCmdResponseGenerator->addRemoveTab(cfgIdConstants::ZONA_NAVEGACIO,
                cfgIdConstants::TB_ADMIN);

            $ajaxCmdResponseGenerator->addRemoveTab(cfgIdConstants::ZONA_NAVEGACIO,
                cfgIdConstants::TB_SHORTCUTS);
        } else  {
            $info['type']= 'success';
            $info['message'] = 'Usuari connectat';
        }

        for ($i=0; $i<count($responseData['notifications']); $i++) {
            $action = $responseData['notifications'][$i]['action'];
            $params = $responseData['notifications'][$i]['params'];
            $ajaxCmdResponseGenerator->addNotification($action, $params);
        }

    }
}
