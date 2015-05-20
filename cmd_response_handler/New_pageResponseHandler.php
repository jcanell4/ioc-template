<?php

/**
 * Description of New_pageResponseHandler
 *
 * @author Eduardo Latorre Jarque <eduardo.latorre@gmail.com>
 */

if (!defined("DOKU_INC")) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(tpl_incdir().'cmd_response_handler/PageResponseHandler.php');
require_once DOKU_PLUGIN.'ajaxcommand/JsonGenerator.php';

class New_pageResponseHandler extends pageResponseHandler
{
    function __construct()
    {
        parent::__construct(pageResponseHandler::PAGE);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator)
    {
        parent::response($requestParams, $responseData, $ajaxCmdResponseGenerator);
    }

}
