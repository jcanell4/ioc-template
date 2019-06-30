<?php
/**
 * Description of WikiIocResponseHandler
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_LIB_IOC')) define('DOKU_LIB_IOC', DOKU_INC . "lib/lib_ioc/");
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . "lib/plugins/");
require_once(DOKU_LIB_IOC . "ajaxcommand/AbstractResponseHandler.php");
require_once(DOKU_PLUGIN . "ajaxcommand/defkeys/ResponseHandlerKeys.php");

abstract class WikiIocResponseHandler extends AbstractResponseHandler {

    /**
     * Constructor que reb el nom del Command com argument.
     * @param string $cmd
     * @param string $subcmd . Tractament especial d'alguns $params['do'] de project_command
     */
    function __construct($cmd, $subcmd="") {
        parent::__construct($cmd, $subcmd);
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

        $projectId = ($responseData[ProjectKeys::KEY_ID]) ? $responseData[ProjectKeys::KEY_ID] : $responseData['info'][ProjectKeys::KEY_ID];

        if ($requestParams[ProjectKeys::PROJECT_TYPE] && !isset($responseData[ProjectKeys::KEY_CODETYPE])) {
            if (!$responseData[ProjectKeys::KEY_PROJECT_EXTRADATA][ProjectKeys::PROJECT_TYPE]) {
                //es una página de un proyecto pero (es raro) no tiene aún: $responseData[ProjectKeys::KEY_PROJECT_EXTRADATA]['projectType']
                $ajaxCmdResponseGenerator->addExtraContentStateResponse($projectId, ProjectKeys::PROJECT_TYPE, $requestParams[ProjectKeys::PROJECT_TYPE]);
            }

        } else if ($data['command'] !== 'notify'){  //S'ha de canviar aquesta condició per alguna cosa més genèrica que només englobi comandes de documents
            if($responseData['structure'] && $responseData['structure']['id']){
                $ajaxCmdResponseGenerator->addExtraContentStateResponse($responseData['structure']['id'], AjaxKeys::FORMAT, $this->getFormat());
            }
        }

        if ($requestParams[ProjectKeys::PROJECT_OWNER]) {
            $ajaxCmdResponseGenerator->addExtraContentStateResponse($projectId, ProjectKeys::PROJECT_OWNER, $requestParams[ProjectKeys::PROJECT_OWNER]);
            $ajaxCmdResponseGenerator->addExtraContentStateResponse($projectId, ProjectKeys::PROJECT_SOURCE_TYPE, $requestParams[ProjectKeys::PROJECT_SOURCE_TYPE]);
        }
        if ($responseData[ProjectKeys::KEY_GENERATED]){
            $ajaxCmdResponseGenerator->addExtraContentStateResponse($projectId, ProjectKeys::KEY_GENERATED, $responseData[ProjectKeys::KEY_GENERATED]);
        }
        if ($responseData[ProjectKeys::KEY_ID]) {
            $value = ($responseData[ProjectKeys::KEY_ACTIVA_UPDATE_BTN] === "1"||$responseData[ProjectKeys::KEY_ACTIVA_UPDATE_BTN] >=1 ) ? "1" : "0";
            $ajaxCmdResponseGenerator->addExtraContentStateResponse($responseData[ProjectKeys::KEY_ID], "updateButton", $value);
        }

        if ($responseData[ProjectKeys::KEY_ACTIVA_FTPSEND_BTN]){
            $ajaxCmdResponseGenerator->addExtraContentStateResponse($projectId, ProjectKeys::KEY_FTPSEND_BUTTON, $responseData[ProjectKeys::KEY_ACTIVA_FTPSEND_BTN]);
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
        $this->getModelAdapter()->getToolbarIds($value);
    }

  /**
   * Afegeix al paràmetre $value els selectors css que es fan servir per seleccionar els forms al html del pluguin ACL
   * @param array $value - array de paràmetres
   */
    protected function getAclSelectors(&$value){
        $this->getModelAdapter()->getAclSelectors($value);
    }

  /**
   * Afegeix al paràmetre $value els selectors css que es fan servir per seleccionar els forms al html del pluguin PLUGIN
   * @param array $value - array de paràmetres
   */
    protected function getPluginSelectors(&$value){
        $this->getModelAdapter()->getPluginSelectors($value);
    }

   /**
    * Afegeix al paràmetre $value els selectors css que es fan servir per seleccionar els forms al html del pluguin PLUGIN
    * @param array $value - array de paràmetres
    */
     protected function getConfigSelectors(&$value){
         $this->getModelAdapter()->getConfigSelectors($value);
     }

   /**
    * Afegeix al paràmetre $value els selectors css que es fan servir per seleccionar els forms al html del pluguin USERMANAGER
    * @param array $value - array de paràmetres
    */
     protected function getUserManagerSelectors(&$value){
         $this->getModelAdapter()->getUserManagerSelectors($value);
     }

   /**
    * Afegeix al paràmetre $value els selectors css que es fan servir per seleccionar els forms al html del pluguin REVERT
    * @param array $value - array de paràmetres
    */
     protected function getRevertSelectors(&$value){
         $this->getModelAdapter()->getRevertSelectors($value);
     }

   /**
    * Afegeix al paràmetre $value els selectors css que es fan servir per seleccionar els forms al html del pluguin LATEX
    * @param array $value - array de paràmetres
    */
     protected function getLatexSelectors(&$value){
         $this->getModelAdapter()->getLatexSelectors($value);
     }

     protected $defaultFormat = "undefined";

    protected function getFormat(){
        return IocCommon::getFormat($this->params[PageKeys::KEY_ID], $this->defaultFormat);
    }
}
