<?php
/**
 * Description of PrintResponseHandler
 *
 * @author josep
 */
require_once(tpl_incdir() . 'cmd_response_handler/WikiIocResponseHandler.php');

class PrintResponseHandler extends WikiIocResponseHandler{
    function __construct($cmd = WikiIocResponseHandler::PRINT_ACTION){
        parent::__construct($cmd);
    }    
    
    //put your code here
    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $resp = array();
        if(isset($responseData['draftType'])){
            //Indicar que hi ha un esborrany però que el que s'imprimeix és la versió original. 
            //Indicar que si es vol imprimir els canvis de l'esborrany cal guardar-lo abans
            $resp["ns"] = $requestParams["id"];
            $resp["html"] = $responseData['html'];
            $resp["pageCommand"] = "lib/plugins/ajaxcommand/ajax.php?call=page";
        }else{
            //Imprimir 
            $resp["ns"] = $requestParams["id"];
            $resp["html"] = $responseData['html'];
            $resp["pageCommand"] = "lib/plugins/ajaxcommand/ajax.php?call=page";
        }
        $ajaxCmdResponseGenerator->addPrintResponse($resp);
    }

}
