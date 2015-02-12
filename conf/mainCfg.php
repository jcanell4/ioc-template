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
    
    public function LeeFicheroArray() {
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
        //$ruta = realpath(dirname(__FILE__));
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

//    private $arrIds;
//    private $arrTpl;

    private function __construct(){
            /*
            //LoginResponseHandler utilitza els id's: zN_index_id, zonaMetaInfo
            $this->arrIds = array(
                    "mainContent" => "mainContent"
                    ,"bodyContent" => "bodyContent"
                    //id's de les Zones/Contenidors principals
                    ,"zonaAccions"   => "zonaAccions"  
                    ,"zonaNavegacio" => "zonaNavegacio"
                    ,"zonaMetaInfo"  => "zonaMetaInfo"
                    ,"zonaMissatges" => "zonaMissatges"
                    ,"zonaCanvi"   => "zonaCanvi"
                    ,"barraMenu"   => "barraMenu"
                    //id's de les pestanyes (tabs) de la zona de Navegació
                    ,"zN_index_id"  => "tb_index"  
                    ,"zN_perfil_id" => "tb_perfil"  
                    ,"zN_admin_id"  => "tb_admin"  
                    ,"zN_docum_id"  => "tb_docu"  
                    //id's dels botons de la zona de Canvi
                    ,"loginDialog"   => "loginDialog"
                    ,"loginButton"   => "loginButton"
                    ,"exitButton"  => "exitButton"
                    ,"editButton"  => "editButton"
                    ,"newButton"   => "newButton"
                    ,"saveButton"  => "saveButton"
                    ,"previewButton" => "previewButton"
                    ,"mediaDetailButton" => "mediaDetailButton"
                    ,"cancelButton"  => "cancelButton"
                    ,"edparcButton"  => "edparcButton"
                    ,"userDialog"   => "userDialog"
                    ,"userButton"   => "userButton"
                    ,"userMenuItem"   => "userMenuItem"
                    ,"talkUserMenuItem" => "talkUserMenuItem"
                    ,"logoffMenuItem"   => "logoffMenuItem"
            );
            $this->arrTpl = array(
                    "%%ID%%" => "ajax"
                    ,'%%SECTOK%%'    => getSecurityToken()
                    ,'@@MAIN_CONTENT@@'  => $this->getArrIds("mainContent")
                    ,'@@BODY_CONTENT@@'  => $this->getArrIds("bodyContent")
                    ,'@@NAVEGACIO_NODE_ID@@' => $this->getArrIds("zonaNavegacio")
                    ,'@@METAINFO_NODE_ID@@'  => $this->getArrIds("zonaMetaInfo")
                    ,'@@INFO_NODE_ID@@'  => $this->getArrIds("zonaMissatges")
                    ,'@@CANVI_NODE_ID@@'   => $this->getArrIds("zonaCanvi")
                    ,'@@TAB_INDEX@@'   => $this->getArrIds("zN_index_id")
                    ,'@@TAB_DOCU@@'    => $this->getArrIds("zN_docum_id")
                    ,'@@LOGIN_DIALOG@@'  => $this->getArrIds("loginDialog")
                    ,'@@LOGIN_BUTTON@@'  => $this->getArrIds("loginButton")
                    ,'@@EXIT_BUTTON@@'   => $this->getArrIds("exitButton")
                    ,'@@EDIT_BUTTON@@'   => $this->getArrIds("editButton")
                    ,'@@NEW_BUTTON@@'  => $this->getArrIds("newButton")
                    ,'@@SAVE_BUTTON@@'   => $this->getArrIds("saveButton")
                    ,'@@PREVIEW_BUTTON@@'  => $this->getArrIds("previewButton")
                    ,'@@MEDIA_DETAIL_BUTTON@@' => $this->getArrIds("mediaDetailButton")
                    ,'@@CANCEL_BUTTON@@'   => $this->getArrIds("cancelButton")
                    ,'@@ED_PARC_BUTTON@@'  => $this->getArrIds("edparcButton")
                    ,'@@USER_DIALOG@@'   => $this->getArrIds("userDialog")
                    ,'@@USER_BUTTON@@'   => $this->getArrIds("userButton")
                    ,'@@USER_MENUITEM@@'   => $this->getArrIds("userMenuItem")
                    ,'@@TALKUSER_MENUITEM@@' => $this->getArrIds("talkUserMenuItem")
                    ,'@@LOGOFF_MENUITEM@@'   => $this->getArrIds("logoffMenuItem")
                    ,'@@DOJO_SOURCE@@'   => $this->getJsPackage("dojo")
            );
            */
    }
    /*
    public function getIocCfg(){
        return $this->arrIocCfg;
    }
    */
    /*
    public function getJsPackage($id){
        global $js_packages;
        return $js_packages[$id];
    }
    public function getArrIds($key){
        return $this->arrIds[$key];
    }
    public function getArrayTpl(){
        return $this->arrTpl;
    }
    */
}
