<?php
/**
 * Description of WikiIocResponseHandler
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_LIB_IOC')) define('DOKU_LIB_IOC', DOKU_INC . "lib/lib_ioc/");
require_once(DOKU_LIB_IOC . "ajaxcommand/AbstractResponseHandler.php");

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

        $id = ($responseData[ProjectKeys::KEY_ID]) ? $responseData[ProjectKeys::KEY_ID] : $responseData['info'][ProjectKeys::KEY_ID];

        if ($requestParams[ProjectKeys::PROJECT_TYPE] && !isset($responseData[ProjectKeys::KEY_CODETYPE])) {
            if (!$responseData[ProjectKeys::KEY_PROJECT_EXTRADATA][ProjectKeys::PROJECT_TYPE]) {
                //es una página de un proyecto pero (es raro) no tiene aún: $responseData[ProjectKeys::KEY_PROJECT_EXTRADATA]['projectType']
                $ajaxCmdResponseGenerator->addExtraContentStateResponse($id, ProjectKeys::PROJECT_TYPE, $requestParams[ProjectKeys::PROJECT_TYPE]);
            }

        } else if ($data['command'] !== 'notify'){  //S'ha de canviar aquesta condició per alguna cosa més genèrica que només englobi comandes de documents
            if($responseData['structure'] && $responseData['structure']['id']){
                $ajaxCmdResponseGenerator->addExtraContentStateResponse($responseData['structure']['id'], AjaxKeys::FORMAT, $this->getFormat());
            }
        }

        if ($requestParams[ProjectKeys::PROJECT_OWNER]) {
            $ajaxCmdResponseGenerator->addExtraContentStateResponse($id, ProjectKeys::PROJECT_OWNER, $requestParams[ProjectKeys::PROJECT_OWNER]);
            $ajaxCmdResponseGenerator->addExtraContentStateResponse($id, ProjectKeys::PROJECT_SOURCE_TYPE, $requestParams[ProjectKeys::PROJECT_SOURCE_TYPE]);
        }
        if ($responseData[ProjectKeys::KEY_GENERATED]){
            $ajaxCmdResponseGenerator->addExtraContentStateResponse($id, ProjectKeys::KEY_GENERATED, $responseData[ProjectKeys::KEY_GENERATED]);
        }
        if ($responseData[ProjectKeys::KEY_ID] && $responseData[ProjectKeys::KEY_CODETYPE] !== ProjectKeys::VAL_CODETYPE_REMOVE) {
            $value = ($responseData[AjaxKeys::KEY_ACTIVA_UPDATE_BTN] === "1"||$responseData[AjaxKeys::KEY_ACTIVA_UPDATE_BTN] >=1 ) ? "1" : "0";
            $ajaxCmdResponseGenerator->addExtraContentStateResponse($responseData[ProjectKeys::KEY_ID], "updateButton", $value);
        }

        if ($responseData[ProjectKeys::KEY_EXTRA_STATE]) {
            $stateId = $responseData[ProjectKeys::KEY_EXTRA_STATE][ProjectKeys::KEY_EXTRA_STATE_ID];
            $stateValue = $responseData[ProjectKeys::KEY_EXTRA_STATE][ProjectKeys::KEY_EXTRA_STATE_VALUE];
            $ajaxCmdResponseGenerator->addExtraContentStateResponse($responseData[ProjectKeys::KEY_ID], $stateId, $stateValue);
        }

        if ($responseData[AjaxKeys::KEY_ACTIVA_FTPSEND_BTN]){
            $ajaxCmdResponseGenerator->addExtraContentStateResponse($id, ProjectKeys::KEY_FTPSEND_BUTTON, $responseData[ProjectKeys::KEY_ACTIVA_FTPSEND_BTN]);
        }

        if ($responseData[ProjectKeys::KEY_USER_STATE]) {
            $ajaxCmdResponseGenerator->addUserState($responseData[ProjectKeys::KEY_USER_STATE]);
        }

        if ($responseData[ProjectKeys::KEY_LOGIN_RESULT] && $responseData[ProjectKeys::KEY_MOODLE_TOKEN]) {
            $ajaxCmdResponseGenerator->addLoginInfo($responseData[ProjectKeys::KEY_LOGIN_REQUEST],
                                                    $responseData[ProjectKeys::KEY_LOGIN_RESULT],
                                                    $responseData[ProjectKeys::KEY_USER_ID],
                                                    $responseData[ProjectKeys::KEY_MOODLE_TOKEN]);
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

    protected function getExtensionSelectors(&$value) {
        $this->getModelAdapter()->getExtensionSelectors($value);
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

    protected function getSmtpSelectors(&$value){
        $this->getModelAdapter()->getSmtpSelectors($value);
    }

    protected $defaultFormat = "undefined";

    protected function getFormat(){
        return IocCommon::getFormat($this->params[PageKeys::KEY_ID], $this->defaultFormat);
    }

    // Extraido de DokuModelAdapter
    protected function getMediaFileUpload() {
        global $NS, $AUTH, $JUMPTO;
        ob_start();
        media_tab_upload($NS, $AUTH, $JUMPTO);
        $strData = ob_get_clean();
        $tree_ret = ['id' => 'metaMediafileupload',
                     'title' => "Càrrega de fitxers",
                     'content' => $strData];
        return $tree_ret;
    }

    // Extraido de DokuModelAdapter
    public function getMediaTabFileOptions() {
        global $INPUT;

        $checkThumbs = "checked";
        $checkRows = "";
        if ($INPUT->str('list')) {
            if ($INPUT->str('list') == "rows") {
                $checkThumbs = "";
                $checkRows = "checked";
            }
        }
        ob_start();
        echo '<span style="font-weight: bold;">Visualització</span></br>';
        echo '<div style="margin-left:10px;">';
        echo '  <input type="radio" data-dojo-type="dijit/form/RadioButton" name="fileoptions" id="thumbs" value="thumbs" ' . $checkThumbs . '/>
                <label for="radioOne">Thumbnails</label> <br />';
        echo '  <input type="radio" data-dojo-type="dijit/form/RadioButton" name="fileoptions" id="rows" value="rows" ' . $checkRows . '/>
                <label for="radioTwo">Rows</label> <br/><br/></div>';
        $strData = ob_get_clean();
        return $strData;
    }

    // Extraido de DokuModelAdapter
    public function getMediaTabFileSort() {
        global $INPUT;
        $checkedNom = "checked";
        $checkedData = "";
        if ($INPUT->str('sort')) {
            if ($INPUT->str('sort') == "date") {
                $checkedNom = "";
                $checkedData = "checked";
            }
        }

        ob_start();
        echo '<span style="font-weight: bold;">Ordenació</span></br>';
        echo '<div style="margin-left:10px;">';
        echo '  <input type="radio" data-dojo-type="dijit/form/RadioButton" name="filesort" id="nom" value="name" ' . $checkedNom . '/>
                <label for="nom">Nom</label> <br />';
        echo '  <input type="radio" data-dojo-type="dijit/form/RadioButton" name="filesort" id="data" value="date" ' . $checkedData . '/>
                <label for="data">Data</label> <br/><br/></div>';
        $strData = ob_get_clean();
        return $strData;
    }

    // Extraido de DokuModelAdapter
    public function getMediaTabSearch() {
        global $NS;
        ob_start();
        echo '<span style="font-weight: bold;">Cerca</span></br>';
        echo '<div class="search" style="margin-left:10px;">';
        echo '<form accept-charset="utf-8" method="post"  id="dw__mediasearch">';
        echo '<div class="no">';
        echo '<p>';
        echo '<input type="text" id="mediaSearchq" placeholder = "Nom de fitxer" title="Cerca en: ' . $NS . '" class="edit" name="q">';
        echo '</label>';
        echo '<input type="submit" class="button" value="Filtrar" id="mediaSearchs">';
        echo '<input style="display:none" type="submit" class="button" value="Desfer filtre" id="mediaSearchr">';
        echo '</p>';
        echo '</div></form></div>';
        $strData = ob_get_clean();
        return $strData;
    }

    // Extraido de DokuModelAdapter
    // Omple la pestanya històric de la zona de metadades del mediadetails
    function mediaDetailsHistory($ns, $image) {
        global $NS, $IMG, $INPUT;
        $NS = $ns;
        $IMG = $image;

        ob_start();
        $first = $INPUT->int('first');
        html_revisions($first, $image);
        $content = ob_get_clean();

        // Substitució de l'id del form per fer-ho variable
        $patrones = ['/form id="page__revisions"/'];
        $sustituciones = ['form id="page__revisions_' . $image . '"'];
        $content = preg_replace($patrones, $sustituciones, $content);
        return $content;
    }

}
