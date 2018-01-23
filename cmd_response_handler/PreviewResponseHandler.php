<?php
/**
 * Description of PrintResponseHandler
 * @author josep
 */
if (!defined("DOKU_INC")) die();
require_once(DOKU_INC . 'lib/plugins/ajaxcommand/defkeys/ResponseHandlerKeys.php');
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/PrintResponseHandler.php');

class PreviewResponseHandler extends PrintResponseHandler {

    function __construct($cmd = ResponseHandlerKeys::PREVIEW_ACTION){
        parent::__construct($cmd);
    }
}
