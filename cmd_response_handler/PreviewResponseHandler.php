<?php
/**
 * Description of PrintResponseHandler
 *
 * @author josep
 */
require_once(tpl_incdir() . 'cmd_response_handler/PrintResponseHandler.php');

class PreviewResponseHandler extends PrintResponseHandler{
    function __construct($cmd = WikiIocResponseHandler::PREVIEW_ACTION){
        parent::__construct($cmd);
    }    
}
