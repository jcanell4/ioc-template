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

class MediaResponseHandler extends WikiIocResponseHandler {
    function __construct() {
        parent::__construct(WikiIocResponseHandler::MEDIA);
    }
    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
      $ajaxCmdResponseGenerator->addMedia($responseData['id'], 
                                                $responseData['ns'], 
                                                $responseData['title'], 
                                                $responseData['content']);
        
        //$metaData = $this->getModelWrapper()->getMediaMetaResponse();
        //getNsTree($currentnode, $sortBy, $onlyDirs = FALSE)
        global $NS;
        $metaData = $this->getModelWrapper()->getNsMediaTree($NS, 0 ,TRUE);

        $ajaxCmdResponseGenerator->addMetadata("media", 
                                                array($metaData));

        $info=Array();
        $info["id"] = "media";
        if(!$responseData["info"]){
            $info["info"] = $responseData["info"];
        }else{
            $info["type"] = "info";
            $info["message"] = "";
            $info[""] = "Càrrega del Media Manager finalitzada";
            $info["duration"] = -1;
            $info["timestamp"] = "04-02-2015 13:20:18";
        }
        //$info["info"] = $responseData["info"];
        $ajaxCmdResponseGenerator->addInfoDta($info);

        /*$ajaxCmdResponseGenerator->addProcessDomFromFunction(
            $responseData['id'],
            true,
            "ioc/dokuwiki/processContentPage",  //TODO configurable
            array(
                "ns" => $responseData['ns'], 
                "editCommand" => "lib/plugins/ajaxcommand/ajax.php?call=edit",
                "detailCommand" => "lib/plugins/ajaxcommand/ajax.php?call=get_image_detail",
            )
        );*/
    }    
}

?>
