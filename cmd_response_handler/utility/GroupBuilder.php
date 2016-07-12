<?php

/**
 * Description of GroupBuilder
 *
 * @author Xavier García <xaviergaro.dev@gmail.com>
 */
class GroupBuilder
{

    protected $id;
    protected $title;
    protected $hasFrame;
    protected $columns;
    protected $priority;
    protected $fields = [];

    public function __construct($id = null, $title = null, $hasFrame = false, $columns = 12, $priority = 0)
    {
        $this->setId($id)
            ->setTitle($title)
            ->setFrame($hasFrame)
            ->setColumns($columns)
            ->setPriority($priority);
    }

    public function build()
    {
        if ($this->id) {
            $group['id'] = $this->id;
        }

        if ($this->title) {
            $group['title'] = $this->title;
        }

        if ($this->hasFrame) {
            $group['hasFrame'] = $this->hasFrame;
        }

        $group['columns'] = $this->columns;
        $group['priority'] = $this->priority;
        $group['fields'] = $this->fields;

        return $group;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setFrame($frame)
    {
        $this->hasFrame = $frame;
        return $this;
    }

    public function setColumns($columns)
    {
        if ($columns>12 || $columns <1) {
            throw new Exception();
        }

        $this->columns = $columns;
        return $this;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    public function addField($field)
    {
        $this->fields[] = $field;
        return $this;
    }

    public function addFields(array $fields)
    {
        for ($i = 0, $len = count($fields); $i < $len; $i++) {
            $this->addField($fields[$i]);
        }
        return $this;
    }

}