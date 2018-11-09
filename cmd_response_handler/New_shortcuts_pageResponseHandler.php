<?php
/**
 * [WARNING] [JOSEP] Mira este pobre y desgraciao código que nadie utiliza
 * Description of New_shortcuts_pageResponseHandler
 * @author Eduardo Latorre Jarque <eduardo.latorre@gmail.com>
 */
if (!defined("DOKU_INC")) die();
require_once(DOKU_INC . 'lib/plugins/ajaxcommand/defkeys/ResponseHandlerKeys.php');
require_once(DOKU_TPL_INCDIR.'cmd_response_handler/New_pageResponseHandler.php');
require_once(DOKU_TPL_INCDIR.'conf/cfgIdConstants.php');

class New_shortcuts_pageResponseHandler extends New_pageResponseHandler {

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        parent::response($requestParams, $responseData, $ajaxCmdResponseGenerator);

        $responseData['selected'] = TRUE;
        IocCommon::addResponseTab($responseData, $ajaxCmdResponseGenerator);
    }

}
