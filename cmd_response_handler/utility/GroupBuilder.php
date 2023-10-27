<?php
require_once(tpl_incdir() . 'cmd_response_handler/utility/AbstractFormBuilder.php');

/**
 * Construeix un array amb els atributs d'un grup del formulari
 */
class GroupBuilder extends AbstractFormBuilder {

    protected $frame;
    protected $columns;

    public function __construct($id=NULL, $title=NULL, $frame=FALSE, $columns=12, $priority=0) {
        $this->setId($id)
            ->setTitle($title)
            ->setFrame($frame)
            ->setColumns($columns)
            ->setPriority($priority);
    }

    public function build() {
        $group = [];
        if ($this->id)
            $group['id'] = $this->id;

        if ($this->title)
            $group['title'] = $this->title;

        $group['formType'] = "group";
        $group['frame'] = $this->frame;
        $group['columns'] = $this->columns;

        if ($this->rows) {
            $group['rows'] = $this->rows;
        }

        $group['priority'] = $this->priority;
        if(count($this->props)>0){
            $group['props'] = $this->props;
        }
        if(count($this->config)>0){
            $group['config'] = $this->config;
        }
        $group['elements'] = $this->buildElements();
        return $group;
    }

    public function setFrame($frame) {
        $this->frame = $frame;
        return $this;
    }

    public function setColumns($columns) {
        if ($columns > 12 || $columns < 1) {
            throw new WrongNumberOfColumnsFormBuilderException($this->id, $columns);
        }
        $this->columns = $columns;
        return $this;
    }

    public function hasData() {
        return ($this->columns !== NULL && $this->title !== NULL);
    }

    public function getColumns() {
        return $this->columns;
    }

    public function setRows($rows) {
        if ($rows) {
            $this->rows = $rows;
        }

        return $this;
    }
}