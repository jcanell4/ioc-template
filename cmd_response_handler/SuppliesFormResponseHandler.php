<?php
/**
 * SuppliesFormResponseHandler
 * @author Rafael
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', WikiGlobalConfig::tplIncDir());
require_once(DOKU_TPL_INCDIR . 'conf/cfgIdConstants.php');
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/PageResponseHandler.php');
require_once(DOKU_TPL_INCDIR . "cmd_response_handler/utility/FormBuilder.php");

class SuppliesFormResponseHandler extends WikiIocResponseHandler {

    private $responseType = NULL;

    function __construct() {
        parent::__construct("supplies_form");
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $this->responseType = ($requestParams[ProjectKeys::KEY_ACTION]) ? $requestParams[ProjectKeys::KEY_ACTION] : $requestParams[ProjectKeys::KEY_DO];
        $outValues = [];
        $form = $this->buildForm($responseData[AjaxKeys::KEY_ID],
                                 $requestParams[AjaxKeys::KEY_ID],
                                 $responseData[ProjectKeys::KEY_PROJECT_METADATA],
                                 $responseData[ProjectKeys::KEY_PROJECT_VIEWDATA],
                                 $outValues);

        $ajaxCmdResponseGenerator->addEditProject($responseData[AjaxKeys::KEY_ID],
                                           $requestParams[AjaxKeys::KEY_ID],
                                           $responseData[PageKeys::KEY_TITLE],
                                           $form,
                                           $outValues);

//        $ajaxCmdResponseGenerator->addHtmlForm(
//                $responseData[AjaxKeys::KEY_ID],
//                $responseData[PageKeys::KEY_TITLE],
//                $responseData[PageKeys::KEY_CONTENT]['list'],
//                array(
//                    'urlBase' => "lib/exe/ioc_ajax.php?call=${responseData[AjaxKeys::KEY_ACTION_COMMAND]}",
//                    'formId' => $responseData[PageKeys::KEY_CONTENT]['formId'],
//                ),
//                array(
//                    'callAtt' => "call",
//                    'urlBase' => "lib/exe/ioc_ajax.php",
//                    'do' => $responseData[AjaxKeys::KEY_ACTION_COMMAND]
//                )
//        );

        $ajaxCmdResponseGenerator->addInfoDta(AjaxCmdResponseGenerator::generateInfo(
                RequestParameterKeys::KEY_INFO,
                WikiIocLangManager::getLang("select_projects_loaded"),
                $requestParams[AjaxKeys::KEY_ID]
        ));
    }

    protected function postResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $ajaxCmdResponseGenerator->addSetJsInfo($this->getJsInfo());
    }

    /** El grid esta compuesto por 12 columnas
     *
     * @param string: $id, $ns, $action
     * @param array: $structure, obtenido de configMain.json
     * @param array: $view, obtenido de defaultView.json
     * @return array
     */
    protected function buildForm($id, $ns, $structure, $view, &$outValues, $action=NULL, $form_readonly=FALSE, $extratitle="") {
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

                $this->updateReadonlyFromProps($arrValues);
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
                    if(($arrValues['type']=="objectArray")||($arrValues['type']=="array")||($arrValues['type']=="table")){
                        $rows = $arrValues['n_rows'] = 5;
                    }else{
                        $arrValues['n_rows'] = 1;
                        $rows = false;
                    }
                } else {
                    $rows = $arrValues['n_rows'];
                }

                $label = ($arrValues['label']) ? $arrValues['label'] : WikiIocLangManager::getLang('projectLabelForm')[$keyField];
                if (!$label) {
                    $label = $keyField;
                }

                $this->updateReadonlyFromConfig($arrValues);

                $aGroups[$grupo]->addElement(FormBuilder::createFieldBuilder()
                    ->setId($arrValues[ProjectKeys::KEY_ID])
                    ->setLabel(($label != NULL) ? $label : $keyField)
                    ->setType(($arrValues['type']) ? $arrValues['type'] : "text")
                    ->addProps($arrValues['props'])
                    ->addConfig($arrValues['config'])
                    ->setColumns($columns)
                    ->setRows($rows)
                    ->setValue($arrValues['value'])
                    ->addConfig($arrValues['typeDef']?["typeDef" => $arrValues['typeDef']]:null)
                    ->addConfig($arrValues['array_columns']?["array_columns" => $arrValues['array_columns']]:null)
                    ->addConfig($arrValues['array_rows']?["array_rows" => $arrValues['array_rows']]:null)
                );
            }
        }

        $form = $builder->addElement($mainRow)
            ->build();
        return $form;
    }

    protected function mergeStructureToForm($structure, &$viewFields, &$viewGroups, $viewDefinition, &$outValues, $mandatoryParent=false, $defaultParent="") {
        if (isset($structure['type'])) {
            $ret = $this->mergeStructureDefaultToForm($structure, $viewFields, $outValues, $mandatoryParent, $defaultParent);
        } else {
            $ret = $this->mergeStructureObjectToForm($structure, $viewFields, $viewGroups, $viewDefinition, $outValues, $mandatoryParent, $defaultParent);
        }
        return $ret;
    }

    protected function mergeStructureObjectToForm($structure, &$viewFields, &$viewGroups, $viewDefinition, &$outValues, $mandatoryParent=false, $defaultParent="") {
        $ret = false;
        foreach ($structure as $structureKey => $structureProperties) {
            if (isset($structureProperties['renderAsMultiField'])) {
                if (isset($structureProperties['value'])) {
                    $needGroup = $this->mergeStructureToForm($structureProperties['value'], $viewFields, $viewGroups, $viewDefinition, $outValues, $structureProperties['mandatory'], $structureProperties[ProjectKeys::KEY_ID]);
                    if ($needGroup) {
                        $viewGroups[$structureProperties[ProjectKeys::KEY_ID]]['label'] = $structureKey;
                        $viewGroups[$structureProperties[ProjectKeys::KEY_ID]]['frame'] = true;
                        $viewGroups[$structureProperties[ProjectKeys::KEY_ID]]['n_columns'] = $viewDefinition['n_columns'];
                        $viewGroups[$structureProperties[ProjectKeys::KEY_ID]]['parent'] = $defaultParent;
                        $ret = true;
                    }
                }
            } else {
                $ret = $this->mergeStructureToForm($structureProperties, $viewFields, $viewGroups, $viewDefinition, $outValues, $mandatoryParent, $defaultParent);
            }
        }
        return $ret;
    }

    protected function mergeStructureDefaultToForm($structureProperties, &$viewFields, &$outValues, $mandatoryParent=false, $defaultParent="") {
        $ret = false;
        if (array_key_exists($structureProperties[ProjectKeys::KEY_ID], $viewFields)) {
            //merge
            if(isset($structureProperties["viewType"]) && !isset($viewFields[$structureProperties[ProjectKeys::KEY_ID]]["type"])){
                $viewFields[$structureProperties[ProjectKeys::KEY_ID]]["type"] = $structureProperties["viewType"];
            }
            if(isset($structureProperties['props']) && isset($viewFields[$structureProperties[ProjectKeys::KEY_ID]]['props'])){
                $viewFields[$structureProperties[ProjectKeys::KEY_ID]]['props'] = array_merge($structureProperties['props'], $viewFields[$structureProperties[ProjectKeys::KEY_ID]]['props']);
            }
            $viewFields[$structureProperties[ProjectKeys::KEY_ID]] = array_merge(array(), $structureProperties, $viewFields[$structureProperties[ProjectKeys::KEY_ID]]);
        } else {
            if ($mandatoryParent || $structureProperties['mandatory']) {
                $ret = true;
                $viewFields[$structureProperties[ProjectKeys::KEY_ID]] = $structureProperties;
                if(isset($structureProperties["viewType"])){
                    $viewFields[$structureProperties[ProjectKeys::KEY_ID]]["type"] = $structureProperties["viewType"];
                }
                $viewFields[$structureProperties[ProjectKeys::KEY_ID]]['group'] = $defaultParent;
            }
        }
        if (isset($viewFields[$structureProperties[ProjectKeys::KEY_ID]]['defaultRow'])) {
            if (!isset($viewFields[$structureProperties[ProjectKeys::KEY_ID]]['config'])) {
                $viewFields[$structureProperties[ProjectKeys::KEY_ID]]['config'] = [];
            }
            $viewFields[$structureProperties[ProjectKeys::KEY_ID]]['config']['defaultRow'] = $viewFields[$structureProperties[ProjectKeys::KEY_ID]]['defaultRow'];
            if( $structureProperties["type"]==="objectArray" && !isset($viewFields[$structureProperties[ProjectKeys::KEY_ID]]['config']["fields"])){
                $viewFields[$structureProperties[ProjectKeys::KEY_ID]]['config']["fields"]=[];
                foreach ($viewFields[$structureProperties[ProjectKeys::KEY_ID]]['defaultRow'] as $key => $value) {
                    if(isset($structureProperties["rowTypes"]) && isset($structureProperties["rowTypes"][$key])){
                        $viewFields[$structureProperties[ProjectKeys::KEY_ID]]['config']["fields"][$key]=["type" => $structureProperties["rowTypes"][$key]];
                    }else{
                        $viewFields[$structureProperties[ProjectKeys::KEY_ID]]['config']["fields"][$key]=["type" => "string" ];
                    }
                }
            }
        }

        if ($this->responseType !== "edit"  && !(isset($structureProperties["parseOnView"]) && $structureProperties["parseOnView"])){
            $mode="";
            if(isset($structureProperties['config']['renderable']) && $structureProperties['config']['renderable']) {
                $mode = $structureProperties['config']['mode'];
            }elseif (isset($viewFields[$structureProperties[ProjectKeys::KEY_ID]]['config']['renderable']) && $viewFields[$structureProperties[ProjectKeys::KEY_ID]]['config']['renderable']) {
                $mode = $viewFields[$structureProperties[ProjectKeys::KEY_ID]]['config']['mode'];
            }
            if(!empty($mode)){
                $originalValue = $structureProperties['value'];
                $structureProperties['value'] = $this->renderContent($originalValue, $mode);
                $outValues[$structureProperties[ProjectKeys::KEY_ID]] = $structureProperties['value'];
            }
        }

        $outValues[$structureProperties[ProjectKeys::KEY_ID]] = $structureProperties['value'];

        return $ret;
    }

    protected function updateReadonlyFromProps(&$outArrValues) {
        $this->updateReadonlyFrom($outArrValues, "props");
    }

    protected function updateReadonlyFromConfig(&$outArrValues) {
        $this->updateReadonlyFrom($outArrValues, "config");
    }

    private function updateReadonlyFrom(&$outArrValues, $fromAtt) {
        if (!isset($outArrValues[$fromAtt]) || !isset($outArrValues[$fromAtt]['readonly'])) {
            return; //no s'ha establert la propietat al config, no cal fer res
        }
        $isReadOnly = $outArrValues[$fromAtt]['readonly'];

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

        $outArrValues[$fromAtt]['readonly'] = $isReadOnly;

        if($fromAtt!=="props"){
            if (!isset($outArrValues['props'])) {
                $outArrValues['props'] = [];
            }
            $outArrValues['props']['readonly'] = $isReadOnly;
        }
    }

    protected function renderContent($content, $mode = 'xhtml') {
        $instructions = p_get_instructions($content);
        return p_render($mode, $instructions, $outInfo); // No fem res amb la info
    }

}
