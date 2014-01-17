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
if (!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', tpl_incdir().'classes/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'ajaxcommand/ResponseHandler.php');

abstract class WikiIocResponseHandler extends ResponseHandler {
    function __construct($cmd) {
        parent::__construct($cmd);
    }

    protected function postProcess($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $data = array(
            "command" => $this->getCommandName(),
            "requestParams" => $requestParams,
            "responseData" => $responseData,
            "ajaxCmdesponseGenerator" => $ajaxCmdResponseGenerator,
        );
        $evt = new Doku_Event("PROCESS_CMD", $data);
        $evt->advise_after();
        unset($evt);
    }

    protected function preProcess($requestParams, &$ajaxCmdResponseGenerator) {
        $data = array(
            "command" => $this->getCommandName(),
            "requestParams" => $requestParams,
            "ajaxCmdesponseGenerator" => $ajaxCmdResponseGenerator,
        );
        $evt = new Doku_Event("PROCESS_CMD", $data);
        $ret = $evt->advise_before();
        unset($evt);
        return $ret;
    }
    
    protected function getJsInfo(){
        global $INFO;
        global $JSINFO;
        global $ID;
        
        $INFO = pageinfo();
        //export minimal infos to JS, plugins can add more
        $JSINFO['id']        = $ID;
        $JSINFO['namespace'] = (string) $INFO['namespace']; 
    
        return $JSINFO;                        
    }  
    
    protected function getMetaResponse($id){
        global $lang;
        $ret=array('docId' => \str_replace(":", "_",$id));
        $meta=array();
        $mEvt = new Doku_Event('ADD_META', $meta);                
        if($mEvt ->advise_before()){
            $toc = wrapper_tpl_toc();
            $metaId = \str_replace(":", "_",$id).'_toc';
            $meta[] = $this->getMetaPage($metaId, $lang['toc'], $toc);
        }       
        $mEvt->advise_after();
        unset($mEvt);        
        $ret['meta']=$meta;
        return $ret;        
    }
    
    private function getMetaPage($metaId, $metaTitle, $metaToSend){
        $contentData = array('id' => $metaId,
            'title' => $metaTitle,
            'content' => $metaToSend);
        return $contentData;                
    }
    
    protected function getToolbarIds(){
        return array("varName" => "toolbar", 
                    "toolbarId" => "tool__bar",
                    "wikiTextId" => "wiki__text");
    }
}

?>