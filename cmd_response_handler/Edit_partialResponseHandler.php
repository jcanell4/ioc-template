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

        if (isset($responseData['full_draft'])) {
            $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processDraftSelectionDialog",
                [
                    'id' => $responseData['id'],
                    'original_call' =>$responseData['original_call']
                ]);
        } else if (isset($responseData['show_draft_dialog'])) {

            // TODO Xavi, refactoritzar per fer servir un array de params?
            $ajaxCmdResponseGenerator->addDraftDialog();
        } else {

            $ajaxCmdResponseGenerator->addWikiCodeDocPartial($responseData['structure']);
        }

        if (isset($responseData['info'])) {
            $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
        }

    }
}
