<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of page_response_handler
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>, Xavier García <xaviergaro.dev@gmail.com>
 */

if (!defined("DOKU_INC")) {
    die();
}
if (!defined('DOKU_PLUGIN')) {
    define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
}
require_once(tpl_incdir() . 'cmd_response_handler/WikiIocResponseHandler.php');
require_once DOKU_PLUGIN . 'ajaxcommand/JsonGenerator.php';

class Edit_partialResponseHandler extends WikiIocResponseHandler
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

        if (isset($responseData['show_draft_conflict_dialog'])) { // ALERTA[Xavi] Aquest es el dialog que avisa que s'ha de seleccionar entre edició parcial i completa

            $this->addDraftConflictDialogResponse($responseData, $ajaxCmdResponseGenerator);

        } else if (isset($responseData['show_draft_dialog'])) {

            $this->addDraftDialogResponse($responseData, $ajaxCmdResponseGenerator);

        } else if (isset($responseData["codeType"])) {

            $ajaxCmdResponseGenerator->addCodeTypeResponse($responseData["codeType"]);

        } else {

            if ($responseData['structure']["locked"]) {

                $this->addRequiringDialogResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);

            } else {
                $responseData['structure']['readonly'] = $this->getPermission()->isReadOnly();

                if (isset($responseData[PageKeys::KEY_RECOVER_LOCAL_DRAFT])) {
                    $responseData['structure'][PageKeys::KEY_RECOVER_LOCAL_DRAFT] = $responseData[PageKeys::KEY_RECOVER_LOCAL_DRAFT];
                }

                $ajaxCmdResponseGenerator->addWikiCodeDocPartial($responseData['structure']);

            }


            // ALERTA[Xavi] Si no es fica això no funciona el doble click al chunks
            $this->addProcessContentResponse($responseData, $ajaxCmdResponseGenerator);


        }


        // ALERTA[Xavi] això cal quan no s'esta enviant ni document ni draft?
        $this->addMetadataResponse($responseData, $ajaxCmdResponseGenerator);
        $this->addInfoDataResponse($responseData, $ajaxCmdResponseGenerator);


    }

    /** TODO[Xavi] Aquesta funció s'ha d'heretar de EditResponseHandler **/
    protected function addDraftDialogResponse($responseData, &$cmdResponseGenerator)
    {
        $params = $this->generateDraftDialogParams($responseData);

        if (!WikiIocInfoManager::getInfo('locked')) {
            $this->addDraftDialog($responseData, $cmdResponseGenerator, $params);
        }
    }

    /**
     * @param $responseData
     * @param $cmdResponseGenerator
     * @param $params
     * @override // ALERTA[Xavi] Ara es idèntic al de EditResponseHandler
     */
    protected function addDraftDialog($responseData, &$cmdResponseGenerator, $params)
    {
        $cmdResponseGenerator->addDraftDialog(
            $responseData['id'],
            $responseData['ns'],
            $responseData['rev'],
            $params,
            WikiGlobalConfig::getConf("locktime")
        );
    }

    /**
     * @param $responseData
     * @return array
     * @override
     */
    protected function generateDraftDialogParams($responseData)
    {
        $params = [
            'title' => $responseData['title'],
            'content' => $responseData['content'],
            'lastmod' => $responseData['lastmod'],
            'type' => 'partial_document',
            'base' => 'lib/plugins/ajaxcommand/ajax.php?call=edit_partial',
            'selected' => $responseData['section_id'],
            'editing_chunks' => $responseData['editing_chunks']
        ];

        if ($responseData['local']) {
            $params['local'] = true;
        } else {
            $params['draft'] = $responseData['draft'];
        }

        return $params;
    }

    protected function addDraftConflictDialogResponse($responseData, &$cmdResponseGenerator)
    {
        // TODO[Xavi] Canviar al mateix sistema que el DraftProcessor i fer servir un unic processor per tots dos
        $cmdResponseGenerator->addProcessFunction(
            true,
            "ioc/dokuwiki/processDraftSelectionDialog",
            [
                'id' => $responseData['id'],
                'original_call' => $responseData['original_call'],
                'timeout' => WikiGlobalConfig::getConf("locktime")
            ]);
    }

    /** TODO[Xavi] Aquesta funció s'ha d'heretar de EditResponseHandler **/
    protected function addMetadataResponse($responseData, $cmdResponseGenerator)
    {
        if ($responseData['meta']) {
            $cmdResponseGenerator->addMetadata($responseData['id'], $responseData['meta']);
        }
    }

    /** TODO[Xavi] Aquesta funció s'ha d'heretar de EditResponseHandler **/
    protected function addInfoDataResponse($responseData, $cmdResponseGenerator)
    {
        if (!$responseData['info']) {
            $cmdResponseGenerator->addInfoDta($responseData['info']);
        }
    }

    protected function addProcessContentResponse($responseData, $cmdResponseGenerator)
    {
        // ALERTA[Xavi] Això es crida sempre, perquè? Que fa? <-- Afegeix les capçaleres, listeners a imatges, etc.

        $cmdResponseGenerator->addProcessDomFromFunction(
            $responseData['structure']['id'],
            TRUE,
            "ioc/dokuwiki/processContentPage",  //TODO configurable
            array(
                "ns" => $responseData['structure']['ns'],
                "editCommand" => "lib/plugins/ajaxcommand/ajax.php?call=edit",
                "detailCommand" => "lib/plugins/ajaxcommand/ajax.php?call=get_image_detail",
            )
        );
    }



//    protected function addRequiringDialogResponse($requestParams, $responseData, $cmdResponseGenerator)
//    {
//        // TODO[Xavi] Aquí va el codi similar al del EditResponseHandler amb el requiring
//         $cmdResponseGenerator->addAlert(WikiIocLangManager::getLang('lockedByAlert')); // Alerta[Xavi] fent servir el lock state no tenim accés al nom de l'usuari que el bloqueja
//
//    }


// ALERTA[Xavi] Duplicat al EditResponseHandler

    protected function addRequiringDialogResponse($requestParams, $responseData, $cmdResponseGenerator)
    {
        $params = $this->generateRequiringDialogParams($requestParams, $responseData);

        //TODO[Josep]: Generar un diàleg per preguntar si vol que l'avisin quan s'alliberi
        //$ajaxCmdResponseGenerator->addAlert(WikiIocLangManager::getLang('lockedByAlert')); // Alerta[Xavi] fent servir el lock state no tenim accés al nom de l'usuari que el bloqueja

        if ($requestParams[PageKeys::KEY_TO_REQUIRE]) {
            $this->addRequiringDialogParamsToParams($params, $requestParams, $responseData);
        } else {
            $this->addDialogParamsToParams($params, $requestParams, $responseData);
        }

        $this->addRequiringDoc($cmdResponseGenerator, $params);
    }

    protected function generateRequiringDialogParams($requestParams, $responseData)
    {
        $timer = $this->generateRequiringDialogTimer($requestParams, $responseData);

        $params = [
            "id" => $responseData["structure"]["id"],
            "ns" => $responseData["structure"]["ns"],
            "title" => $responseData["structure"]["title"],
            "timer" => $timer,
            "content" =>  $responseData["structure"], //ALERTA / TODO [JOSEP] Canviar content per data

        ];

        return $params;
    }

    protected function generateRequiringDialogTimer($requestParams, $responseData)
    {
        $timer = [
            "eventOnExpire" => "edit_partial",
            "paramsOnExpire" => [
                "dataToSend" => PageKeys::KEY_ID . "=" . $requestParams[PageKeys::KEY_ID]
                    . "&" . PageKeys::KEY_TO_REQUIRE . "=true"
                    . "&" . PageKeys::KEY_SECTION_ID . "=" . $requestParams[PageKeys::KEY_SECTION_ID]
                    . (PageKeys::KEY_REV ? ("&" . PageKeys::KEY_REV . "=" . $requestParams[PageKeys::KEY_REV]) : "")
                    . (PageKeys::KEY_IN_EDITING_CHUNKS ? ("&" . PageKeys::KEY_IN_EDITING_CHUNKS . "=" . $requestParams[PageKeys::KEY_IN_EDITING_CHUNKS]) : ""), // ALERTA[Xavi] S'haurà d'afegir la informació dels chunks en edició i el elected
            ],
            "eventOnCancel" => "cancel", //
            "paramsOnCancel" => [
                "dataToSend" => PageKeys::KEY_ID . "=" . $requestParams[PageKeys::KEY_ID]
                    . "&" . PageKeys::KEY_DO . "=leaveResource"
                    . (PageKeys::KEY_REV ? ("&" . PageKeys::KEY_REV . "=" . $requestParams[PageKeys::KEY_REV]) : ""),
            ],
//            "timeout" => ($responseData["lockInfo"]["locker"]["time"] + WikiGlobalConfig::getConf("locktime") - time() + 60) * 1000,
            "timeout" => $this->_getExpiringTime($responseData, 1),
        ];

        return $timer;
    }

    private function _getExpiringData($responseData, /*0 locker, 1 requirer*/
                                      $for = 0)
    {
        $addSecs = 0;
        if ($for == 1) {
            $addSecs = 60;
        }
        return $responseData["lockInfo"]["locker"]["time"] + WikiGlobalConfig::getConf("locktime") + $addSecs;
    }

    private function _getExpiringTime($responseData, /*0 locker, 1 requirer*/
                                      $for = 0)
    { // afegeix 1 minut si es tracta del requeridor o 0 minuts si es locker
        return ($this->_getExpiringData($responseData, $for) - time()) * 1000;
    }

    protected function addRequiringDialogParamsToParams(&$params, $requestParams, $responseData)
    {
        $params["action"] = "refresh";
        $params["content"]["requiring"] = [
            "message" => sprintf(WikiIocLangManager::getLang("requiring_message"),
                $requestParams[PageKeys::KEY_ID],
                $responseData["lockInfo"]["locker"]["name"],
//                date("d-m-Y H:i:s", $responseData["lockInfo"]["locker"]["time"] + WikiGlobalConfig::getConf("locktime") + 60)),
//            //                    "messageReplacements" => array("user" => "user", "resource" => "resource"),
                date("d-m-Y H:i:s", $this->_getExpiringData($responseData, 1))),
            //                    "messageReplacements" => array("user" => "user", "resource" => "resource"),
        ];
    }

    protected function addDialogParamsToParams(&$params, $requestParams, $responseData)
    {
        $params["action"] = "dialog";
        $params["timer"]["timeout"] = 0;
        $params["dialog"] = [
            "title" => WikiIocLangManager::getLang("requiring_dialog_title"),
            "message" => sprintf(WikiIocLangManager::getLang("requiring_dialog_message"),
                $requestParams[PageKeys::KEY_ID],
                $responseData["lockInfo"]["locker"]["name"],
//                date("d-m-Y H:i:s", $responseData["lockInfo"]["locker"]["time"] + WikiGlobalConfig::getConf("locktime") + 60),
                date("d-m-Y H:i:s", $this->_getExpiringData($responseData, 1)),
                $responseData["lockInfo"]["locker"]["name"],
                $requestParams[PageKeys::KEY_ID]),
            "ok" => [
                "text" => WikiIocLangManager::getLang("yes"),
            ],
            "cancel" => [
                "text" => WikiIocLangManager::getLang("no"),
            ],
        ];
//        $params["content"]["htmlForm"] = $responseData["htmlForm"]; // ALERTA[Xavi] Això només serveix pel editor complet, el parcial fa servir la estructura
        $params["info"] = $responseData["info"];
    }

    protected function addRequiringDoc($cmdResponseGenerator, $params)
    {
        //$ajaxCmdResponseGenerator->addProcessFunction(TRUE, "ioc/dokuwiki/processRequiringTimer", $params);
        $cmdResponseGenerator->addRequiringDoc(
            $params["id"],
            $params["ns"],
            $params["title"],
            $params["action"],
            $params["timer"],
            $params["content"],
        'structured',
            $params["dialog"]);
    }


}
