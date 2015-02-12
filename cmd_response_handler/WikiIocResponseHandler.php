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

function _tplIncDir(){
    global $conf;
    if(is_callable('tpl_incdir')){
        $ret = tpl_incdir();
    }else{
        $ret = DOKU_INC.'lib/tpl/'.$conf['template'].'/';
    }
    return $ret;
}

if (!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', _tplIncDir().'classes/');
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'ajaxcommand/AbstractResponseHandler.php');

abstract class WikiIocResponseHandler extends AbstractResponseHandler {
    function __construct($cmd) {
        parent::__construct($cmd);
    }
    
    private function _getDataEvent(&$ajaxCmdResponseGenerator, 
                                    $requestParams=NULL, 
                                    $responseData=NULL){
        $ret = array(
            "command" => $this->getCommandName(),
            "requestParams" => $requestParams,
            "responseData" => $responseData,
            "ajaxCmdResponseGenerator" => $ajaxCmdResponseGenerator,
        );  
        return $ret;        
    }

    protected function postResponse($requestParams, 
                                        $responseData, 
                                        &$ajaxCmdResponseGenerator) {
        $data = $this->_getDataEvent($ajaxCmdResponseGenerator, 
                                        $requestParams, 
                                        $responseData);
        $evt = new Doku_Event("WIOC_PROCESS_RESPONSE", $data);
        $evt->advise_after();
        unset($evt);
        $evt = new Doku_Event("WIOC_PROCESS_RESPONSE_".$this->getCommandName(), $data);
        $evt->advise_after();
        unset($evt);
    }

    protected function preResponse($requestParams, &$ajaxCmdResponseGenerator) {
        $data = $this->_getDataEvent($ajaxCmdResponseGenerator, $requestParams);
        $evt = new Doku_Event("WIOC_PROCESS_RESPONSE", $data);
        $ret = $evt->advise_before();
        unset($evt);
        $evt = new Doku_Event("WIOC_PROCESS_RESPONSE_".$this->getCommandName(), $data);
        $ret = $ret.$evt->advise_before();
        unset($evt);
        return $ret;
    }
    
    protected function getJsInfo(){
        return $this->getModelWrapper()->getJsInfo();                     
    }  
    
    protected function getToolbarIds(&$value){
        $this->getModelWrapper()->getToolbarIds($value);
//        $value["varName"] = "toolbar";
//        $value["toolbarId"] = "tool__bar";
//        $value["wikiTextId"] = "wiki__text";
//        $value["editBarId"] = "wiki__editbar";
//        $value["editFormId"] = "dw__editform";
//        $value["summaryId"] = "edit__summary";
    }
}
