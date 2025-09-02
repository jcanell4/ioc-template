<?php
/**
 * Description of SaveResponseHandler
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined("DOKU_INC")) die();
require_once(DOKU_TPL_INCDIR.'cmd_response_handler/PageResponseHandler.php');
require_once(DOKU_TPL_INCDIR.'conf/cfgIdConstants.php');
require_once(DOKU_TPL_INCDIR.'cmd_response_handler/utility/ExpiringCalc.php');

class SaveResponseHandler extends PageResponseHandler {

    function __construct() {
        parent::__construct(ResponseHandlerKeys::SAVE);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {

        if ($responseData['close']) {
            if (isset($requestParams['rev'])) {
                $ajaxCmdResponseGenerator->addAlert($responseData['info']['message']);
            }
            $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processCloseTab", $responseData['close']);

            if ($responseData['reload']) {
                $params = ['urlBase' => "lib/exe/ioc_ajax.php?",
                           'params' => $responseData['reload']
                          ];
                $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processRequest", $params);
            }
            return;
        }
        elseif ($responseData['code']===0 && !$responseData['deleted']) {
            if (isset($requestParams['rev'])) {
                $ajaxCmdResponseGenerator->addAlert($responseData['info']['message']);
            }else {
                $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
            }
            $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processSaving");

            if($responseData['lockInfo']){
                $timeout = ExpiringCalc::getExpiringTime($responseData);
                $ajaxCmdResponseGenerator->addRefreshLock($responseData[AjaxKeys::KEY_ID], $requestParams[AjaxKeys::KEY_ID], $timeout);
            }

            $params = array(
                'formId' => $responseData['formId'],
                'inputs' => $responseData['inputs']
            );

            if ($responseData['reload']) {
                $params = ['urlBase' => "lib/exe/ioc_ajax.php?",
                           'params' => $responseData['reload']
                          ];
                $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processRequest", $params);
            }

            //ALERTA[Xavi] El formulari només cal actualitzar-lo quan no es tanca la pestanya
            $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processSetFormInputValue", $params);
        }
        elseif ($responseData['deleted']){
            $ajaxCmdResponseGenerator->addRemoveContentTab($responseData[AjaxKeys::KEY_ID]);
            $ajaxCmdResponseGenerator->addAlert($responseData['info']['message']);
            $ajaxCmdResponseGenerator->addRemoveItemTree(cfgIdConstants::TB_INDEX, $requestParams[AjaxKeys::KEY_ID]);
        }
        else if ($responseData['code'] === "cancel_document") {
            $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processEvent", $responseData['cancel_params']);
        }
        else{
            $ajaxCmdResponseGenerator->addError($responseData['code'], $responseData['info']);
            $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processCancellation");
            parent::response($requestParams, $responseData['page'], $ajaxCmdResponseGenerator);
        }

        //CASOS ESPECIALS
        if (preg_match("/wiki:user:.*:dreceres/", $requestParams[AjaxKeys::KEY_ID])){
            if ($responseData['deleted']){
                $ajaxCmdResponseGenerator->addRemoveTab(cfgIdConstants::ZONA_NAVEGACIO,
                cfgIdConstants::TB_SHORTCUTS);
            }else{
// [WARNING] [JOSEP] Mira este pobre, huérfano y desgraciado código
// [Rafa] Me sabe grave pero parece ser que este código es un pobre huerfanito al que nadie llama
                $action = $this->getModelManager()->getActionInstance("ShortcutsTaskListAction", WikiIocInfoManager::getInfo("client"));
                $dades = $action->get(['id' => $action->getNsShortcut()]);

                $dades['selected'] = FALSE;
                IocCommon::addResponseTab($dades, $ajaxCmdResponseGenerator);
            }
        }
    }

}
