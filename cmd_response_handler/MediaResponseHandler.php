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
        //$ajaxCmdResponseGenerator->addSetJsInfo($this->getJsInfo());
        $ajaxCmdResponseGenerator->addMedia($responseData['id'], $responseData['ns'], $responseData['title'], $responseData['content']);

        //$metaData = $this->getModelWrapper()->getMediaMetaResponse();
        //getNsTree($currentnode, $sortBy, $onlyDirs = FALSE)
        global $NS;
        
        //Càrrega de la zona info de missatges
        global $JUMPTO;
        $info = array('id' => '', 'duration' => -1, 'timestamp' => date('d-m-Y H:i:s'));
        $info['type'] = 'success';
        $info['message'] = 'Llista de fitxers de ' . $responseData['ns'] . ' carregada.';
        if (isset($JUMPTO)) {
            if ($JUMPTO == false) {                
                $info['type'] = 'error';
                $info['message'] = "El fitxer no s'ha pogut carregar. El motiu és a Índex Media.";
            }
        }
        $ajaxCmdResponseGenerator->addInfoDta($info);
        

        $metaData = $this->getModelWrapper()->getNsMediaTree($NS, 0, TRUE);
        $metaDataFileOptions = $this->getModelWrapper()->getMediaTabFileOptions();
        $metaDataFileSort = $this->getModelWrapper()->getMediaTabFileSort();
        $metaDataFileUpload = $this->getModelWrapper()->getMediaFileUpload();
        $metaAgrupa = array(
            "0" => $metaData,
            "1" => $metaDataFileOptions,
            "2" => $metaDataFileSort,
            "3" => $metaDataFileUpload
        );

        $ajaxCmdResponseGenerator->addMetaMediaData("media", $metaAgrupa);

        

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
