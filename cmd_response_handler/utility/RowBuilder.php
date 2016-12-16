<?php
require_once(tpl_incdir() . 'cmd_response_handler/utility/AbstractFormBuilder.php');

/**
 * Description of RowBuilder
 */
class RowBuilder extends AbstractFormBuilder {

    public function __construct($id='', $title='', $priority=0) {
        $this->setId($id)
            ->setTitle($title)
            ->setPriority($priority);
    }

    public function build() {
        $row = [];

        $row['id'] = $this->id;
        $row['title'] = $this->title;
        $row['formType'] = "row";
        $row['elements'] = $this->buildElements();   //$this->elements;
        return $row;
    }

}