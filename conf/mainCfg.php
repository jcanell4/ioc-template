<?php
/**
 * Valors de configuració
 * @author Rafael Claver <rclaver@xtec.cat>
 */
if (!defined('DOKU_INC')) die();  //check if we are running within the DokuWiki environment
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', tpl_incdir());
require_once(DOKU_TPL_INCDIR . 'conf/default.php');
require_once(DOKU_TPL_INCDIR . 'classes/WikiIocComponents.php');
require_once(DOKU_TPL_INCDIR . 'conf/cfgIdConstants.php');
require_once(DOKU_TPL_INCDIR . 'conf/cfgBuilder.php');

class WikiIocCfg {

    private $fileArrayCfgGUI;
    private $arrCfgGUI;
    
    public function getArrayIocCfg() {
        global $conf;
        $this->fileArrayCfgGUI = $conf["ioc_file_cfg_gui"];
        if (!file_exists($this->fileArrayCfgGUI)) {
            $this->GeneraFicheroArray();
        }else {
            include ($this->fileArrayCfgGUI);
            $f_needReset = $conf['ioc_function_array_gui_needReset'];
            if ($f_needReset() === 0) {
                $f_loadArray = $conf['ioc_function_array_gui'];
                $this->arrCfgGUI = $f_loadArray();
            }else {
                $this->GeneraFicheroArray();
            }
        }
        return $this->arrCfgGUI;
    }
    
    private function GeneraFicheroArray() {
        global $conf;
        $ruta = $conf["ioc_path_cfg_gui"];

        $inst = new cfgBuilder();
        $arrIocCfg = $inst->getArrayCfg($ruta);
        $this->arrCfgGUI = $inst->writeArrayToFile($arrIocCfg, $this->fileArrayCfgGUI);
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
