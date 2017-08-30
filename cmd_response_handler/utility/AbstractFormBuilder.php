<?php
/**
 * Definición general de variables y métodos get accesibles
 * @culpable Rafael Claver
 */
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', tpl_incdir());
require_once(DOKU_TPL_INCDIR . "cmd_response_handler/utility/FieldBuilder.php");
require_once(DOKU_TPL_INCDIR . "cmd_response_handler/utility/GroupBuilder.php");
require_once(DOKU_TPL_INCDIR . "cmd_response_handler/utility/RowBuilder.php");

class AbstractFormBuilder {

    protected $id;
    protected $title;
    protected $priority;    //un valor més alt indica major prioritat
    protected $elements = [];

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getElements() {
        return $this->elements;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function setPriority($priority) {
        $this->priority = $priority;
        return $this;
    }

    public function addElement(&$element) {
        $this->elements[] = $element;
        return $this;
    }

    public function addElements(&$elements) {
        foreach ($elements as $elem) {
            $this->addElement($elem);
        }
        return $this;
    }

    protected function buildElements() {
        foreach ($this->elements as $elem) {
            $ret[] = $elem->build();
        }
        return $ret;
    }

}
