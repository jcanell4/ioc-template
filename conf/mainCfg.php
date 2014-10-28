<?php
/**
 * Valors de configuració
 * @author Rafael Claver <rclaver@xtec.cat>
 */
if (!defined('DOKU_INC')) die();	//check if we are running within the DokuWiki environment
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', tpl_incdir());
require_once(DOKU_TPL_INCDIR . 'classes/WikiIocComponents.php');


class WikiIocCfg {
    /*
    private function createArrayWikiIocCfg() {
        $this->arrIocCfg = array();
        $this->arrIocCfg[] = array(
                  "class" => "WikiIocBody"
                 ,"parms" => array()
        );
        $this->arrIocCfg['parms'] = array("top" => array());
        $this->arrIocCfg['top'] = array(
                   "class" => "WikiIocDivBloc"
                  ,"parms" => array(
                               "height" => "55px"
                              ,"width" => "100%"
                              ,"items" => array()
                  )
        );
        $this->arrIocCfg['top']['parms']['items'] = array(
                   "class" => "WikiIocHeadContainer"
                  ,"parms" => array(
                               "logo" => array()
                              ,"menu" => array()
                  )
        );
        $this->arrIocCfg['top']['parms']['items']['parms']['logo'] = array(
                   "class" => "WikiIocImage"
                  ,"parms" => array("id" => "logo")
        );
        $this->arrIocCfg['top']['parms']['items']['parms']['menu'] = array(
                   "class" => "WikiDojoToolBar"
                  ,"parms" => array()
        );

        
        $this->arrIocCfg[] = array("main" => array());
    }
    */
    
    private $arrIocCfg = array(
      "class" => "WikiIocBody"
     ,"parms" => array(
         "id" => "main"
        ,"label" => "IocBody"
        ,"items" => array(
            "top" => array(
                      "class" => "WikiIocDivBloc"
                     ,"parms" => array(
                                    "id" => ""
                                   ,"label" => "TopBloc"
                                   ,"height" => "55px"
                                   ,"width" => "100%"
                                   ,"items" => array(
                                                  "logo" => array(
                                                               "class" => "WikiIocImage"
                                                              ,"parms" => array(
                                                                             "id"=> "id_logo"
                                                                            ,"label"=> "iocLogo"
                                                                            ,"span-position"=> "absolute"
                                                                            ,"span-top"=> "2px"
                                                                            ,"span-left"=> "0px"
                                                                            ,"width"=> "240px"
                                                                            ,"height"=> "50px"
                                                                            ,"z-index"=> "900"
                                                                            ,"src" => "img/logo.png"
                                                                          )
                                                            )
                                                 ,"menu" => array(
                                                               "class" => "WikiDojoToolBar"
                                                              ,"parms" => array(
                                                                             "id" => "barraMenu"
                                                                            ,"label" => "BarraMenu"
                                                                            ,"position" => "fixed"
                                                                            ,"top" => "28px"
                                                                            ,"left" => "270px"
                                                                            ,"zindex" => "900"
                                                                            ,"items" => array(
                                                                                           array(
                                                                                              "class" => "WikiDojoButton"
                                                                                             ,"parms" => array("id" => "menu_vista", "label" => "VISTA", "action" => "alert('VISTA')", "display" => true, "displayBlock" => false)
                                                                                           )
                                                                                          ,array(
                                                                                              "class" => "WikiDojoButton"
                                                                                             ,"parms" => array("id" => "menu_edicio", "label" => "EDICIÓ", "action" => "alert('EDICIO')", "display" => true, "displayBlock" => false)
                                                                                           )
                                                                                          ,array(
                                                                                              "class" => "WikiDojoButton"
                                                                                             ,"parms" => array("id" => "menu_correccio", "label" => "CORRECCIÓ", "action" => "alert('CORRECCIO')", "display" => true, "displayBlock" => false)
                                                                                           )
                                                                                        )
                                                                          )
                                                            )
                                               )
                                 )
                     )
           ,"main" => array(
                       "class" => "WikiIocBorderContainer"
                      ,"parms" => array(
                                   "id" => "mainContent"
                                  ,"label" => "MainContent"
                                  ,"items" => array(
                                               "left" => array(
                                                          "class" => "WikiIocItemsPanel"
                                                         ,"parms" => array(
                                                                      "id" => "id_LeftPanel"
                                                                     ,"label" => "LeftPanel"
                                                                     ,"region" => "left"
                                                                     ,"width" => "190px"
                                                                     ,"doLayout" => "true"
                                                                     ,"splitter" => "true"
                                                                     ,"minSize" => "150px"
                                                                     ,"closable" => "false"
                                                                     ,"items" => array(
                                                                                  "zN" => array(
                                                                                      "class" => "WikiIocDivBloc"
                                                                                     ,"parms" => array(
                                                                                                   "id" => "tb_container"
                                                                                                  ,"label" => "tb_container"
                                                                                                  ,"height" => "40%"
                                                                                                  ,"items" => array(
                                                                                                                array(
                                                                                                                 "class" => "WikiIocTabsContainer"
                                                                                                                ,"parms" => array(
                                                                                                                             "id" => "zonaNavegacio"
                                                                                                                            ,"label" => "tabsNavegacio"
                                                                                                                            ,"tabType" => WikiIocTabsContainer::RESIZING_TAB_TYPE
                                                                                                                            ,"bMenuButton" => true
                                                                                                                            ,"items" => array(
                                                                                                                                          array(
                                                                                                                                            "class" => "WikiIocTreeContainer"
                                                                                                                                           ,"parms" => array(
                                                                                                                                                         "id" => "tb_index"
                                                                                                                                                        ,"label" => "Índex"
                                                                                                                                                        ,"treeDataSource" => "lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/"
                                                                                                                                                       )
                                                                                                                                          )
                                                                                                                                         ,array(
                                                                                                                                            "class" => "WikiIocContentPane"
                                                                                                                                           ,"parms" => array(
                                                                                                                                                         "id" => "tb_perfil"
                                                                                                                                                        ,"label" => "Perfil"
                                                                                                                                                       )
                                                                                                                                          )
                                                                                                                                         ,array(
                                                                                                                                            "class" => "WikiIocContentPane"
                                                                                                                                           ,"parms" => array(
                                                                                                                                                         "id" => "tb_admin"
                                                                                                                                                        ,"label" => "Admin"
                                                                                                                                                       )
                                                                                                                                          )
                                                                                                                                         ,array(
                                                                                                                                            "class" => "WikiIocContainerFromPage"
                                                                                                                                           ,"parms" => array(
                                                                                                                                                         "id" => "tb_docu"
                                                                                                                                                        ,"label" => "documentació"
                                                                                                                                                        ,"page" => ":wiki:navigation"
                                                                                                                                                       )
                                                                                                                                          )
                                                                                                                                        )
                                                                                                                            )
                                                                                                                )
                                                                                                              )
                                                                                                 )
                                                                                          )
                                                                                 ,"zM" => array(
                                                                                      "class" => "WikiIocDivBloc"
                                                                                     ,"parms" => array(
                                                                                                   "id" => "zonaMetaInfo_DivBloc"
                                                                                                  ,"label" => "zonaMetaInfo"
                                                                                                  ,"height" => "60%"
                                                                                                  ,"items" => array(
                                                                                                                array(
                                                                                                                   "class" => "WikiIocAccordionContainer"
                                                                                                                  ,"parms" => array("id" => "zonaMetaInfo", "label" => "ContainerMetaInfo")
                                                                                                                )
                                                                                                              )
                                                                                                 )
                                                                                          )
                                                                                 )
                                                                     )
                                                         )
                                              ,"center" => array(
                                                            "class" => "WikiIocItemsPanel"
                                                           ,"parms" => array(
                                                                         "id" => "content"
                                                                        ,"label" => "CentralPanel"
                                                                        ,"region" => "center"
                                                                        ,"div" => true
                                                                        ,"class" => "ioc_content dokuwiki"
                                                                        ,"doLayout" => "false"
                                                                        ,"splitter" => "false"
                                                                        ,"items" => array(
                                                                                      array(
                                                                                         "class" => "WikiIocTabsContainer"
                                                                                        ,"parms" => array(
                                                                                                      "id" => "bodyContent"
                                                                                                     ,"label" => "bodyContent"
                                                                                                     ,"tabType" => WikiIocTabsContainer::SCROLLING_TAB_TYPE
                                                                                                     ,"bMenuButton" => true
                                                                                                     ,"bScrollingButtons" => true
                                                                                                    )
                                                                                      )
                                                                                    )
                                                                       )
                                                          )
                                              ,"right" => array(
                                                           "class" => "WikiIocItemsPanel"
                                                          ,"parms" => array(
                                                                       "id" => "zonaCanvi"
                                                                      ,"region" => "right"
                                                                      ,"width" => "65px"
                                                                      ,"doLayout" => "true"
                                                                      ,"splitter" => "true"
                                                                      ,"minSize" => "0px"
                                                                      ,"style" => "padding:0px"
                                                                      ,"closable" => "true"
                                                                      ,"items" => array(
                                                                                     array(
                                                                                        "class" => "WikiIocDropDownButton"
                                                                                       ,"parms" => array(
                                                                                                     "id" => "loginButton"
                                                                                                    ,"label" => "Entrar"
                                                                                                    ,"autoSize" => true
                                                                                                    ,"display" => true
                                                                                                    ,"displayBlock" => true
                                                                                                    ,"actionHidden" => array(
                                                                                                                   "class" => "WikiIocHiddenDialog"
                                                                                                                  ,"parms" => array(
                                                                                                                                "id" => "loginDialog"
                                                                                                                               ,"label" => "loginDialog"
                                                                                                                               ,"items" => array(
                                                                                                                                               array(
                                                                                                                                                 "class" => "WikiIocFormInputField"
                                                                                                                                                ,"parms" =>  array(
                                                                                                                                                               "id" => "name"
                                                                                                                                                              ,"label" => "Usuari:"
                                                                                                                                                              ,"name" => "u"
                                                                                                                                                             )
                                                                                                                                                 )
                                                                                                                                              ,array(
                                                                                                                                                 "class" => "WikiIocFormInputField"
                                                                                                                                                ,"parms" =>  array(
                                                                                                                                                               "id" => "pass"
                                                                                                                                                              ,"label" => "Contrasenya:"
                                                                                                                                                              ,"name" => "p"
                                                                                                                                                              ,"type" => "password"
                                                                                                                                                             )
                                                                                                                                               )
                                                                                                                                            )
                                                                                                                              )
                                                                                                                )
                                                                                                   )
                                                                                      )
                                                                                     ,array(
                                                                                        "class" => "WikiIocButton"
                                                                                       ,"parms" => array(
                                                                                                      "id" => "newButton"
                                                                                                     ,"label" => "Nou"
                                                                                                     ,"query" => "do=new"
                                                                                                     ,"autoSize" => true
                                                                                                     ,"display" => false
                                                                                                     ,"displayBlock" => true
                                                                                                   )
                                                                                      )
                                                                                     ,array(
                                                                                        "class" => "WikiIocButton"
                                                                                       ,"parms" => array(
                                                                                                      "id" => "saveButton"
                                                                                                     ,"label" => "Desar"
                                                                                                     ,"query" => "do=save"
                                                                                                     ,"autoSize" => true
                                                                                                     ,"display" => false
                                                                                                     ,"displayBlock" => true
                                                                                                   )
                                                                                      )
                                                                                     ,array(
                                                                                        "class" => "WikiIocButton"
                                                                                       ,"parms" => array(
                                                                                                      "id" => "previewButton"
                                                                                                     ,"label" => "Previsualitza"
                                                                                                     ,"query" => "do=preview"
                                                                                                     ,"autoSize" => true
                                                                                                     ,"display" => false
                                                                                                     ,"displayBlock" => true
                                                                                                   )
                                                                                      )
                                                                                     ,array(
                                                                                        "class" => "WikiIocButton"
                                                                                       ,"parms" => array(
                                                                                                      "id" => "cancelButton"
                                                                                                     ,"label" => "Cancel·la"
                                                                                                     ,"query" => "do=cancel"
                                                                                                     ,"autoSize" => true
                                                                                                     ,"display" => false
                                                                                                     ,"displayBlock" => true
                                                                                                   )
                                                                                      )
                                                                                     ,array(
                                                                                        "class" => "WikiIocButton"
                                                                                       ,"parms" => array(
                                                                                                      "id" => "editButton"
                                                                                                     ,"label" => "Edició"
                                                                                                     ,"query" => "do=edit"
                                                                                                     ,"autoSize" => true
                                                                                                     ,"display" => false
                                                                                                     ,"displayBlock" => true
                                                                                                   )
                                                                                      )
                                                                                     ,array(
                                                                                        "class" => "WikiIocButton"
                                                                                       ,"parms" => array(
                                                                                                      "id" => "edparcButton"
                                                                                                     ,"label" => "Ed. Parc."
                                                                                                     ,"query" => "do=edparc"
                                                                                                     ,"autoSize" => true
                                                                                                     ,"display" => false
                                                                                                     ,"displayBlock" => true
                                                                                                   )
                                                                                      )
                                                                                     ,array(
                                                                                        "class" => "WikiIocButton"
                                                                                       ,"parms" => array(
                                                                                                      "id" => "exitButton"
                                                                                                     ,"label" => "Sortir"
                                                                                                     ,"query" => "do=logoff"
                                                                                                     ,"autoSize" => true
                                                                                                     ,"display" => false
                                                                                                     ,"displayBlock" => true
                                                                                                   )
                                                                                      )

                                                                                  )
                                                           )
                                               )
                                              ,"bottom" => array(
                                                            "class" => "WikiIocItemsPanel"
                                                           ,"parms" => array(
                                                                          "id" => "id_BottomPanel"
                                                                         ,"region" => "bottom"
                                                                         ,"height" => "30px"
                                                                         ,"doLayout" => "false"
                                                                         ,"splitter" => "true"
                                                                         ,"items" => array(
                                                                                       array(
                                                                                          "class" => "WikiIocTextContentPane"
                                                                                         ,"parms" => array("id" => "zonaMissatges", "missatge" => "eooo! estoy aquíiiii!")
                                                                                       )
                                                                                     )
                                                                       )
                                                           )
                                               
                                              )
                                  )
                      )
                    )
                 )
    );

    
    private $arrCfg;
    private $arrTpl;
	private $arrMain;

    private function __construct(){

        //LoginResponseHandler utilitza els id's: zN_index_id, zonaMetaInfo
        $this->arrCfg = array(
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

    public function getJsPackage($id){
        global $js_packages;
        return $js_packages[$id];
    }
	
	public function getConfig($key){
		return $this->arrCfg[$key];
	}

	public function getIocCfg(){
		return $this->arrIocCfg;
	}

	public function getArrayTpl(){
		return $this->arrTpl;
	}

    public function getArrayMain(){
		return $this->arrMain;
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
