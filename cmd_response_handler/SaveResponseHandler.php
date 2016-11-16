<?php

/**
 * Description of SaveResponseHandler
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined("DOKU_INC")) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(tpl_incdir().'cmd_response_handler/PageResponseHandler.php');
//require_once(tpl_incdir().'cmd_response_handler/EditResponseHandler.php');
require_once DOKU_PLUGIN.'ajaxcommand/JsonGenerator.php';
require_once(DOKU_PLUGIN.'ajaxcommand/requestparams/PageKeys.php');
require_once(tpl_incdir().'conf/cfgIdConstants.php');
require_once(tpl_incdir() . 'cmd_response_handler/utility/ExpiringCalc.php');

//class SaveResponseHandler extends EditResponseHandler {
class SaveResponseHandler extends PageResponseHandler {
    function __construct() {
        parent::__construct(WikiIocResponseHandler::SAVE);
    }
    
    protected function response($requestParams, 
                                $responseData, 
                                &$ajaxCmdResponseGenerator) {

        // TODO[Xavi] Com els errors es gestionen amb excepcions, cal fer servir el code? Si es llença excepció no arriba aquí
        if ($responseData["code"]==0 && !$responseData["deleted"]){
            $ajaxCmdResponseGenerator->addInfoDta($responseData["info"]);
            $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processSaving");
            $params = array(
                "formId" => $responseData["formId"],
                "inputs" => $responseData["inputs"],
            );
            $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processSetFormInputValue", $params);
            if($responseData['lockInfo']){
                $timeout = ExpiringCalc::getExpiringTime($responseData);

                $ajaxCmdResponseGenerator->addRefreshLock($responseData["id"], $requestParams[PageKeys::KEY_ID], $timeout);
            }
        }
        elseif ($responseData["deleted"]){
            $ajaxCmdResponseGenerator->addRemoveContentTab($responseData['id']);
            $ajaxCmdResponseGenerator->addAlert($responseData["info"]['message']);
            $ajaxCmdResponseGenerator->addRemoveItemTree(cfgIdConstants::TB_INDEX, $requestParams[PageKeys::KEY_ID]);
            
        }
        else{
            $ajaxCmdResponseGenerator->addError($responseData["code"], 
                                                $responseData["info"]);
            $ajaxCmdResponseGenerator->addProcessFunction(true, 
                                            "ioc/dokuwiki/processCancellation");        
            parent::response($requestParams, $responseData["page"], 
                                                $ajaxCmdResponseGenerator);
        }
        
        //CASOS ESPECIALS
        if(preg_match("/wiki:user:.*:dreceres/", $requestParams[id])){
            $dades = $this->getModelWrapper()->getShortcutsTaskList(WikiIocInfoManager::getInfo("client"));
//            $dades = $this->getModelWrapper()->getShortcutsTaskList();
            $urlBase = "lib/plugins/ajaxcommand/ajax.php?call=page";

            $ajaxCmdResponseGenerator->addShortcutsTab(cfgIdConstants::ZONA_NAVEGACIO,
                cfgIdConstants::TB_SHORTCUTS,
                $dades['title'],
                $dades['content'],
                $urlBase);
            
        }
    }
}

