<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of diff_response_handler
 *
 * @author Xavier García<xaviergaro.dev@gmail.com>
 */

if (!defined("DOKU_INC")) {
    die();
}
if (!defined('DOKU_PLUGIN')) {
    define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
}

if (!defined('DOKU_COMMAND')) {
    define('DOKU_COMMAND', DOKU_PLUGIN . "ajaxcommand/");
}

require_once(tpl_incdir() . 'cmd_response_handler/WikiIocResponseHandler.php');
require_once(tpl_incdir() . 'cmd_response_handler/utility/FormBuilder.php');

require_once DOKU_PLUGIN . 'ajaxcommand/JsonGenerator.php';
require_once DOKU_COMMAND . 'requestparams/RequestParameterKeys.php';

class ProjectResponseHandler extends WikiIocResponseHandler
{

    private $rows = [];
    private $lastRow = 0;

    function __construct()
    {
        parent::__construct(WikiIocResponseHandler::PAGE);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator)
    {

        switch ($requestParams[RequestParameterKeys::DO_KEY]) {
            case 'edit':
                $this->editResponse($requestParams, $responseData, $ajaxCmdResponseGenerator);
                break;

            case 'save':
//                $this->saveResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator);
                break;

            default:
                // TODO[Xavi] Llençar una excepció personlitzada, no existeix aquest 'do'.
                throw new Exception();
        }


//		ALERTA[Xavi] Això es necessari? es troba enganxat en molts els response handlers
//		$ajaxCmdResponseGenerator->addProcessDomFromFunction(
//			$responseData['id'],
//			TRUE,
//			"ioc/dokuwiki/processContentPage",  //TODO configurable
//			array(
//				"ns"            => $responseData['ns'],
//				"editCommand"   => "lib/plugins/ajaxcommand/ajax.php?call=edit",
//				"detailCommand" => "lib/plugins/ajaxcommand/ajax.php?call=get_image_detail",
//			)
//		);
    }

    protected function editResponse($requestParams, $responseData, &$ajaxCmdResponseGenerator)
    {
        // El grid està compost per 12 columnes
        // Si no s'especifica el nombre de columnes s'utilitzen 6
        // Les columnes es poden especificar a:
        // * Group: indica el nombre de columnes que emplea el grup
        // * Field: indica el nombre de columnes que emplea el camp. S'ha de tenir en compte que es sobre 12 INDEPENDENMENT del nombre de columnes del grup ja que són niuades


        if ($responseData['info']) {
            $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);
        }

        $id = $requestParams['id']; // Alerta[Xavi] només és una prova, però s'ha de comptar que no es reemplcen els : si es fa servir una carpeta
        $ns = $requestParams['id'];
        $title = "Formulari TestForm";

        // TODO[Xavi] Dividir la generació del formulari en estrucutra i dades que corresponen a $responseData[project][structure] i  $responseData[project][values]


        $action = 'lib/plugins/ajaxcommand/ajax.php?call=project&do=save';
//        $form = FormBuilder::buildTestForm($id, $action, $requestParams['projectType']);
        $form = $this->buildForm($id, $action, $requestParams['projectType'], $responseData['projectMetaData']['structure']);


        $ajaxCmdResponseGenerator->addForm($id, $ns, $title, $form);

    }


    protected function buildForm($id, $action, $projectType, $structure)
    {
        $this->lastRow = 1; // Es reserva la primera posició del array
        //
        $builder = new FormBuilder();

        // Es fa una passada del primer nivell, tots els elements que no siguien o array va en la mateixa row i grup.
        $builder->setId($id)
            ->setAction($action);

        $mainRow = FormBuilder::createRowBuilder()
            ->setTitle('Projecte: ' . $id);


        $mainGroup = FormBuilder::createGroupBuilder()
            ->setTitle('Dades generals del projecte')//TODO[Xavi] Localitzar al lang
            ->setFrame(true);

        // Camps ocults obligatoris pel formulari
        $mainGroup->addFields([
            FormBuilder::createFieldBuilder()
                ->setLabel('Modificar el render per que no es mostri label')
                ->setType('hidden')
                ->setName('projectType')
                ->setValue($projectType)
                ->setColumns(12)
                ->build(),
            FormBuilder::createFieldBuilder()
                ->setLabel('Modificar el render per que no es mostri label')
                ->setType('hidden')
                ->setName('id')
                ->setValue($id)
                ->setColumns(12)
                ->build()
        ]);

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


        // Afegir totes les rows

//
//
//        $form = $builder
//            ->setId($id)
//            ->setAction($action)
//            ->addRow(
//                'Projecte: ' . $id, // Títol de prova
//                [
//                    $builder->createGroupBuilder()
//                        ->setTitle('Formulari de prova mini')
//                        ->setFrame(true)
//                        ->addFields(
//                            [
//                                $builder->createFieldBuilder()
//                                    ->setLabel('Títol')
//                                    ->setName('title')
//                                    ->setColumns(12)
//                                    ->build(),
//                                $builder->createFieldBuilder()
//                                    ->setLabel('Modificar el render per que no es mostri label')
//                                    ->setType('hidden')
//                                    ->setName('projectType')
//                                    ->setValue($projectType)
//                                    ->setColumns(12)
//                                    ->build(),
//                                $builder->createFieldBuilder()
//                                    ->setLabel('Modificar el render per que no es mostri label')
//                                    ->setType('hidden')
//                                    ->setName('id')
//                                    ->setValue($id)
//                                    ->setColumns(12)
//                                    ->build(),
//                            ]
//                        )
//                        ->build()
//                ]
//            )
//            ->build();

        return $form;
    }


    protected function generateRow($values, $title = '')
    {
        // El valor que ha arribat sempre és un objecte o un array
        // Si és un objecte (apartat) conté propietats i aquestes poden contenir un array

        // Sí és un array (bloc) conté només elements. Es salta aquesta fila, l'index ha de ser el mateix


        if ($values['tipus'] === 'object') {
            $index = $this->lastRow; // Guardem el valor de $lastRow al entrar

            $row = FormBuilder::createRowBuilder();
            $group = FormBuilder::createGroupBuilder();
            $row->setTitle($title);

            // TODO s'ha de recorre de forma diferent
//            $this->generateRowFromObject($value);

            $this->lastRow++; // Els objectes sempre afegiran una fila encara que no continguin res (que no ha de ser el cas)

            foreach ($values['value'] as $key => $value) {


                if ($value['tipus'] === 'object') {

                    $this->generateRow($value, $key);

                } else if ($value['tipus'] === 'array') {

                    $this->generateRow($value, $key);

                } else {

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

}
