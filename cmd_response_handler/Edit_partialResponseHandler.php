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


        if ($responseData[PageKeys::KEY_LOCK_STATE] == 200) { //
//        if ($responseData['locked']) {
            unset($responseData['show_draft_dialog']);
            unset($responseData['show_full_draft_dialog']);

        }


        if (isset($responseData['show_full_draft_dialog'])) {
            $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processDraftSelectionDialog",
                [
                    'id' => $responseData['id'],
                    'original_call' => $responseData['original_call'],
                    'timeout' => $responseData['timeout'] // ALERTA[Xavi] El timeout s'hauria de passar per tots els dialegs?
                ]);

        } else if (isset($responseData['show_draft_dialog'])) {

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

            $ajaxCmdResponseGenerator->addDraftDialog(
                $responseData['structure']['id'],
                $responseData['structure']['ns'],
                $responseData['structure']['rev'],
                $params

            );

        } else {

            if ($responseData['locked'] === false || $responseData[PageKeys::KEY_LOCK_STATE] == 200) { // El fitxer està bloquejat
                $ajaxCmdResponseGenerator->addAlert(WikiIocLangManager::getLang('lockedByAlert')); // Alerta[Xavi] fent servir el lock state no tenim accés al nom de l'usuari que el bloqueja
            }

            $responseData['structure']['editing']['readonly'] = $this->getPermission()->isReadOnly();

            if (isset($responseData[PageKeys::KEY_RECOVER_LOCAL_DRAFT])){
                $responseData['structure'][PageKeys::KEY_RECOVER_LOCAL_DRAFT] = $responseData[PageKeys::KEY_RECOVER_LOCAL_DRAFT];
            }

            $ajaxCmdResponseGenerator->addWikiCodeDocPartial($responseData['structure']);
        }

        if (isset($responseData['info'])) {
            $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
        }


        $ajaxCmdResponseGenerator->addProcessDomFromFunction(
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
