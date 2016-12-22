<?php
/**
 * Construye los datos para la respuesta de la parte servidor en función de la petición
 */

if (!defined("DOKU_INC")) die();
if (!defined('DOKU_COMMAND')) define('DOKU_COMMAND', DOKU_PLUGIN . "ajaxcommand/");
if (!defined('DOKU_PLUGIN'))  define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');

require_once(tpl_incdir() . 'cmd_response_handler/WikiIocResponseHandler.php');
require_once(tpl_incdir() . 'cmd_response_handler/utility/FormBuilder.php');
require_once DOKU_PLUGIN . 'ajaxcommand/JsonGenerator.php';
require_once DOKU_COMMAND . 'requestparams/RequestParameterKeys.php';
require_once DOKU_PLUGIN . 'wikiiocmodel/WikiIocLangManager.php';
require_once DOKU_PLUGIN . 'wikiiocmodel/projects/documentation/DocumentationModelExceptions.php' ;

class ProjectResponseHandler extends WikiIocResponseHandler {

    private $rows = [];
    private $lastRow = 0;

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
        $id = str_replace(":", "_", $requestParams['id']);
        $ns = $requestParams['id'];
        $title = "Projecte $ns";

        $action = 'lib/plugins/ajaxcommand/ajax.php?call=project&do=save';
        //$form = $this->buildForm($id, $ns, $action, $responseData['projectMetaData']['structure']);
        $form = $this->newBuildForm($id, $ns, $action, $responseData['projectMetaData']['structure'], $responseData['projectViewData']);

        $values = $responseData['projectMetaData']['values'];
        $extra = ['projectType' => $requestParams['projectType']];

        $ajaxCmdResponseGenerator->addForm($id, $ns, $title, $form, $values, $extra);
    }

    /** El grid esta compuesto por 12 columnas
     * 
     * @param string: $id, $ns, $action
     * @param array: $structure, obtenido de configMain.json
     * @param array: $view, obtenido de defaultView.json
     * @return array
     */
    protected function newBuildForm($id, $ns, $action, $structure, $view) {

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
                throw new MissingValueFormBuilderException($ns, "El camp $keyField és obligatori i no té valor");

            //Se establecen los atributos del campo
            if ($arrValues['n_columns'])
                $columns = $arrValues['n_columns'];
            elseif ($arrValues['struc_chars'])
                $columns = $arrValues['struc_chars'] / $view['definition']['chars_column'];
            else
                $columns = $aGroups[$grupo]->getColumns();
            
            if (!$arrValues['struc_rows'])
                $arrValues['struc_rows'] = 1;
            
            $label = ($arrValues['label']) ? $arrValues['label'] : WikiIocLangManager::getLang('projectLabelForm')[$keyField];
            
            $aGroups[$grupo]->addElement(FormBuilder::createFieldBuilder()
                ->setId($arrValues['id'])
                ->setLabel(($label != NULL) ? $label : $keyField)
                ->setColumns($columns)
                ->setValue($arrValues['value'])
            );
        }

        $form = $builder->addElement($mainRow)
                    ->build();
        return $form;
    }

    /** El grid està compost per 12 columnes
     * Si no s'especifica el nombre de columnes s'utilitzen 6
     * Les columnes es poden especificar a:
     * - Group: indica el nombre de columnes que emplea el grup
     * - Field: indica el nombre de columnes que emplea el camp. S'ha de tenir en compte que es sobre 12 INDEPENDENMENT del nombre de columnes del grup ja que són niuades
     * 
     * @param string $id, $ns, $action
     * @param array $structure
     * @return array
     */
    protected function buildForm($id, $ns, $action, $structure) {

        $this->lastRow = 1; //es reserva la primera posició del array
        $builder = new FormBuilder($id, $action);
        
        $mainRow = FormBuilder::createRowBuilder()
            ->setTitle('Projecte: ' . $ns);

        //obtener el conjunto de grupos definidos en la estructura
        $groups = $this->getGroups($structure);

        //Como mínimo se creará el grupo 'main' aunque todos los elementos tengan grupo propio.
        //Al grupo 'main' se incorporarán todos los elementos que no tengan grupo
        foreach ($groups as $key => $value) {

            $aGroups[$key] = FormBuilder::createGroupBuilder()
                ->setTitle(WikiIocLangManager::getLang('projectGroup')[$key])
                ->setFrame(true);
        
            foreach ($value as $field) {
                if ($structure[$field]['type'] === 'array' || $structure[$field]['type'] === 'object') {
                    // Afegir a una altra fila
                    $this->generateRow($structure[$field], $field);
                } else {
                    if ($structure[$field]['mandatory'] === TRUE && (!$structure[$field]['value'] || $structure[$field]['value']==""))
                        throw new Exception();
                    if (!$structure[$field]['struc_chars'])
                        $structure[$field]['struc_chars'] = 40;
                    if (!$structure[$field]['struc_rows'])
                        $structure[$field]['struc_rows'] = 1;
                    
                    $aGroups[$key]->addElement(FormBuilder::createFieldBuilder()
                        ->setId($structure[$field]['id'])
                        ->setLabel(WikiIocLangManager::getLang('projectLabelForm')[$field])
                        ->setColumns($structure[$field]['struc_chars'])
                        ->setValue($structure[$field]['value'])
                        ->build() // Es construeix el camp
                    );
                }
            }
        }
        
        foreach ($aGroups as $key => $value) {
            $mainRow->addElement($aGroups[$key]->build()); // Es construeix el grup
        }
        $this->rows[0] = $mainRow->build(); // Es construeix la fila
        
        $form = $builder->addElements($this->rows)
                    ->build();

/*
        $mainGroup = FormBuilder::createGroupBuilder()
            ->setTitle('Dades generals del projecte')
            ->setFrame(true);
/*
        // Camps ocults obligatoris pel formulari
        $mainGroup->addFields([
            FormBuilder::createFieldBuilder()
                ->setLabel('Modificar el render per que no es mostri label')
                ->setType('hidden')
                ->setName('projectType')
                ->setValue($projectType)
                ->setColumns(0)
                ->build(),
            FormBuilder::createFieldBuilder()
                ->setLabel('Modificar el render per que no es mostri label')
                ->setType('hidden')
                ->setName('id')
                ->setValue($ns)
                ->setColumns(0)
                ->build()
        ]);
*
        foreach ($structure as $key => $value) {
            if ($value['tipus'] === 'array' || ($value['tipus'] === 'object')) {
                // Afegir a una altra fila
                $this->generateRow($value, $key);

            } else {

                $mainGroup->addField(FormBuilder::createFieldBuilder()
                    ->setId($value['id'])
                    ->setLabel($key)
                    ->setColumns(6)
                    ->setValue($value['value'])
                    ->build() // Es construeix el camp
                );
            }
        }

        $mainRow->addGroup($mainGroup->build()); // Es construeix el grup
        $this->rows[0] = $mainRow->build(); // Es construeix la fila

        $form = $builder->addRows($this->rows)
                    ->build();
*/
        return $form;
    }

    protected function generateRow($values, $title = '')
    {
        // El valor que ha arribat sempre és un objecte o un array
        // Si és un objecte (apartat) conté propietats i aquestes poden contenir un array
        // Si és un array (bloc) conté només elements. Es salta aquesta fila, l'index ha de ser el mateix

        if ($values['tipus'] === 'object') {
            $index = $this->lastRow; // Guardem el valor de $lastRow al entrar

            $row = FormBuilder::createRowBuilder();
            $group = FormBuilder::createGroupBuilder();
            $row->setTitle($title);
            $this->lastRow++; // Els objectes sempre afegiran una fila encara que no continguin res (que no ha de ser el cas)

            foreach ($values['value'] as $key => $value) {

                if ($value['tipus'] === 'object') {
                    $this->generateRow($value, $key);
                } else if ($value['tipus'] === 'array') {
                    $this->generateRow($value, $key);
                } else {
                    // TODO[Xavi] Canviar per un mètode i afegir la configuració per tipus
                    $group->addField(
                        FormBuilder::createFieldBuilder()
                            ->setId($value['id'])
                            ->setLabel($key)
                            ->setColumns(6)
                            ->setValue($value['value'])
                            ->build() // Es construeix el camp
                    );
                }
            }

            $group->setFrame(true);
            $row->addGroup($group->build());
            $this->rows[$index] = $row->build();

        } else if ($values['tipus'] === 'array') {
            // No es mostra per pantalla, per tant no cal incrementar el nombre de files

            $this->generateHeader($title);
            for ($i = 0, $len = count($values['value']); $i < $len; $i++) {
                // Primera passada: És processan dos blocs, el títol no es mostra
                // Segona passada: és una unitat
                $title = $values['itemsType'] . ' ' . ($i + 1);
                $this->generateRow($values['value'][$i], $title);
            }
        } else {
            throw new Exception();
        }
        // Si el valor és un array només es recorre però no s'ha de crear
    }

    private function generateHeader($title)
    {
        $row = FormBuilder::createRowBuilder();
        $row->setTitle($title);
        $this->rows[$this->lastRow] = $row->build();
        $this->lastRow++;
    }

    /** 
     * Obtener el array de grupos definidos en la estructura. Los elementos de la estructura
     * que no tienen grupo asignado se asignarán al grupo "main"
     * @return array
     */
    private function getGroups($structure) {
        $group = array();
        $group['main'] = array();
        foreach ($structure as $key => $value) {
            if ($value['group']) {
                $group[$value['group']][] = $key;
            }else {
                $group['main'][] = $key;
            }
        }
        return $group;
    }
}
