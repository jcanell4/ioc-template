<?php
/**
 * EditResponseHandler
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined("DOKU_INC")) die();
require_once(DOKU_INC . 'lib/plugins/ajaxcommand/defkeys/ResponseHandlerKeys.php');
require_once(DOKU_INC . 'lib/plugins/ajaxcommand/defkeys/PageKeys.php');
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/WikiIocResponseHandler.php');
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/utility/ExpiringCalc.php');

class EditResponseHandler extends WikiIocResponseHandler
{
    function __construct() {
        parent::__construct(ResponseHandlerKeys::EDIT);
    }

    /**
     * @param string[] $requestParams
     * @param mixed $responseData
     * @param AjaxCmdResponseGenerator $ajaxCmdResponseGenerator
     * @return void
     */
    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {

        if ($responseData['show_draft_dialog']) {
            //Hi ha un esborrany. Es pregunta que cal fer.
            $this->addDraftDialogResponse($responseData, $ajaxCmdResponseGenerator);

        } else if (isset($responseData[ResponseHandlerKeys::KEY_CODETYPE])) {

            $ajaxCmdResponseGenerator->addCodeTypeResponse($responseData[ResponseHandlerKeys::KEY_CODETYPE]);

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

        $params["base"] = 'lib/exe/ioc_ajax.php?call=edit&do=edit';

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
        $isRev = isset($responseData['rev']);

        $autosaveTimer = NULL;
        if(WikiGlobalConfig::getConf("autosaveTimer")){
            $autosaveTimer = WikiGlobalConfig::getConf("autosaveTimer")*1000;
        }
        $recoverDrafts = $this->getRecoverDrafts($responseData);
        $editing = $this->generateEditDocumentParams($responseData);
        $editing['readonly'] = $this->getPermission()->isReadOnly() || $forceReadOnly || $isRev;

        if ($editing['readonly']) {
            $timer = null;
        } else {
            $timer = $this->generateEditDocumentTimer($requestParams, $responseData);
        }

        $this->addSaveOrDiscardDialog($responseData, $responseData['id']);
        $this->addEditDocumentCommand($responseData, $cmdResponseGenerator, $recoverDrafts, $editing, $timer, $autosaveTimer);
    }

    protected function addEditDocumentCommand($responseData, &$cmdResponseGenerator, $recoverDrafts, $editing, $timer, $autosaveTimer=NULL)
    {
        $cmdResponseGenerator->addWikiCodeDoc(
            $responseData['id'], $responseData['ns'],
            $responseData['title'], $responseData['content'], $responseData['draft'], $recoverDrafts,
            $responseData["htmlForm"], $editing, $timer, $responseData['rev'],
            $autosaveTimer, $responseData['extra'],
            $responseData['format']
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

        if ($requestParams[PageKeys::KEY_TO_REQUIRE]) {
            $this->addRequiringDialogParamsToParams($params, $requestParams, $responseData);
            $responseData['info'] = $cmdResponseGenerator->addInfoToInfo($responseData['info'], $params['content']['requiring']['message']);
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
                date("H:i:s", ExpiringCalc::getExpiringData($responseData, 1))),
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

    protected function addRequiringDoc(&$cmdResponseGenerator, $params) {
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

    protected function addSaveOrDiscardDialog(&$responseData, $id) {
        $responseData['extra']['messageChangesDetected'] = WikiIocLangManager::getLang('cancel_editing_with_changes');
        $responseData['extra']['dialogSaveOrDiscard'] = $this->generateSaveOrDiscardDialog($id);
    }

    protected function generateSaveOrDiscardDialog($id) {
        $dialogConfig = [
            'id' => $id,
            'title' => WikiIocLangManager::getLang("save_or_discard_dialog_title"),
            'message' => WikiIocLangManager::getLang("save_or_discard_dialog_message"), //'Vols desar els canvis?',
            'closable' => false,
            'buttons' => [
                [
                    'id' => 'discard',
                    'description' => WikiIocLangManager::getLang("save_or_discard_dialog_dont_save"), //'No desar',
                    'buttonType' => 'fire_event',
                    'extra' => [
                        [
                            'eventType' => 'cancel',
                            'data' => [
                                'dataToSend' =>[
                                    'discardChanges' => true,
                                    'keep_draft' => false
                                ]
                            ],
                            'observable' => $id
                        ]
                    ]
                ],
                [
                    'id' => 'save',
                    'description' => WikiIocLangManager::getLang("save_or_discard_dialog_save"), //'Desar',
                    'buttonType' => 'fire_event',
                    'extra' => [
                        [
                            'eventType' => 'save',
                            'data' => [
                                'dataToSend' =>[
                                    'cancel'=>true,
                                    'keep_draft'=>false
                                ]
                            ],
                            'observable' => $id,

                        ],
                    ]
                ]
            ]

        ];

        return $dialogConfig;
    }
}
