<?php
require_once(tpl_incdir() . 'cmd_response_handler/utility/FieldBuilder.php');
require_once(tpl_incdir() . 'cmd_response_handler/utility/GroupBuilder.php');


/**
 * Description of FormBuilder
 *
 * @author Xavier García <xaviergaro.dev@gmail.com>
 */
class FormBuilder
{

    private $id;
    private $method;
    private $action;
    private $rows = [];


    public function __construct($id = null, $action = null, $method = 'GET')
    {
        $this->setId($id)
            ->setAction($action)
            ->setMethod($method);
    }

    public function build()
    {
        $form = [];

        // TODO: generar el form i retornarlo
        if (!($this->id && $this->action)) {
            // Si no estàn tots definits s'ha de llençar excepció
            throw new Exception();
        }

        $form['id'] = $this->id;
        $form['method'] = $this->method;
        $form['action'] = $this->action;
        $form['rows'] = $this->rows;

        return $form;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    public function addRow($title, array $groups)
    {
        // TODO[Xavi]
        $row['title'] = $title;
        $row['groups'] = $groups;
        $this->rows[] = $row;

        return $this;
    }

    public static function createFieldBuilder()
    {
        return new FieldBuilder();
    }

    public static function createGroupBuilder()
    {
        return new GroupBuilder();
    }

}
