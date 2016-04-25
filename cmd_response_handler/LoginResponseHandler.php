<?php

/**
 * Description of WikiIocResponseHandler
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */

if (!defined("DOKU_INC")) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC.'lib/plugins/');
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', tpl_incdir());
require_once(DOKU_TPL_INCDIR . 'conf/cfgIdConstants.php');
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/WikiIocResponseHandler.php');
require_once(DOKU_PLUGIN . 'ajaxcommand/JsonGenerator.php');

class LoginResponseHandler extends WikiIocResponseHandler {

    function __construct() {
        parent::__construct(WikiIocResponseHandler::LOGIN);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $this->login($requestParams, $responseData, $ajaxCmdResponseGenerator);
    }

    private function login ($requestParams, $responseData, &$ajaxCmdResponseGenerator){

        $ajaxCmdResponseGenerator->addLoginInfo($responseData["loginRequest"],
                                                $responseData['loginResult'],
                                                $responseData['userId']);
        $ajaxCmdResponseGenerator->addSectokData(getSecurityToken());

        if($responseData["loginResult"]){
            $ajaxCmdResponseGenerator->addReloadWidgetContent(cfgIdConstants::TB_INDEX);
            $ajaxCmdResponseGenerator->addChangeWidgetProperty(
                                                    cfgIdConstants::USER_BUTTON,
                                                    "label",
                                                    $responseData["userId"]);

            if($this->getPermission()->isAdminOrManager()){
                $dades = $this->getModelWrapper()->getAdminTaskList();
                $urlBase = "lib/plugins/ajaxcommand/ajax.php?call=admin_task";

                $ajaxCmdResponseGenerator->addAdminTab(cfgIdConstants::ZONA_NAVEGACIO,
                                                   cfgIdConstants::TB_ADMIN,
                                                   $dades['title'],
                                                   $dades['content'],
                                                   $urlBase);
            }

            $title = $_SERVER['REMOTE_USER'];
            $sig = toolbar_signature();
        }else{
            $ajaxCmdResponseGenerator->addReloadWidgetContent(cfgIdConstants::TB_INDEX);
            $ajaxCmdResponseGenerator->addRemoveAllContentTab();
            //$ajaxCmdResponseGenerator->addRemoveAllWidgetChildren(cfgIdConstants::ZONA_METAINFO);
            $title = '';
            $sig = '';
        }
        $ajaxCmdResponseGenerator->addTitle($title);
        $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/setSignature", $sig);


        global $lang;

        //$info = array('id' => null, 'duration' => -1, 'timestamp' => date('d-m-Y H:i:s'));
        $info = array('timestamp' => date('d-m-Y H:i:s'));

        if ($responseData['loginRequest'] && !$responseData['loginResult']) {
            $info['type'] = 'error';
            //$info['message'] = $lang['auth_error'];
            $info['message'] = 'Error d\'autenticació';


        } else if (!$responseData['loginRequest'] && !$responseData['loginResult']) {
            $info['type'] = 'info';
            //$info['message'] = $lang['user_logout'];             
            $info['message'] = 'Usuari desconnectat';
             $ajaxCmdResponseGenerator->addRemoveAdminTab(cfgIdConstants::ZONA_NAVEGACIO,
                                                   cfgIdConstants::TB_ADMIN,
                                                   $urlBase);
        } else  {
            $info['type']= 'success';
            // $info['message'] = $lang['user_login'];
            $info['message'] = 'Usuari connectat';
        }

        $ajaxCmdResponseGenerator->addInfoDta($info);
    }
}
