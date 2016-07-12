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
        $form = $this->buildTestForm($id, $action, $requestParams['projectType']);


        $ajaxCmdResponseGenerator->addForm($id, $ns, $title, $form);

    }

    protected function buildForm($id, $action, $projectType, $structure)
    {
        $builder = new FormBuilder();

        $form = $builder
            ->setId($id)
            ->setAction($action)
            ->addRow(
                'Projecte: ' . $id, // Títol de prova
                [
                    $builder->createGroupBuilder()
                        ->setTitle('Formulari de prova mini')
                        ->setFrame(true)
                        ->addFields(
                            [
                                $builder->createFieldBuilder()
                                    ->setLabel('Títol')
                                    ->setName('title')
                                    ->setColumns(12)
                                    ->build(),
                                $builder->createFieldBuilder()
                                    ->setLabel('Modificar el render per que no es mostri label')
                                    ->setType('hidden')
                                    ->setName('projectType')
                                    ->setValue($projectType)
                                    ->setColumns(12)
                                    ->build(),
                                $builder->createFieldBuilder()
                                    ->setLabel('Modificar el render per que no es mostri label')
                                    ->setType('hidden')
                                    ->setName('id')
                                    ->setValue($id)
                                    ->setColumns(12)
                                    ->build(),
                            ]
                        )
                        ->build()
                ]
            )
            ->build();

        return $form;
    }

    protected function buildTestForm($id, $action, $projectType)
    {
        $builder = new FormBuilder();

        $form = $builder
            ->setId($id)
            ->setAction($action)
            ->addRow(
                'Paràmetres de Dokuwiki',
                [
                    FormBuilder::createGroupBuilder()
                        ->setTitle('Paràmetres bàsics')
                        ->setFrame(true)
                        ->setcolumns(3)
                        ->addFields(
                            [
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
                                    ->build(),
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Títol del wiki')
                                    ->setName('title')
                                    ->setColumns(12)
                                    ->setPriority(1)
                                    ->build(),
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Nom de la pàgina d\'inici')
                                    ->setName('start')
                                    ->setColumns(12)
                                    ->setPriority(10)
                                    ->build(),
                            ]
                        )
                        ->build(),
                    FormBuilder::createGroupBuilder()
                        ->setFrame(true)
                        ->addFields(
                            [
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Canvis recents')
                                    ->setType('number')
                                    ->setName('recent')
                                    ->setPriority(10)
                                    ->build(),
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Quantitat de canvis recentes que es mantenenanvis recents')
                                    ->setType('number')
                                    ->setName('recent_Days')
                                    ->setPriority(1)
                                    ->addProp('placeholder', 'Quantitat de canvis recents en dies')
                                    ->build(),
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Camp d\'amplada total')
                                    ->setName('amplada')
                                    ->setColumns(12)
                                    ->setPriority(1)
                                    ->build(),
                            ]
                        )
                        ->build(),
                    FormBuilder::createGroupBuilder()
                        ->setTitle('Títol del test sense frame')
                        ->setPriority(10)
                        ->setColumns(3)
                        ->addFields(
                            [
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Utilitza llistes de control')
                                    ->setType('checkbox')
                                    ->setName('useacl')
                                    ->setPriority(10)
                                    ->addProp('checked', true)
                                    ->build(),
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Notificacions')
                                    ->setType('checkbox')
                                    ->setName('notifications')
                                    ->setPriority(10)
                                    ->build(),
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Camp d\'amplada total 2')
                                    ->setName('amplada 2')
                                    ->setColumns(12)
                                    ->setPriority(1)
                                    ->build(),
                            ]
                        )
                        ->build()
                ])
            ->addRow(
                'Segona fila',
                [
                    FormBuilder::createGroupBuilder()
                        ->setTitle('Amplada de columna 6 = 50%')
                        ->setFrame(true)
                        ->setPriority(10)
                        ->addFields(
                            [
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Camp d\'amplada màxima')
                                    ->setName('surname')
                                    ->addProp('placeholder', 'Introdueix el cognom')
                                    ->build()
                            ]
                        )
                        ->build(),
                    FormBuilder::createGroupBuilder()
                        ->setFrame(true)
                        ->setTitle('Grup d\'amplada 3 = 25%')
                        ->setColumns(3)
                        ->addFields([
                            FormBuilder::createFieldBuilder()
                                ->setLabel('Camp d\'amplada mitja 6 = 50%')
                                ->setName('name2')
                                ->build(),
                            FormBuilder::createFieldBuilder()
                                ->setLabel('Camp d\'amplada mitja 6 = 50%')
                                ->setName('name3')
                                ->build(),
                            FormBuilder::createFieldBuilder()
                                ->setLabel('Camp d\'amplada completa 12 = 100%')
                                ->setName('name3')
                                ->setColumns(12)
                                ->setPriority(99)
                                ->build()
                        ])
                        ->build(),
                    FormBuilder::createGroupBuilder()
                        ->setColumns(3)
                        ->setPriority(10)
                        ->addFields([
                            FormBuilder::createFieldBuilder()
                                ->setLabel('Camp d\'amplada completa dins d\'amplada 3 = 25%')
                                ->setName('name4')
                                ->setColumns(12)
                                ->setPriority(10)
                                ->build(),
                        ])
                        ->build()
                ]
            )
            ->addRow(
                'Demostració controls afegits',
                [
                    FormBuilder::createGroupBuilder()
                        ->setTitle('check/radio')
                        ->setFrame(true)
                        ->addFields(
                            [
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Checkbox1')
                                    ->setName('check1')
                                    ->setType('checkbox')
                                    ->setColumns(2)
                                    ->addProp('checked', 'true')
                                    ->build(),
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Checkbox2')
                                    ->setName('check2')
                                    ->setType('checkbox')
                                    ->setColumns(2)
                                    ->build(),
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Radio 1 (grp 1)')
                                    ->setName('radio-group-1')
                                    ->setColumns(2)
                                    ->setType('radio')
                                    ->addProp('checked', 'true')
                                    ->build(),
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Radio 2 (grp 1)')
                                    ->setName('radio-group-1')
                                    ->setColumns(2)
                                    ->setType('radio')
                                    ->addProp('checked', 'true')
                                    ->build(),
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Radio 1 (grp 1)')
                                    ->setName('radio-group-2')
                                    ->setColumns(2)
                                    ->setType('radio')
                                    ->addProp('checked', 'true')
                                    ->build(),
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Radio 2 (grp 2)')
                                    ->setName('radio-group-2')
                                    ->setColumns(2)
                                    ->setType('radio')
                                    ->addProp('checked', 'true')
                                    ->build(),
                            ]
                        )
                        ->build(),
                    FormBuilder::createGroupBuilder()
                        ->setTitle('Grup d\'amplada 3 = 25%')
                        ->setColumns(3)
                        ->addFields([
                            FormBuilder::createFieldBuilder()
                                ->setLabel('Camp d\'amplada mitja 6 = 50%')
                                ->setName('name2')
                                ->build(),
                            FormBuilder::createFieldBuilder()
                                ->setLabel('Camp d\'amplada mitja 6 = 50%')
                                ->setName('name3')
                                ->build(),
                            FormBuilder::createFieldBuilder()
                                ->setLabel('Camp d\'amplada completa 12 = 100%')
                                ->setName('name3')
                                ->setColumns(12)
                                ->setPriority(99)
                                ->build()
                        ])
                        ->build(),
                    FormBuilder::createGroupBuilder()
                        ->setTitle('Grup d\'amplada 3 = 25%')
                        ->setColumns(3)
                        ->addFields([
                            FormBuilder::createFieldBuilder()
                                ->setLabel('Nom43')
                                ->setName('name43')
                                ->setColumns(12)
                                ->build(),
                        ])
                        ->build(),
                    FormBuilder::createGroupBuilder()
                        ->setTitle('Demostració textarea i select')
                        ->setColumns(12)
                        ->addFields([
                            FormBuilder::createFieldBuilder()
                                ->setLabel('Textarea')
                                ->setType('textarea')
                                ->setName('demotextarea')
                                ->setColumns(9)
                                ->addProp('rows', 5)
                                ->build(),
                            FormBuilder::createFieldBuilder()
                                ->setLabel('Demostració select')
                                ->setType('select')
                                ->setName('demoselect')
                                ->setColumns(3)
                                ->addOption('B', 'Barceolna')
                                ->addOption('T', 'Tarragona')
                                ->addOption('L', 'Lleida', true)
                                ->addOption('G', 'Girona')
                                ->build(),
                        ])
                        ->build(),
                ]
            )
            ->build();

        return $form;
    }
}
