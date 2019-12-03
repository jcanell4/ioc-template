<?php
// check if we are running within the DokuWiki environment
if(!defined("DOKU_INC")) die();
if(!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', WikiGlobalConfig::tplIncDir().'classes/');

require_once(DOKU_TPL_CLASSES . 'WikiIocBuilderManager.php');

/**
 * Class WikiIocBuilder
 * Aquesta classe es la superclasse de WikiIocComponent, que es superclasse de WikiDojoButton, WikiIocContainer,
 * WikiIocFormInputField i WikiIocProperty->(eliminat).
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>  i Rafael Claver <rclaver@xtec.cat>
 */
abstract class WikiIocBuilder {
    protected $requiredPakages; //WE MUST CONTROL PAKAGE LEVEL ONLY
    protected $requiredJsModules;   //array de moduls AMD requerits en els atributs dojo
    protected $requiredStyles;

    /**
     * @param array[] $requiredPakages array amb un hash que conté els packages requerits amb el format:
     *                                 "array("name" => "ioc", "location" => $js_packages["ioc"])
     */
    public function __construct($requiredPakages = array(), $reqJsModules = array(), $reqStyles = array()) {
        $this->requiredPakages = $requiredPakages;
        $this->requiredJsModules = $reqJsModules;
        $this->requiredStyles = $reqStyles;
        WikiIocBuilderManager::Instance()->processComponent($this);
    }

    /**
     * @param array[] $requiredPakages array amb un hash que conté els packages requerits amb el format:
     *                                 "array("name" => "ioc", "location" => $js_packages["ioc"])
     */
    public function setRequiredPakages($requiredPakages = array()) {
        $this->requiredPakages = $requiredPakages;
    }

    /**
     * @return array[] array amb hash que conté els packages requerits.
     */
    public function getRequiredPackages() {
        return $this->requiredPakages;
    }
    public function getRequiredStyles() {
        return $this->requiredStyles;
    }
    /**
     * @return array[] hash que conté els mòduls javascript requerits.
     */
    public function getRequiredJsModules() {
        return $this->requiredJsModules;
    }
}