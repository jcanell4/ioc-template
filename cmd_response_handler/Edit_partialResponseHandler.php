<?php
/**
 * Edit_partialResponseHandler
 * @author Josep Cañellas <jcanell4@ioc.cat>, Xavier García <xaviergaro.dev@gmail.com>
 */
if (!defined("DOKU_INC")) die();
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/WikiIocResponseHandler.php');
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/utility/ExpiringCalc.php');

class Edit_partialResponseHandler extends WikiIocResponseHandler
{
    function __construct() {
        parent::__construct(ResponseHandlerKeys::EDIT);
    }

    /**
     * @param string[] $requestParams
     * @param mixed $responseData
     * @param AjaxCmdResponseGenerator $ajaxCmdResponseGenerator
     *
     * @return void
     */
    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {

        if (isset($responseData['show_draft_conflict_dialog'])) { // ALERTA[Xavi] Aquest es el dialog que avisa que s'ha de seleccionar entre edició parcial i completa
            $this->addDraftConflictDialogResponse($responseData, $ajaxCmdResponseGenerator);

        } else if (isset($responseData['show_draft_dialog'])) {
            $this->addDraftDialogResponse($responseData, $ajaxCmdResponseGenerator);

        } else if (isset($responseData[ResponseHandlerKeys::KEY_CODETYPE])) {
            $ajaxCmdResponseGenerator->addCodeTypeResponse($responseData[ResponseHandlerKeys::KEY_CODETYPE]);

        } else {
            if ($responseData['structure']["locked"]) {
                $this->addRequiringDialogResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                $this->addRevisionListResponse($responseData, $ajaxCmdResponseGenerator);

            } else {
                $responseData['structure']['readonly'] = $this->isReadOnly($responseData);

                if (isset($responseData[PageKeys::KEY_RECOVER_LOCAL_DRAFT])) {
                    $responseData['structure'][PageKeys::KEY_RECOVER_LOCAL_DRAFT] = $responseData[PageKeys::KEY_RECOVER_LOCAL_DRAFT];
                }

                $this->addEditPartialDocumentResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                if ($requestParams[PageKeys::KEY_TO_REQUIRE]) {
                    $this->addRevisionListResponse($responseData, $ajaxCmdResponseGenerator);
                }
            }

            // ALERTA[Xavi] Si no es fica això no funciona el doble click al chunks
            $this->addProcessContentResponse($responseData, $ajaxCmdResponseGenerator);
        }

        // ALERTA[Xavi] això cal quan no s'esta enviant ni document ni draft?
        $this->addMetadataResponse($responseData, $ajaxCmdResponseGenerator);
        $this->addInfoDataResponse($responseData, $ajaxCmdResponseGenerator);


    }

    private function isReadOnly($responseData) {
        return $responseData['structure']['locked_before']? true : $this->getPermission()->isReadOnly();
    }

    /** TODO[Xavi] Aquesta funció s'ha d'heretar de EditResponseHandler **/
    protected function addDraftDialogResponse($responseData, &$cmdResponseGenerator) {
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
    protected function addDraftDialog($responseData, &$cmdResponseGenerator, $params) {
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
    protected function generateDraftDialogParams($responseData) {
        $params = [
            'title' => $responseData['title'],
            'content' => $responseData['content'],
            'lastmod' => $responseData['lastmod'],
            'type' => 'partial_document',
            'base' => 'lib/exe/ioc_ajax.php?call=edit_partial',
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
    protected function addMetadataResponse($responseData, &$cmdResponseGenerator)
    {
        if ($responseData['meta']) {
            $id = isset($responseData['id']) ? $responseData['id'] :  $responseData['structure']['id'];
            $cmdResponseGenerator->addMetadata($id, $responseData['meta']);
        }
    }

    /** TODO[Xavi] Aquesta funció s'ha d'heretar de EditResponseHandler **/
    protected function addRevisionListResponse($responseData, &$cmdResponseGenerator)
    {
        if (isset($responseData[PageKeys::KEY_REVISIONS]) && count($responseData[PageKeys::KEY_REVISIONS]) > 0) {

            $responseData[PageKeys::KEY_REVISIONS]['urlBase'] = "lib/exe/ioc_ajax.php?call=diff";
            $cmdResponseGenerator->addRevisionsTypeResponse($responseData['structure']['id'], $responseData[PageKeys::KEY_REVISIONS]);

        } else {
            $cmdResponseGenerator->addExtraMetadata(
                $responseData['structure']['id'],
                $responseData['structure']['id'] . '_revisions',
                'No hi ha revisions',
                "<h2> Aquest document no té revisions </h2>" //TODO[Xavi] localització
            );
        }
    }

    /** TODO[Xavi] Aquesta funció s'ha d'heretar de EditResponseHandler **/
    protected function addInfoDataResponse($responseData, &$cmdResponseGenerator)
    {
        if ($responseData['info']) {
            $cmdResponseGenerator->addInfoDta($responseData['info']);
        }
    }

    protected function addProcessContentResponse($responseData, &$cmdResponseGenerator)
    {
        // ALERTA[Xavi] Això es crida sempre, perquè? Que fa? <-- Afegeix les capçaleres, listeners a imatges, etc.

        $cmdResponseGenerator->addProcessDomFromFunction(
            $responseData['structure']['id'],
            TRUE,
            "ioc/dokuwiki/processContentPage",  //TODO configurable
            array(
                "ns" => $responseData['structure']['ns'],
                "editCommand" => "lib/exe/ioc_ajax.php?call=edit",
                "pageCommand" => "lib/exe/ioc_ajax.php?call=page",
                "detailCommand" => "lib/exe/ioc_ajax.php?call=get_image_detail",
            )
        );
    }

// ALERTA[Xavi] Duplicat al EditResponseHandler

    protected function addRequiringDialogResponse($requestParams, $responseData, &$cmdResponseGenerator)
    {
        $params = $this->generateRequiringDialogParams($requestParams, $responseData);

        if ($requestParams[PageKeys::KEY_TO_REQUIRE] || strlen($requestParams[PageKeys::KEY_IN_EDITING_CHUNKS]) > 0) {
            // ja hi ha chunks en edició
            $this->addRequiringDialogParamsToParams($params, $requestParams, $responseData);
            $responseData['info'] = $cmdResponseGenerator->addInfoToInfo($responseData['info'], $params['content']['requiring']['message']);
        } else {
            $this->addDialogParamsToParams($params, $requestParams, $responseData);
        }

        $this->addRequiringDoc($cmdResponseGenerator, $params);
    }

    protected function generateRequiringDialogParams($requestParams, $responseData) {
        $timer = $this->generateRequiringDialogTimer($requestParams, $responseData);

        $params = [
            "id" => $responseData["structure"]["id"],
            "ns" => $responseData["structure"]["ns"],
            "title" => $responseData["structure"]["title"],
            "timer" => $timer,
            "content" => $responseData["structure"], //ALERTA / TODO [JOSEP] Canviar content per data

        ];

        return $params;
    }

    protected function generateRequiringDialogTimer($requestParams, $responseData) {

        $chunks = $this->getEditingChunksIds($requestParams);
        $timer = [
            "eventOnExpire" => "edit_partial",
            "paramsOnExpire" => [
                "dataToSend" => PageKeys::KEY_ID . "=" . $requestParams[PageKeys::KEY_ID]
                    . "&" . PageKeys::KEY_TO_REQUIRE . "=true"
                    . "&" . PageKeys::KEY_SECTION_ID . "=" . $requestParams[PageKeys::KEY_SECTION_ID]
                    . "&" . PageKeys::KEY_IN_EDITING_CHUNKS . "=" . $chunks
                    . (PageKeys::KEY_REV ? ("&" . PageKeys::KEY_REV . "=" . $requestParams[PageKeys::KEY_REV]) : "")
            ],
            "eventOnCancel" => "cancel",
            "paramsOnCancel" => [
                "dataToSend" => PageKeys::KEY_ID . "=" . $requestParams[PageKeys::KEY_ID]
                    . "&" . PageKeys::KEY_DO . "=leaveResource"
                    . (PageKeys::KEY_REV ? ("&" . PageKeys::KEY_REV . "=" . $requestParams[PageKeys::KEY_REV]) : ""),
            ],
            "timeout" => $this->_getExpiringTime($responseData, 1),
        ];

        return $timer;
    }

    private function _getExpiringData($responseData, $for = 0) {
        //$for: 0 locker, 1 requirer
        return ExpiringCalc::getExpiringData($responseData, $for);
    }

    private function _getExpiringTime($responseData, $for = 0) {
        //$for: 0 locker, 1 requirer
        //afegeix 1 minut si es tracta del requeridor o 0 minuts si es locker
        return ExpiringCalc::getExpiringTime($responseData, $for);
    }

    protected function addRequiringDialogParamsToParams(&$params, $requestParams, $responseData)
    {
        $params["action"] = PageKeys::KEY_REFRESH;
        $params["content"]["requiring"] = [
            "message" => sprintf(WikiIocLangManager::getLang("requiring_message"),
                $requestParams[PageKeys::KEY_ID],
                $responseData["lockInfo"]["locker"]["name"],
                date("H:i:s", $this->_getExpiringData($responseData, 1))),
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
                date("H:i:s", $this->_getExpiringData($responseData, 1)),
                $responseData["lockInfo"]["locker"]["name"],
                $requestParams[PageKeys::KEY_ID]),
            "ok" => [
                "text" => WikiIocLangManager::getLang("yes"),
            ],
            "cancel" => [
                "text" => WikiIocLangManager::getLang("no"),
            ],
        ];
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
            'structured',
            $params["dialog"]);
    }


    private function addEditPartialDocumentResponse($requestParams, $responseData, &$cmdResponseGenerator)
    {
        $autosaveTimer = NULL;
        if (WikiGlobalConfig::getConf("autosaveTimer")) {
            $autosaveTimer = WikiGlobalConfig::getConf("autosaveTimer") * 1000;
        }


        if ($responseData['structure']['readonly']) {
            $timer = null;
        } else {
            $timer = $this->generateEditDocumentTimer($requestParams, $responseData);
        }


        $this->addSaveOrDiscardDialog($responseData, $responseData['structure']['id']);
        $this->addSaveOrDiscardDialogAll($responseData, $responseData['structure']['id']);




        $cmdResponseGenerator->addWikiCodeDocPartial(
            $responseData['structure'],
            $timer,
            NULL,
            $autosaveTimer,
            $responseData['extra']
        );
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
                "okContentEvent" => "save_partial_all",
                "okEventParams" => [
                    PageKeys::KEY_ID => $requestParams[PageKeys::KEY_ID],
                ],
                "cancelContentEvent" => "cancel",
                "cancelEventParams" => [
                    PageKeys::KEY_ID => $this->cleanId($requestParams[PageKeys::KEY_ID]),
                    "extraDataToSend" => PageKeys::KEY_KEEP_DRAFT . "=false&auto=true",
                    PageKeys::DISCARD_CHANGES => true
                ],
                "timeoutContentEvent" => "cancel",
                "timeoutParams" => [
                    PageKeys::KEY_ID => $this->cleanId($requestParams[PageKeys::KEY_ID]),
                    "extraDataToSend" => PageKeys::KEY_KEEP_DRAFT . "=true&auto=true",
                    PageKeys::DISCARD_CHANGES => true
                ],
            ],
            "timeout" => $this->_getExpiringTime($responseData, 0),
        ];

        return $timer;
    }


    private function getEditingChunksIds($requestParams)
    {
        $chunks = $requestParams[PageKeys::KEY_IN_EDITING_CHUNKS];


        if (strpos($requestParams[PageKeys::KEY_SECTION_ID], $requestParams[PageKeys::KEY_IN_EDITING_CHUNKS]) > -1) {
            // Ja es troba el seleccionat als chunks en edició

        } else {
            if (strlen($chunks) > 0) {
                $chunks .= ',' . $requestParams[PageKeys::KEY_SECTION_ID];
            } else {
                $chunks = $requestParams[PageKeys::KEY_SECTION_ID];
            }

        }

        return $chunks;
    }

    protected function cleanId($id)
    {
        return str_replace(":", "_", $id);
    }


    // ALERTA[Xavi] Duplicat al EditResponseHandler
    protected function addSaveOrDiscardDialog(&$responseData, $id) {
        $responseData['extra']['messageChangesDetected'] = WikiIocLangManager::getLang('cancel_editing_with_changes');
        $responseData['extra']['dialogSaveOrDiscard'] = $this->generateSaveOrDiscardDialog($id);;
    }

    protected function addSaveOrDiscardDialogAll(&$responseData, $id) {
        $responseData['extra']['messageChangesDetected'] = WikiIocLangManager::getLang('cancel_editing_with_changes');
        $responseData['extra']['dialogSaveOrDiscardAll'] = $this->generateSaveOrDiscardAllDialog($id);
    }


    // ALERTA[Xavi] Canviats els events dels botons per la cancel·lació compelta (quan es tanca la pestanya)
    protected function generateSaveOrDiscardAllDialog($id)
    {
        $dialogConfig = [
            'id' => $id,
            'title' => WikiIocLangManager::getLang("save_or_discard_dialog_title"),
            'message' => WikiIocLangManager::getLang("save_or_discard_dialog_message"),
            'closable' => false,
            'buttons' => [
                [
                    'id' => 'discard',
                    'description' => WikiIocLangManager::getLang("save_or_discard_dialog_dont_save"),
                    'buttonType' => 'fire_event',
                    'extra' => [
                        [
                            'eventType' => 'cancel',
                            'data' => [
                                'dataToSend' => [
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
                            'eventType' => 'save_partial_all',
                            'data' => [
                                    'dataToSend' => [
                                        'discardChanges' => true,
                                        'cancel'=>true,
                                        'keep_draft'=>false
                                    ]
                            ],
                            'observable' => $id
                        ],
                    ]
                ]
            ]
        ];

        return $dialogConfig;
    }

    protected function generateSaveOrDiscardDialog($id)
    {
        $dialogConfig = [
            'id' => $id,
            'title' => WikiIocLangManager::getLang("save_or_discard_dialog_title"),
            'message' => WikiIocLangManager::getLang("save_or_discard_dialog_message"),
            'closable' => false,
            'buttons' => [
                [
                    'id' => 'discard',
                    'description' => WikiIocLangManager::getLang("save_or_discard_dialog_dont_save"),
                    'buttonType' => 'fire_event',
                    'extra' => [
                        [
                            'eventType' => 'cancel_partial',
                            'data' => [
                                'dataToSend' => [
                                    'discardChanges' => true,
                                    'keep_draft' => false,
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
                            'eventType' => 'save_partial',
                            'data' => [
                                'dataToSend' =>[
                                    'cancel'=>true,
                                    'keep_draft'=>false
                                ]
                            ],
                            'observable' => $id
                        ],

                    ]
                ]
            ]
        ];

        return $dialogConfig;
    }
}
