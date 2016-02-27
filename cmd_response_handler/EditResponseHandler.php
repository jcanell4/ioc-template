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
        global $conf;
        global $INFO;


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


            // TODO[Xavi] si està bloquejat no s'ha de mostrar el dialog
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
            $params['locked'] = $responseData['locked']; // Nou, ho passem com a param -> true: està bloquejat
            $params['readonly'] = $this->getPermission()->isReadOnly();
 
            $ajaxCmdResponseGenerator->addWikiCodeDoc(
                $responseData['id'], $responseData['ns'],
                $responseData['title'], $responseData['content'], $responseData['draft'], $responseData['recover_draft'],
                $params, $responseData['rev']
            );

            $meta = $responseData['meta'];
            $ajaxCmdResponseGenerator->addMetadata($responseData['id'], $meta);
            $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);

        }


    }
}
