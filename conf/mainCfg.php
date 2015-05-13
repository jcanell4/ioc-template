<?php
/**
 * Valors de configuració
 * @author Rafael Claver <rclaver@xtec.cat>
 */
if (!defined('DOKU_INC')) die();  //check if we are running within the DokuWiki environment
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', tpl_incdir());
require_once(DOKU_TPL_INCDIR . 'conf/default.php');
require_once(DOKU_TPL_INCDIR . 'conf/cfgBuilder.php');
require_once(DOKU_TPL_INCDIR . 'conf/generator/ControlTplGenerator.php');
require_once(DOKU_INC . 'inc/events.php');

class WikiIocCfg {

    private $fileArrayCfgGUI;
    private $fileArrayAmdGUI;
    private $arrCfgGUI;
    private $strAmdGUI;
    private $generator;
    
    public function getArrayIocCfg() {
        global $conf;
        $this->fileArrayCfgGUI = $conf["ioc_file_cfg_gui"];
        $this->fileArrayAmdGUI = $conf["ioc_file_amd_gui"];
        if (!file_exists($this->fileArrayCfgGUI) || !file_exists($this->fileArrayAmdGUI)) {
            $this->GeneraFicheroArray();
        }else {
            include ($this->fileArrayCfgGUI);
            $f_needReset = $conf['ioc_function_array_gui_needReset'];
            if ($f_needReset() === 0) {
                $f_loadArray = $conf['ioc_function_array_gui'];
                $this->arrCfgGUI = $f_loadArray();
                $this->strAmdGUI = file_get_contents($this->fileArrayAmdGUI);
            }else {
                $this->GeneraFicheroArray();
            }
        }
        $this->generator  = new ControlTplGenerator();
        $this->processAddTplControls();
        $this->processAddScripts();
        unset($this->generator);
        return array('arrCfg' => $this->arrCfgGUI, 'amd' => $this->strAmdGUI);
    }
    
    private function processAddScripts(){
        $evt = new Doku_Event("ADD_TPL_CONTROL_SCRIPTS", $this->generator);
        $evt->trigger();
        unset($evt);
        $this->addScripts();        
    }
    
    private function processAddTplControls(){
        $evt = new Doku_Event("ADD_TPL_CONTROLS", $this->generator);
        $evt->trigger();
        unset($evt);
        $this->addControls();        
    }
    
    
    private function addScripts(){
        $toAdd = $this->generator->getControlScripts();
        foreach ($toAdd as $value) {
            $this->strAmdGUI .= "\n";
            $this->strAmdGUI .= $value;
        }
    }
    
    private function addControls(){
        $path = split("/", cfgIdConstants::WIKI_IOC_BUTTON_PATH);
        $root = &$this->arrCfgGUI;
        foreach ($path as $dir) {
            if(empty($dir)){
                $root=&$root["items"];
            }else{
                $root=&$root[$dir]["items"];
            }
        }
        
        $toAdd = $this->generator->getWikiIocButtonControls();
        foreach ($toAdd as $value) {
            $root[$value["name"]]["class"] = "WikiIocButton";
            $root[$value["name"]]["parms"] = $value["parms"];
        }
    }

    private function GeneraFicheroArray() {
        global $conf;
        $ruta = $conf["ioc_path_cfg_gui"];

        $inst = new cfgBuilder();
        $arrIocCfg = $inst->getArrayCfg($ruta);
        $this->arrCfgGUI = $inst->writeArrayToFile($arrIocCfg['arrCfg'], $this->fileArrayCfgGUI);
        $this->strAmdGUI = $inst->writeAMDToFile($arrIocCfg['amd'], $this->fileArrayAmdGUI);
    }
    
    /* SINGLETON CLASS */
    public static function Instance(){
        static $inst = null;
        if ($inst === null) {
            $inst = new WikiIocCfg();
        }
        return $inst;
    }

    private function __construct() {}
}
