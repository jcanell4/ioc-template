<?php
/**
 * MediadetailsResponseHandler
 * @author Miguel Angel Lozano <mlozan54@ioc.cat>
 */
if (!defined("DOKU_INC")) die();
require_once(DOKU_TPL_INCDIR . "cmd_response_handler/WikiIocResponseHandler.php");

class MediadetailsResponseHandler extends WikiIocResponseHandler {

    function __construct() {
        parent::__construct(ResponseHandlerKeys::MEDIADETAILS);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        if ($requestParams[MediaKeys::KEY_DELETE]){
            $this->_responseDelete($requestParams, $responseData, $ajaxCmdResponseGenerator);
        }else {
            $this->_responseDetail($requestParams, $responseData, $ajaxCmdResponseGenerator);
        }
    }

    private function _responseDelete($requestParams, $responseData, &$ajaxCmdResponseGenerator){
        if ($responseData["result"] & 1){
            $ajaxCmdResponseGenerator->addMediaDetails( "",
                                                        "",
                                                        MediaKeys::KEY_DELETE,
                                                        $requestParams[MediaKeys::KEY_DELETE],
                                                        $responseData['ns'],
                                                        $requestParams['title'],
                                                        $responseData['content']
                                                    );
            $info = array(
                        'id' => "media",
                        'duration' => -1,
                        'timestamp' => date('d-m-Y H:i:s'),
                        'type' => "warning",
                        'message' => $responseData["info"]
                    );
            $ajaxCmdResponseGenerator->addInfoDta($info);

        }else {
            $ajaxCmdResponseGenerator->addAlert($responseData["info"]);
            $info = array(
                        'id' => $requestParams[MediaKeys::KEY_DELETE],
                        'duration' => -1,
                        'timestamp' => date('d-m-Y H:i:s'),
                        'type' => "error",
                        'message' => $responseData["info"]
                    );
            $ajaxCmdResponseGenerator->addInfoDta($info);
        }
    }

    private function _responseDetail($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $difftype = ($requestParams[MediaKeys::KEY_DIFFTYPE]) ? $requestParams[MediaKeys::KEY_DIFFTYPE] : "";
        $mediado = ($requestParams[MediaKeys::KEY_MEDIA_DO]) ? $requestParams[MediaKeys::KEY_MEDIA_DO] : "";
        $ajaxCmdResponseGenerator->addMediaDetails($difftype, 
                                                   $mediado,
                                                   "details", 
                                                   $responseData[MediaKeys::KEY_ID],
                                                   $responseData[MediaKeys::KEY_NS],
                                                   $responseData['title'],
                                                   $responseData['content'],
                                                   $responseData[MediaKeys::KEY_REV]);

        // Històric de versions
        $propLlista = array(
            'id' => $responseData['id'] . '_metaMediaDetailsProva',
            'title' => "Històric de versions",
            'ns' => $responseData['ns'],
            'content' => $this->getModelAdapter()->mediaDetailsHistory($responseData['ns'], $responseData['image'])
        );

        // File Upload
        $metaDataFileUpload = $this->getModelAdapter()->getMediaFileUpload();
        $metaDataFileUpload['ns'] = $responseData['ns'];
        $metaDataFileUpload['id'] = $responseData['id'] . '_metaMediafileupload';
        if ($requestParams["versioupload"]){
            $metaDataFileUpload['versioupload'] = $responseData['id'];
        }
        $patrones = array();
        //Los patrones 0 a 2 se ponen temporalmente para evitar que se modifique el nombre de archivo y el check
        //de sobreescritura. Esto no será necesario cuando mediadetails disponga de un formulario propio para
        //hacer un upload que sólo sirva para sustituir el fichero actual por uno nuevo (sin cambio de nombre)
        $patrones[0] = '/type="text"/';
        $patrones[1] = '/label for="upload__name"/';
        $patrones[2] = '/label class="check"/';
        $patrones[3] = '/dw__upload/';
        $patrones[4] = '/upload__file/';
        $patrones[5] = '/upload__name/';
        $patrones[6] = '/dw__ow/';
        $sustituciones = array();
        $sustituciones[0] = 'type="text" readonly ';
        $sustituciones[1] = 'label for="upload__name" style="display:none" ';
        $sustituciones[2] = 'label class="check" style="display:none" ';
        $sustituciones[3] = 'dw__upload_'.$responseData['id'];
        $sustituciones[4] = 'upload__file_'.$responseData['id'];
        $sustituciones[5] = 'upload__name_'.$responseData['id'];
        $sustituciones[6] = 'dw__ow_'.$responseData['id'];
        $metaDataFileUpload['content'] = preg_replace($patrones, $sustituciones, $metaDataFileUpload['content']);

        $metaAgrupa = [$propLlista];
        if ($responseData[MediaKeys::KEY_REV] === 0) {
            $metaAgrupa[] = $metaDataFileUpload;
        }
        $ajaxCmdResponseGenerator->addMetaMediaDetailsData($responseData['id'], $metaAgrupa);

        //Càrrega de la zona info de missatges
        $info = array('id' => "bodyContent_tablist_{$responseData['id']}", 'duration' => -1, 'timestamp' => date('d-m-Y H:i:s'));
        $info['type'] = 'success';
        if ($mediado == "diff"){
            $info['message'] = ' &nbsp;Es mostren les versions a comparar.';
        }else{
            $info['message'] = ' &nbsp;Imatge ' . $responseData['id'] . ' carregada.';
        }
        $ajaxCmdResponseGenerator->addInfoDta($info);
    }

}
