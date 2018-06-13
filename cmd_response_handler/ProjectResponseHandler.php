<?php
/**
 * ProjectResponseHandler: Construye los datos para la respuesta de la parte servidor en función de la petición
 * @culpable Rafael Claver
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_LIB_IOC')) define('DOKU_LIB_IOC', DOKU_INC.'lib/lib_ioc/');
if (!defined('DOKU_PLUGIN'))  define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
require_once(DOKU_LIB_IOC."wikiiocmodel/ProjectModelExceptions.php");
require_once(DOKU_PLUGIN."ajaxcommand/defkeys/ProjectKeys.php");
require_once(DOKU_PLUGIN."ajaxcommand/defkeys/LockKeys.php");
require_once(DOKU_TPL_INCDIR."conf/cfgIdConstants.php");
require_once(DOKU_TPL_INCDIR."cmd_response_handler/WikiIocResponseHandler.php");
require_once(DOKU_TPL_INCDIR."cmd_response_handler/utility/FormBuilder.php");
require_once(DOKU_TPL_INCDIR."cmd_response_handler/utility/ExpiringCalc.php");

class ProjectResponseHandler extends WikiIocResponseHandler {

    private $responseType = null; // ALERTA[Xavi] Afegit per poder discriminar el tipus de resposta sense afegir més paràmetres a les crides que generan els formularis.

    function __construct() {
        parent::__construct(ProjectKeys::KEY_PROJECT);
    }

    protected function postResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        parent::postResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
        if ($requestParams[ProjectKeys::PROJECT_TYPE] && !isset($responseData[ProjectKeys::KEY_CODETYPE])) {
            if (!$responseData['projectExtraData'][ProjectKeys::PROJECT_TYPE]) { //es una página de un proyecto
                $ajaxCmdResponseGenerator->addExtraContentStateResponse($responseData['id'], ProjectKeys::PROJECT_TYPE, $requestParams[ProjectKeys::PROJECT_TYPE]);
            }
        }
    }


    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        if (isset($responseData[ProjectKeys::KEY_CODETYPE])) {
            if ($responseData['info']) {
                $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
            }
            if ($responseData['alert']) {
                $ajaxCmdResponseGenerator->addAlert($responseData['alert']);
            }
            $ajaxCmdResponseGenerator->addCodeTypeResponse($responseData[ProjectKeys::KEY_CODETYPE]);
        }
        else {
            if (isset($requestParams[ProjectKeys::KEY_REV]) && $requestParams[ProjectKeys::KEY_DO] !== ProjectKeys::KEY_DIFF) {
                $requestParams[ProjectKeys::KEY_DO] = ProjectKeys::KEY_VIEW;
            }

            $this->responseType = $requestParams[ProjectKeys::KEY_DO];
            switch ($requestParams[ProjectKeys::KEY_DO]) {

                case ProjectKeys::KEY_DIFF:
                    $ajaxCmdResponseGenerator->addDiffProject($responseData['rdata'],
                                                              $responseData['projectExtraData']
                                                             );
                    //afegir la metadata de revisions com a resposta
                    if ($this->addMetaDataRevisions($requestParams, $responseData, $ajaxCmdResponseGenerator)) {
                        $ajaxCmdResponseGenerator->addRevisionsTypeResponse($responseData['rdata']['id'], $responseData[ProjectKeys::KEY_REV]);
                        $param = ['ns' => $responseData['rdata']['ns'],
                                  'pageCommand' => "lib/exe/ioc_ajax.php?call=project&do=view&projectType={$requestParams[ProjectKeys::KEY_PROJECT_TYPE]}"
                                 ];
                        $ajaxCmdResponseGenerator->addProcessDomFromFunction($responseData['rdata']['id'], true, "ioc/dokuwiki/processContentPage", $param);
                    }

                    if ($responseData['info']) {
                        $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
                    }
                    break;

                case ProjectKeys::KEY_CANCEL:
                    $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processSaving");
                    if (isset($responseData[ProjectKeys::KEY_CODETYPE])) {
                        $ajaxCmdResponseGenerator->addCodeTypeResponse($responseData[ProjectKeys::KEY_CODETYPE]);
                    }else {
                        $this->_responseViewResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                    }
                    break;

                case ProjectKeys::KEY_SAVE:
                    $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processSaving");
                    if (!$requestParams['cancel']) {
                        $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
                    }else {
                        $this->_responseViewResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                    }
                    break;

                case ProjectKeys::KEY_VIEW:
                    $this->_responseViewResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                    break;

                case ProjectKeys::KEY_EDIT:
                    if ($requestParams[ProjectKeys::KEY_HAS_DRAFT]){
                        $responseData['projectExtraData']['edit'] = 1;
                        $this->_responseViewResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                    }else {
                        switch ($responseData['lockInfo']['state']) {
                            case LockKeys::LOCKED:
                                //se ha obtenido el bloqueo, continuamos la edición
                                if ($requestParams[ProjectKeys::KEY_RECOVER_DRAFT]){
                                    $responseData['projectExtraData'][ProjectKeys::KEY_RECOVER_DRAFT] = TRUE;
                                }
                                $this->_addUpdateLocalDrafts($requestParams, $responseData, $ajaxCmdResponseGenerator);
                                $this->editResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                                $this->_addMetaDataRevisions($requestParams, $responseData, $ajaxCmdResponseGenerator);
                                break;
                            case LockKeys::REQUIRED:
                                //el recurso está bloqueado por otro usuario. Mostramos los datos del formulario y un cuadro de diálogo
                                $this->addRequireDialogResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                                break;
                            case LockKeys::LOCKED_BEFORE:
                                //el recurso está bloqueado por el propio usuario en otra sesión
                                $this->_responseViewResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                                break;
                        }
                    }
                    break;

                case ProjectKeys::KEY_CREATE:
                    $this->editResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                    break;

                case ProjectKeys::KEY_GENERATE:
                    if ($responseData['info'])
                        $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
                    break;

                case ProjectKeys::KEY_REVERT:
                    throw new Exception("Excepció a ProjectResponseHandler: [".ProjectKeys::KEY_REVERT."]\n"
                                        . "S'ha traslladat a: wikiocmodel/projects/documentation/command/responseHandler/ProjectRevertResponseHandler.php");

                case ProjectKeys::KEY_SAVE_PROJECT_DRAFT:
                    if ($responseData['lockInfo']){
                        $timeout = ExpiringCalc::getExpiringTime($responseData['lockInfo']['locker']['time'], 0);
                        $ajaxCmdResponseGenerator->addRefreshLock($responseData['id'], $requestParams['id'], $timeout);
                    }
                    if ($responseData['info']) {
                        $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
                    }else{
                        $ajaxCmdResponseGenerator->addCodeTypeResponse(0);
                    }
                    break;

                case ProjectKeys::KEY_REMOVE_PROJECT_DRAFT:
                    throw new Exception("Excepció a ProjectResponseHandler: [".ProjectKeys::KEY_REMOVE_PROJECT_DRAFT."]");

                default:
                    if ($responseData['info']) {
                        $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
                    }
                    if ($responseData['alert']) {
                        $ajaxCmdResponseGenerator->addAlert($responseData['alert']);
                    }

            }
        }

    }

    private function _responseViewResponse($requestParams, &$responseData, &$ajaxCmdResponseGenerator) {
        $this->_addUpdateLocalDrafts($requestParams, $responseData, $ajaxCmdResponseGenerator);
        $this->viewResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
        //afegir la metadata de revisions com a resposta
        $this->_addMetaDataRevisions($requestParams, $responseData, $ajaxCmdResponseGenerator);
    }

    private function _addUpdateLocalDrafts($requestParams, &$responseData, &$ajaxCmdResponseGenerator) {
        if ($responseData['drafts']) {
            $responseData['projectExtraData']['hasDraft'] = TRUE;
            $ajaxCmdResponseGenerator->addUpdateLocalDrafts($requestParams['id'], $responseData['drafts']);
        }
    }

    private function _addMetaDataRevisions($requestParams, &$responseData, &$ajaxCmdResponseGenerator) {
        if ($this->addMetaDataRevisions($requestParams, $responseData, $ajaxCmdResponseGenerator)) {
            $ajaxCmdResponseGenerator->addRevisionsTypeResponse($responseData[ProjectKeys::KEY_ID], $responseData[ProjectKeys::KEY_REV]);
        }
    }

    private function addMetaDataRevisions($requestParams, &$responseData, &$ajaxCmdResponseGenerator) {
        if (isset($responseData[ProjectKeys::KEY_REV]) && count($responseData[ProjectKeys::KEY_REV]) > 0) {
            $pType = $requestParams[ProjectKeys::KEY_PROJECT_TYPE];
            $responseData[ProjectKeys::KEY_REV]['call_diff'] = "project&do=diff&projectType=$pType";
            $responseData[ProjectKeys::KEY_REV]['call_view'] = "project&do=view&projectType=$pType";
            $responseData[ProjectKeys::KEY_REV]['urlBase'] = "lib/exe/ioc_ajax.php?call=".$responseData[ProjectKeys::KEY_REV]['call_diff'];
            return true;
        }else {
            $extramd = ['id' => $responseData[ProjectKeys::KEY_ID],
                        'idr' => $responseData[ProjectKeys::KEY_ID]."_revisions",
                        'txt' => "No hi ha revisions",
                        'html' => "<h3>Aquest projecte no té revisions</h3>"
                       ];
            $ajaxCmdResponseGenerator->addExtraMetadata($extramd['id'], $extramd['idr'], $extramd['txt'], $extramd['html']);
        }
    }

    protected function viewResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $id = $responseData['id'];
        $ns = $requestParams['id'];

        if (isset($requestParams['rev']))
            $title_rev = "- revisió (" . date("d.m.Y h:i:s", $requestParams['rev']) . ")";
        $title = "Projecte $ns $title_rev";

        $outValues = [];
        $form = $this->buildForm($id, $ns, $responseData['projectMetaData'], $responseData['projectViewData'], $outValues);

        if ($requestParams[ProjectKeys::KEY_DISCARD_CHANGES])
            $responseData['projectExtraData'][ResponseHandlerKeys::KEY_DISCARD_CHANGES] = $requestParams[ProjectKeys::KEY_DISCARD_CHANGES];

        if ($responseData['originalLastmod'])
            $responseData['projectExtraData']['originalLastmod'] = $responseData['originalLastmod'];

        $ajaxCmdResponseGenerator->addViewProject($id, $ns, $title, $form, $outValues,
                                                  $responseData['projectExtraData']);
        $this->addMetadataResponse($id, $ns, $requestParams[ProjectKeys::KEY_PROJECT_TYPE], $responseData['create'], $ajaxCmdResponseGenerator);
        if ($responseData['info']) {
            $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
        }
    }

    protected function editResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $id = $responseData['id'];
        $ns = $requestParams['id'];
        if (isset($requestParams['rev']))
            $title_rev = date("d-m-Y h:i:s", $requestParams['rev']);
        $title = "Projecte $ns $title_rev";
        $action = "lib/exe/ioc_ajax.php?call=project&do=save";

        $outValues = [];
        $form = $this->buildForm($id, $ns, $responseData['projectMetaData'], $responseData['projectViewData'], $outValues, $action);

        $this->addSaveOrDiscardDialog($responseData);
        $autosaveTimer = WikiGlobalConfig::getConf("autosaveTimer") ? WikiGlobalConfig::getConf("autosaveTimer") : NULL;
        $timer = $this->generateEditProjectTimer($requestParams, $responseData);

        /*Ahora está en viewResponse ya que solo se usa en el diálogo de comparar el original con el draft
        if ($responseData['originalLastmod'])
            $responseData['projectExtraData']['originalLastmod'] = $responseData['originalLastmod'];
        */

        $ajaxCmdResponseGenerator->addEditProject($id, $ns, $title, $form, $outValues,
                                                  $autosaveTimer, $timer,
                                                  $responseData['projectExtraData']);

        $this->addMetadataResponse($id, $ns, $requestParams[ProjectKeys::KEY_PROJECT_TYPE], $responseData['create'], $ajaxCmdResponseGenerator);
        if ($responseData['info']) {
            $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
        }
    }

    //[JOSEP] Alerta cal canviar la crida hardcode als botons per una que impliqui configuració
    protected function addMetadataResponse($projectId, $projectNs, $projectType, $rdCreate, &$ajaxCmdResponseGenerator) {
        $rdata['id'] = "metainfo_tree_".$projectId;
        $rdata['type'] = "meta_dokuwiki_ns_tree";
        $rdata['title'] = "Espai de noms del projecte";
        $rdata['standbyId'] = cfgIdConstants::BODY_CONTENT;
        $rdata['fromRoot'] = $projectNs;
        $rdata['treeDataSource'] = "lib/exe/ioc_ajaxrest.php/ns_tree_rest/";
        $rdata['typeDictionary'] = ["p" => [
                                            "urlBase" => "lib/exe/ioc_ajax.php?call=project",
                                            "params" => ['projectType','nsproject']
                                           ],
                                    "po" => [
                                            "urlBase" => "lib/exe/ioc_ajax.php?call=project",
                                            "params" => ['projectType','nsproject']
                                            ]
                                   ];
        $rdata['urlBase'] = "lib/exe/ioc_ajax.php?call=page";
        $rdata['processOnClickAndOpenOnClick'] = array('p', 'po');
        $rdata['buttons'][0] = ['id' => "projectMetaDataTreeZone_topRight_".$projectId,
                                'amdClass' => "ioc/gui/IocDialogButton",
                                'position' => "bottomRight",
                                'class' => "imageOnly",
                                'buttonParams' => [
                                    'iconClass' => "iocIconAdd",
                                    'id' => "projectMetaDataTreeZone_topRight_".$projectId,
                                    'dialogParams' => [
                                            'ns' => $projectNs,
                                            'fromRoot' => $projectNs,
                                            'projectType' => $projectType,
                                            'dialogType' => "project_new_element",
                                            'urlBase' => "lib/exe/ioc_ajax.php/",
                                            'treeDataSource' => "lib/exe/ioc_ajaxrest.php/ns_tree_rest/"
                                            ],
                                    'formParams' => [
                                            'EspaiDeNomsLabel' => "Espai de Noms",
                                            'ProjectesLabel' => "Selecció del tipus de projecte",
                                            'NouProjecteLabel' => "Nom del nou Projecte",
                                            'TemplatesLabel' => "Selecció de la plantilla",
                                            'NouDocumentLabel' => "Nom del nou Document",
                                            'NovaCarpetaLabel' => "Nom de la nova Carpeta",
                                            ]
                                    ],
                                'urlBase' => "lib/exe/ioc_ajax.php/"
                               ];
        if ($rdCreate[ProjectKeys::KEY_MD_CT_SUBPROJECTS]) {
            $rdata['buttons'][0]['buttonParams']['dialogParams']['call_project'] = "call=project&do=create";
            if ($rdCreate[ProjectKeys::KEY_MD_CT_SUBPROJECTS] === TRUE)
                $post = "true";
            elseif ($rdCreate[ProjectKeys::KEY_MD_CT_SUBPROJECTS] === FALSE)
                $post = "false";
            else
                $post = $rdCreate[ProjectKeys::KEY_MD_CT_SUBPROJECTS];
            $rdata['buttons'][0]['buttonParams']['dialogParams']['urlListProjects'] = "lib/exe/ioc_ajaxrest.php/list_projects_rest/$projectType/$projectNs/$post/";
        }
        if ($rdCreate[ProjectKeys::KEY_MD_CT_DOCUMENTS]) {
            $rdata['buttons'][0]['buttonParams']['dialogParams']['call_document'] = "call=project&do=new_document";
            if ($rdCreate[ProjectKeys::KEY_MD_CT_DOCUMENTS] === TRUE)
                $post = "true";
            elseif ($rdCreate[ProjectKeys::KEY_MD_CT_DOCUMENTS] === FALSE)
                $post = "false";
            else
                $post = $rdCreate[ProjectKeys::KEY_MD_CT_DOCUMENTS];
            $rdata['buttons'][0]['buttonParams']['dialogParams']['urlListTemplates'] = "lib/exe/ioc_ajaxrest.php/list_templates_rest/projectType/$projectType/template_list_type/$post/";
        }
        if ($rdCreate[ProjectKeys::KEY_MD_CT_FOLDERS]) {
            $rdata['buttons'][0]['buttonParams']['dialogParams']['call_folder'] = "call=project&do=new_folder";
        }

        $ajaxCmdResponseGenerator->addMetadata($projectId, [$rdata]);
    }

    /** El grid esta compuesto por 12 columnas
     *
     * @param string: $id, $ns, $action
     * @param array: $structure, obtenido de configMain.json
     * @param array: $view, obtenido de defaultView.json
     * @return array
     */
    protected function buildForm($id, $ns, $structure, $view, &$outValues, $action=NULL, $form_readonly=FALSE) {

        $this->mergeStructureToForm($structure, $view['fields'], $view['groups'], $view['definition'], $outValues);
        $aGroups = array();
        $builder = new FormBuilder($id, $action);

        $mainRow = FormBuilder::createRowBuilder()->setTitle('Projecte: ' . $ns);

        //Construye, como objetos, los grupos definidos en la vista y los enlaza jerarquicamente
        foreach ($view['groups'] as $keyGroup => $valGroup) {
            //Se obtienen los atributos del grupo
            $label = ($valGroup['label']) ? $valGroup['label'] : WikiIocLangManager::getLang('projectGroup')[$keyGroup];
            $frame = ($valGroup['frame']) ? true : false;
            $columns = ($valGroup['n_columns']) ? $valGroup['n_columns'] : $view['definition']['n_columns'];
            $pare = $valGroup['parent'];

            $rows = isset($valGroup['n_rows']) ? $valGroup['n_rows'] : null;

            if ($aGroups[$keyGroup]) {
                //El grupo ya ha sido creado con anterioridad
                if (!$aGroups[$keyGroup]->hasData()) {
                    //se añaden los atributos al grupo que fue creado sin ellos
                    $aGroups[$keyGroup]
                        ->setTitle($label)
                        ->setFrame($frame)
                        ->setColumns($columns)
                        ->setRows($rows);
                }
            }else {
                //Se crea un nuevo grupo principal
                $aGroups[$keyGroup] = FormBuilder::createGroupBuilder()
                    ->setTitle($label)
                    ->setFrame($frame)
                    ->setColumns($columns)
                    ->setRows($rows);
            }

            if (!$pare) {
                $mainRow->addElement($aGroups[$keyGroup]); //se añade como grupo principal
            }else {
                if (!$aGroups[$pare]) {
                    //si el grupo padre de este grupo todavía no está creado, se crea el grupo padre sin atributos
                    $aGroups[$pare] = FormBuilder::createGroupBuilder();
                }
                $aGroups[$pare]->addElement($aGroups[$keyGroup]); //se añade como elemento al grupo padre
            }
        }

        foreach ($view['fields'] as $keyField => $valField) {

            //combina los atributos y valores de los arrays de estructura y de vista
            if (!is_array($valField)) $valField = array($valField);
            $arrValues = array_merge((!is_array($structure[$keyField])) ? array($structure[$keyField]) : $structure[$keyField], $valField);

            if ($form_readonly && (!isset($arrValues['props']) || ($arrValues['props'] && $arrValues['props']['readonly']==FALSE)))
                $arrValues['props']['readonly'] = TRUE;

            //obtiene el grupo, al que pertenece este campo, de la vista o, si no lo encuentra, de la estructura
            $grupo = ($arrValues['group']) ? $arrValues['group'] : "main";
            if (!$aGroups[$grupo])
                throw new MissingGroupFormBuilderException($ns, "El grup \'$grupo\' no està definit a la vista.");

            //Se establecen los atributos del campo
            if ($arrValues['n_columns'])
                $columns = $arrValues['n_columns'];
            elseif ($arrValues['struc_chars'])
                $columns = $arrValues['struc_chars'] / $view['definition']['chars_column'];
            else
                $columns = $view['definition']['n_columns'];

            if (!$arrValues['n_rows']) {
                $arrValues['n_rows'] = 1;
                $rows = false;
            } else {
                $rows = $arrValues['n_rows'];
            }


            $label = ($arrValues['label']) ? $arrValues['label'] : WikiIocLangManager::getLang('projectLabelForm')[$keyField];

            $aGroups[$grupo]->addElement(FormBuilder::createFieldBuilder()
                ->setId($arrValues['id'])
                ->setLabel(($label != NULL) ? $label : $keyField)
                ->setType(($arrValues['type']) ? $arrValues['type'] : "text")
                ->addProps($arrValues['props'])
                ->addConfig($arrValues['config'])
                ->setColumns($columns)
                ->setRows($rows)
                ->setValue($arrValues['value'])
            );
        }

        $form = $builder->addElement($mainRow)
                    ->build();
        return $form;
    }

    protected function mergeStructureToForm($structure, &$viewFields, &$viewGroups, $viewDefinition, &$outValues, $mandatoryParent=false, $defaultParent =""){
        if (isset($structure['type'])){
            $ret = $this->mergeStructureDefaultToForm($structure, $viewFields, $viewGroups, $viewDefinition, $outValues, $mandatoryParent, $defaultParent);
        }else{
            $ret = $this->mergeStructureObjectToForm($structure, $viewFields, $viewGroups, $viewDefinition, $outValues, $mandatoryParent, $defaultParent);
        }
        return $ret;
    }

    protected function mergeStructureObjectToForm($structure, &$viewFields, &$viewGroups, $viewDefinition, &$outValues, $mandatoryParent=false, $defaultParent =""){
        $ret = false;
        foreach ($structure as $structureKey => $structureProperties){
            if($structureProperties['renderAsMultiField']){
                if(isset($structureProperties['value'])){
                    $discardValues = [];
                    $needGroup = $this->mergeStructureToForm($structureProperties['value'], $viewFields, $discardValues, $viewDefinition, $outValues, $structureProperties['mandatory'], $structureProperties['id']);
                    if($needGroup){
                        $viewGroups[$structureKey]['label'] = $structureKey;
                        $viewGroups[$structureKey]['frame'] = true;
                        $viewGroups[$structureKey]['n_columns'] = $viewDefinition['n_columns'];
                        $viewGroups[$structureKey]['parent'] = $defaultParent;
                        $ret = true;
                    }
                }
            }else{
                $ret = $this->mergeStructureToForm($structureProperties, $viewFields, $viewGroups, $viewDefinition, $outValues, $mandatoryParent, $defaultParent);
            }
        }
        return $ret;
    }

    protected function mergeStructureDefaultToForm($structureProperties, &$viewFields, &$viewGroups, $viewDefinition, &$outValues, $mandatoryParent=false, $defaultParent =""){
        $ret = false;
        if(array_key_exists($structureProperties['id'], $viewFields)){
            //merge
            $viewFields[$structureProperties['id']] = array_merge(array(), $structureProperties, $viewFields[$structureProperties['id']]);
//            if(!isset($viewFields[$structureProperties['id']]['value'])){
//                $viewFields[$structureProperties['id']]['value'] = $viewFields[$structureProperties['id']]['default'];
//            }
        }else{
            if($mandatoryParent || $structureProperties['mandatory']){
                $ret=true;
                $viewFields[$structureProperties['id']] = $structureProperties;
//                if(!isset($viewFields[$structureProperties['id']]['value'])){
//                    $viewFields[$structureProperties['id']]['value'] = $viewFields[$structureProperties['id']]['default'];
//                }
                $viewFields[$structureProperties['id']]['group']= $defaultParent;
            }
        }
        if(isset($viewFields[$structureProperties['id']]['defaultRow'])){
            if(!isset($viewFields[$structureProperties['id']]['config'])){
                $viewFields[$structureProperties['id']]['config']=[];
            }
            $viewFields[$structureProperties['id']]['config']['defaultRow']=$viewFields[$structureProperties['id']]['defaultRow'];

            //TODO[Xavi] Determinar quin es el valor que s'ha de guardar aquí!
        }

        // ALERTA! Comprovar el mode, no s'ha de renderitzar en edit
        if ($this->responseType !== "edit" && $structureProperties['config'] && $structureProperties['config']['renderable']) {
            $mode = $structureProperties['config']['mode'];
            $originalValue = $structureProperties['value'];
            $structureProperties['value'] = $this->renderContent($originalValue, $mode);
            $outValues[$structureProperties['id']] = $structureProperties['value'];
        }

        $outValues[$structureProperties['id']] = $structureProperties['value'];



        return $ret;
    }

    protected function addSaveOrDiscardDialog(&$responseData) {
        $responseData['projectExtraData']['messageChangesDetected'] = WikiIocLangManager::getLang('projects')['cancel_editing_with_changes'];
        $responseData['projectExtraData']['dialogSaveOrDiscard'] = $this->generateSaveOrDiscardDialog($responseData['id']);
    }

    protected function generateSaveOrDiscardDialog($id) {
        $dialogConfig = [
            'id' => $id,
            'title' => WikiIocLangManager::getLang("save_or_discard_dialog_title"),
            'message' => WikiIocLangManager::getLang("save_or_discard_dialog_message"), //'Vols desar els canvis?',
            'closable' => false,
            'buttons' => [
                [
                    'id' => "discard",
                    'description' => WikiIocLangManager::getLang("save_or_discard_dialog_dont_save"), //'No desar',
                    'buttonType' => "fire_event",
                    'extra' => [
                        [
                            'eventType' => "cancel_project",
                            'data' => [
                                'dataToSend' => [
                                    'cancel' => true,
                                    'discard_changes' => true,
                                    'keep_draft' => false
                                ]
                            ],
                            'observable' => $id
                        ]
                    ]
                ],
                [
                    'id' => "save",
                    'description' => WikiIocLangManager::getLang("save_or_discard_dialog_save"), //'Desar',
                    'buttonType' => "fire_event",
                    'extra' => [
                        [
                            'eventType' => "save_project",
                            'data' => [
                                'dataToSend' => [
                                    'cancel' => true,
                                    'keep_draft' => false
                                ]
                            ],
                            'observable' => $id,
                        ],
                    ]
                ]
            ]
        ];

        return $dialogConfig;
    }

    private function generateEditProjectTimer($requestParams, $responseData) {
        $timer = [
            "dialogOnExpire" => [
                "title" => WikiIocLangManager::getLang("expiring_dialog_title"),
                "message" => WikiIocLangManager::getLang("expiring_dialog_message"),
                "ok" => [
                    "text" => WikiIocLangManager::getLang("expiring_dialog_yes"),
                ],
                "cancel" => [
                    "text" => WikiIocLangManager::getLang("expiring_dialog_no"),
                ],
                "okContentEvent" => "save_project",
                "okEventParams" => [
                    ProjectKeys::KEY_ID => $requestParams[ProjectKeys::KEY_ID]
                ],
                "cancelContentEvent" => "cancel_project",
                "cancelEventParams" => [
                    ProjectKeys::KEY_ID => $requestParams[ProjectKeys::KEY_ID],
                    "extraDataToSend" => ProjectKeys::KEY_DISCARD_CHANGES."=true&" . ProjectKeys::KEY_KEEP_DRAFT."=false&auto=true",
                ],
                "timeoutContentEvent" => "cancel_project",
                "timeoutParams" => [
                    ProjectKeys::KEY_ID => $requestParams[ProjectKeys::KEY_ID],
                    "extraDataToSend" => ProjectKeys::KEY_DISCARD_CHANGES."=true&" . ProjectKeys::KEY_KEEP_DRAFT."=true&auto=true",
                ],
            ],
        ];
        $timer['timeout'] = ExpiringCalc::getExpiringTime($responseData['lockInfo']['time'], 0);

        return $timer;
    }

    private function addRequireDialogResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator) {

        $params = $this->_generateRequireDialogParams($requestParams, $responseData);

        if ($requestParams[PageKeys::KEY_TO_REQUIRE]) {
            $this->_addRequireDialogRefreshParams($params, $requestParams, $responseData);
            $message = $ajaxCmdResponseGenerator->generateInfo('warning', $params['content']['requiring']['message'], $requestParams[ProjectKeys::KEY_ID]);
            $responseData['info'] = $ajaxCmdResponseGenerator->addInfoToInfo($responseData['info'], $message);
        }else {
            $this->_addDialogParamsToParams($params, $requestParams, $responseData);
        }

        $this->_addRequireProject($ajaxCmdResponseGenerator, $params);
        $this->addMetadataResponse($params['id'], $params['ns'], $responseData['create'], $ajaxCmdResponseGenerator);
        if ($responseData['info']) {
            $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
        }
    }

    private function _generateRequireDialogParams($requestParams, $responseData) {
        $id = $responseData['id'];
        $ns = $requestParams['id'];
        $content = $this->buildForm($id, $ns, $responseData['projectMetaData'], $responseData['projectViewData']);
        $timer = $this->_generateRequireDialogTimer($requestParams, $responseData);
        $params = [
            'id' => $id,
            'ns' => $ns,
            'title' => "Projecte $ns",
            'content' => $content,
            'originalContent' => $responseData['projectMetaData'],
            'timer' => $timer,
            'extra' => $responseData['projectExtraData']
        ];
        return $params;
    }

    private function _generateRequireDialogTimer($requestParams, $responseData) {
        $rev = $requestParams[ProjectKeys::KEY_REV] ? "&".ProjectKeys::KEY_REV."=".$requestParams[ProjectKeys::KEY_REV] : "" ;
        $timer = [
            'eventOnExpire' => "edit_project",
            'paramsOnExpire' => [
                'dataToSend' => ProjectKeys::KEY_ID."=".$requestParams[ProjectKeys::KEY_ID]
                                . "&".ProjectKeys::KEY_TO_REQUIRE."=true"
                                . "&".ProjectKeys::KEY_PROJECT_TYPE ."=".$requestParams[ProjectKeys::KEY_PROJECT_TYPE]
                                . $rev
            ],
            'eventOnCancel' => "cancel_project",
            'paramsOnCancel' => [
                'dataToSend' => ProjectKeys::KEY_ID."=".$requestParams[ProjectKeys::KEY_ID]
                                . "&".ProjectKeys::KEY_LEAVERESOURCE."=true"
                                . "&".ProjectKeys::KEY_PROJECT_TYPE ."=".$requestParams[ProjectKeys::KEY_PROJECT_TYPE]
                                . $rev
            ],
            'timeout' => ExpiringCalc::getExpiringTime($responseData['lockInfo']['time'], 1),
        ];
        return $timer;
    }

    private function _addRequireDialogRefreshParams(&$params, $requestParams, $responseData) {
        $params['action'] = "refresh";
        $params['content']['requiring'] = [
            "message" => sprintf(WikiIocLangManager::getLang("requiring_message"),
                $requestParams[PageKeys::KEY_ID],
                $responseData['lockInfo']['name'],
                date("H:i:s", ExpiringCalc::getExpiringData($responseData['lockInfo']['time'], 1))),
        ];
    }

    private function _addDialogParamsToParams(&$params, $requestParams, $responseData) {
        $params['action'] = "dialog";
        $params['timer']['timeout'] = 0;
        $params['dialog'] = [
            'title' => WikiIocLangManager::getLang("require_dialog_title"),
            'message' => sprintf(WikiIocLangManager::getLang("require_dialog_message"),
                $requestParams[ProjectKeys::KEY_ID],
                $responseData['lockInfo']['name'],
                date("H:i:s", ExpiringCalc::getExpiringData($responseData['lockInfo']['time'], 1)),
                $responseData['lockInfo']['name'],
                $requestParams[ProjectKeys::KEY_ID]),
            'ok' => [
                'text' => WikiIocLangManager::getLang("yes"),
            ],
            'cancel' => [
                'text' => WikiIocLangManager::getLang("no"),
            ],
        ];
    }

    private function _addRequireProject(&$ajaxCmdResponseGenerator, $params) {
        $ajaxCmdResponseGenerator->addRequireProject(
                                        $params['id'],
                                        $params['ns'],
                                        $params['title'],
                                        $params['content'],
                                        $params['originalContent'],
                                        $params['action'],
                                        $params['timer'],
                                        $params['dialog'],
                                        $params['extra']
                                    );
    }

    protected function renderContent($content, $mode = 'xhtml') {
        $instructions = p_get_instructions($content);
        return p_render($mode,$instructions, $outInfo); // No fem res amb la info
    }

}
