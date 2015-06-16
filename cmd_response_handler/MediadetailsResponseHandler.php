<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of page_response_handler
 *
 * @author Miguel Angel Lozano <mlozan54@ioc.cat>
 */
if (!defined("DOKU_INC"))
    die();
if (!defined('DOKU_PLUGIN'))
    define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
require_once(tpl_incdir() . 'cmd_response_handler/WikiIocResponseHandler.php');
require_once DOKU_PLUGIN . 'ajaxcommand/JsonGenerator.php';
require_once(tpl_incdir() . 'conf/cfgIdConstants.php');

class MediadetailsResponseHandler extends WikiIocResponseHandler {

    function __construct() {
        parent::__construct(WikiIocResponseHandler::MEDIADETAILS);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
    /*$ret = array(
            "content" => $this->doMediaDetailsPreProcess(),
            "id" => "mediadetails",
            "title" => $image,
            "ns" => $NS,
            "imageTitle" => $image,
            "image" => $image
        );*/
      $ajaxCmdResponseGenerator->addMediaDetails($responseData['id'], 
                                                $responseData['ns'], 
                                                $responseData['title'], 
                                                $responseData['content']);
        
      /*
       * UNA PROVA AMB META INFO I DECORATOR PER AL MEDIA DETAILS
       */
      $propLlista = array(
			'id'      => $responseData['id'].'_metaMediaDetailsProva',
			'title'   => "PROVA DE MEDIA DETAIL",
			'content' => "<div class='provadetail' id='".$responseData['id']."_provaMediaDetails'><span>Hola Touch Me</span></div>"
		);
            
            $metaDataFileUpload = $this->getModelWrapper()->getMediaFileUpload();
            $metaAgrupa = array(
                "0" => $propLlista
            );

            $ajaxCmdResponseGenerator->addMetaMediaDetailsData($responseData['id'], $metaAgrupa);
      
        //$metaData = $this->getModelWrapper()->getMediaMetaResponse();
        //getNsTree($currentnode, $sortBy, $onlyDirs = FALSE)
        global $NS;
        
        //Càrrega de la zona info de missatges
        global $JUMPTO;
        $info = array('id' => $responseData['id'], 'duration' => -1, 'timestamp' => date('d-m-Y H:i:s'));
        $info['type'] = 'success';
        $info['message'] = 'Imatge ' . $responseData['id'] . ' carregada.';
        if (isset($JUMPTO)) {
            if ($JUMPTO == false) {                
                $info['type'] = 'error';
                $info['message'] = "El fitxer no s'ha pogut carregar.";
            }
        }
        
        
        $ajaxCmdResponseGenerator->addInfoDta($info);
        

    }

}

?>
