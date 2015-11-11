<?php

/**
 * Description of SaveResponseHandler
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>, Xavier García <xaviergaro.dev@gmail.com>
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
require_once(tpl_incdir() . 'cmd_response_handler/PageResponseHandler.php');
//require_once(tpl_incdir().'cmd_response_handler/EditResponseHandler.php');
require_once DOKU_PLUGIN . 'ajaxcommand/JsonGenerator.php';

class Cancel_partialResponseHandler extends PageResponseHandler
{
    function __construct()
    {
        parent::__construct(WikiIocResponseHandler::SAVE);
    }

    protected function response($requestParams,
                                $responseData,
                                &$ajaxCmdResponseGenerator)
    {

        // Cancelació parcial
        $ajaxCmdResponseGenerator->addWikiCodeDocPartial(
            $responseData['structure'],
            true
        );


    }
}
