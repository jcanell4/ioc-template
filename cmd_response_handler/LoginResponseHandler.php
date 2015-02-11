<?php

/**
 * Description of WikiIocResponseHandler
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */

if (!defined("DOKU_INC")) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(tpl_incdir().'cmd_response_handler/WikiIocResponseHandler.php');
require_once DOKU_PLUGIN.'ajaxcommand/JsonGenerator.php';
require_once tpl_incdir()."conf/mainCfg.php";

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
		$cfg = WikiIocCfg::Instance();

        if($responseData["loginResult"]){
            $ajaxCmdResponseGenerator->addSetJsInfo($this->getJsInfo());
            $ajaxCmdResponseGenerator->addReloadWidgetContent($cfg->getArrIds("zN_index_id"));
            $ajaxCmdResponseGenerator->addChangeWidgetProperty(
                                                    $cfg->getArrIds("userButton"),
                                                    "label",
                                                    $responseData["userId"]);

            $dades = $this->getModelWrapper()->getAdminTaskList();
            $urlBase = "lib/plugins/ajaxcommand/ajax.php?call=admin_task";

            $ajaxCmdResponseGenerator->addAdminTab($cfg->getArrIds("zonaNavegacio"),
                                                   $cfg->getArrIds("zN_admin_id"),
                                                   $dades['title'],
                                                   $dades['content'],
                                                   $urlBase);
            $title = $_SERVER['REMOTE_USER'];
            $sig = toolbar_signature();
        }else{
            $ajaxCmdResponseGenerator->addReloadWidgetContent($cfg->getArrIds("zN_index_id"));
            $ajaxCmdResponseGenerator->addRemoveAllContentTab();
            $ajaxCmdResponseGenerator->addRemoveAllWidgetChildren($cfg->getArrIds("zonaMetaInfo"));
            $title = '';
            $sig = '';
        }
        $ajaxCmdResponseGenerator->addTitle($title);
        $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/setSignature", $sig);


        global $lang;

        $info = array('id' => '', 'duration' => -1, 'timestamp' => date('d-m-Y H:i:s'));

        if ($responseData['loginRequest'] && !$responseData['loginResult']) {
            $info['type'] = 'error';
            //$info['message'] = $lang['auth_error'];
            $info['message'] = 'Error d\'autenticació';


        } else if (!$responseData['loginRequest'] && !$responseData['loginResult']) {
            $info['type'] = 'info';
            //$info['message'] = $lang['user_logout'];
            $info['message'] = 'Usuari desconnectat';


        } else  {
            $info['type']= 'success';
            // $info['message'] = $lang['user_login'];
            $info['message'] = 'Usuari connectat';
        }

        $ajaxCmdResponseGenerator->addInfoDta($info);
    }
}
