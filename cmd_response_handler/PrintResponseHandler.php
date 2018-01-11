<?php
/**
 * Description of PrintResponseHandler
 * @author josep
 */
if (!defined("DOKU_INC")) die();
require_once(DOKU_INC . 'lib/plugins/ajaxcommand/defkeys/ResponseHandlerKeys.php');
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/WikiIocResponseHandler.php');

class PrintResponseHandler extends WikiIocResponseHandler{

    function __construct($cmd = ResponseHandlerKeys::PRINT_ACTION){
        parent::__construct($cmd);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $resp = array();
        if(isset($responseData['draftType'])){
            //Indicar que hi ha un esborrany però que el que s'imprimeix és la versió original.
            //Indicar que si es vol imprimir els canvis de l'esborrany cal guardar-lo abans
            $resp["ns"] = $requestParams["id"];
            $resp["html"] = $responseData['html'];
            $resp["pageCommand"] = "lib/exe/ioc_ajax.php?call=page";
        }else{
            //Imprimir
            $resp["ns"] = $requestParams["id"];
            $resp["html"] = $responseData['html'];
            $resp["pageCommand"] = "lib/exe/ioc_ajax.php?call=page";
        }
        $ajaxCmdResponseGenerator->addPrintResponse($resp);
    }

}
