<?php
/**
 * Description of WikiIocModelException
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */

class WikiIocTplGeneratorException extends Exception {
    public function __construct($message, $code, $previous=NULL) {
        parent::__construct($message, $code, $previous);
    }
}
class RequiredParamNotFoundException extends WikiIocTplGeneratorException {
    public function __construct($param, $message="Required param %s not found", $code=2001, $previous=NULL) {
        parent::__construct(sprintf($message, $param), $code, $previous);
    }
}
class UnknownTypeParamException extends WikiIocTplGeneratorException {
    public function __construct($type, $message="The param type %s is unknown", $code=2002, $previous=NULL) {
        parent::__construct(sprintf($message, $type), $code, $previous);
    }
}
class FileScriptNotFoundException extends WikiIocTplGeneratorException {
    public function __construct($file, $message="File %s not found", $code=2003, $previous=NULL) {
        parent::__construct(sprintf($message, $file), $code, $previous);
    }
}
