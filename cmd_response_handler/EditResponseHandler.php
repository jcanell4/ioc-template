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
require_once(tpl_incdir() . 'cmd_response_handler/utility/ExpiringCalc.php');

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

        if ($responseData['show_draft_dialog']) {
            //Hi ha un esborrany. Es pregunta que cal fer.
            $this->addDraftDialogResponse($responseData, $ajaxCmdResponseGenerator);

        } else if (isset($responseData["codeType"])) {

            $ajaxCmdResponseGenerator->addCodeTypeResponse($responseData["codeType"]);

        } else if ($responseData["locked"]) {
            //El recurs està bloquejat per un altre usuari. Es pregunta si cal fer-ne seguiment per saber 
            //quan acaba el bloqueig
            $this->addRequiringDialogResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
            $this->addInfoDataResponse($responseData, $ajaxCmdResponseGenerator);

        } else if ($responseData["locked_before"]) {
            //El recurs està bloquejat pel propi usuari en una altre sessió. De moment no es deixa editar 
            //fins que es tanqui la sessió oberta. Cal canviar-ho per una quadre de diàleg
            $this->addEditDocumentResponse($requestParams, $responseData, $ajaxCmdResponseGenerator, TRUE);
            $this->addMetadataResponse($responseData, $ajaxCmdResponseGenerator);
            $this->addInfoDataResponse($responseData, $ajaxCmdResponseGenerator);

        } else {
            //Tot OK. Es retornen les dades per editar el recurs
            $this->addEditDocumentResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
            $this->addMetadataResponse($responseData, $ajaxCmdResponseGenerator);
            $this->addInfoDataResponse($responseData, $ajaxCmdResponseGenerator);

        }
    }

    protected function addDraftDialogResponse($responseData, &$cmdResponseGenerator)
    {
        if (!WikiIocInfoManager::getInfo('locked')) {
            $params = $this->generateDraftDialogParams($responseData);
            $this->addDraftDialog($responseData, $cmdResponseGenerator, $params);
        }
    }

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

    protected function generateDraftDialogParams($responseData)
    {
        $excludeKeyList = ['show_draft_dialog', 'id', 'ns', 'rev'];
        $params = [];

        foreach ($responseData as $key => $value) {
            if (!in_array($key, $excludeKeyList)) {
                if ($key == "local" && $value) {
                    $params[$key] = $value;
                } else {
                    $params[$key] = $value;
                }
            }
        }

        $params["base"] = 'lib/plugins/ajaxcommand/ajax.php?call=edit&do=edit';

        return $params;
    }

    protected function addMetadataResponse($responseData, &$cmdResponseGenerator)
    {

        if ($responseData['meta']) {
            $cmdResponseGenerator->addMetadata($responseData['id'], $responseData['meta']);
        }
    }


    protected function addInfoDataResponse($responseData, &$cmdResponseGenerator)
    {
        if ($responseData['info']) {
            $cmdResponseGenerator->addInfoDta($responseData['info']);
        }
    }


    protected function addEditDocumentResponse($requestParams, $responseData, &$cmdResponseGenerator, $forceReadOnly=FALSE)
    {
        $autosaveTimer = NULL;
        if(WikiGlobalConfig::getConf("autosaveTimer")){
            $autosaveTimer = WikiGlobalConfig::getConf("autosaveTimer")*1000;
        }
        $recoverDrafts = $this->getRecoverDrafts($responseData);
        $editing = $this->generateEditDocumentParams($responseData);
        $editing['readonly'] = $this->getPermission()->isReadOnly() || $forceReadOnly;
        $timer = $this->generateEditDocumentTimer($requestParams, $responseData);
        $this->addEditDocumentCommand($responseData, $cmdResponseGenerator, $recoverDrafts, $editing, $timer, $autosaveTimer);
    }

    protected function addEditDocumentCommand($responseData, &$cmdResponseGenerator, $recoverDrafts, $editing, $timer, $autosaveTimer=NULL)
    {
        $cmdResponseGenerator->addWikiCodeDoc(
            $responseData['id'], $responseData['ns'],
            $responseData['title'], $responseData['content'], $responseData['draft'], $recoverDrafts,
            $responseData["htmlForm"], $editing, $timer, $responseData['rev'],
            $autosaveTimer
        );
    }

    protected function getRecoverDrafts($responseData)
    {
        $recoverKeys = [PageKeys::KEY_RECOVER_DRAFT, PageKeys::KEY_RECOVER_LOCAL_DRAFT];
        $recoverDrafts = [];

        foreach ($recoverKeys as $key) {
            if (isset($responseData[$key]) && $responseData[$key] === true) {
                $recoverDrafts[$key] = TRUE;
            }
        }

        return $recoverDrafts;
    }

    protected function generateEditDocumentParams($responseData)
    {
        $excludeKeyList = [PageKeys::KEY_RECOVER_DRAFT, PageKeys::KEY_RECOVER_LOCAL_DRAFT, 'id', 'ns', 'rev', 'title',
            'content', 'draft', 'meta', 'htmlForm', 'info'];

        $params = [];
        foreach ($responseData as $key => $value) {
            if (!in_array($key, $excludeKeyList)) {
                $params[$key] = $value;
            }
        }

        return $params;
    }

    private function generateEditDocumentTimer($requestParams, $responseData)
    {
        $timer = [
            "dialogOnExpire" => [
                "title" => WikiIocLangManager::getLang("expiring_dialog_title"),
                "message" => WikiIocLangManager::getLang("expiring_dialog_message"),
                "ok" => [
                    "text" => WikiIocLangManager::getLang("expiring_dialog_yes"),
                ],
                "cancel" => [
                    "text" => WikiIocLangManager::getLang("expiring_dialog_no"),
                ],
                "okContentEvent" => "save",
                "okEventParams" => [
                    PageKeys::KEY_ID => $requestParams[PageKeys::KEY_ID]
                ],
                "cancelContentEvent" => "cancel",
                "cancelEventParams" => [
                    PageKeys::KEY_ID => $requestParams[PageKeys::KEY_ID],
                    "extraDataToSend" => PageKeys::DISCARD_CHANGES . "=true&" . PageKeys::KEY_KEEP_DRAFT . "=false&auto=true",
                ],
                "timeoutContentEvent" => "cancel",
                "timeoutParams" => [
                    PageKeys::KEY_ID => $requestParams[PageKeys::KEY_ID],
                    "extraDataToSend" => PageKeys::DISCARD_CHANGES . "=true&" . PageKeys::KEY_KEEP_DRAFT . "=true&auto=true",
                ],
            ],
//            "timeout" => ($responseData["lockInfo"]["locker"]["time"] + WikiGlobalConfig::getConf("locktime") - time()) * 1000,
            "timeout" => ExpiringCalc::getExpiringTime($responseData, 0),
        ];

        return $timer;
    }

    protected function addRequiringResponse($requestParams, $responseData, &$cmdResponseGenerator){
        $params = [
            "id" => $responseData["id"],
            "ns" => $responseData["ns"],
            "title" => $responseData["title"],
            "content" => [ //ALERTA / TODO [JOSEP] Canviar content per data
                "text" => $responseData["content"],
            ],
        ];
        $this->addRequiringDoc($cmdResponseGenerator, $params);
    }
    
    protected function addRequiringDialogResponse($requestParams, $responseData, &$cmdResponseGenerator)
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
            "id" => $responseData["id"],
            "ns" => $responseData["ns"],
            "title" => $responseData["title"],
            "timer" => $timer,
            "content" => [ //ALERTA / TODO [JOSEP] Canviar content per data
                "text" => $responseData["content"],
            ],
        ];

        return $params;
    }

    protected function generateRequiringDialogTimer($requestParams, $responseData)
    {
        $timer = [
            "eventOnExpire" => "edit",
            "paramsOnExpire" => [
                "dataToSend" => PageKeys::KEY_ID . "=" . $requestParams[PageKeys::KEY_ID]
                    . "&" . PageKeys::KEY_TO_REQUIRE . "=true"
                    . (PageKeys::KEY_REV ? ("&" . PageKeys::KEY_REV . "=" . $requestParams[PageKeys::KEY_REV]) : ""),
            ],
            "eventOnCancel" => "cancel",
            "paramsOnCancel" => [
                "dataToSend" => PageKeys::KEY_ID . "=" . $requestParams[PageKeys::KEY_ID]
                    . "&" . PageKeys::KEY_DO . "=leaveResource"
                    . (PageKeys::KEY_REV ? ("&" . PageKeys::KEY_REV . "=" . $requestParams[PageKeys::KEY_REV]) : ""),
            ],
//            "timeout" => ($responseData["lockInfo"]["locker"]["time"] + WikiGlobalConfig::getConf("locktime") - time() + 60) * 1000,
            "timeout" => ExpiringCalc::getExpiringTime($responseData, 1),
        ];

        return $timer;
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
                date("H:i:s", ExpiringCalc::getExpiringData($responseData, 1))),
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
                date("H:i:s", ExpiringCalc::getExpiringData($responseData, 1)),
                $responseData["lockInfo"]["locker"]["name"],
                $requestParams[PageKeys::KEY_ID]),
            "ok" => [
                "text" => WikiIocLangManager::getLang("yes"),
            ],
            "cancel" => [
                "text" => WikiIocLangManager::getLang("no"),
            ],
        ];
        $params["content"]["htmlForm"] = $responseData["htmlForm"];
        $params["info"] = $responseData["info"];
    }

    protected function addRequiringDoc(&$cmdResponseGenerator, $params)
    {
        //$ajaxCmdResponseGenerator->addProcessFunction(TRUE, "ioc/dokuwiki/processRequiringTimer", $params);
        $cmdResponseGenerator->addRequiringDoc(
            $params["id"],
            $params["ns"],
            $params["title"],
            $params["action"],
            $params["timer"],
            $params["content"],
            'full',
            $params["dialog"]);
    }

}
