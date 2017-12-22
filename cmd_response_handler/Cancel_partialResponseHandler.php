<?php
/**
 * Cancel_partialResponseHandler
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>, Xavier García <xaviergaro.dev@gmail.com>
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', tpl_incdir());

require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/PageResponseHandler.php');


class Cancel_partialResponseHandler extends PageResponseHandler
{
    function __construct() {
        parent::__construct(WikiIocResponseHandler::SAVE);
    }
}
