<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of page_response_handler
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */

if (!defined("DOKU_INC")) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(tpl_incdir().'cmd_response_handler/WikiIocResponseHandler.php');
require_once DOKU_PLUGIN.'ajaxcommand/JsonGenerator.php';

class EditResponseHandler extends WikiIocResponseHandler {
    function __construct() {
        parent::__construct(WikiIocResponseHandler::EDIT);
    }

    /**
     * @param string[]                 $requestParams
     * @param mixed                    $responseData
     * @param AjaxCmdResponseGenerator $ajaxCmdResponseGenerator
     *
     * @return void
     */
    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        global $conf;
        
        $ajaxCmdResponseGenerator->addWikiCodeDoc(
            $responseData['id'], $responseData['ns'], 
            $responseData['title'], $responseData['content']
        );

        $meta = $responseData['meta'];
//        if($requestParams["reload"]){
//            $respostaMeta = $this->getModelWrapper()->getMetaResponse($responseData['id'])['meta'];
//            $meta = array_merge($meta, $respostaMeta);
//        }
        $ajaxCmdResponseGenerator->addMetadata($responseData['id'], $meta);
        $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);

        $params = array();                
        $this->getToolbarIds($params);
        $params['id'] = $responseData['id'];
        $params['licenseClass'] = "license";
        $params['timeout']= $conf['locktime'] - 60;
        $params['draft']=$conf['usedraft']!=0;
        $ajaxCmdResponseGenerator->addProcessFunction(
            TRUE, "ioc/dokuwiki/processEditing",
            $params
        );

    }
}
