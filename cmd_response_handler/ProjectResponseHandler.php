<?php
/**
 * ProjectResponseHandler: Construye los datos para la respuesta de la parte servidor en función de la petición
 * @culpable Rafael Claver
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_PLUGIN'))  define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
require_once(DOKU_PLUGIN."ajaxcommand/defkeys/ProjectKeys.php");
require_once(DOKU_PLUGIN."wikiiocmodel/projects/documentation/DocumentationModelExceptions.php");
require_once(DOKU_TPL_INCDIR."conf/cfgIdConstants.php");
require_once(DOKU_TPL_INCDIR."cmd_response_handler/WikiIocResponseHandler.php");
require_once(DOKU_TPL_INCDIR."cmd_response_handler/utility/FormBuilder.php");

class ProjectResponseHandler extends WikiIocResponseHandler {

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
            $ajaxCmdResponseGenerator->addCodeTypeResponse($responseData[ProjectKeys::KEY_CODETYPE]);
        }
        else {
            if (isset($requestParams['rev']) && $requestParams[ProjectKeys::KEY_DO] !== ProjectKeys::KEY_DIFF) {
                $requestParams[ProjectKeys::KEY_DO] = ProjectKeys::KEY_VIEW;
            }

            $extramd = ['id' => $responseData['id'],
                        'idr' => $responseData['id']."_revisions",
                        'txt' => "No hi ha revisions",
                        'html' => "<h3>Aquest projecte no té revisions</h3>"
                       ];

            switch ($requestParams[ProjectKeys::KEY_DO]) {

                case ProjectKeys::KEY_DIFF:
                    $ajaxCmdResponseGenerator->addDiffProject($responseData['rdata'],
                                                              $responseData['projectExtraData']
                                                             );
                    //afegir la metadata de revisions com a resposta
                    if (isset($responseData[ProjectKeys::KEY_REV]) && count($responseData[ProjectKeys::KEY_REV]) > 0) {
                        $urlBase = "lib/exe/ioc_ajax.php?call=";
                        $responseData[ProjectKeys::KEY_REV]['call_diff'] = "project&do=diff&projectType={$requestParams[ProjectKeys::KEY_PROJECT_TYPE]}";
                        $responseData[ProjectKeys::KEY_REV]['call_view'] = "project&do=edit&projectType={$requestParams[ProjectKeys::KEY_PROJECT_TYPE]}";
                        $responseData[ProjectKeys::KEY_REV]['urlBase'] = $urlBase.$responseData[ProjectKeys::KEY_REV]['call_diff'];
                        $ajaxCmdResponseGenerator->addRevisionsTypeResponse($responseData['rdata']['id'], $responseData[ProjectKeys::KEY_REV]);
                        $param = ['ns' => $responseData['rdata']['ns'],
                                  'pageCommand' => $urlBase."project&do=view&projectType={$requestParams[ProjectKeys::KEY_PROJECT_TYPE]}"
                                 ];
                        $ajaxCmdResponseGenerator->addProcessDomFromFunction($responseData['rdata']['id'], true, "ioc/dokuwiki/processContentPage", $param);
                    }else {
                        $ajaxCmdResponseGenerator->addExtraMetadata($extramd['id'], $extramd['idr'], $extramd['txt'], $extramd['html']);
                    }

                    if ($responseData['info']) {
                        $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
                    }
                    break;

                case ProjectKeys::KEY_VIEW:
                    if ($responseData['drafts']) {
                        $responseData['hasDraft'] = TRUE;
                        $ajaxCmdResponseGenerator->addUpdateLocalDrafts($requestParams['id'], $responseData['drafts']);
                    }

                    $this->viewResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                    //afegir la metadata de revisions com a resposta
                    if (isset($responseData[ProjectKeys::KEY_REV]) && count($responseData[ProjectKeys::KEY_REV]) > 0) {
                        $responseData[ProjectKeys::KEY_REV]['call_diff'] = "project&do=diff&projectType={$requestParams[ProjectKeys::KEY_PROJECT_TYPE]}";
                        $responseData[ProjectKeys::KEY_REV]['call_view'] = "project&do=view&projectType={$requestParams[ProjectKeys::KEY_PROJECT_TYPE]}";
                        $responseData[ProjectKeys::KEY_REV]['urlBase'] = "lib/exe/ioc_ajax.php?call=".$responseData[ProjectKeys::KEY_REV]['call_diff'];
                        $ajaxCmdResponseGenerator->addRevisionsTypeResponse($responseData['id'], $responseData[ProjectKeys::KEY_REV]);
                    }else {
                        $ajaxCmdResponseGenerator->addExtraMetadata($extramd['id'], $extramd['idr'], $extramd['txt'], $extramd['html']);
                    }
                    break;

                case ProjectKeys::KEY_EDIT:
                    if ($responseData['drafts']) {
                        $responseData['hasDraft'] = TRUE;
                        $ajaxCmdResponseGenerator->addUpdateLocalDrafts($requestParams['id'], $responseData['drafts']);
                    }

                    $this->editResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                    //afegir la metadata de revisions com a resposta
                    if (isset($responseData[ProjectKeys::KEY_REV]) && count($responseData[ProjectKeys::KEY_REV]) > 0) {
                        $responseData[ProjectKeys::KEY_REV]['call_diff'] = "project&do=diff&projectType={$requestParams[ProjectKeys::KEY_PROJECT_TYPE]}";
                        $responseData[ProjectKeys::KEY_REV]['call_view'] = "project&do=edit&projectType={$requestParams[ProjectKeys::KEY_PROJECT_TYPE]}";
                        $responseData[ProjectKeys::KEY_REV]['urlBase'] = "lib/exe/ioc_ajax.php?call=".$responseData[ProjectKeys::KEY_REV]['call_diff'];
                        $ajaxCmdResponseGenerator->addRevisionsTypeResponse($responseData['id'], $responseData[ProjectKeys::KEY_REV]);
                    }else {
                        $ajaxCmdResponseGenerator->addExtraMetadata($extramd['id'], $extramd['idr'], $extramd['txt'], $extramd['html']);
                    }
                    break;

                case ProjectKeys::KEY_SAVE:
                    $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processSaving");
                    $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
                    break;

                case ProjectKeys::KEY_CREATE:
                    $this->editResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                    break;

                case ProjectKeys::KEY_GENERATE:
                    if ($responseData['info'])
                        $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
                    break;

                case ProjectKeys::KEY_CANCEL:
                    if (isset($responseData[ProjectKeys::KEY_CODETYPE])) {
                        $ajaxCmdResponseGenerator->addCodeTypeResponse($responseData[ProjectKeys::KEY_CODETYPE]);
                    }
                    throw new Exception("Excepció a ProjectResponseHandler: [".ProjectKeys::KEY_CANCEL."]");
                    break;

                case ProjectKeys::KEY_REVERT:
                    throw new Exception("Excepció a ProjectResponseHandler: [".ProjectKeys::KEY_REVERT."]\n"
                                        . "S'ha traslladat a: wikiocmodel/projects/documentation/command/responseHandler/ProjectRevertResponseHandler.php");

                case ProjectKeys::KEY_SAVE_PROJECT_DRAFT:
                    if ($responseData['lockInfo']){
                        $timeout = ($responseData['lockInfo']['locker']['time'] + WikiGlobalConfig::getConf("locktime") - 60 - time()) * 1000;
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
                    throw new Exception();
            }
        }

    }

    protected function viewResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $id = $responseData['id'];
        $ns = $requestParams['id'];
        $title_rev = date("d-m-Y h:i:s", isset($requestParams['rev']) ? $requestParams['rev'] : "");
        $title = "Projecte $ns $title_rev";

        //$form = $this->buildForm($id, $ns, $responseData['projectMetaData']['structure'], $responseData['projectViewData']);
        $form = $this->buildForm($id, $ns, $responseData['projectMetaData'], $responseData['projectViewData']);

        $ajaxCmdResponseGenerator->addViewProject($id, $ns, $title, $form,
                                                  $responseData['projectMetaData']['values'],
                                                  $responseData['hasDraft'],
                                                  $responseData['projectExtraData']);
        $this->addMetadataResponse($id, $ns, $ajaxCmdResponseGenerator);
        if ($responseData['info']) {
            $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
        }
    }

    protected function editResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $id = $responseData['id'];
        $ns = $requestParams['id'];
        $title_rev = date("d-m-Y h:i:s", isset($requestParams['rev']) ? $requestParams['rev'] : "");
        $title = "Projecte $ns $title_rev";
        $action = "lib/exe/ioc_ajax.php?call=project&do=save";

        $outValues = [];
        $form = $this->buildForm($id, $ns, $responseData['projectMetaData'], $responseData['projectViewData'], $action, $outValues);

        //El action que dispara este ProjectResponseHandler envía el array projectExtraData
        $this->addSaveOrDiscardDialog($responseData, $responseData['id']);
        $autosaveTimer = WikiGlobalConfig::getConf("autosaveTimer") ? WikiGlobalConfig::getConf("autosaveTimer") : NULL;

        $ajaxCmdResponseGenerator->addEditProject($id, $ns, $title, $form,
                                                  $responseData['projectMetaData']['values'], //substituir per l'array de valors generat 
                                                  $responseData['hasDraft'], $autosaveTimer, $responseData['originalLastmod'],
                                                  $responseData['projectExtraData']);

        $this->addMetadataResponse($id, $ns, $ajaxCmdResponseGenerator);
        if ($responseData['info']) {
            $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
        }
    }

    protected function addMetadataResponse($projectId, $projectNs, &$ajaxCmdResponseGenerator) {
        $rdata['id'] = "metainfo_tree_".$projectId;
        $rdata['type'] = "meta_dokuwiki_ns_tree";
        $rdata['title'] = "Espai de noms del projecte";
        $rdata['standbyId'] = cfgIdConstants::BODY_CONTENT;
        $rdata['fromRoot'] = $projectNs;
        $rdata['treeDataSource'] = "lib/exe/ioc_ajaxrest.php/ns_tree_rest/";
        $rdata['typeDictionary'] = array(
                                      array('urlBase' => "'lib/exe/ioc_ajax.php?call=project'",
                                            'params' => array(0 => ProjectKeys::PROJECT_TYPE)
                                           )
                                        );
        $rdata['urlBase'] = "lib/exe/ioc_ajax.php?call=page";

        $ajaxCmdResponseGenerator->addMetadata($projectId, [$rdata]);
    }

    /** El grid esta compuesto por 12 columnas
     *
     * @param string: $id, $ns, $action
     * @param array: $structure, obtenido de configMain.json
     * @param array: $view, obtenido de defaultView.json
     * @return array
     */
    protected function buildForm($id, $ns, $structure, $view, $action=NULL, $form_readonly=FALSE, &$outValues) {

        
        
        
//        $structure = $this->flatStructure($structure);
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

            if ($aGroups[$keyGroup]) {
                //El grupo ya ha sido creado con anterioridad
                if (!$aGroups[$keyGroup]->hasData()) {
                    //se añaden los atributos al grupo que fue creado sin ellos
                    $aGroups[$keyGroup]
                        ->setTitle($label)
                        ->setFrame($frame)
                        ->setColumns($columns);
                }
            }else {
                //Se crea un nuevo grupo principal
                $aGroups[$keyGroup] = FormBuilder::createGroupBuilder()
                    ->setTitle($label)
                    ->setFrame($frame)
                    ->setColumns($columns);
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

            if (!$arrValues['struc_rows'])
                $arrValues['struc_rows'] = 1;

            $label = ($arrValues['label']) ? $arrValues['label'] : WikiIocLangManager::getLang('projectLabelForm')[$keyField];

            $aGroups[$grupo]->addElement(FormBuilder::createFieldBuilder()
                ->setId($arrValues['id'])
                ->setLabel(($label != NULL) ? $label : $keyField)
                ->setType(($arrValues['type']) ? $arrValues['type'] : "text")
                ->addProps($arrValues['props'])
                ->setColumns($columns)
                ->setValue($arrValues['value'])
            );
        }

        $form = $builder->addElement($mainRow)
                    ->build();
        return $form;
    }

    protected function mergeStructureToForm($structure, &$viewFields, &$viewGroups, $viewDefinition, &$outValues, $mandatoryParent=false, $defaultParent =""){
        $ret;
        if(isset($structure['type'])){
            $ret = $this->mergeStructureDefaultToForm($structure, $viewFields, $viewGroups, $viewDefinition, $outValues, $mandatoryParent, $defaultParent);
        }else{
            $ret = $this->mergeStructureObjectToForm($structure, $viewFields, $viewGroups, $viewDefinition, $mandatoryParent, $defaultParent);
        }
        return $ret;
    }
    
    protected function mergeStructureObjectToForm($structure, &$viewFields, &$viewGroups, $viewDefinition, $mandatoryParent=false, $defaultParent =""){
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
            if(!isset($viewFields[$structureProperties['id']]['props'])){
                $viewFields[$structureProperties['id']]['props']=[];
            }
            $viewFields[$structureProperties['id']]['props']['defaultRow']=$viewFields[$structureProperties['id']]['defaultRow'];
        }

        //TODO[Xavi] Determinar quin es el valor que s'ha de guardar aquí!

        $outValues[$structureProperties['id']] =
        return $ret;
    }

    protected function addSaveOrDiscardDialog(&$responseData, $id) {
        $responseData['projectExtraData']['messageChangesDetected'] = WikiIocLangManager::getLang('projects')['cancel_editing_with_changes'];
        $responseData['projectExtraData']['dialogSaveOrDiscard'] = $this->generateSaveOrDiscardDialog($id, strlen($responseData["rev"]) > 0);
    }

    protected function generateSaveOrDiscardDialog($id, $isRev) {
        $dialogConfig = [
            'id' => $id,
            'title' => WikiIocLangManager::getLang("save_or_discard_dialog_title"),
            'message' => WikiIocLangManager::getLang("save_or_discard_dialog_message"), //'Vols desar els canvis?',
            'closable' => false,
            'buttons' => [
                [
                    'id' => 'discard',
                    'description' => WikiIocLangManager::getLang("save_or_discard_dialog_dont_save"), //'No desar',
                    'buttonType' => 'fire_event',
                    'extra' => [
                        [
                            'eventType' => 'cancel_project',
                            'data' => [
                                'dataToSend' => [
                                    'discard_changes' => true,
                                    'keep_draft' => false
                                ]
                            ],
                            'observable' => $id
                        ]
                    ]
                ],
            ]
        ];

        if ($isRev) {
            $dialogConfig['buttons'][] =
                [
                    'id' => 'save',
                    'description' => WikiIocLangManager::getLang("save_or_discard_dialog_save"), //'Desar',
                    'buttonType' => 'fire_event',
                    'extra' => [
                        [
                            'eventType' => 'save_project',
                            'data' => [
                                'dataToSend' => [
                                    'reload' => false
                                ]
                            ],
                            'observable' => $id
                        ],
                    ]
                ];
        }
        else {
            $dialogConfig['buttons'][] =
                [
                    'id' => 'save',
                    'description' => WikiIocLangManager::getLang("save_or_discard_dialog_save"), //'Desar',
                    'buttonType' => 'fire_event',
                    'extra' => [
                        [
                            'eventType' => 'save_project',
                            'data' => [
                                'dataToSend' => [
                                    'cancel' => true,
                                    'keep_draft' => false,
                                    'close' => true,
                                    'no_response' => true
                                ]
                            ],
                            'observable' => $id,
                        ],
                    ]
                ];
        }

        return $dialogConfig;
    }

}
