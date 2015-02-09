<?php

/**
 * Description of WikiIocBuilderManager.
 * Aquesta classe es un Singleton, s'obté la instància amb WikiIocBuiderManager::Instance().
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
class WikiIocBuilderManager {
    public static $MESSAGES = array(
        "Error. El nom del paquet està repetit",
        "Error. Ja existeix un altre paquet amb la mateixa localització i nom diferent"
    );
    public static $REPEATED_PACKAGE_NAME = 0;
    public static $REPEATED_PACKAGE_LOC = 1;
    private $resourcePackages;
    private $locationController;

    /**
     * Crea una nova instància o si ja existeix la retorna.
     *
     * @return WikiIocBuilderManager
     */
    public static function Instance() {
        static $inst = NULL;
        if($inst === NULL) {
            $inst = new WikiIocBuilderManager();
        }
        return $inst;
    }

    /**
     * El constructor es privat perquè es un Singleton, s'ha de fer servir WikiIocBuilderManager::Instance()
     */
    private function __construct() {
        $this->locationController = array();
        $this->resourcePackages   = array();
    }

    /**
     * Afegeix la informació del package passat com argument.
     *
     * @param array[] $obj es hash amb la informació del package i la seva localització amb el format:
     *                     "array("name" => "ioc", "location" => $js_packages["ioc"])
     *
     * @throws Exception si el nom o la localització estan repetits
     */
    function putRequiredPackage($obj) {
        $existName     = array_key_exists($obj["location"], $this->locationController);
        $existLocation = array_key_exists($obj["name"], $this->resourcePackages);
        if(!$existLocation && !$existName) {
            $this->resourcePackages[$obj["name"]]       = $obj;
            $this->locationController[$obj["location"]] = $obj;
        } else if($existName && !$existLocation) {
            //error nom repetit /*TO DO Multilanguage */
            throw new Exception(
                $this->getErrorMessage(
                     WikiIocBuilderManager::$REPEATED_PACKAGE_NAME
                ));
        } else if($existName && !$existLocation) {
            //error locallització repetida /*TO DO Multilanguage */
            throw new Exception(
                $this->getErrorMessage(
                     WikiIocBuilderManager::$REPEATED_PACKAGE_LOC
                ));
        }
    }

    /**
     * Processa el component passat com argument.
     *
     * @param WikiIocBuilder $component
     */
    function processComponent($component) {
        $packages = $component->getRequiredPackages();

        if ($packages) {
            foreach($packages as $obj) {
                $this->putRequiredPackage($obj);
            }
        }

    }

    /**
     * Crea una cadena amb format JSON a la que s'afegeixen tots els packages requerits per renderitzar el codi.
     *
     * @return string amb format JSON amb els packages requerits.
     */
    public function getRenderingCodeForRequiredPackages() {
        /*
        packages: [
            { name: "dojo", location: "//ajax.googleapis.com/ajax/libs/dojo/1.8/dojo" },
            { name: "dijit", location: "//ajax.googleapis.com/ajax/libs/dojo/1.8/dijit" },
            { name: "dojox", location: "//ajax.googleapis.com/ajax/libs/dojo/1.8/dojox" }
        ]
         */
        $json = new JSON();
        $ret  = "packages: [\n";
        foreach($this->resourcePackages as $obj) {
            static $first = TRUE;
            if($first) {
                $ret .= $json->encode($obj); //  json_encode($obj);
                $first = FALSE;
            } else {
                $ret .= ", \n" . $json->encode($obj); //json_encode($obj);
            }
        }
        $ret .= "\n]\n";
        return $ret;
    }

    /**
     * Retorna el missatge d'error corresponent al enter passat com argument.
     *
     * @param int $ind index del missatge d'error, corresponent a un dels valors emmagatzemats a $MESSAGES[]
     *
     * @return string amb el missatge d'error
     */
    public function getErrorMessage($ind) {
        return WikiIocBuilderManager::$MESSAGES[$ind];
    }
}
