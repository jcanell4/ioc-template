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

        /*
         * Aquest handler ha de controlar diverses crides
         * - Petició del detall d'un media
         * - Eliminació d'un media --> existeix $requestParams['delete'] amb l'ns i l'id del media
         * 
         */
        $mediado = "";
        if($requestParams['mediado']){
            $mediado = $requestParams['mediado'];
        }
        $difftype = "";
        if($requestParams['difftype']){
            $difftype = $requestParams['difftype'];
        }
        if ($requestParams['delete']) {
            $ajaxCmdResponseGenerator->addMediaDetails($difftype,$mediado,"delete",$requestParams['delete'], $responseData['ns'], $requestParams['title'], $responseData['content']);
        } else {
            $ajaxCmdResponseGenerator->addMediaDetails($difftype,$mediado,"details",$responseData['id'], $responseData['ns'], $responseData['title'], $responseData['content']);
            /*
             * HISTÒRIC DE VERSIONS
             */
            
            $propLlista = array(
                'id' => $responseData['id'] . '_metaMediaDetailsProva',
                'title' => "Històric de versions",
                'ns' => $responseData['ns'],
                'content' => $this->getModelWrapper()->mediaDetailsHistory()
            );

                        
            /*
             * File Upload
             */
            
            $metaDataFileUpload = $this->getModelWrapper()->getMediaFileUpload();
            $metaDataFileUpload['ns'] = $responseData['ns'];
            $metaDataFileUpload['id'] = $responseData['id'] . '_metaMediafileupload';
            if($requestParams["versioupload"]){
                $metaDataFileUpload['versioupload'] = $responseData['id'];
            }
            $patrones = array();
            $patrones[0] = '/dw__upload/';
            $patrones[1] = '/upload__file/';
            $patrones[2] = '/upload__name/';
            $patrones[3] = '/dw__ow/';                  
            $sustituciones = array();
            $sustituciones[0] = 'dw__upload_'.$responseData['id'];         
            $sustituciones[1] = 'upload__file_'.$responseData['id'];         
            $sustituciones[2] = 'upload__name_'.$responseData['id'];         
            $sustituciones[3] = 'dw__ow_'.$responseData['id'];                              
            $metaDataFileUpload['content'] = preg_replace($patrones, $sustituciones, $metaDataFileUpload['content']);  
            
            $metaAgrupa = array(
                "0" => $propLlista,
                "1" => $metaDataFileUpload
            );

            $ajaxCmdResponseGenerator->addMetaMediaDetailsData($responseData['id'], $metaAgrupa);

            //$metaData = $this->getModelWrapper()->getMediaMetaResponse();
            //getNsTree($currentnode, $sortBy, $onlyDirs = FALSE, $expandProject=FALSE)
            global $NS;

            //Càrrega de la zona info de missatges
            global $JUMPTO;
            $info = array('id' => $responseData['id'], 'duration' => -1, 'timestamp' => date('d-m-Y H:i:s'));
            $info['type'] = 'success';
            if($mediado == "diff"){
                $info['message'] = ' &nbsp;Es mostren les versions a comparar.';
            }else{
                $info['message'] = ' &nbsp;Imatge ' . $responseData['id'] . ' carregada.';
            }

            if (isset($JUMPTO)) {
                if ($JUMPTO == false) {
                    $info['type'] = 'error';
                    $info['message'] = "El fitxer no s'ha pogut carregar.";
                }
            }


            $ajaxCmdResponseGenerator->addInfoDta($info);
        }
    }

}

?>
