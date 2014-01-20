<?php
/**
 * Paràmetres de configuració
 * @author Rafael Claver <rclaver@xtec.cat>
 */

if (!defined("DOKU_INC")) die();	//check if we are running within the DokuWiki environment
if (!defined("DOKU_TPL_CLASSES")) define("DOKU_TPL_CLASSES", tpl_incdir()."classes/");

class WikiIocCfg {
	private $arrConfig = array(
				"mainContent" => "mainContent"
				,"bodyContent" => "bodyContent"
				,"zonaAccions" => "zonaAccions"
				,"zonaNavegacio" => "zonaNavegacio" //ojo, ojito, musho cuidadito, antes se llamaba "nav"
				,"zonaMetaInfo" => "zonaMetaInfo"
				,"zonaMissatges" => "zonaMissatges"
				,"zonaCanvi" => "zonaCanvi"
				,"barraMenu" => "barraMenu"
				,"zN_Index_id" => "tb_index"
				,"zN_perfil_id" => "tb_perfil"
				,"zN_admin_id" => "tb_admin"
				,"zN_docum_id" => "tb_docu"
				,"loginDialog" => "loginDialog"
				,"loginButton" => "loginButton"
				,"exitButton" => "exitButton"
				,"editButton" => "editButton"
				,"newButton" => "newButton"
				,"saveButton" => "saveButton"
				,"edparcButton" => "edparcButton"
			);
	
	private $arrTpl = array(
				"%%ID%%" => "ajax"
				,"%%SECTOK%%" => "getSecurityToken()"
				,"@@MAIN_CONTENT@@" => "mainContent"
				,"@@BODY_CONTENT@@" => "bodyContent"
				,"@@NAVEGACIO_NODE_ID@@" => "zonaNavegacio"
				,"@@METAINFO_NODE_ID@@" => "zonaMetaInfo"
				,"@@INFO_NODE_ID@@" => "zonaMissatges"
				,"@@CANVI_NODE_ID@@" => "zonaCanvi"
				,"@@TAB_INDEX@@"    => "tb_index"
				,"@@TAB_DOCU@@"     => "tb_docu"
				,"@@LOGIN_DIALOG@@" => "loginDialog"
				,"@@LOGIN_BUTTON@@" => "loginButton"
				,"@@EXIT_BUTTON@@" => "exitButton"
				,"@@EDIT_BUTTON@@" => "editButton"
                                ,'@@NEW_BUTTON@@' => "newButton"
                                ,'@@SAVE_BUTTON@@' => "saveButton"
                                ,'@@ED_PARC_BUTTON@@' => "edparcButton"
            
			);

//    function __construct(){
//		foreach ($this->arrConfig as $key => $value) {
//			$this->arrTpl[$key] = $value;
//		}
//	}
	
	public function getConfig($key){
		return $this->arrConfig[$key];
	}

	public function getArrayTpl(){
		return $this->arrTpl;
	}
	
}
?>
