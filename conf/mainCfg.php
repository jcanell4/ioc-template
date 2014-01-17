<?php
/**
 * Paràmetres de configuració
 * @author Rafael Claver <rclaver@xtec.cat>
 */

if (!defined("DOKU_INC")) die();	//check if we are running within the DokuWiki environment
if (!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', tpl_incdir().'classes/');

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
				,"tb_index" => "tb_index"
				,"tb_perfil" => "tb_perfil"
				,"tb_admin" => "tb_admin"
				,"tb_docu" => "tb_docu"
				,"loginDialog" => "loginDialog"
				,"loginButton" => "loginButton"
				,"exitButton" => "exitButton"
			);
	
	public function getConfig($key){
		return $this->arrConfig[$key];
	}
}
?>
