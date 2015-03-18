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
require_once(tpl_incdir(). 'conf/cfgIdConstants.php');

class MediaResponseHandler extends WikiIocResponseHandler {
    function __construct() {
        parent::__construct(WikiIocResponseHandler::MEDIA);
    }
    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        //$ajaxCmdResponseGenerator->addSetJsInfo($this->getJsInfo());
      $ajaxCmdResponseGenerator->addMedia($responseData['id'], 
                                                $responseData['ns'], 
                                                $responseData['title'], 
                                                $responseData['content']);
        
        //$metaData = $this->getModelWrapper()->getMediaMetaResponse();
        //getNsTree($currentnode, $sortBy, $onlyDirs = FALSE)
        global $NS;
        $metaData = $this->getModelWrapper()->getNsMediaTree($NS, 0 ,TRUE);
        $metaDataFileOptions = $this->getModelWrapper()->getMediaTabFileOptions();
        $metaDataFileSort = $this->getModelWrapper()->getMediaTabFileSort();
        $metaDataFileUpload = $this->getModelWrapper()->getMediaFileUpload();
        $metaAgrupa = array(
                "0"  => $metaData,
                "1"  => $metaDataFileOptions,
                "2"  => $metaDataFileSort,
                "3"  => $metaDataFileUpload
        );

        $ajaxCmdResponseGenerator->addMetaMediaData("media",$metaAgrupa);

        /*$info=Array();
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
        $ajaxCmdResponseGenerator->addInfoDta($info);*/

        $ajaxCmdResponseGenerator->addProcessDomFromFunction(
            $responseData['id'],
            true,
            "ioc/dokuwiki/processMediaList",  //TODO configurable
            array(
                "ns" => $responseData['ns'] 
                //"editCommand" => "lib/plugins/ajaxcommand/ajax.php?call=edit",
                //"detailCommand" => "lib/plugins/ajaxcommand/ajax.php?call=get_image_detail"
            )
        );
        $ajaxCmdResponseGenerator->addProcessDomFromFunction(
            cfgIdConstants::ZONA_METAINFO_DIV,
            true,
            "ioc/dokuwiki/processMetaMedia",  //TODO configurable
            array(
                "ns" => $responseData['ns'] 
                //"editCommand" => "lib/plugins/ajaxcommand/ajax.php?call=edit",
                //"detailCommand" => "lib/plugins/ajaxcommand/ajax.php?call=get_image_detail"
            )
        );
    }    
}

?>
