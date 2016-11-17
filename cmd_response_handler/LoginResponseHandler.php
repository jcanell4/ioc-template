<?php

/**
 * Description of WikiIocResponseHandler
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */

if (!defined("DOKU_INC")) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC.'lib/plugins/');
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', tpl_incdir());
if(!defined('DOKU_COMMAND')) define('DOKU_COMMAND', DOKU_PLUGIN . "ajaxcommand/");

require_once(DOKU_TPL_INCDIR . 'conf/cfgIdConstants.php');
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/WikiIocResponseHandler.php');
require_once(DOKU_PLUGIN . 'ajaxcommand/JsonGenerator.php');
require_once(DOKU_COMMAND . 'requestparams/ResponseParameterKeys.php');

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

//                $ajaxCmdResponseGenerator->addAdminTab(cfgIdConstants::ZONA_NAVEGACIO,
//                                                   cfgIdConstants::TB_ADMIN,
//                                                   $dades['title'],
//                                                   $dades['content'],
//                                                   $urlBase);

                $ajaxCmdResponseGenerator->addAddTab(cfgIdConstants::ZONA_NAVEGACIO,
                    cfgIdConstants::TB_ADMIN,
                    $dades['title'],
                    $dades['content'],
                    $urlBase);
            }

            // TODO|ALERTA[Xavi] Dades de prova, s'han de sustituir les dades i la URL per la pàgina de dreceres
            $dades = $this->getModelWrapper()->getShortcutsTaskList($responseData['userId']);
//            $dades = $this->getModelWrapper()->getShortcutsTaskList();
            $urlBase = "lib/plugins/ajaxcommand/ajax.php?call=page";

            $ajaxCmdResponseGenerator->addAddTab(cfgIdConstants::ZONA_NAVEGACIO,
                cfgIdConstants::TB_SHORTCUTS,
                $dades['title'],
                $dades['content'],
                $urlBase,
                ResponseParameterKeys::FIRST_POSITION);

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
//             $ajaxCmdResponseGenerator->addRemoveAdminTab(cfgIdConstants::ZONA_NAVEGACIO,
//                                                   cfgIdConstants::TB_ADMIN);

            $ajaxCmdResponseGenerator->addRemoveTab(cfgIdConstants::ZONA_NAVEGACIO,
                cfgIdConstants::TB_ADMIN);

//            $urlBase = "lib/plugins/ajaxcommand/ajax.php?call=shortcuts_task";
            $ajaxCmdResponseGenerator->addRemoveTab(cfgIdConstants::ZONA_NAVEGACIO,
                cfgIdConstants::TB_SHORTCUTS);
        } else  {
            $info['type']= 'success';
            // $info['message'] = $lang['user_login'];
            $info['message'] = 'Usuari connectat';

        }

        if (isset($responseData['notification'])) {
            $action = $responseData['notification']['action'];
            $params = $responseData['notification']['params'];

            $ajaxCmdResponseGenerator->addNotification($action, $params);
        }



    }
}
