<?php

/**
 * Description of SaveResponseHandler
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined("DOKU_INC")) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(tpl_incdir().'cmd_response_handler/PageResponseHandler.php');
//require_once(tpl_incdir().'cmd_response_handler/EditResponseHandler.php');
require_once DOKU_PLUGIN.'ajaxcommand/JsonGenerator.php';

//class SaveResponseHandler extends EditResponseHandler {
class Save_partialResponseHandler extends PageResponseHandler {
    function __construct() {
        parent::__construct(WikiIocResponseHandler::SAVE);
    }
    
    protected function response($requestParams, 
                                $responseData, 
                                &$ajaxCmdResponseGenerator) {
        if($responseData["code"]==0){
            $ajaxCmdResponseGenerator->addInfoDta($responseData["info"]);
            $ajaxCmdResponseGenerator->addProcessFunction(true, 
                                            "ioc/dokuwiki/processSaving");


            $params = array(
                "formId" => strtolower("form_" . str_replace(':', '_',$requestParams['id']) . "_" .$requestParams['section_id']), // TODO[Xavi] cercar una manera més adequada de processar el form
                "docId" => str_replace(':', '_',$requestParams['id']),
                "inputs" => $responseData["inputs"],
                "date" => $responseData["inputs"]["date"]+1, // TODO[Xavi] TEST Sembla que amb el mateix nombre no funciona encara que hauria de funcionar
                "structure" => $responseData["structure"]
            );

            $ajaxCmdResponseGenerator->addProcessFunction(true, 
                                        "ioc/dokuwiki/processSetFormInputValueForPartials",
                                        $params);

        }else{
            $ajaxCmdResponseGenerator->addError($responseData["code"], 
                                                $responseData["info"]);
            $ajaxCmdResponseGenerator->addProcessFunction(true, 
                                            "ioc/dokuwiki/processCancellation");        
            parent::response($requestParams, $responseData["page"], 
                                                $ajaxCmdResponseGenerator);
        }
    }
}

?>
