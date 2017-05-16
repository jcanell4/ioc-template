<?php

/**
 * Description of SaveResponseHandler
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>, Xavier García <xaviergaro.dev@gmail.com>
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
require_once(tpl_incdir() . 'cmd_response_handler/PageResponseHandler.php');
//require_once(tpl_incdir().'cmd_response_handler/EditResponseHandler.php');
require_once DOKU_PLUGIN . 'ajaxcommand/JsonGenerator.php';
require_once(tpl_incdir() . 'cmd_response_handler/utility/ExpiringCalc.php');

class Save_partialResponseHandler extends PageResponseHandler
{
    function __construct()
    {
        parent::__construct(WikiIocResponseHandler::SAVE);
    }

    protected function response($requestParams,
                                $responseData,
                                &$ajaxCmdResponseGenerator)
    {
        if ($responseData["code"] === 0) {
            $ajaxCmdResponseGenerator->addInfoDta($responseData["info"]);
            $ajaxCmdResponseGenerator->addProcessFunction(true,
                "ioc/dokuwiki/processSaving");

//            $ajaxCmdResponseGenerator->addWikiCodeDocPartial(
//                $responseData['structure']
//            );


            $params = array(
                "formId" => strtolower("form_" . str_replace(':', '_', $requestParams['id']) . "_" . $requestParams['section_id']), // TODO[Xavi] cercar una manera més adequada de processar el form
                "docId" => str_replace(':', '_', $requestParams['id']),
                "inputs" => $responseData["inputs"],
                "date" => $responseData["inputs"]["date"],
                "structure" => $responseData["structure"]
            );

            // TODO[Xavi] aquest es el que es fa servir pel guardar normal
            $ajaxCmdResponseGenerator->addProcessFunction(true,
                "ioc/dokuwiki/processSetFormInputValue",
                $params);


            $ajaxCmdResponseGenerator->addProcessFunction(true,
                "ioc/dokuwiki/processSetFormsDate",
                $params);

            if($responseData['lockInfo']){
                $timeout = ExpiringCalc::getExpiringTime($responseData);

                $ajaxCmdResponseGenerator->addRefreshLock($responseData["id"], $requestParams[PageKeys::KEY_ID], $timeout);
            }


        } else if ($responseData["code"] === "cancel_document") {
//            $params = [
//                "urlBase" => "lib/plugins/ajaxcommand/ajax.php?",
//                "params" => $responseData["cancel_params"]
//            ];

//            $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processRequest", $params);

            $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processEvent", $responseData["cancel_params"]);


//

        } else {
            $ajaxCmdResponseGenerator->addError($responseData["code"],
                $responseData["info"]);
            $ajaxCmdResponseGenerator->addProcessFunction(true,
                "ioc/dokuwiki/processCancellation");
            parent::response($requestParams, $responseData["page"],
                $ajaxCmdResponseGenerator);
        }


        // Actualització de les metas, info i les revisions
        if (isset($responseData['meta'])) {
            $ajaxCmdResponseGenerator->addMetadata($responseData['meta']['id'], $responseData['meta']['meta']);
        }

        if (isset($responseData['info'])) {
            $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
        }

        if (isset($responseData['revs']) && count($responseData['revs']) > 0) {
            // No ha de ser possible cap altre cas perquè hem desat així que com a minim hi ha una
            $ajaxCmdResponseGenerator->addRevisionsTypeResponse($responseData['structure']['id'], $responseData['revs']);
        }
        
        //CASOS ESPECIALS
        if(preg_match("/wiki:user:.*:dreceres/", $requestParams["id"])){
            if($responseData["deleted"]){
                 $ajaxCmdResponseGenerator->addRemoveTab(cfgIdConstants::ZONA_NAVEGACIO,
                cfgIdConstants::TB_SHORTCUTS);
            }else{
                $dades = $this->getModelWrapper()->getShortcutsTaskList(WikiIocInfoManager::getInfo("client"));
    //            $dades = $this->getModelWrapper()->getShortcutsTaskList();

                $containerClass = "ioc/gui/ContentTabNsTreeListFromPage";
                $urlBase = "lib/plugins/ajaxcommand/ajax.php?call=page";
                $urlTree = "lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/";

                $params = array(
                    "id" => cfgIdConstants::TB_SHORTCUTS,
                    "title" =>  $dades['title'],
                    "standbyId" => cfgIdConstants::BODY_CONTENT,
                    "urlBase" => $urlBase,
                    "data" => $dades["content"],
                    "treeDataSource" => $urlTree,
                    'typeDictionary' => array (
                                            'p' => 
                                            array (
                                              'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=project\'',
                                              'params' => 
                                              array (
                                                0 => 'projectType',
                                              ),
                                            ),
                                          ),                
                );
                $ajaxCmdResponseGenerator->addAddTab(cfgIdConstants::ZONA_NAVEGACIO,
                                    $params,
                                    ResponseParameterKeys::FIRST_POSITION,
                                    FALSE,          
                                    $containerClass);
            }
        }
    }
}
