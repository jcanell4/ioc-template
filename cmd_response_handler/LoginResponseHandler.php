<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WikiIocResponseHandler
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */

if (!defined("DOKU_INC")) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(tpl_incdir().'cmd_response_handler/WikiIocResponseHandler.php');
require_once DOKU_PLUGIN.'ajaxcommand/JsonGenerator.php';

class LoginResponseHandler extends WikiIocResponseHandler {
    
    function __construct() {
        parent::__construct(WikiIocResponseHandler::LOGIN);
    }
    
    protected function process($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $this->login($requestParams, $responseData, $ajaxCmdResponseGenerator);
    }

    private function login ($requestParams, $responseData, &$ajaxCmdResponseGenerator){
        $ajaxCmdResponseGenerator->addLoginInfo($responseData["loginRequest"],
                $responseData['loginResult']);
        $ajaxCmdResponseGenerator->addSectokData(getSecurityToken());
        if($responseData["loginResult"]){
            $ajaxCmdResponseGenerator->addSetJsInfo($this->getJsInfo());
//            $pr = $this->getLoginPageResponse();
//            $ajaxCmdResponseGenerator->addHtmlDoc($pr["id"], $pr["ns"], 
//                                                $pr["title"], $pr["content"]);
//            $ajaxCmdResponseGenerator->addChangeWidgetProperty("exitButton", "visible", true);
//            $ajaxCmdResponseGenerator->addChangeWidgetProperty("loginButton", "visible", false);
            $ajaxCmdResponseGenerator->addReloadWidgetContent("tb_index");
//            //elimina, si existe, la pestaña 'desconectat'
//            $logout = $this->getLogoutPageResponse();
//            $ajaxCmdResponseGenerator->addRemoveContentTab($logout['id']);
            $title = $_SERVER['REMOTE_USER'];
            $sig = toolbar_signature();
        }else{
//            $ajaxCmdResponseGenerator->addChangeWidgetProperty("exitButton", "visible", false);
//            $ajaxCmdResponseGenerator->addChangeWidgetProperty("loginButton", "visible", true);
            $ajaxCmdResponseGenerator->addReloadWidgetContent("tb_index");
            $ajaxCmdResponseGenerator->addRemoveAllContentTab();
            $ajaxCmdResponseGenerator->addRemoveAllWidgetChildren("zonaMetaInfo");
//            $pr = $this->getLogoutPageResponse();
//            $ajaxCmdResponseGenerator->addHtmlDoc($pr["id"], $pr["ns"], 
//                                                $pr["title"], $pr["content"]);
            $title = '';
            $sig = '';
        }
        $ajaxCmdResponseGenerator->addTitle($title);
        $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/setSignature", $sig);
    }    
    
//    private function getLogoutPageResponse(){
//        return array('id' => "logout_info",
//        'title' => "desconectat",
//        'content' => "Accés restringit. Per accedir cal que us identifiqueu");                
//    }


//    private function getLoginPageResponse(){
//        $id = tpl_getConf("ioc_template_startpage");
//        $resp = array(
//            'id' => \str_replace(":", "_",$id),
//            'ns' => $id,
//            'title' => tpl_pagetitle($id, true),
//            'content' => $this->getFormatedPage($id),
//        );
//        return $resp;
//    }
    
//     private function getFormatedPage($page){
//        global $ACT;
//        global $ID;
//        
//        $old_id = $ID;
//        $ID = $page;
//        
//        ob_start();
//        trigger_event('TPL_ACT_RENDER', $ACT, 'html_show');
//        $html_output = ob_get_clean()."\n";
//        $ID = $old_id;
//        return $html_output;
//    }
}

?>
