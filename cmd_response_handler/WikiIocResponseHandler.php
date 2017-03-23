<?php
/**
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'ajaxcommand/AbstractResponseHandler.php');
require_once(DOKU_PLUGIN.'ajaxcommand/defkeys/ProjectKeys.php');

// ESTO NO SIRVE DE NADA
//require_once(DOKU_INC . 'lib/plugins/ownInit/WikiGlobalConfig.php');
//function _tplIncDir(){
//    return WikiGlobalConfig::tplIncDir();
//}
//if (!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', _tplIncDir().'classes/');

abstract class WikiIocResponseHandler extends AbstractResponseHandler {
    const K_PROJECTTYPE = ProjectKeys::KEY_PROJECT_TYPE;

    function __construct($cmd) {
        parent::__construct($cmd);
    }

    private function _getDataEvent(&$ajaxCmdResponseGenerator, $requestParams=NULL, $responseData=NULL){
        $ret = array(
            "command" => $this->getCommandName(),
            "requestParams" => $requestParams,
            "responseData" => $responseData,
            "ajaxCmdResponseGenerator" => $ajaxCmdResponseGenerator,
        );
        return $ret;
    }

    protected function postResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $data = $this->_getDataEvent($ajaxCmdResponseGenerator, $requestParams, $responseData);
        $evt = new Doku_Event("WIOC_PROCESS_RESPONSE", $data);
        $evt->advise_after();
        unset($evt);
        $evt = new Doku_Event("WIOC_PROCESS_RESPONSE_".$this->getCommandName(), $data);
        $evt->advise_after();
        unset($evt);
        $ajaxCmdResponseGenerator->addSetJsInfo($this->getJsInfo());
        //omplir el extrastate si hi ha projectType a $requestParams==> $ajaxCmdResponseGenerator->addExtraContentStateResponse(id, stateKey, stateValue);
        if ($requestParams[self::K_PROJECTTYPE]) {
            $ajaxCmdResponseGenerator->addExtraContentStateResponse($requestParams['id'], $requestParams[self::K_PROJECTTYPE], $requestParams[self::K_PROJECTTYPE]);
        }
    }

    protected function preResponse($requestParams, &$ajaxCmdResponseGenerator) {
        $data = $this->_getDataEvent($ajaxCmdResponseGenerator, $requestParams);
        $evt = new Doku_Event("WIOC_PROCESS_RESPONSE", $data);
        $ret = $evt->advise_before();
        unset($evt);
        $evt = new Doku_Event("WIOC_PROCESS_RESPONSE_".$this->getCommandName(), $data);
        $ret = $ret.$evt->advise_before();
        unset($evt);
        return $ret;
    }

    protected function getJsInfo(){
        return $this->getModelWrapper()->getJsInfo();
    }

    protected function getToolbarIds(&$value){
        $this->getModelWrapper()->getToolbarIds($value);
//        $value["varName"] = "toolbar";
//        $value["toolbarId"] = "tool__bar";
//        $value["wikiTextId"] = "wiki__text";
//        $value["editBarId"] = "wiki__editbar";
//        $value["editFormId"] = "dw__editform";
//        $value["summaryId"] = "edit__summary";
    }

  /**
   * Afegeix al paràmetre $value els selectors css que es fan servir per seleccionar els forms al html del pluguin ACL
   * @param array $value - array de paràmetres
   */
    protected function getAclSelectors(&$value){
        $this->getModelWrapper()->getAclSelectors($value);
    }

  /**
   * Afegeix al paràmetre $value els selectors css que es fan servir per seleccionar els forms al html del pluguin PLUGIN
   * @param array $value - array de paràmetres
   */
    protected function getPluginSelectors(&$value){
        $this->getModelWrapper()->getPluginSelectors($value);
    }

   /**
    * Afegeix al paràmetre $value els selectors css que es fan servir per seleccionar els forms al html del pluguin PLUGIN
    * @param array $value - array de paràmetres
    */
     protected function getConfigSelectors(&$value){
         $this->getModelWrapper()->getConfigSelectors($value);
     }

   /**
    * Afegeix al paràmetre $value els selectors css que es fan servir per seleccionar els forms al html del pluguin USERMANAGER
    * @param array $value - array de paràmetres
    */
     protected function getUserManagerSelectors(&$value){
         $this->getModelWrapper()->getUserManagerSelectors($value);
     }

   /**
    * Afegeix al paràmetre $value els selectors css que es fan servir per seleccionar els forms al html del pluguin REVERT
    * @param array $value - array de paràmetres
    */
     protected function getRevertSelectors(&$value){
         $this->getModelWrapper()->getRevertSelectors($value);
     }

   /**
    * Afegeix al paràmetre $value els selectors css que es fan servir per seleccionar els forms al html del pluguin LATEX
    * @param array $value - array de paràmetres
    */
     protected function getLatexSelectors(&$value){
         $this->getModelWrapper()->getLatexSelectors($value);
     }

}
