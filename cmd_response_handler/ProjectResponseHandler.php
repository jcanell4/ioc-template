<?php
/**
 * ProjectResponseHandler: Construye los datos para la respuesta de la parte servidor en función de la petición
 * @culpable Rafael Claver
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_LIB_IOC')) define('DOKU_LIB_IOC', DOKU_INC . 'lib/lib_ioc/');
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
require_once(DOKU_LIB_IOC . "wikiiocmodel/ProjectModelExceptions.php");
require_once(DOKU_PLUGIN . "ajaxcommand/defkeys/ProjectKeys.php");
require_once(DOKU_PLUGIN . "ajaxcommand/defkeys/LockKeys.php");
require_once(DOKU_TPL_INCDIR . "conf/cfgIdConstants.php");
require_once(DOKU_TPL_INCDIR . "cmd_response_handler/WikiIocResponseHandler.php");
require_once(DOKU_TPL_INCDIR . "cmd_response_handler/utility/FormBuilder.php");
require_once(DOKU_TPL_INCDIR . "cmd_response_handler/utility/ExpiringCalc.php");

class ProjectResponseHandler extends WikiIocResponseHandler {

    private $responseType = null; // ALERTA[Xavi] Afegit per poder discriminar el tipus de resposta sense afegir més paràmetres a les crides que generan els formularis.
    private $responseData = null; // ALERTA[Josep] Afegit per poder disposar de la resposta en qualsevol dels mètodes de tractament.

    function __construct($cmd = NULL) {
        parent::__construct(($cmd !== NULL) ? $cmd : ProjectKeys::KEY_PROJECT);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $this->responseData = $responseData;
        if (isset($responseData[ProjectKeys::KEY_CODETYPE])) {
            if ($responseData[ProjectKeys::KEY_INFO]) {
                $ajaxCmdResponseGenerator->addInfoDta($responseData[ProjectKeys::KEY_INFO]);
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

            if ($responseData[ProjectKeys::KEY_ACTIVA_UPDATE_BTN]) {
                $ajaxCmdResponseGenerator->addExtraContentStateResponse($responseData[ProjectKeys::KEY_ID], "updateButton", $responseData[ProjectKeys::KEY_ACTIVA_UPDATE_BTN]);
            }

            if ($responseData[ProjectKeys::KEY_ACTIVA_FTPSEND_BTN]) {
                $ajaxCmdResponseGenerator->addExtraContentStateResponse($responseData[ProjectKeys::KEY_ID], "ftpSendButton", $responseData[ProjectKeys::KEY_ACTIVA_FTPSEND_BTN]);
            }

            $this->responseType = $requestParams[ProjectKeys::KEY_DO];
            $responseData[ProjectKeys::KEY_PROJECT_EXTRADATA][ProjectKeys::KEY_GENERATED] = $responseData[ProjectKeys::KEY_GENERATED];

            switch ($requestParams[ProjectKeys::KEY_DO]) {

                case ProjectKeys::KEY_DIFF:
                    $ajaxCmdResponseGenerator->addDiffProject($responseData['rdata'], $responseData[ProjectKeys::KEY_PROJECT_EXTRADATA]);
                    //afegir la metadata de revisions com a resposta
                    if ($this->addMetaDataRevisions($requestParams, $responseData, $ajaxCmdResponseGenerator)) {
                        $ajaxCmdResponseGenerator->addRevisionsTypeResponse($responseData['rdata'][ProjectKeys::KEY_ID], $responseData[ProjectKeys::KEY_REV]);
                        $param = [ProjectKeys::KEY_NS => $responseData['rdata'][ProjectKeys::KEY_NS],
                            'pageCommand' => "lib/exe/ioc_ajax.php?call=project&do=view&projectType={$requestParams[ProjectKeys::KEY_PROJECT_TYPE]}"
                        ];
                        $ajaxCmdResponseGenerator->addProcessDomFromFunction($responseData['rdata'][ProjectKeys::KEY_ID], true, "ioc/dokuwiki/processContentPage", $param);
                    }

                    if ($responseData[ProjectKeys::KEY_INFO]) {
                        $ajaxCmdResponseGenerator->addInfoDta($responseData[ProjectKeys::KEY_INFO]);
                    }
                    break;

                case ProjectKeys::KEY_CANCEL:
                    $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processSaving");
                    $this->_responseViewResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                    break;

                case ProjectKeys::KEY_SAVE:
                    $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processSaving");
                    if (!$requestParams['cancel']) {
                        $ajaxCmdResponseGenerator->addInfoDta($responseData[ProjectKeys::KEY_INFO]);
                    } else {
                        $this->_responseViewResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                    }
                    break;

                case ProjectKeys::KEY_VIEW:
                    $this->_responseViewResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                    break;

                case ProjectKeys::KEY_PARTIAL:
                    $this->_responseEditResponse($requestParams, $responseData,$ajaxCmdResponseGenerator, JsonGenerator::PROJECT_PARTIAL_TYPE);
                    break;

                    // ALERTA[Xavi] FI AVÍS

                case ProjectKeys::KEY_EDIT:
                    $this->_responseEditResponse($requestParams, $responseData,$ajaxCmdResponseGenerator, JsonGenerator::PROJECT_EDIT_TYPE);

                    break;

                case ProjectKeys::KEY_CREATE_PROJECT:
                    $this->editResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                    break;

                case ProjectKeys::KEY_GENERATE:
                    if ($responseData[ProjectKeys::KEY_INFO])
                        $ajaxCmdResponseGenerator->addInfoDta($responseData[ProjectKeys::KEY_INFO]);
                    if(!$responseData[ProjectKeys::KEY_GENERATED]){
                        $ajaxCmdResponseGenerator->addAlert(WikiIocLangManager::getLang("project_not_generated"));
                    }
                    if($responseData["sendData"]){
                        $this->_responseViewResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                    }
                    break;

                case ProjectKeys::KEY_REVERT:
                    throw new Exception("Excepció a ProjectResponseHandler: [" . ProjectKeys::KEY_REVERT . "]\n"
                        . "S'ha traslladat a: wikiocmodel/projects/documentation/command/responseHandler/ProjectRevertResponseHandler.php");

                case ProjectKeys::KEY_SAVE_PROJECT_DRAFT:
                    if ($responseData['lockInfo']) {
                        $timeout = ExpiringCalc::getExpiringTime($responseData['lockInfo']['locker']['time'], 0);
                        $ajaxCmdResponseGenerator->addRefreshLock($responseData[ProjectKeys::KEY_ID], $requestParams[ProjectKeys::KEY_ID], $timeout);
                    }
                    if ($responseData[ProjectKeys::KEY_INFO]) {
                        $ajaxCmdResponseGenerator->addInfoDta($responseData[ProjectKeys::KEY_INFO]);
                    } else {
                        $ajaxCmdResponseGenerator->addCodeTypeResponse(0);
                    }
                    break;

                case ProjectKeys::KEY_REMOVE_PROJECT_DRAFT:
                    throw new Exception("Excepció a ProjectResponseHandler: [" . ProjectKeys::KEY_REMOVE_PROJECT_DRAFT . "]");

                case ProjectKeys::KEY_NEW_DOCUMENT:
                    $this->setSubCmd(ProjectKeys::KEY_NEW_DOCUMENT);
                    include_once(DOKU_TPL_INCDIR . "cmd_response_handler/PageResponseHandler.php");
                    PageResponseHandler::staticResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);

                default:
                    if ($responseData[ProjectKeys::KEY_INFO]) {
                        $ajaxCmdResponseGenerator->addInfoDta($responseData[ProjectKeys::KEY_INFO]);
                    }
                    if ($responseData['alert']) {
                        $ajaxCmdResponseGenerator->addAlert($responseData['alert']);
                    } else if (!$responseData[ProjectKeys::KEY_INFO]) {
                        new IncorrectParametersException();
                    }

            }

        }

    }

    protected function _responseEditResponse(&$requestParams, &$responseData, &$ajaxCmdResponseGenerator, $projectResponseType) {
        if ($requestParams[ProjectKeys::KEY_HAS_DRAFT]) {
            $responseData[ProjectKeys::KEY_PROJECT_EXTRADATA]['edit'] = 1;
            $this->_responseViewResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
        } else {
            switch ($responseData['lockInfo']['state']) {

                case LockKeys::LOCKED:
                    //se ha obtenido el bloqueo, continuamos la edición
                    if ($requestParams[ProjectKeys::KEY_RECOVER_DRAFT]) {
                        $responseData[ProjectKeys::KEY_PROJECT_EXTRADATA][ProjectKeys::KEY_RECOVER_DRAFT] = TRUE;
                    }
                    $this->_addUpdateLocalDrafts($requestParams, $responseData, $ajaxCmdResponseGenerator);
                    $this->editResponse($requestParams, $responseData, $ajaxCmdResponseGenerator, $projectResponseType);
                    $this->_addMetaDataRevisions($requestParams, $responseData, $ajaxCmdResponseGenerator);
                    break;
                case LockKeys::REQUIRED:
                    //el recurso está bloqueado por otro usuario. Mostramos los datos del formulario y un cuadro de diálogo
                    $this->addRequireDialogResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                    $this->_addMetaDataRevisions($requestParams, $responseData, $ajaxCmdResponseGenerator);
                    break;
                case LockKeys::LOCKED_BEFORE:
                    //el recurso está bloqueado por el propio usuario en otra sesión
                    $this->_responseViewResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                    break;
            }
        }
    }

    protected function remoteViewResponse($requestParams, &$responseData, &$ajaxCmdResponseGenerator)
    {
        $this->viewResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
        //afegir la metadata de revisions com a resposta
        $this->_addMetaDataRevisions($requestParams, $responseData, $ajaxCmdResponseGenerator);
    }

    private function _responseViewResponse($requestParams, &$responseData, &$ajaxCmdResponseGenerator)
    {
        $this->_addUpdateLocalDrafts($requestParams, $responseData, $ajaxCmdResponseGenerator);
        $this->viewResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
        //afegir la metadata de revisions com a resposta
        $this->_addMetaDataRevisions($requestParams, $responseData, $ajaxCmdResponseGenerator);
    }

    private function _addUpdateLocalDrafts($requestParams, &$responseData, &$ajaxCmdResponseGenerator)
    {
        if ($responseData['drafts']) {
            $responseData[ProjectKeys::KEY_PROJECT_EXTRADATA][ProjectKeys::KEY_HAS_DRAFT] = TRUE;
            $extra = [ProjectKeys::KEY_PROJECT_TYPE => $requestParams[ProjectKeys::KEY_PROJECT_TYPE]];
            if ($requestParams[ProjectKeys::KEY_METADATA_SUBSET]) {
                $extra[ProjectKeys::KEY_METADATA_SUBSET] = $requestParams[ProjectKeys::KEY_METADATA_SUBSET];
            }else {
                $extra[ProjectKeys::KEY_METADATA_SUBSET] = $requestParams[ProjectKeys::VAL_DEFAULTSUBSET];
            }
            $ajaxCmdResponseGenerator->addUpdateLocalDrafts($requestParams[ProjectKeys::KEY_ID], $responseData['drafts'], $extra);
        }
    }

    private function _addMetaDataRevisions($requestParams, &$responseData, &$ajaxCmdResponseGenerator)
    {
        if ($this->addMetaDataRevisions($requestParams, $responseData, $ajaxCmdResponseGenerator)) {
            $ajaxCmdResponseGenerator->addRevisionsTypeResponse($responseData[ProjectKeys::KEY_ID], $responseData[ProjectKeys::KEY_REV]);
        }
    }

    private function addMetaDataRevisions($requestParams, &$responseData, &$ajaxCmdResponseGenerator)
    {
        if (isset($responseData[ProjectKeys::KEY_REV]) && count($responseData[ProjectKeys::KEY_REV]) > 0) {
            $pType = $requestParams[ProjectKeys::KEY_PROJECT_TYPE];
            $subSet = ($this->_isSubSet($requestParams[ProjectKeys::KEY_METADATA_SUBSET])) ? "&metaDataSubSet=".$requestParams[ProjectKeys::KEY_METADATA_SUBSET] : "";
            $responseData[ProjectKeys::KEY_REV]['call_diff'] = "project&do=diff&projectType=$pType$subSet";
            $responseData[ProjectKeys::KEY_REV]['call_view'] = "project&do=view&projectType=$pType$subSet";
            $responseData[ProjectKeys::KEY_REV]['urlBase'] = "lib/exe/ioc_ajax.php?call=" . $responseData[ProjectKeys::KEY_REV]['call_diff'];
            return true;
        } else {
            $extramd = [ProjectKeys::KEY_ID => $responseData[ProjectKeys::KEY_ID],
                'idr' => $responseData[ProjectKeys::KEY_ID] . "_revisions",
                'txt' => "No hi ha revisions",
                'html' => "<h3>Aquest projecte no té revisions</h3>"
            ];
            $ajaxCmdResponseGenerator->addExtraMetadata($extramd[ProjectKeys::KEY_ID], $extramd['idr'], $extramd['txt'], $extramd['html']);
        }
    }

    protected function viewResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator)
    {
        $id = $responseData[ProjectKeys::KEY_ID];
        $ns = $requestParams[ProjectKeys::KEY_ID];

        if (isset($requestParams[ProjectKeys::KEY_REV]) && $requestParams[ProjectKeys::KEY_DO] !== ProjectKeys::KEY_REVERT)
            $title_rev = "- revisió (" . date("d.m.Y h:i:s", $requestParams[ProjectKeys::KEY_REV]) . ")";
        if (($responseData['isSubSet']))
            $extratitle = $this->_getExtraTitle($requestParams[ProjectKeys::KEY_METADATA_SUBSET], TRUE);
        $title = "Projecte $ns $extratitle $title_rev";

        $outValues = [];
        $form = $this->buildForm($id, $ns, $responseData['projectMetaData'], $responseData['projectViewData'], $outValues, NULL, FALSE, $extratitle);

        if ($requestParams[ProjectKeys::KEY_DISCARD_CHANGES])
            $responseData[ProjectKeys::KEY_PROJECT_EXTRADATA][ResponseHandlerKeys::KEY_DISCARD_CHANGES] = $requestParams[ProjectKeys::KEY_DISCARD_CHANGES];

        if ($responseData['originalLastmod'])
            $responseData[ProjectKeys::KEY_PROJECT_EXTRADATA]['originalLastmod'] = $responseData['originalLastmod'];

        $ajaxCmdResponseGenerator->addViewProject($id, $ns, $title, $form, $outValues, $responseData[ProjectKeys::KEY_PROJECT_EXTRADATA]);
        $this->addMetadataResponse($id, $ns, $requestParams[ProjectKeys::KEY_PROJECT_TYPE], $responseData[ProjectKeys::KEY_CREATE], $ajaxCmdResponseGenerator, $responseData["meta"]);
        if ($responseData[ProjectKeys::KEY_INFO]) {
            $ajaxCmdResponseGenerator->addInfoDta($responseData[ProjectKeys::KEY_INFO]);
        }
    }

    protected function editResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator, $responseType=JsonGenerator::PROJECT_EDIT_TYPE)
    {
        $id = $responseData[ProjectKeys::KEY_ID];
        $ns = isset($responseData[ProjectKeys::KEY_NS]) ? $responseData[ProjectKeys::KEY_NS] : $requestParams[ProjectKeys::KEY_ID];
        if (isset($requestParams[ProjectKeys::KEY_REV]))
            $title_rev = date("d-m-Y h:i:s", $requestParams[ProjectKeys::KEY_REV]);
        if (($responseData['isSubSet']))
            $extratitle = $this->_getExtraTitle($requestParams[ProjectKeys::KEY_METADATA_SUBSET], TRUE);
        $title = "Projecte $ns $extratitle $title_rev";
        $action = "lib/exe/ioc_ajax.php?call=project&do=save";

        $outValues = [];
        $form = $this->buildForm($id, $ns, $responseData['projectMetaData'], $responseData['projectViewData'], $outValues, $action, FALSE, $extratitle);

        $this->addSaveOrDiscardDialog($responseData);
        $autosaveTimer = WikiGlobalConfig::getConf("autosaveTimer") ? WikiGlobalConfig::getConf("autosaveTimer")*1000 : NULL;
        $timer = $this->generateEditProjectTimer($requestParams[ProjectKeys::KEY_ID], $requestParams[ProjectKeys::KEY_METADATA_SUBSET], $responseData['lockInfo']['time']);

        $ajaxCmdResponseGenerator->addEditProject($id, $ns, $title, $form, $outValues, $autosaveTimer, $timer, $responseData[ProjectKeys::KEY_PROJECT_EXTRADATA], $responseType);

        $pType = isset($responseData[ProjectKeys::KEY_PROJECT_TYPE]) ? $responseData[ProjectKeys::KEY_PROJECT_TYPE] : $requestParams[ProjectKeys::KEY_PROJECT_TYPE];
        $this->addMetadataResponse($id, $ns, $pType, $responseData[ProjectKeys::KEY_CREATE], $ajaxCmdResponseGenerator, $responseData["meta"]);
        if ($responseData[ProjectKeys::KEY_INFO]) {
            $ajaxCmdResponseGenerator->addInfoDta($responseData[ProjectKeys::KEY_INFO]);
        }
    }

    //[JOSEP] Alerta cal canviar la crida hardcode als botons per una que impliqui configuració
    protected function addMetadataResponse($projectId, $projectNs, $projectType, $rdCreate, &$ajaxCmdResponseGenerator, $meta=NULL)
    {
        if(!$meta){
            $meta = array();
        }
        $rdata[ProjectKeys::KEY_ID] = "metainfo_tree_" . $projectId;
        $rdata['type'] = "meta_dokuwiki_ns_tree";
        $rdata['title'] = "Espai de noms del projecte";
        $rdata['standbyId'] = cfgIdConstants::BODY_CONTENT;
        $rdata['fromRoot'] = $projectNs;
        $rdata['treeDataSource'] = "lib/exe/ioc_ajaxrest.php/ns_tree_rest/";
        $rdata['typeDictionary'] = [
            "p" => [
                "urlBase" => "lib/exe/ioc_ajax.php?call=project",
                "params" => [ProjectKeys::KEY_PROJECT_TYPE, ProjectKeys::KEY_NSPROJECT]
            ],
            "po" => [
                "urlBase" => "lib/exe/ioc_ajax.php?call=project",
                "params" => [ProjectKeys::KEY_PROJECT_TYPE, ProjectKeys::KEY_NSPROJECT]
            ],
            "s" => [
                "urlBase" => "lib/exe/ioc_ajax.php?call=project",
                "params" => [ProjectKeys::KEY_PROJECT_TYPE, ProjectKeys::KEY_METADATA_SUBSET]
            ]
        ];
        $rdata['urlBase'] = "lib/exe/ioc_ajax.php?call=page";
        $rdata['processOnClickAndOpenOnClick'] = array('p', 'po');
        if ($rdCreate[ProjectKeys::KEY_MD_CT_SUBPROJECTS]
            || $rdCreate[ProjectKeys::KEY_MD_CT_DOCUMENTS]
            || $rdCreate[ProjectKeys::KEY_MD_CT_FOLDERS]) {
            $rdata['buttons'][0] = [ProjectKeys::KEY_ID => "projectMetaDataTreeZone_topRight_" . $projectId,
                'amdClass' => "ioc/gui/IocDialogButton",
                'position' => "bottomRight",
                'class' => "imageOnly",
                'buttonParams' => [
                    'iconClass' => "iocIconAdd",
                    ProjectKeys::KEY_ID => "projectMetaDataTreeZone_topRight_" . $projectId,
                    'dialogParams' => [
                        ProjectKeys::KEY_NS => $projectNs,
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
                //$rdata['buttons'][0]['buttonParams']['dialogParams']['call_project'] = "call=project&do=".ProjectKeys::KEY_CREATE_SUBPROJECT;
                $rdata['buttons'][0]['buttonParams']['dialogParams']['call_project'] = "call=".ProjectKeys::KEY_CREATE_SUBPROJECT;
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
        }
        array_unshift($meta, $rdata);
        $ajaxCmdResponseGenerator->addMetadata($projectId, $meta);
    }

    /** El grid esta compuesto por 12 columnas
     *
     * @param string: $id, $ns, $action
     * @param array: $structure, obtenido de configMain.json
     * @param array: $view, obtenido de defaultView.json
     * @return array
     */
    protected function buildForm($id, $ns, $structure, $view, &$outValues, $action=NULL, $form_readonly=FALSE, $extratitle="")
    {
        $firsKeyGroup = "";
        $this->mergeStructureToForm($structure, $view['fields'], $view['groups'], $view['definition'], $outValues);
        $aGroups = array();
        $builder = new FormBuilder($id, $action);

        $mainRow = FormBuilder::createRowBuilder()->setTitle("Projecte: $ns $extratitle");

        if (!isset($view['definition'])) {
            $view['definition'] = [
                "n_columns" => 12,
                "n_rows" => 16,
                "chars_column" => 10,
                "rows_row" => 1
            ];
        }

        if (!isset($view['groups'])) {
            $view['groups'] = [
                "main" => [
                    "parent" => "",
                    /*"label": "Principal",*/
                    "n_columns" => 12,
                    "n_rows" => 16,
                    "frame" => false
                ]
            ];
        }

        //Construye, como objetos, los grupos definidos en la vista y los enlaza jerarquicamente
        foreach ($view['groups'] as $keyGroup => $valGroup) {
            if (empty($firsKeyGroup)) {
                $firsKeyGroup = $keyGroup;
            }
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
                        ->addProps($valGroup['props'])
                        ->addConfig($valGroup['config'])
                        ->setRows($rows);
                }
            } else {
                //Se crea un nuevo grupo principal
                $aGroups[$keyGroup] = FormBuilder::createGroupBuilder()
                    ->setTitle($label)
                    ->setFrame($frame)
                    ->setColumns($columns)
                    ->addProps($valGroup['props'])
                    ->addConfig($valGroup['config'])
                    ->setRows($rows);
            }

            if (!$pare) {
                $mainRow->addElement($aGroups[$keyGroup]); //se añade como grupo principal
            } else {
                if (!$aGroups[$pare]) {
                    //si el grupo padre de este grupo todavía no está creado, se crea el grupo padre sin atributos
                    $aGroups[$pare] = FormBuilder::createGroupBuilder();
                }
                $aGroups[$pare]->addElement($aGroups[$keyGroup]); //se añade como elemento al grupo padre
            }
        }

        if (empty($firsKeyGroup)) {
            $firsKeyGroup = "main";
        }

        if (isset($view['fields']) && is_array($view['fields'])) {

            foreach ($view['fields'] as $keyField => $valField) {

                //combina los atributos y valores de los arrays de estructura y de vista
                if (!is_array($valField)) $valField = array($valField);
                if(preg_match("/#/", $keyField)){
                    $akeys = explode("#", $keyField);
                    $properties = $structure;
                    $lim = count($akeys)-1;
                    for($ind=0; $ind<$lim; $ind++){
                        $properties =  $properties[$akeys[$ind]]["value"];
                    }
                    $properties = $properties[$akeys[$lim]];
                }else{
                    $properties = $structure[$keyField];
                }
                $arrValues = array_merge((!is_array($properties)) ? array($properties) : $properties, $valField);

                if ($form_readonly && (!isset($arrValues['props']) || ($arrValues['props'] && $arrValues['props']['readonly'] == FALSE)))
                    $arrValues['props']['readonly'] = TRUE;

                //obtiene el grupo, al que pertenece este campo, de la vista o, si no lo encuentra, de la estructura
                $grupo = ($arrValues['group']) ? $arrValues['group'] : $firsKeyGroup;
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
                if (!$label) {
                    $label = $keyField;
                }

                $this->updateReadonlyFromConfig($arrValues);
                $this->updateFromCalculate($ns, $arrValues, $outValues);

                $aGroups[$grupo]->addElement(FormBuilder::createFieldBuilder()
                    ->setId($arrValues[ProjectKeys::KEY_ID])
                    ->setLabel(($label != NULL) ? $label : $keyField)
                    ->setType(($arrValues['type']) ? $arrValues['type'] : "text")
                    ->addProps($arrValues['props'])
                    ->addConfig($arrValues['config'])
                    ->setColumns($columns)
                    ->setRows($rows)
                    ->setValue($arrValues['value'])
                );
            }
        }

        $form = $builder->addElement($mainRow)
            ->build();
        return $form;
    }

    protected function updateFromCalculate($ns, &$arrValues, $outValues) {
        if (isset($arrValues['calculate'])) {
            $className = $arrValues['calculate']['class'];
            $calculator = new $className;
            if ($calculator) {
                $calculator->init($ns);
                $value = $calculator->calculate($outValues[$arrValues['calculate']['data']]);
                $arrValues['value'] = $value;
            }
        }
    }

    protected function updateReadonlyFromConfig(&$outArrValues) {
        if (!isset($outArrValues['config']) || !isset($outArrValues['config']['readonly'])) {
            return; //no s'ha establert la propietat al config, no cal fer res
        }

        $isReadOnly = $outArrValues['config']['readonly'];

        if (is_array($isReadOnly)) {
            $funcREadOnly = $isReadOnly;
            if(isset($funcREadOnly["or"])){
                $isReadOnly=FALSE;
                foreach ($funcREadOnly["or"] as $readOnlyValidator){
                    $isReadOnly = $isReadOnly || $this->getValidatorValue($readOnlyValidator);
                }
            }else if(isset($funcREadOnly["and"])){
                $isReadOnly=TRUE;
                foreach ($funcREadOnly["and"] as $readOnlyValidator){
                    $isReadOnly = $isReadOnly && $this->getValidatorValue($readOnlyValidator);
                }
            }else{
                $isReadOnly = $this->getValidatorValue($funcREadOnly);
            }
        }

        $outArrValues['config']['readonly'] = $isReadOnly;

        if (!isset($outArrValues['props'])) {
            $outArrValues['props'] = [];
        }

        $outArrValues['props']['readonly'] = $isReadOnly;
    }

    private function getValidatorValue($outArrValues){
        $className = $outArrValues['class'];
        $validator = new $className;

        if (!$validator) {
            // TODO: la classe no existeix, llençar execepció
            return;
        }
        $validatorTypeData = $validator->getValidatorTypeData();
        switch ($validatorTypeData){
            case "permission":
                $validator->init($this->getPermission());
                break;
            case "response":
                $validator->init($this->responseData);
                break;
        }
        $isReadOnly = $validator->validate($outArrValues['data']);
        return $isReadOnly;
    }

    protected function mergeStructureToForm($structure, &$viewFields, &$viewGroups, $viewDefinition, &$outValues, $mandatoryParent=false, $defaultParent="")
    {
        if (isset($structure['type'])) {
            $ret = $this->mergeStructureDefaultToForm($structure, $viewFields, $outValues, $mandatoryParent, $defaultParent);
        } else {
            $ret = $this->mergeStructureObjectToForm($structure, $viewFields, $viewGroups, $viewDefinition, $outValues, $mandatoryParent, $defaultParent);
        }
        return $ret;
    }

    protected function mergeStructureObjectToForm($structure, &$viewFields, &$viewGroups, $viewDefinition, &$outValues, $mandatoryParent=false, $defaultParent="")
    {
        $ret = false;
        foreach ($structure as $structureKey => $structureProperties) {
            if (isset($structureProperties['renderAsMultiField'])) {
                if (isset($structureProperties['value'])) {
                    //$discardValues = [];
//                    $needGroup = $this->mergeStructureToForm($structureProperties['value'], $viewFields, $discardValues, $viewDefinition, $outValues, $structureProperties['mandatory'], $structureProperties[ProjectKeys::KEY_ID]);
                    $needGroup = $this->mergeStructureToForm($structureProperties['value'], $viewFields, $viewGroups, $viewDefinition, $outValues, $structureProperties['mandatory'], $structureProperties[ProjectKeys::KEY_ID]);
                    if ($needGroup) {
                        $viewGroups[$structureProperties[ProjectKeys::KEY_ID]]['label'] = $structureKey;
                        $viewGroups[$structureProperties[ProjectKeys::KEY_ID]]['frame'] = true;
                        $viewGroups[$structureProperties[ProjectKeys::KEY_ID]]['n_columns'] = $viewDefinition['n_columns'];
                        $viewGroups[$structureProperties[ProjectKeys::KEY_ID]]['parent'] = $defaultParent;
//                        $viewGroups[$structureKey]['label'] = $structureKey;
//                        $viewGroups[$structureKey]['frame'] = true;
//                        $viewGroups[$structureKey]['n_columns'] = $viewDefinition['n_columns'];
//                        $viewGroups[$structureKey]['parent'] = $defaultParent;
                        $ret = true;
                    }
                }
            } else {
                $ret = $this->mergeStructureToForm($structureProperties, $viewFields, $viewGroups, $viewDefinition, $outValues, $mandatoryParent, $defaultParent);
            }
        }
        return $ret;
    }

    protected function mergeStructureDefaultToForm($structureProperties, &$viewFields, &$outValues, $mandatoryParent=false, $defaultParent="")
    {
        $ret = false;
        if (array_key_exists($structureProperties[ProjectKeys::KEY_ID], $viewFields)) {
            //merge
            $viewFields[$structureProperties[ProjectKeys::KEY_ID]] = array_merge(array(), $structureProperties, $viewFields[$structureProperties[ProjectKeys::KEY_ID]]);
        } else {
            if ($mandatoryParent || $structureProperties['mandatory']) {
                $ret = true;
                $viewFields[$structureProperties[ProjectKeys::KEY_ID]] = $structureProperties;
                $viewFields[$structureProperties[ProjectKeys::KEY_ID]]['group'] = $defaultParent;
            }
        }
        if (isset($viewFields[$structureProperties[ProjectKeys::KEY_ID]]['defaultRow'])) {
            if (!isset($viewFields[$structureProperties[ProjectKeys::KEY_ID]]['config'])) {
                $viewFields[$structureProperties[ProjectKeys::KEY_ID]]['config'] = [];
            }
            $viewFields[$structureProperties[ProjectKeys::KEY_ID]]['config']['defaultRow'] = $viewFields[$structureProperties[ProjectKeys::KEY_ID]]['defaultRow'];

            //TODO[Xavi] Determinar quin es el valor que s'ha de guardar aquí!
        }

        // ALERTA! Comprovar el mode, no s'ha de renderitzar en edit
        if ($this->responseType !== "edit" && $structureProperties['config'] && $structureProperties['config']['renderable']) {
            $mode = $structureProperties['config']['mode'];
            $originalValue = $structureProperties['value'];
            $structureProperties['value'] = $this->renderContent($originalValue, $mode);
            $outValues[$structureProperties[ProjectKeys::KEY_ID]] = $structureProperties['value'];
        }

        $outValues[$structureProperties[ProjectKeys::KEY_ID]] = $structureProperties['value'];

        return $ret;
    }

    protected function addSaveOrDiscardDialog(&$responseData) {
        $responseData[ProjectKeys::KEY_PROJECT_EXTRADATA]['messageChangesDetected'] = WikiIocLangManager::getLang('projects')['cancel_editing_with_changes'];
        $responseData[ProjectKeys::KEY_PROJECT_EXTRADATA]['dialogSaveOrDiscard'] = $this->generateSaveOrDiscardDialog($responseData[ProjectKeys::KEY_ID], $responseData[ProjectKeys::KEY_PROJECT_EXTRADATA][ProjectKeys::KEY_METADATA_SUBSET]);
    }

    protected function generateSaveOrDiscardDialog($id, $metaDataSubSet) {
        $dialogConfig = [
            ProjectKeys::KEY_ID => $id,
            'title' => WikiIocLangManager::getLang("save_or_discard_dialog_title"),
            'message' => WikiIocLangManager::getLang("save_or_discard_dialog_message"), //'Vols desar els canvis?',
            'closable' => false,
            'buttons' => [
                [
                    ProjectKeys::KEY_ID => "discard",
                    'description' => WikiIocLangManager::getLang("save_or_discard_dialog_dont_save"), //'No desar',
                    'buttonType' => "fire_event",
                    'extra' => [
                        [
                            'eventType' => "cancel_project",
                            'data' => [
                                'dataToSend' => [
                                    ProjectKeys::KEY_METADATA_SUBSET => $metaDataSubSet,
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
                    ProjectKeys::KEY_ID => "save",
                    'description' => WikiIocLangManager::getLang("save_or_discard_dialog_save"), //'Desar',
                    'buttonType' => "fire_event",
                    'extra' => [
                        [
                            'eventType' => "save_project",
                            'data' => [
                                'dataToSend' => [
                                    ProjectKeys::KEY_METADATA_SUBSET => $metaDataSubSet,
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

    private function generateEditProjectTimer($id, $metaDataSubSet, $locktime) {
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
                    ProjectKeys::KEY_ID => $id,
                    ProjectKeys::KEY_METADATA_SUBSET => $metaDataSubSet
                ],
                "cancelContentEvent" => "cancel_project",
                "cancelEventParams" => [
                    ProjectKeys::KEY_ID => $id,
                    ProjectKeys::KEY_METADATA_SUBSET => $metaDataSubSet,
                    "extraDataToSend" => ProjectKeys::KEY_DISCARD_CHANGES."=true&" . ProjectKeys::KEY_KEEP_DRAFT."=false&auto=true",
                ],
                "timeoutContentEvent" => "cancel_project",
                "timeoutParams" => [
                    ProjectKeys::KEY_ID => $id,
                    ProjectKeys::KEY_METADATA_SUBSET => $metaDataSubSet,
                    "extraDataToSend" => ProjectKeys::KEY_DISCARD_CHANGES."=true&" . ProjectKeys::KEY_KEEP_DRAFT."=true&auto=true",
                ],
            ],
        ];
        $timer['timeout'] = ExpiringCalc::getExpiringTime($locktime, 0);

        return $timer;
    }

    private function addRequireDialogResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator)
    {
        $params = $this->_generateRequireDialogParams($requestParams, $responseData);

        if ($requestParams[PageKeys::KEY_TO_REQUIRE]) {
            $this->_addRequireDialogRefreshParams($params, $requestParams, $responseData);
            $message = $ajaxCmdResponseGenerator->generateInfo('warning', $params['content']['requiring']['message'], $requestParams[ProjectKeys::KEY_ID], -1, $requestParams[ProjectKeys::KEY_METADATA_SUBSET]);
            $responseData[ProjectKeys::KEY_INFO] = $ajaxCmdResponseGenerator->addInfoToInfo($responseData[ProjectKeys::KEY_INFO], $message);
        } else {
            $this->_addDialogParamsToParams($params, $requestParams, $responseData);
        }

        $this->_addRequireProject($ajaxCmdResponseGenerator, $params);
        $pType = isset($responseData[ProjectKeys::KEY_PROJECT_TYPE]) ? $responseData[ProjectKeys::KEY_PROJECT_TYPE] : $requestParams[ProjectKeys::KEY_PROJECT_TYPE];
        $this->addMetadataResponse($params[ProjectKeys::KEY_ID], $params[ProjectKeys::KEY_NS], $pType, $responseData[ProjectKeys::KEY_CREATE], $ajaxCmdResponseGenerator, $responseData['meta']);
        if ($responseData[ProjectKeys::KEY_INFO]) {
            $ajaxCmdResponseGenerator->addInfoDta($responseData[ProjectKeys::KEY_INFO]);
        }
    }

    private function _generateRequireDialogParams($requestParams, $responseData)
    {
        $id = $responseData[ProjectKeys::KEY_ID];
        $ns = $requestParams[ProjectKeys::KEY_ID];
        $extratitle = $this->_getExtraTitle($requestParams[ProjectKeys::KEY_METADATA_SUBSET]);
        $outValues = [];
        $content = $this->buildForm($id, $ns, $responseData['projectMetaData'], $responseData['projectViewData'], $outValues, NULL, FALSE, $extratitle);
        $timer = $this->_generateRequireDialogTimer($requestParams, $responseData);
        $params = [
            ProjectKeys::KEY_ID => $id,
            ProjectKeys::KEY_NS => $ns,
            'title' => "Projecte $ns $extratitle",
            'content' => $content,
            'originalContent' => $outValues,
            'timer' => $timer,
            'extra' => $responseData[ProjectKeys::KEY_PROJECT_EXTRADATA]
        ];
        return $params;
    }

    private function _generateRequireDialogTimer($requestParams, $responseData)
    {
        $rev = $requestParams[ProjectKeys::KEY_REV] ? "&" . ProjectKeys::KEY_REV . "=" . $requestParams[ProjectKeys::KEY_REV] : "";
        $subSet = ($this->_isSubSet($requestParams[ProjectKeys::KEY_METADATA_SUBSET])) ? "&".ProjectKeys::KEY_METADATA_SUBSET."=".$requestParams[ProjectKeys::KEY_METADATA_SUBSET] : "";
        $timer = [
            'eventOnExpire' => "edit_project",
            'paramsOnExpire' => [
                'dataToSend' => ProjectKeys::KEY_ID . "=" . $requestParams[ProjectKeys::KEY_ID]
                    . "&" . ProjectKeys::KEY_TO_REQUIRE . "=true"
                    . "&" . ProjectKeys::KEY_PROJECT_TYPE . "=" . $requestParams[ProjectKeys::KEY_PROJECT_TYPE]
                    . $subSet
                    . $rev
            ],
            'eventOnCancel' => "cancel_project",
            'paramsOnCancel' => [
                'dataToSend' => ProjectKeys::KEY_ID . "=" . $requestParams[ProjectKeys::KEY_ID]
                    . "&" . ProjectKeys::KEY_LEAVERESOURCE . "=true"
                    . "&" . ProjectKeys::KEY_PROJECT_TYPE . "=" . $requestParams[ProjectKeys::KEY_PROJECT_TYPE]
                    . $subSet
                    . $rev
            ],
            'timeout' => ExpiringCalc::getExpiringTime($responseData['lockInfo']['time'], 1),
        ];
        return $timer;
    }

    private function _addRequireDialogRefreshParams(&$params, $requestParams, $responseData)
    {
        $project_and_subset = $requestParams[ProjectKeys::KEY_ID] ." ". $this->_getExtraTitle($requestParams[ProjectKeys::KEY_METADATA_SUBSET]);
        $params['action'] = PageKeys::KEY_REFRESH;
        $params['content']['requiring'] = [
            "message" => sprintf(WikiIocLangManager::getLang("require_message"),
                $project_and_subset,
                $responseData['lockInfo']['name'],
                date("H:i:s", ExpiringCalc::getExpiringData($responseData['lockInfo']['time'], 1))),
        ];
    }

    private function _addDialogParamsToParams(&$params, $requestParams, $responseData)
    {
        $project_and_subset = $requestParams[ProjectKeys::KEY_ID] ." ". $this->_getExtraTitle($requestParams[ProjectKeys::KEY_METADATA_SUBSET]);
        $params['action'] = "dialog";
        $params['timer']['timeout'] = 0;
        $params['dialog'] = [
            'title' => WikiIocLangManager::getLang("require_dialog_title"),
            'message' => sprintf(WikiIocLangManager::getLang("require_dialog_message"),
                $project_and_subset,
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

    private function _addRequireProject(&$ajaxCmdResponseGenerator, $params)
    {
        $ajaxCmdResponseGenerator->addRequireProject(
            $params[ProjectKeys::KEY_ID],
            $params[ProjectKeys::KEY_NS],
            $params['title'],
            $params['content'],
            $params['originalContent'],
            $params['action'],
            $params['timer'],
            $params['dialog'],
            $params['extra']
        );
    }

    protected function renderContent($content, $mode = 'xhtml')
    {
        $instructions = p_get_instructions($content);
        return p_render($mode, $instructions, $outInfo); // No fem res amb la info
    }

    public function getSubSet($subSet="", $default=FALSE, $suffix="") {
        if ($default) {
            $ret = ProjectKeys::VAL_DEFAULTSUBSET;
        }else {
            $ret = "";
            if ($subSet !== "" && $subSet !== ProjectKeys::VAL_DEFAULTSUBSET) {
                $ret = $suffix . $subSet;
            }
        }
        return $ret;
    }

    private function _getExtraTitle($subSet, $isSubSet=FALSE) {
        $extratitle = "";
        if ($subSet && ($isSubSet || $subSet !== ProjectKeys::VAL_DEFAULTSUBSET)) {
            $extratitle = "[subset: $subSet]";
        }
        return $extratitle;
    }

    private function _isSubSet($subSet=NULL) {
        return ($subSet && $subSet !== ProjectKeys::VAL_DEFAULTSUBSET);
    }

}
