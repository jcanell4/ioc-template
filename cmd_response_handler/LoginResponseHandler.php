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
//require_once(DOKU_TPL_INCDIR . 'conf/mainCfg.php'); // se ha sustituido por el modelo de Constantes de cfgIdConstants.php

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
		//$cfg = WikiIocCfg::Instance();

        if($responseData["loginResult"]){
            $ajaxCmdResponseGenerator->addSetJsInfo($this->getJsInfo());
//            $ajaxCmdResponseGenerator->addReloadWidgetContent($cfg->getArrIds("zN_index_id"));
//            $ajaxCmdResponseGenerator->addChangeWidgetProperty(
//                                                    $cfg->getArrIds("userButton"), 
//                                                    "label", 
//                                                    $responseData["userId"]);
            $ajaxCmdResponseGenerator->addReloadWidgetContent(cfgIdConstants::TB_INDEX);
            $ajaxCmdResponseGenerator->addChangeWidgetProperty(
                                                    cfgIdConstants::USER_BUTTON,
                                                    "label", 
                                                    $responseData["userId"]);
            $title = $_SERVER['REMOTE_USER'];
            $sig = toolbar_signature();
        }else{
//            $ajaxCmdResponseGenerator->addReloadWidgetContent($cfg->getArrIds("zN_index_id"));
            $ajaxCmdResponseGenerator->addReloadWidgetContent(cfgIdConstants::TB_INDEX);
            $ajaxCmdResponseGenerator->addRemoveAllContentTab();
//            $ajaxCmdResponseGenerator->addRemoveAllWidgetChildren($cfg->getArrIds("zonaMetaInfo"));
            $ajaxCmdResponseGenerator->addRemoveAllWidgetChildren(cfgIdConstants::ZONA_METAINFO);
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
