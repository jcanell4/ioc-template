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

class MediaResponseHandler extends WikiIocResponseHandler {

    function __construct() {
        parent::__construct(WikiIocResponseHandler::MEDIA);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $preserveMetaData = true;
        if(!$requestParams["preserveMetaData"]){
            $preserveMetaData = false;
        }
        $ajaxCmdResponseGenerator->addMedia($responseData['id'], 
                                                $responseData['ns'], 
                                                $responseData['title'], 
                                                $responseData['content'],$preserveMetaData);
        
        //$metaData = $this->getModelWrapper()->getMediaMetaResponse();
        //getNsTree($currentnode, $sortBy, $onlyDirs = FALSE, $expandProject=FALSE)
        global $NS;
        
        //Càrrega de la zona info de missatges
        global $JUMPTO;
        global $MSG;
        $info = array('id' => '', 'duration' => -1, 'timestamp' => date('d-m-Y H:i:s'));
        $info['type'] = 'success';
        $info['message'] = 'Llista de fitxers de ' . $responseData['ns'] . ' carregada.';
        if (isset($JUMPTO)) {
            if ($JUMPTO == false) {                
                $info['type'] = 'error';
                $info['message'] = $MSG[0]["msg"];
            }
        }
        if (isset($_REQUEST['mediado'])) {
            if ($_REQUEST['mediado'] == 'searchlist') {                
                $info['type'] = 'success';
                $info['message'] = 'Fitxers de carpeta "' . $responseData['ns'] . '" i subcarpetes, que es corresponen amb la cerca: "'.$_REQUEST['q'].'"';
            }
        }
        
        $ajaxCmdResponseGenerator->addInfoDta($info);
        
        /*
         * 20150430 Miguel Angel Lozano
         * Canvi per fer servir ContentTabDokuWikiNsTree
         * En comptes de cridar a getModelWrapper()->getNsMediaTree, es construeix la resposta
         * necessària per tal de que al fer clic a ContentTabDokuWikiNsTree es pugui fer la crida
         * amb els paràmetres necessaris (que aniran directament a la urlBase)
         */

        //$metaData = $this->getModelWrapper()->getNsMediaTree($NS, 0, TRUE);
        if(!$requestParams["preserveMetaData"]){
            global $INPUT;
            $sort = "name";
            if($INPUT->str('sort')){
                $sort = $INPUT->str('sort');
            }
            $list = "thumbs";
            if($INPUT->str('list')){
                $list = $INPUT->str('list');
            }
            $metaData = array(
                'id' => 'metaMedia', 
                'sort' => $sort,
                'list' => $list
            );

            $metaDataFileOptions = $this->getModelWrapper()->getMediaTabFileOptions();
            $metaDataFileSort = $this->getModelWrapper()->getMediaTabFileSort();
            $metaDataSearch= $this->getModelWrapper()->getMediaTabSearch();
            
            /*
             * Agrupant Visualtizació, Ordenació i Cerca al mateix element de l'acordió
             */
            
            $propLlista = array(
			'id'      => 'metaMediaProperties',
			'title'   => "Propietats de la llista",
			'content' => $metaDataFileOptions.$metaDataFileSort.$metaDataSearch
		);
            
            $metaDataFileUpload = $this->getModelWrapper()->getMediaFileUpload();
            if($requestParams["versioupload"]){
                $metaDataFileUpload['versioupload'] = $requestParams["id"];
            }
            $metaAgrupa = array(
                "0" => $metaData,
                "1" => $propLlista,
                "2" => $metaDataFileUpload
            );

            $ajaxCmdResponseGenerator->addMetaMediaData("media", $metaAgrupa);
        }
        

        

        $ajaxCmdResponseGenerator->addProcessDomFromFunction(
                $responseData['id'], true, "ioc/dokuwiki/processMediaList", //TODO configurable
                array(
            "ns" => $responseData['ns']
                //"editCommand" => "lib/plugins/ajaxcommand/ajax.php?call=edit",
                //"detailCommand" => "lib/plugins/ajaxcommand/ajax.php?call=get_image_detail"
                )
        );
        $ajaxCmdResponseGenerator->addProcessDomFromFunction(
                cfgIdConstants::ZONA_METAINFO_DIV, true, "ioc/dokuwiki/processMetaMedia", //TODO configurable
                array(
            "ns" => $responseData['ns']
                //"editCommand" => "lib/plugins/ajaxcommand/ajax.php?call=edit",
                //"detailCommand" => "lib/plugins/ajaxcommand/ajax.php?call=get_image_detail"
                )
        );
    }

}

?>
