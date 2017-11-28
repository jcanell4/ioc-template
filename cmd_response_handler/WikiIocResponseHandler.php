<?php
/**
 * Description of WikiIocResponseHandler
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined("DOKU_INC")) die();

abstract class WikiIocResponseHandler extends AbstractResponseHandler {
    const K_PROJECTTYPE = AjaxKeys::PROJECT_TYPE;

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
        if ($requestParams[self::K_PROJECTTYPE]) {
            if (!$responseData['projectExtraData'][self::K_PROJECTTYPE]) { //es una página de un proyecto
                $ajaxCmdResponseGenerator->addExtraContentStateResponse($responseData['id'], self::K_PROJECTTYPE, $requestParams[self::K_PROJECTTYPE]);
            }
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

    protected function getToolbarIds(&$value){
        $this->getModelWrapper()->getToolbarIds($value);
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
