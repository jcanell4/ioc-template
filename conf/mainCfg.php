<?php
/**
 * Valors de configuració
 * @author Rafael Claver <rclaver@xtec.cat>
 */
if (!defined('DOKU_INC')) die();  //check if we are running within the DokuWiki environment
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', tpl_incdir());
require_once(DOKU_TPL_INCDIR . 'conf/default.php');
require_once(DOKU_TPL_INCDIR . 'conf/cfgBuilder.php');

class WikiIocCfg {

    private $fileArrayCfgGUI;
    private $fileArrayAmdGUI;
    private $arrCfgGUI;
    private $strAmdGUI;
    
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
        return array('arrCfg' => $this->arrCfgGUI, 'amd' => $this->strAmdGUI);
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
