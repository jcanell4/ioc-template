<?php
/**
 * Description of LoginResponseHandler
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined("DOKU_INC")) die();
require_once(DOKU_TPL_INCDIR . 'conf/cfgIdConstants.php');
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/WikiIocResponseHandler.php');

class LoginResponseHandler extends WikiIocResponseHandler {

    function __construct() {
        parent::__construct(ResponseHandlerKeys::LOGIN);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $this->login($requestParams, $responseData, $ajaxCmdResponseGenerator);
    }

    private function login ($requestParams, $responseData, &$ajaxCmdResponseGenerator){

        $ajaxCmdResponseGenerator->addLoginInfo($responseData[ProjectKeys::KEY_LOGIN_REQUEST],
                                                $responseData[ProjectKeys::KEY_LOGIN_RESULT],
                                                $responseData[ProjectKeys::KEY_USER_ID],
                                                $responseData[ProjectKeys::KEY_MOODLE_TOKEN]);

        $ajaxCmdResponseGenerator->addSectokData(getSecurityToken());

        if ($responseData[ProjectKeys::KEY_LOGIN_RESULT]){
            if ($responseData[ProjectKeys::KEY_MOODLE_TOKEN]) {
                $ajaxCmdResponseGenerator->addProcessFunction(TRUE,
                                                              "ioc/dokuwiki/processMoodleTimeout",
                                                              ['urlBase' => "lib/exe/ioc_ajax.php?call=refresh_moodle_session",
                                                               ProjectKeys::KEY_MOODLE_TOKEN => $responseData[ProjectKeys::KEY_MOODLE_TOKEN]]);
            }

            $ajaxCmdResponseGenerator->addReloadWidgetContent(cfgIdConstants::TB_INDEX);

            $ajaxCmdResponseGenerator->addChangeWidgetProperty(cfgIdConstants::USER_BUTTON,
                                                               "label",
                                                               $responseData[ProjectKeys::KEY_USER_ID]);
            $modelManager = $this->getModelManager();

            if ($this->getPermission()->isAdminOrManager()){
                $action = $modelManager->getActionInstance("AdminTaskListAction");
                $dades = $action->get();
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

            $action = $modelManager->getActionInstance("ShortcutsTaskListAction", $responseData[ProjectKeys::KEY_USER_ID]);
            $dades = $action->get(['id' => $action->getNsShortcut()]);

            if ($dades["content"]){
                $dades['selected'] = TRUE;
                IocCommon::addResponseTab($dades, $ajaxCmdResponseGenerator);
            }
            $title = $_SERVER['REMOTE_USER'];
            $sig = toolbar_signature();
        }else{
            $ajaxCmdResponseGenerator->addReloadWidgetContent(cfgIdConstants::TB_INDEX);
            $title = '';
            $sig = '';
        }

        $ajaxCmdResponseGenerator->addTitle($title);
        $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/setSignature", $sig);

        $info = array('timestamp' => date('d-m-Y H:i:s'));

        if ($responseData[ProjectKeys::KEY_LOGIN_REQUEST] && !$responseData[ProjectKeys::KEY_LOGIN_RESULT]) {
            $info['type'] = 'error';
            $info['message'] = 'Error d\'autenticació';

        } else if (!$responseData[ProjectKeys::KEY_LOGIN_REQUEST] && !$responseData[ProjectKeys::KEY_LOGIN_RESULT]) {
            $info['type'] = 'info';
            $info['message'] = 'Usuari desconnectat';

            $ajaxCmdResponseGenerator->addRemoveTab(cfgIdConstants::ZONA_NAVEGACIO, cfgIdConstants::TB_ADMIN);
            $ajaxCmdResponseGenerator->addRemoveTab(cfgIdConstants::ZONA_NAVEGACIO, cfgIdConstants::TB_SHORTCUTS);
        } else  {
            $info['type'] = 'success';
            $info['message'] = 'Usuari connectat';
        }

        $info['login'] = $requestParams[ProjectKeys::KEY_DO];
        $info = $ajaxCmdResponseGenerator->generateInfo($info['type'], $info['message'], $info['login'], 20);
        $responseData[ProjectKeys::KEY_INFO] = $ajaxCmdResponseGenerator->addInfoToInfo($responseData[ProjectKeys::KEY_INFO], $info);
        $ajaxCmdResponseGenerator->addInfoDta($responseData[ProjectKeys::KEY_INFO]);

        for ($i=0; $i<count($responseData['notifications']); $i++) {
            $action = $responseData['notifications'][$i]['action'];
            $params = $responseData['notifications'][$i]['params'];
            $ajaxCmdResponseGenerator->addNotification($action, $params);
        }
    }

    protected function postResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        parent::postResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);

        if (!$responseData[ProjectKeys::KEY_LOGIN_RESULT]){
            $ajaxCmdResponseGenerator->addRemoveAllContentTab();
        }
    }
}
