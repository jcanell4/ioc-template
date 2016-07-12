<?php

/**
 * Description of FormBuilder
 *
 * @author Xavier García <xaviergaro.dev@gmail.com>
 */
class FormBuilder {

    private $id;
    private $method;
    private $action;



    public function __construct($id = null, $action = null, $method = 'GET')
    {
        $this->id = $id;
        $this->action = $action;
        $this->method = $method;
    }

    public function build() {
        $form = [];

        // TODO: generar el form i retornarlo
        if (!($this->id && $this->action)) {
            // Si no estàn tots definits s'ha de llençar excepció
            throw new Exception();
        }

        return $form;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }


    public function setMethod($method) {
        $this->method = $method;
        return $this;
    }

    public function setAction($action) {
        $this->action = $action;
        return $this;
    }


}
