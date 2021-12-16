<?php
/**
 * Description of New_pageResponseHandler
 * @author Eduardo Latorre Jarque <eduardo.latorre@gmail.com>
 */
if (!defined("DOKU_INC")) die();
require_once(DOKU_TPL_INCDIR.'cmd_response_handler/PageResponseHandler.php');
require_once(DOKU_TPL_INCDIR.'conf/cfgIdConstants.php');

class New_pageResponseHandler extends PageResponseHandler {

    function __construct() {
        parent::__construct(ResponseHandlerKeys::PAGE);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        parent::response($requestParams, $responseData, $ajaxCmdResponseGenerator);
        $ajaxCmdResponseGenerator->addAddItemTree(cfgIdConstants::TB_INDEX, $requestParams[AjaxKeys::KEY_ID]);
        if (preg_match("/wiki:user:.*:dreceres/", $requestParams[AjaxKeys::KEY_ID])){
            $action = $this->getModelManager()->getActionInstance("ShortcutsTaskListAction", WikiIocInfoManager::getInfo("client"));
            $dades = $action->get(['id' => $action->getNsShortcut()]);
            $this->shortcutsResponse($dades, $ajaxCmdResponseGenerator);
        }
    }

    private function shortcutsResponse($responseData, &$ajaxCmdResponseGenerator){
        $responseData['selected'] = TRUE;
        IocCommon::addResponseTab($responseData, $ajaxCmdResponseGenerator);
    }

}
