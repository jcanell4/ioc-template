<?php
/**
 * ProjectResponseHandler: Construye los datos para la respuesta de la parte servidor en función de la petición
 * @culpable Rafael Claver
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_PLUGIN'))  define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
if (!defined('DOKU_COMMAND')) define('DOKU_COMMAND', DOKU_PLUGIN . "ajaxcommand/");
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', tpl_incdir());

require_once(DOKU_TPL_INCDIR."cmd_response_handler/WikiIocResponseHandler.php");
require_once(DOKU_TPL_INCDIR."cmd_response_handler/utility/FormBuilder.php");
require_once(DOKU_COMMAND."JsonGenerator.php");
require_once(DOKU_COMMAND."defkeys/RequestParameterKeys.php");
require_once(DOKU_PLUGIN."wikiiocmodel/WikiIocLangManager.php");
require_once(DOKU_PLUGIN."wikiiocmodel/projects/documentation/DocumentationModelExceptions.php");

class ProjectResponseHandler extends WikiIocResponseHandler {

    function __construct() {
        parent::__construct(WikiIocResponseHandler::PAGE);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {

        switch ($requestParams[RequestParameterKeys::DO_KEY]) {
            case 'edit':
                $this->editResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                break;

            case 'save':
                $ajaxCmdResponseGenerator->addProcessFunction(true, "ioc/dokuwiki/processSaving");
                $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
                break;

            case 'create':
                $this->editResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                break;

            case 'generate':
                if ($responseData['info'])
                    $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
                break;

            default:
                throw new Exception();
        }

    }

    protected function editResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        if ($responseData['info']) {
            $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
        }
        $id = $responseData['id'];
        $ns = $requestParams['id'];
        $title = "Projecte $ns";
        $action = 'lib/plugins/ajaxcommand/ajax.php?call=project&do=save';
        $form = $this->buildForm($id, $ns, $action, $responseData['projectMetaData']['structure'], $responseData['projectViewData']);
        $values = $responseData['projectMetaData']['values'];
        //El action que dispara este ProjectResponseHandler envía el array projectExtraData
        $extra = $responseData['projectExtraData'];

        $ajaxCmdResponseGenerator->addForm($id, $ns, $title, $form, $values, $extra);
    }

    /** El grid esta compuesto por 12 columnas
     *
     * @param string: $id, $ns, $action
     * @param array: $structure, obtenido de configMain.json
     * @param array: $view, obtenido de defaultView.json
     * @return array
     */
    protected function buildForm($id, $ns, $action, $structure, $view) {

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

        //Construye, como objetos, los campos definidos en la vista y los enlaza a los grupos correspondientes
        foreach ($view['fields'] as $keyField => $valField) {

            //combina los atributos y valores de los arrays de estructura y de vista
            $arrValues = array_merge($structure[$keyField], $valField);

            //obtiene el grupo, al que pertenece este campo, de la vista o, si no lo encuentra, de la estructura
            $grupo = ($arrValues['group']) ? $arrValues['group'] : "main";
            if (!$aGroups[$grupo])
                throw new MissingGroupFormBuilderException($ns, "El grup \'$grupo\' no està definit a la vista.");

            if ($arrValues['mandatory'] === TRUE && (!$arrValues['value'] || $arrValues['value']==""))
                throw new MissingValueFormBuilderException($ns, "El camp \'$keyField\' és obligatori i no té valor");

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

}
