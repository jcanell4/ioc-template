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
require_once(DOKU_COMMAND . 'requestparams/ResponseParameterKeys.php');

//class SaveResponseHandler extends EditResponseHandler {
class SaveResponseHandler extends PageResponseHandler {
    function __construct() {
        parent::__construct(WikiIocResponseHandler::SAVE);
    }
    
    protected function response($requestParams, 
                                $responseData, 
                                &$ajaxCmdResponseGenerator) {

        // TODO[Xavi] Com els errors es gestionen amb excepcions, cal fer servir el code? Si es llença excepció no arriba aquí
        if ($responseData["code"]===0 && !$responseData["deleted"]){
            $ajaxCmdResponseGenerator->addInfoDta($responseData["info"]);
            $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processSaving");
            $params = array(
                "formId" => $responseData["formId"],
                "inputs" => $responseData["inputs"],
            );




            if($responseData['lockInfo']){
                $timeout = ExpiringCalc::getExpiringTime($responseData);

                $ajaxCmdResponseGenerator->addRefreshLock($responseData["id"], $requestParams[PageKeys::KEY_ID], $timeout);
            }





            if ($responseData["reload"]) {
                $params = [
                    "urlBase" => "lib/plugins/ajaxcommand/ajax.php?",
                    "params" =>$responseData["reload"]
                ];

                $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processRequest", $params);
            }

//            if ($responseData["close"]) {
//                $params = $responseData["close"];
//                $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processCloseTab", $params);
//            } else {


//                 ALERTA[Xavi] El formulari només cal actualitzar-lo quan no es tanca la pestanya
                $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processSetFormInputValue", $params);
//            }



        }
        else if ($responseData["deleted"]){
            $ajaxCmdResponseGenerator->addRemoveContentTab($responseData['id']);
            $ajaxCmdResponseGenerator->addAlert($responseData["info"]['message']);
            $ajaxCmdResponseGenerator->addRemoveItemTree(cfgIdConstants::TB_INDEX, $requestParams[PageKeys::KEY_ID]);
            
        } else if ($responseData["code"] === "cancel_document") {
                $params = [
                    "urlBase" => "lib/plugins/ajaxcommand/ajax.php?",
                    "params" => $responseData["cancel_params"]
                ];

                $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processRequest", $params);

        } else{
            $ajaxCmdResponseGenerator->addError($responseData["code"], 
                                                $responseData["info"]);
            $ajaxCmdResponseGenerator->addProcessFunction(true, 
                                            "ioc/dokuwiki/processCancellation");        
            parent::response($requestParams, $responseData["page"], 
                                                $ajaxCmdResponseGenerator);
        }
        
        //CASOS ESPECIALS
        if(preg_match("/wiki:user:.*:dreceres/", $requestParams["id"])){
            if($responseData["deleted"]){
                 $ajaxCmdResponseGenerator->addRemoveTab(cfgIdConstants::ZONA_NAVEGACIO,
                cfgIdConstants::TB_SHORTCUTS);
            }else{
                $dades = $this->getModelWrapper()->getShortcutsTaskList(WikiIocInfoManager::getInfo("client"));
    //            $dades = $this->getModelWrapper()->getShortcutsTaskList();
                $containerClass = "ioc/gui/ContentTabNsTreeListFromPage";
                $urlBase = "lib/plugins/ajaxcommand/ajax.php?call=page";
                $urlTree = "lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/";

                $params = array(
                    "id" => cfgIdConstants::TB_SHORTCUTS,
                    "title" =>  $dades['title'],
                    "standbyId" => cfgIdConstants::BODY_CONTENT,
                    "urlBase" => $urlBase,
                    "data" => $dades["content"],
                    "treeDataSource" => $urlTree,
                    'typeDictionary' => array (
                                            'p' => 
                                            array (
                                              'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=project\'',
                                              'params' => 
                                              array (
                                                0 => 'projectType',
                                              ),
                                            ),
                                          ),                
                );
                $ajaxCmdResponseGenerator->addAddTab(cfgIdConstants::ZONA_NAVEGACIO,
                                    $params,
                                    ResponseParameterKeys::FIRST_POSITION,
                                    FALSE,
                                    $containerClass);
            }
        }
    }
}

