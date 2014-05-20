<?php
/**
 * Valors de configuració
 * @author Rafael Claver <rclaver@xtec.cat>
 */

if (!defined("DOKU_INC")) die();	//check if we are running within the DokuWiki environment
if (!defined("DOKU_TPL_CLASSES")) define("DOKU_TPL_CLASSES", tpl_incdir()."classes/");

class WikiIocCfg {
	private $arrConfig = array(
				"mainContent" => "mainContent"
				,"bodyContent" => "bodyContent"
				//id's de les Zones/Contenidors principals
				,"zonaAccions"   => "zonaAccions"	
				,"zonaNavegacio" => "zonaNavegacio" //ojo, ojito, musho cuidadito, antes se llamaba "nav"
				,"zonaMetaInfo"  => "zonaMetaInfo"
				,"zonaMissatges" => "zonaMissatges"
				,"zonaCanvi"     => "zonaCanvi"
				,"barraMenu" => "barraMenu"
				//id's de les pestanyes (tabs) de la zona de Navegació
				,"zN_index_id"  => "tb_index"	
				,"zN_perfil_id" => "tb_perfil"	
				,"zN_admin_id"  => "tb_admin"	
				,"zN_docum_id"  => "tb_docu"	
				//id's dels botons de la zona de Canvi
				,"loginDialog"  => "loginDialog"
				,"loginButton"  => "loginButton"
				,"exitButton"   => "exitButton"
				,"editButton"   => "editButton"
				,"newButton"    => "newButton"
				,"saveButton"   => "saveButton"
				,"previewButton"   => "previewButton"
				,"cancelButton"   => "cancelButton"
				,"edparcButton" => "edparcButton"
			);
	
	private $arrTpl;
	private $arrMain;
	//LoginResponseHandler utilitza els id's: zN_index_id, zonaMetaInfo

    function __construct(){
		$this->arrTpl = array(
				"%%ID%%" => "ajax"
				,"%%SECTOK%%" => getSecurityToken()
				,"@@MAIN_CONTENT@@" => $this->getConfig("mainContent")
				,"@@BODY_CONTENT@@" => $this->getConfig("bodyContent")
				,"@@NAVEGACIO_NODE_ID@@" => $this->getConfig("zonaNavegacio")
				,"@@METAINFO_NODE_ID@@" => $this->getConfig("zonaMetaInfo")
				,"@@INFO_NODE_ID@@" => $this->getConfig("zonaMissatges")
				,"@@CANVI_NODE_ID@@" => $this->getConfig("zonaCanvi")
				,"@@TAB_INDEX@@"    => $this->getConfig("zN_index_id")
				,"@@TAB_DOCU@@"     => $this->getConfig("zN_docum_id")
				,"@@LOGIN_DIALOG@@" => $this->getConfig("loginDialog")
				,"@@LOGIN_BUTTON@@" => $this->getConfig("loginButton")
				,"@@EXIT_BUTTON@@" => $this->getConfig("exitButton")
				,"@@EDIT_BUTTON@@" => $this->getConfig("editButton")
				,'@@NEW_BUTTON@@' => $this->getConfig("newButton")
				,'@@SAVE_BUTTON@@' => $this->getConfig("saveButton")
				,'@@PREVIEW_BUTTON@@' => $this->getConfig("previewButton")
				,'@@CANCEL_BUTTON@@' => $this->getConfig("cancelButton")
				,'@@ED_PARC_BUTTON@@' => $this->getConfig("edparcButton")
                                ,'@@DOJO_SOURCE@@' => $this->getJsPackage("dojo")
		);
		
		$this->arrMain = array(
				"main" => "main"
				,"mainContent" => "mainContent"
				,"tb_container" => "tb_container"
				,"content" => "content"
		);
	}
	
	public function getConfig($key){
		return $this->arrConfig[$key];
	}

	public function getArrayTpl(){
		return $this->arrTpl;
	}
	
	public function getArrayMain(){
		return $this->arrMain;
	}
        
        public function getJsPackage($id){
            global $js_packages;
            return $js_packages[$id];
        }
                
               
        
        /*SINGLETON CLASS*/
        public static function Instance(){
            static $inst = null;
            if ($inst === null) {
                $inst = new WikiIocCfg();
            }
            return $inst;
        }                
}
?>
