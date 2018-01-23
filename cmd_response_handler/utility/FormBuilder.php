<?php
/**
 * FormBuilder
 */
if (!defined('DOKU_INC')) die();
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', WikiGlobalConfig::tplIncDir());
require_once(DOKU_TPL_INCDIR . "cmd_response_handler/utility/AbstractFormBuilder.php");

class FormBuilder extends AbstractFormBuilder {

    private $method;
    private $action;

    public function __construct($id=NULL, $action=NULL, $method='GET') {
        $this->setId($id)
            ->setAction($action)
            ->setMethod($method);
    }

    public function build() {
        $form = [];
        if (!($this->id && $this->action)) {
            throw new Exception("Exception in FormBuilder->build()<br> [ ! (\$this->id && \$this->action) ]<br>- \$this->id='$this->id'<br>- \$this->action='$this->action'", 9999);
        }
        $form['id'] = $this->id;
        $form['method'] = $this->method;
        $form['action'] = $this->action;
        $form['formType'] = "form";
        $form['elements'] = $this->buildElements();

        return $form;
    }

    public function setMethod($method) {
        $this->method = $method;
        return $this;
    }

    public function setAction($action) {
        $this->action = $action;
        return $this;
    }

    public static function createFieldBuilder() {
        return new FieldBuilder();
    }

    public static function createGroupBuilder() {
        return new GroupBuilder();
    }

    public static function createRowBuilder() {
        return new RowBuilder();
    }

    /*
    // Funció de prova que retorna un formulari construit
    public static function buildTestForm($id, $action, $projectType) {
        $builder = new FormBuilder();

        $form = $builder
            ->setId($id)
            ->setAction($action)
            ->addElement(
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
                                    ->build(),
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Modificar el render per que no es mostri label')
                                    ->setType('hidden')
                                    ->setName('id')
                                    ->setValue($id)
                                    ->build(),
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Títol del wiki')
                                    ->setName('title')
                                    ->setPriority(1)
                                    ->build(),
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Nom de la pàgina d\'inici')
                                    ->setName('start')
                                    ->setPriority(10)
                                    ->build(),
                            ]
                        )
                        ->build(),
                    FormBuilder::createGroupBuilder()
                        ->setFrame(true)
                        ->setColumns(6)
                        ->addFields(
                            [
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Canvis recents')
                                    ->setType('number')
                                    ->setName('recent')
                                    ->setColumns(6)
                                    ->setPriority(10)
                                    ->build(),
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Quantitat de canvis recentes que es mantenenanvis recents')
                                    ->setType('number')
                                    ->setName('recent_Days')
                                    ->setColumns(6)
                                    ->setPriority(1)
                                    ->addProp('placeholder', 'Quantitat de canvis recents en dies')
                                    ->build(),
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Camp d\'amplada total')
                                    ->setName('amplada')
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
                                    ->setColumns(6)
                                    ->setPriority(10)
                                    ->addProp('checked', true)
                                    ->build(),
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Notificacions')
                                    ->setType('checkbox')
                                    ->setName('notifications')
                                    ->setColumns(6)
                                    ->setPriority(10)
                                    ->build(),
                                FormBuilder::createFieldBuilder()
                                    ->setLabel('Camp d\'amplada total 2')
                                    ->setName('amplada 2')
                                    ->setPriority(1)
                                    ->build(),
                            ]
                        )
                        ->build()
                ])
            ->addElement(
                'Segona fila',
                [
                    FormBuilder::createGroupBuilder()
                        ->setTitle('Amplada de columna 6 = 50%')
                        ->setFrame(true)
                        ->setColumns(6)
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
                                ->setColumns(6)
                                ->setName('name2')
                                ->build(),
                            FormBuilder::createFieldBuilder()
                                ->setLabel('Camp d\'amplada mitja 6 = 50%')
                                ->setName('name3')
                                ->setColumns(6)
                                ->build(),
                            FormBuilder::createFieldBuilder()
                                ->setLabel('Camp d\'amplada completa 12 = 100%')
                                ->setName('name3')
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
                                ->setPriority(10)
                                ->build(),
                        ])
                        ->build()
                ]
            )
            ->addElement(
                'Demostració controls afegits',
                [
                    FormBuilder::createGroupBuilder()
                        ->setTitle('check/radio')
                        ->setFrame(true)
                        ->setColumns(6)
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
                                ->setColumns(6)
                                ->build(),
                            FormBuilder::createFieldBuilder()
                                ->setLabel('Camp d\'amplada mitja 6 = 50%')
                                ->setName('name3')
                                ->setColumns(6)
                                ->build(),
                            FormBuilder::createFieldBuilder()
                                ->setLabel('Camp d\'amplada completa 12 = 100%')
                                ->setName('name3')
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
                                ->build(),
                        ])
                        ->build(),
                    FormBuilder::createGroupBuilder()
                        ->setTitle('Demostració textarea i select')
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
    */
}
