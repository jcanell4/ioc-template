<?php
/**
 * Description of page_response_handler
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */

if (!defined("DOKU_INC")) {
    die();
}
if (!defined('DOKU_PLUGIN')) {
    define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
}
require_once(tpl_incdir() . 'cmd_response_handler/WikiIocResponseHandler.php');
require_once DOKU_PLUGIN . 'wikiiocmodel/WikiIocInfoManager.php';
require_once DOKU_PLUGIN . 'wikiiocmodel/WikiIocLangManager.php';
require_once DOKU_PLUGIN . 'ajaxcommand/JsonGenerator.php';

class EditResponseHandler extends WikiIocResponseHandler
{
    function __construct()
    {
        parent::__construct(WikiIocResponseHandler::EDIT);
    }

    /**
     * @param string[] $requestParams
     * @param mixed $responseData
     * @param AjaxCmdResponseGenerator $ajaxCmdResponseGenerator
     *
     * @return void
     */
    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator)
    {
//        global $INFO;



        // TODO[Xavi] Pendent per quan afegim els dialegs
        // S'ha d'afegir aquest nou tipus
        // Mostra un dialog indicant que altre usuari el te bloquejat
        // Mostra el nom de l'usuari
        // Dona 2 opcions:
        //      Continuar en mode readonly -> crida ajax obrir read only
        //      Demanar accéss al fitxer -> crida ajax notificació per requeri el fitxer a l'usuari


//        if ($responseData[PageKeys::KEY_LOCK_STATE] == 200) {
//            $lockingUser = WikiIocInfoManager::getInfo(WikiIocInfoManager::KEY_LOCKED);
//            $ajaxCmdResponseGenerator->addDialogData(
//                WikiIocLangManager::getLang('lockedByTitle'),
//                sprintf(WikiIocLangManager::getLang('lockedByDialog'), $lockingUser),
//                [
//                    [
//                        'type' => 'Ajax',
//                        'label' => WikiIocLangManager::getLang('BtnReadOnly'),
//                        'base' => 'lib/plugins/ajaxcommand/ajax.php?call=edit&do=edit&readonly=true&id=' . $responseData['ns'], // TODO[Xavi] Crec que això no està implementat però alguna cosa sobre readonly si que està feta
//                        'params' => []
//                    ],
//                    [
//                        'type' => 'Ajax',
//                        'label' => WikiIocLangManager::getLang('BtnRequireLock'),
//                        'base' => 'lib/plugins/ajaxcommand/ajax.php?call=requirelock&id=' . $responseData['ns'], // TODO[Xavi] Pendend de confirmar si es passa com notify o como ajaxcommand: call=requireLock&id=foo
//                        'params' => []
//                    ]
//                ]);



//        if ($responseData[PageKeys::KEY_LOCK_STATE] == 200) { //
////        if ($responseData['locked']) {
//            unset($responseData['show_draft_dialog']);
//        }


        if ($responseData['show_draft_dialog']) {

            // No s'envien les respostes convencionals
            $params = array();
            foreach ($responseData as $key => $value){
                if($key !="show_draft_dialog" && $key!="id"
                        && $key!="ns" && $key!="rev"){
                    if($key=="local"){
                        if($value){
                            $params[$key] = $value; 
                        }
                    }else{
                        $params[$key] = $value; 
                    }
                }
            }
            $params["base"]='lib/plugins/ajaxcommand/ajax.php?call=edit&do=edit';

//            $params = [
//                'title' => $responseData['title'],
//                'htmlForm' => $responseData['htmlForm'],
//                'content' => $responseData['content'],
//                'draft' => $responseData['draft'],
//                'lastmod' => $this->getModelWrapper()->extractDateFromRevision($INFO['lastmod']),
//                'type' => 'full_document',
//                'base' => 'lib/plugins/ajaxcommand/ajax.php?call=edit&do=edit'
//            ];

//            if ($responseData['local']) {
//                $params['local'] = true;
//            }


            // TODO[Xavi] si està bloquejat no s'ha de mostrar el dialog
            // ALERTA[Xavi] Com que es fa la comprovació anteriorment, no hauria de arribar mati a aquí en aquest cas
            if (!WikiIocInfoManager::getInfo('locked')) {
                $ajaxCmdResponseGenerator->addDraftDialog(
                    $responseData['id'],
                    $responseData['ns'],
                    $responseData['rev'],
                    $params,
                    WikiGlobalConfig::getConf("locktime")    

                );
            }

        }else if($responseData["locked"]){    
            //TODO[Josep]: Generar un diàleg per preguntar si vol que l'avisin quan s'alliberi
            //$ajaxCmdResponseGenerator->addAlert(WikiIocLangManager::getLang('lockedByAlert')); // Alerta[Xavi] fent servir el lock state no tenim accés al nom de l'usuari que el bloqueja
            $params = array(
                "id" => $responseData["id"],
                "ns" => $responseData["ns"],
                "title" => $responseData["title"],
                "timer" => array(
                    "eventOnExpire" => "edit",
                    "paramsOnExpire" => array(
                        "dataToSend"=>PageKeys::KEY_ID."=".$requestParams[PageKeys::KEY_ID]
                                        ."&".PageKeys::KEY_TO_REQUIRE."=true"
                                        .(PageKeys::KEY_REV?("&".PageKeys::KEY_REV."=".$requestParams[PageKeys::KEY_REV]):""),
                    ),
                    "eventOnCancel" => "cancel",                    
                    "paramsOnCancel" => array(
                        "dataToSend" => PageKeys::KEY_ID."=".$requestParams[PageKeys::KEY_ID]
                                        ."&".PageKeys::KEY_DO. "=leaveResource"
                                        .(PageKeys::KEY_REV?("&".PageKeys::KEY_REV."=".$requestParams[PageKeys::KEY_REV]):""),
                    ),
                    "timeout" => ($responseData["lockInfo"]["locker"]["time"]+WikiGlobalConfig::getConf("locktime")-time())*1000,
                ),
                "content" => array( //ALERTA / TODO [JOSEP] Canviar content per data                   
                    "text" => $responseData["content"],
                ),
            );
            if($requestParams[PageKeys::KEY_TO_REQUIRE]){
                $params["action"]="refresh";
                $params["content"]["requiring"] = array(
                        "message" => sprintf(WikiIocLangManager::getLang("requiring_message"), 
                                $requestParams[PageKeys::KEY_ID], 
                                $responseData["lockInfo"]["locker"]["name"],
                                date("d-m-Y H:i:s", $responseData["lockInfo"]["locker"]["time"]+WikiGlobalConfig::getConf("locktime"))),
    //                    "messageReplacements" => array("user" => "user", "resource" => "resource"),
                );
            }else{
                $params["action"]="dialog";
                $params["timer"]["timeout"]=0;
                $params["dialog"]= array(
                    "title" => WikiIocLangManager::getLang("requiring_dialog_title"),
                    "message" => sprintf(WikiIocLangManager::getLang("requiring_dialog_message"), 
                                            $requestParams[PageKeys::KEY_ID], 
                                            $responseData["lockInfo"]["locker"]["name"], 
                                            date("d-m-Y H:i:s", $responseData["lockInfo"]["locker"]["time"]+WikiGlobalConfig::getConf("locktime")),
                                            $responseData["lockInfo"]["locker"]["name"], 
                                            $requestParams[PageKeys::KEY_ID]),
                    "ok" => array(
                        "text" => WikiIocLangManager::getLang("yes"),
                    ),
                    "cancel" => array(
                        "text" => WikiIocLangManager::getLang("no"),
                    ),
                );
                $params["content"]["htmlForm"] = $responseData["htmlForm"];
                $params["info"] = $responseData["info"];
            }
            //$ajaxCmdResponseGenerator->addProcessFunction(TRUE, "ioc/dokuwiki/processRequiringTimer", $params);
            $ajaxCmdResponseGenerator->addRequiringDoc($params["id"], 
                    $params["ns"], $params["title"], $params["action"],
                    $params["timer"], $params["content"], $params["dialog"]);            
        } else {
            $recoverDrafts = [];
            $params = [];
            foreach ($responseData as $key => $value){
                if($key==PageKeys::KEY_RECOVER_DRAFT ||
                            $key==PageKeys::KEY_RECOVER_LOCAL_DRAFT){
                    $recoverDrafts[$key]=TRUE;
                }else if(
                            $key!="id" &&
                            $key!="ns" &&
                            $key!="title" &&
                            $key!="content" &&                        
                            $key!="draft" &&                        
                            $key!="meta" &&                        
                            $key!="rev" &&                        
                            $key!="htmlForm" &&                        
                            $key!="info" 
                        ){
                    $params[$key]=$value;
                }
            }
            
            $timer = array(
                "dialogOnExpire" => array(
                        "title" =>  WikiIocLangManager::getLang("expiring_dialog_title"),
                        "message" =>  WikiIocLangManager::getLang("expiring_dialog_message"),
                        "ok" => array(
                            "text" => WikiIocLangManager::getLang("expiring_dialog_yes"),
                        ),
                        "cancel" => array(
                            "text" => WikiIocLangManager::getLang("expiring_dialog_no"),
                        ),
                        "okContentEvent" => "save",
                        "okEventParams" => array(
                            PageKeys::KEY_ID => $requestParams[PageKeys::KEY_ID]
                        ),
                        "cancelContentEvent" => "cancel",
                        "cancelEventParams" => array(
                            PageKeys::KEY_ID => $requestParams[PageKeys::KEY_ID],
                            "extraDataToSend" => PageKeys::DISCARD_CHANGES."=true",
                        ),
                ),
                "timeout" => ($responseData["lockInfo"]["locker"]["time"]+WikiGlobalConfig::getConf("locktime")-120-time())*1000,
            );
            
            $ajaxCmdResponseGenerator->addWikiCodeDoc(
                $responseData['id'], $responseData['ns'],
                $responseData['title'], $responseData['content'], $responseData['draft'], $recoverDrafts,
                $responseData["htmlForm"], $params, $timer, $responseData['rev']
            );

            $meta = $responseData['meta'];
            $ajaxCmdResponseGenerator->addMetadata($responseData['id'], $meta);
            $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);

            
//            $params = [];
//
//            if ($responseData['locked'] === false || $responseData[PageKeys::KEY_LOCK_STATE] == 200) { // El fitxer està bloquejat
//                $params['locked'] = true;
//                $ajaxCmdResponseGenerator->addAlert(WikiIocLangManager::getLang('lockedByAlert')); // Alerta[Xavi] fent servir el lock state no tenim accés al nom de l'usuari que el bloqueja
//
//            } else {
//                $params['locked'] = false;
//            }
////            $params['locked'] = $responseData['locked'];
//
//            $params['readonly'] = $this->getPermission()->isReadOnly();
//
//
//            $recoverDrafts = [];
//
//            if (isset($responseData[PageKeys::KEY_RECOVER_DRAFT])) {
//                $recoverDrafts[PageKeys::KEY_RECOVER_DRAFT] = $responseData[PageKeys::KEY_RECOVER_DRAFT]==='true';
//            }
//
//            if (isset($responseData[PageKeys::KEY_RECOVER_LOCAL_DRAFT])) {
//                $recoverDrafts[PageKeys::KEY_RECOVER_LOCAL_DRAFT] = $responseData[PageKeys::KEY_RECOVER_LOCAL_DRAFT];
//            }
//
//            $ajaxCmdResponseGenerator->addWikiCodeDoc(
//                $responseData['id'], $responseData['ns'],
//                $responseData['title'], $responseData['content'], $responseData['draft'], $recoverDrafts,
//                $params, $responseData['rev']
//            );
//
//            $meta = $responseData['meta'];
//            $ajaxCmdResponseGenerator->addMetadata($responseData['id'], $meta);
//            $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
        }
    }
}
