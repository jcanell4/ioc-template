<?php
require_once(tpl_incdir() . 'cmd_response_handler/utility/AbstractFormBuilder.php');

/**
 * Construeix un array amb els atributs d'un camp del formulari
 */
class FieldBuilder extends AbstractFormBuilder {

    //private $id;        // igual a 'name' si no s'especifica altra cosa
    private $label;
    private $name;      // igual a 'id' si no s'especifica altra cosa
    private $type;
    private $columns;
    private $value;
    private $props = [];
    private $options = [];

    public function __construct($id=NULL, $label='', $type='text', $name=NULL, $columns=12, $priority=0) {
        $this->setId($id)
            ->setLabel($label)
            ->setType($type)
            ->setName($name)
            ->setColumns($columns)
            ->setPriority($priority);
    }

    public function build() {
        if (!($this->name && $this->id && $this->type)) {
            throw new Exception();
        }

        $field['id'] = $this->id;
        $field['name'] = $this->name;
        $field['formType'] = "field";
        $field['type'] = $this->type;
        $field['label'] = $this->label;
        $field['columns'] = $this->columns;
        $field['priority'] = $this->priority;
        $field['props'] = $this->props;
        $field['value'] = $this->value;

        if (count($this->options) > 0) {
            $field['options'] = $this->options;
        }

        return $field;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function setId($id) {
        $this->id = $id;

        if (!$this->name && $id)
            $this->setName($id);

        return $this;
    }

    public function setLabel($label) {
        $this->label = $label;
        return $this;
    }

    public function setName($name) {
        $this->name = $name;

        if (!$this->id && $name) 
            $this->setId($name);

        if (!$this->label && $name) 
            $this->setLabel($name);

        return $this;
    }

    public function setColumns($columns) {
        if ($columns > 12 || $columns < 1) {
            throw new WrongNumberOfColumnsFormBuilderException("", "Has indicat $columns columnes i el nombre màxim de columnes admés és 12");
        }
        $this->columns = $columns;
        return $this;
    }

    public function addProp($key, $value) {
        $this->props[$key] = $value;
        return $this;
    }

    public function addProps($props) {
        foreach ($props as $key => $value) {
            $this->addProp($key, $value);
        }

        return $this;
    }

    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    public function addOption($value, $description, $selected = false) {
        if ($this->type != 'select') {
            throw new Exception();
        }

        $option['value'] = $value;
        $option['description'] = $description;
        if ($selected) {
            $option['selected'] = true;
        }
        $this->options[] = $option;
        return $this;
    }

    public function addOptions($options) {
        $len = count($options);
        for ($i = 0; $i < $len; $i++) {
            $this->addOption($options['value'], $options['description'], $options['selected']);
        }
    }

}