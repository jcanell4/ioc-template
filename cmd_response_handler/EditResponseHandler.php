<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

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
        global $INFO;



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



        if ($responseData[PageKeys::KEY_LOCK_STATE] == 200) { //
//        if ($responseData['locked']) {
            unset($responseData['show_draft_dialog']);
        }


        if ($responseData['show_draft_dialog']) {

            // No s'envien les respostes convencionals

            $params = [
                'title' => $responseData['title'],
                'content' => $responseData['content'],
                'draft' => $responseData['draft'],
                'lastmod' => $this->getModelWrapper()->extractDateFromRevision($INFO['lastmod']),
                'type' => 'full_document',
                'base' => 'lib/plugins/ajaxcommand/ajax.php?call=edit&do=edit'
            ];

            if ($responseData['local']) {
                $params['local'] = true;
            }


            // TODO[Xavi] si està bloquejat no s'ha de mostrar el dialog
            // ALERTA[Xavi] Com que es fa la comprovació anteriorment, no hauria de arribar mati a aquí en aquest cas
            if (!$INFO['locked']) {
                $ajaxCmdResponseGenerator->addDraftDialog(
                    $responseData['id'],
                    $responseData['ns'],
                    $responseData['rev'],
                    $params

                );
            }

        } else {

            $params = [];

            if ($responseData['locked'] === false || $responseData[PageKeys::KEY_LOCK_STATE] == 200) { // El fitxer està bloquejat
                $params['locked'] = true;
                $ajaxCmdResponseGenerator->addAlert(WikiIocLangManager::getLang('lockedByAlert')); // Alerta[Xavi] fent servir el lock state no tenim accés al nom de l'usuari que el bloqueja

            } else {
                $params['locked'] = false;
            }
//            $params['locked'] = $responseData['locked'];

            $params['readonly'] = $this->getPermission()->isReadOnly();


            $recoverDrafts = [];

            if (isset($responseData[PageKeys::KEY_RECOVER_DRAFT])) {
                $recoverDrafts[PageKeys::KEY_RECOVER_DRAFT] = $responseData[PageKeys::KEY_RECOVER_DRAFT]==='true';
            }

            if (isset($responseData[PageKeys::KEY_RECOVER_LOCAL_DRAFT])) {
                $recoverDrafts[PageKeys::KEY_RECOVER_LOCAL_DRAFT] = $responseData[PageKeys::KEY_RECOVER_LOCAL_DRAFT];
            }

            $ajaxCmdResponseGenerator->addWikiCodeDoc(
                $responseData['id'], $responseData['ns'],
                $responseData['title'], $responseData['content'], $responseData['draft'], $recoverDrafts,
                $params, $responseData['rev']
            );

            $meta = $responseData['meta'];
            $ajaxCmdResponseGenerator->addMetadata($responseData['id'], $meta);
            $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
        }
    }
}
