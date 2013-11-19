<?php

/**
 * Description of WikiIocBuilderManager. 
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */


class WikiIocBuilderManager{
    public static $MESSAGES = array("Error. El nom del paquet està repetit",
        "Error. Ja existeix un altre paquest amb la mateixa localitazació i nom diferent"
        );
    public static $REPEATED_PACKAGE_NAME=0;
    public static $REPEATED_PACKAGE_LOC=1;
    private $resourcePackages;  
    private $locationController;
    
    /*SINGLETON CLASS*/
    public static function Instance(){
        static $inst = null;
        if ($inst === null) {
            $inst = new WikiIocBuilderManager();
        }
        return $inst;
    }

    private function __construct(){
        $this->locationController=array();
        $this->resourcePackages=array();
    }
    
    function processComponent($component){
        $packages= $component->getRequiredPackages();
        foreach ($packages as $obj){
            $existName = array_key_exists($obj["location"], $this->locationController);
            $existLocation = array_key_exists($obj["name"], $this->resourcePackages);
            if(!$existLocation && !$existName){
                $this->resourcePackages[$obj["name"]]=$obj;
                $this->locationController[$obj["location"]]=$obj;
            }else if($existName && !$existLocation){
                //error nom repetit /*TO DO Multilanguage */
                throw new Exception(
                        $this->getErrorMessage(
                                WikiIocBuilderManager::$REPEATED_PACKAGE_NAME));
            }else if($existName && !$existLocation){
                //error locallització repetida /*TO DO Multilanguage */
                throw new Exception(
                        $this->getErrorMessage(
                                WikiIocBuilderManager::$REPEATED_PACKAGE_LOC));
            }
        }
    }
    
    function getRenderingCodeForRequiredPackages(){
        /*
        packages: [
            { name: "dojo", location: "//ajax.googleapis.com/ajax/libs/dojo/1.8/dojo" },
            { name: "dijit", location: "//ajax.googleapis.com/ajax/libs/dojo/1.8/dijit" },
            { name: "dojox", location: "//ajax.googleapis.com/ajax/libs/dojo/1.8/dojox" }
        ]
         */
        $json = new JSON();
        $ret = "packages: [\n";
        foreach ($this->resourcePackages as $obj){
            static $first=true;
            if($first){
                $ret .= $json->encode($obj); //  json_encode($obj);
                $first=false;
            }else{
                $ret .= ", \n".$json->encode($obj);   //json_encode($obj);
            }
        }
        $ret .="\n]\n";
        return $ret;
    }
    
    function getErrorMessage($ind){
        return WikiIocBuilderManager::$MESSAGES[$ind];
    }
}

?>
