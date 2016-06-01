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


//        if ($responseData[PageKeys::KEY_LOCK_STATE] == 200) { //
////        if ($responseData['locked']) {
//            unset($responseData['show_draft_dialog']);
//            unset($responseData['show_draft_conflict_dialog']);
//
//        }


        if (isset($responseData['show_draft_conflict_dialog'])) { // ALERTA[Xavi] Aquest es el dialog que avisa que s'ha de seleccionar entre edició parcial i completa

            $this->addDraftConflictDialogResponse($responseData, $ajaxCmdResponseGenerator);

        } else if (isset($responseData['show_draft_dialog'])) {

            $this->addDraftDialogResponse($responseData, $ajaxCmdResponseGenerator);

        } else if ($responseData["locked"]) {
            // TODO[Xavi] Aquí va el codi similar al del EditResponseHandler amb el requiring


        } else {

            if ($responseData['structure']['locked'] || $responseData[PageKeys::KEY_LOCK_STATE] == 200) { // El fitxer està bloquejat
                $ajaxCmdResponseGenerator->addAlert(WikiIocLangManager::getLang('lockedByAlert')); // Alerta[Xavi] fent servir el lock state no tenim accés al nom de l'usuari que el bloqueja
            }

            $responseData['structure']['editing']['readonly'] = $this->getPermission()->isReadOnly();

            if (isset($responseData[PageKeys::KEY_RECOVER_LOCAL_DRAFT])) {
                $responseData['structure'][PageKeys::KEY_RECOVER_LOCAL_DRAFT] = $responseData[PageKeys::KEY_RECOVER_LOCAL_DRAFT];
            }

            $ajaxCmdResponseGenerator->addWikiCodeDocPartial($responseData['structure']);
        }

        $this->addMetadataResponse($responseData, $ajaxCmdResponseGenerator);

        $this->addInfoDataResponse($responseData, $ajaxCmdResponseGenerator);

        $this->addProcessContentResponse($responseData, $ajaxCmdResponseGenerator);

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
     * @override
     */
    protected function addDraftDialog($responseData, &$cmdResponseGenerator, $params)
    {
        $cmdResponseGenerator->addDraftDialog(
            $responseData['structure']['id'],
            $responseData['structure']['ns'],
            $responseData['structure']['rev'],
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
            'lastmod' => $responseData['structure']['date'],
            'type' => 'partial_document',
            'base' => 'lib/plugins/ajaxcommand/ajax.php?call=edit_partial',
            'original_call' => $responseData['original_call'],
        ];

        if ($responseData['local']) {
            $params['local'] = true;
            $params['selected'] = $responseData['original_call']['section_id'];
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

}
