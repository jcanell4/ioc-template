<?php

/**
 * Description of GroupBuilder
 *
 * @author Xavier García <xaviergaro.dev@gmail.com>
 */
class FieldBuilder
{

    private $id;
    private $label;
    private $name; // igual al id si no s'especifica altra cosa
    private $type;
    private $columns;
    private $priority;
    private $value;
    private $props = [];
    private $options = [];

//      'label' => 'Nom de la pàgina d\'inici', // Etiqueta del formulari
//	'name' => 'start',
//	'value' => '',
//	'type' => 'text',
//      'columns' => 40,
//	'priority' => 10, // Més alt es més prioritari
//	'props' => ['placeholder' => 'Introdueix el nom de la pàgina d\'inici']

    public function __construct($id=null, $label='', $type='text', $name=null, $columns=40, $priority=0) {
        $this->setId($id)
            ->setLabel($label)
            ->setType($type)
            ->setName($name)
            ->setColumns($columns)
            ->setPriority($priority);
    }

    public function build()
    {
        if (!($this->name && $this->id && $this->type)) {
            throw new Exception();
        }

        $field['id'] = $this->id;
        $field['name'] = $this->name;
        $field['type'] = $this->type;
        $field['label'] = $this->label;
        $field['columns'] = $this->columns;
        $field['priority'] = $this->priority;
        $field['props'] = $this->props;
        $field['value'] = $this->value;

        if (count($this->options)>0) {
            $field['options'] = $this->options;
        }

        return $field;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;

        if (!$this->name && $id) {
            $this->setName($id);
        }

        return $this;
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;

        if (!$this->id && $name) {
            $this->setId($name);
        }

        return $this;
    }

    public function setColumns($columns) {
//        if ($columns > 12 || $columns < 1) {
//            throw new Exception();
//        }
        $this->columns = $columns;
        return $this;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    public function addProp($key, $value)
    {
        $this->props[$key] = $value;
        return $this;
    }

    public function addProps(array $props)
    {
        foreach ($props as $key => $value) {
            $this->addProp($key, $value);
        }

        return $this;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function addOption($value, $description, $selected = false)
    {
        if ($this->type != 'select') {
            throw new Exception();
        }

        $option = ['value' => $value, 'description' => $description];

        if ($selected) {
            $option['selected'] = true;
        }

        $this->options[] = $option;


        return $this;
    }

    public function addOptions(array $options)
    {
        for ($i = 0, $len = count($options); $i < $len; $i++) {
            $this->addOption($options['value'], $options['description'], $options['selected']);
        }

    }

}