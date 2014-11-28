<?php
/**
 * Valors de configuració
 * @author Rafael Claver <rclaver@xtec.cat>
 */
if (!defined('DOKU_INC')) die();	//check if we are running within the DokuWiki environment
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', tpl_incdir());
require_once(DOKU_TPL_INCDIR . 'classes/WikiIocComponents.php');


class WikiIocCfg {
    
    private $arrIocCfg = array(
                 "class" => "WikiIocBody"
                ,"parms" => array(
                               "id" => "main"
                              ,"label" => "IocBody"
                            )
                ,"items" => array(
                              "top" => array(
                                          "class" => "WikiIocDivBloc"
                                         ,"parms" => array(
                                                        "id" => ""
                                                       ,"label" => "TopBloc"
                                                       ,"height" => "55px"
                                                       ,"width" => "100%"
                                                     )
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
                                                                             )
                                                                 ,"items" => array(
                                                                                array(
                                                                                  "class" => "WikiDojoButton"
                                                                                 ,"parms" => array(
                                                                                                "id" => "menu_vista"
                                                                                               ,"label" => "VISTA"
                                                                                               ,"action" => "alert('VISTA')"
                                                                                               ,"display" => true
                                                                                               ,"displayBlock" => false
                                                                                             )
                                                                                )
                                                                               ,array(
                                                                                  "class" => "WikiDojoButton"
                                                                                 ,"parms" => array(
                                                                                                "id" => "menu_edicio"
                                                                                               ,"label" => "EDICIÓ"
                                                                                               ,"action" => "alert('EDICIO')"
                                                                                               ,"display" => true
                                                                                               ,"displayBlock" => false
                                                                                             )
                                                                                )
                                                                               ,array(
                                                                                  "class" => "WikiDojoButton"
                                                                                 ,"parms" => array(
                                                                                                "id" => "menu_correccio"
                                                                                               ,"label" => "CORRECCIÓ"
                                                                                               ,"action" => "alert('CORRECCIO')"
                                                                                               ,"display" => true
                                                                                               ,"displayBlock" => false
                                                                                             )
                                                                                )
                                                                             )
                                                                )

                                                     ,"login" => array(
                                                                   "class" => "WikiIocDivBloc"
                                                                  ,"parms" => array(
                                                                                 "id" => "zonaLogin"
                                                                                ,"label" => "zonaLogin"
                                                                                ,"width" => "auto"
                                                                                ,"height" => "100%"
                                                                                ,"style" => "float:right;padding:0px"
                                                                              )
                                                                  ,"items" => array(
                                                                                 array(
                                                                                    "class" => "WikiIocDropDownButton"
                                                                                   ,"parms" => array(
                                                                                                  "id" => "loginButton"
                                                                                                 ,"label" => "Entrar"
                                                                                                 ,"autoSize" => true
                                                                                                 ,"display" => true
                                                                                                 ,"displayBlock" => true
                                                                                               )
                                                                                   ,"items" => array(
                                                                                                  "class" => "WikiIocHiddenDialog"
                                                                                                 ,"parms" => array(
                                                                                                                "id" => "loginDialog"
                                                                                                               ,"label" => "loginDialog"
                                                                                                             )
                                                                                                 ,"items" => array(
                                                                                                                array(
                                                                                                                   "class" => "WikiIocFormInputField"
                                                                                                                  ,"parms" => array(
                                                                                                                                 "id" => "name"
                                                                                                                                ,"label" => "Usuari:"
                                                                                                                                ,"name" => "u"
                                                                                                                              )
                                                                                                                )
                                                                                                               ,array(
                                                                                                                   "class" => "WikiIocFormInputField"
                                                                                                                  ,"parms" => array(
                                                                                                                                 "id" => "pass"
                                                                                                                                ,"label" => "Contrasenya:"
                                                                                                                                ,"name" => "p"
                                                                                                                                ,"type" => "password"
                                                                                                                              )
                                                                                                                )
                                                                                                             )
                                                                                               )
                                                                                 )
                                                                                ,array(
                                                                                    "class" => "WikiIocDropDownButton"
                                                                                   ,"parms" => array(
                                                                                                  "id" => "userButton"
                                                                                                 ,"label" => "Menú User"
                                                                                                 ,"autoSize" => true
                                                                                                 ,"display" => true
                                                                                                 ,"displayBlock" => true
                                                                                               )
                                                                                   ,"items" => array(
                                                                                                  "class" => "WikiIocDropDownMenu"
                                                                                                 ,"parms" => array(
                                                                                                                "id" => "userDialog"
                                                                                                               ,"label" => "userDialog"
                                                                                                             )
                                                                                                 ,"items" => array(
                                                                                                                array(
                                                                                                                   "class" => "WikiIocMenuItem"
                                                                                                                  ,"parms" => array(
                                                                                                                                 "id" => "userMenuItem"
                                                                                                                                ,"label" => "La meva pàgina"
                                                                                                                                ,"query" => "do=user"
                                                                                                                                ,"autoSize" => true
                                                                                                                                ,"display" => false
                                                                                                                                ,"displayBlock" => true
                                                                                                                              )
                                                                                                                )
                                                                                                               ,array(
                                                                                                                   "class" => "WikiIocMenuItem"
                                                                                                                  ,"parms" => array(
                                                                                                                                 "id" => "talkUserMenuItem"
                                                                                                                                ,"label" => "Discussió"
                                                                                                                                ,"query" => "do=talkUser"
                                                                                                                                ,"autoSize" => true
                                                                                                                                ,"display" => false
                                                                                                                                ,"displayBlock" => true
                                                                                                                              )
                                                                                                                )
                                                                                                               ,array(
                                                                                                                   "class" => "WikiIocMenuItem"
                                                                                                                  ,"parms" => array(
                                                                                                                                 "id" => "logoffMenuItem"
                                                                                                                                ,"label" => "Desconnectar"
                                                                                                                                ,"query" => "do=logoff"
                                                                                                                                ,"autoSize" => true
                                                                                                                                ,"display" => false
                                                                                                                                ,"displayBlock" => true
                                                                                                                              )
                                                                                                                )
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
                                                      )
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
                                                                                 )
                                                                     ,"items" => array(
                                                                                    "zN" => array(
                                                                                               "class" => "WikiIocDivBloc"
                                                                                              ,"parms" => array(
                                                                                                             "id" => "tb_container"
                                                                                                            ,"label" => "tb_container"
                                                                                                            ,"height" => "40%"
                                                                                                          )
                                                                                              ,"items" => array(
                                                                                                             array(
                                                                                                                "class" => "WikiIocTabsContainer"
                                                                                                               ,"parms" => array(
                                                                                                                              "id" => "zonaNavegacio"
                                                                                                                             ,"label" => "tabsNavegacio"
                                                                                                                             ,"tabType" => WikiIocTabsContainer::RESIZING_TAB_TYPE
                                                                                                                             ,"bMenuButton" => true
                                                                                                                           )
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
                                                                                   ,"zM" => array(
                                                                                               "class" => "WikiIocDivBloc"
                                                                                              ,"parms" => array(
                                                                                                             "id" => "zonaMetaInfo_DivBloc"
                                                                                                            ,"label" => "zonaMetaInfo"
                                                                                                            ,"height" => "60%"
                                                                                                          )
                                                                                              ,"items" => array(
                                                                                                             array(
                                                                                                                "class" => "WikiIocAccordionContainer"
                                                                                                               ,"parms" => array(
                                                                                                                              "id" => "zonaMetaInfo"
                                                                                                                             ,"label" => "ContainerMetaInfo"
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
                                                                                   )
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
                                                                                  )
                                                                      ,"items" => array(
                                                                                    /*
                                                                                     array(
                                                                                        "class" => "WikiIocDropDownButton"
                                                                                       ,"parms" => array(
                                                                                                      "id" => "r_loginButton"
                                                                                                     ,"label" => "Entrar"
                                                                                                     ,"autoSize" => true
                                                                                                     ,"display" => true
                                                                                                     ,"displayBlock" => true
                                                                                                   )
                                                                                       ,"items" => array(
                                                                                                      "class" => "WikiIocHiddenDialog"
                                                                                                     ,"parms" => array(
                                                                                                                    "id" => "r_loginDialog"
                                                                                                                   ,"label" => "loginDialog"
                                                                                                                 )
                                                                                                     ,"items" => array(
                                                                                                                    array(
                                                                                                                       "class" => "WikiIocFormInputField"
                                                                                                                      ,"parms" => array(
                                                                                                                                     "id" => "r_name"
                                                                                                                                    ,"label" => "Usuari:"
                                                                                                                                    ,"name" => "u"
                                                                                                                                  )
                                                                                                                         )
                                                                                                                   ,array(
                                                                                                                       "class" => "WikiIocFormInputField"
                                                                                                                      ,"parms" => array(
                                                                                                                                     "id" => "r_pass"
                                                                                                                                    ,"label" => "Contrasenya:"
                                                                                                                                    ,"name" => "p"
                                                                                                                                    ,"type" => "password"
                                                                                                                                  )
                                                                                                                         )
                                                                                                                      )
                                                                                                        )
                                                                                          )

                                                                                     ,array(
                                                                                        "class" => "WikiIocDropDownButton"
                                                                                       ,"parms" => array(
                                                                                                      "id" => "r_userButton"
                                                                                                     ,"label" => "Menú User"
                                                                                                     ,"autoSize" => true
                                                                                                     ,"display" => true
                                                                                                     ,"displayBlock" => true
                                                                                                   )
                                                                                       ,"items" => array(
                                                                                                      "class" => "WikiIocDropDownMenu"
                                                                                                     ,"parms" => array(
                                                                                                                    "id" => "r_userDialog"
                                                                                                                   ,"label" => "userDialog"
                                                                                                                 )
                                                                                                     ,"items" => array(
                                                                                                                    array(
                                                                                                                       "class" => "WikiIocMenuItem"
                                                                                                                      ,"parms" => array(
                                                                                                                                     "id" => "r_userMenuItem"
                                                                                                                                    ,"label" => "La meva pàgina"
                                                                                                                                    ,"query" => "do=user"
                                                                                                                                    ,"autoSize" => true
                                                                                                                                    ,"display" => false
                                                                                                                                    ,"displayBlock" => true
                                                                                                                                  )
                                                                                                                         )
                                                                                                                   ,array(
                                                                                                                       "class" => "WikiIocMenuItem"
                                                                                                                      ,"parms" => array(
                                                                                                                                     "id" => "r_talkUserMenuItem"
                                                                                                                                    ,"label" => "Discussió"
                                                                                                                                    ,"query" => "do=talkUser"
                                                                                                                                    ,"autoSize" => true
                                                                                                                                    ,"display" => false
                                                                                                                                    ,"displayBlock" => true
                                                                                                                                  )
                                                                                                                         )
                                                                                                                   ,array(
                                                                                                                       "class" => "WikiIocMenuItem"
                                                                                                                      ,"parms" => array(
                                                                                                                                     "id" => "r_logoffMenuItem"
                                                                                                                                    ,"label" => "Desconnectar"
                                                                                                                                    ,"query" => "do=logoff"
                                                                                                                                    ,"autoSize" => true
                                                                                                                                    ,"display" => false
                                                                                                                                    ,"displayBlock" => true
                                                                                                                                  )
                                                                                                                         )
                                                                                                                      )
                                                                                                        )
                                                                                          )
                                                                                     */
                                                                                     array(
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
                                                        ,"bottom" => array(
                                                                        "class" => "WikiIocItemsPanel"
                                                                       ,"parms" => array(
                                                                                      "id" => "id_BottomPanel"
                                                                                     ,"region" => "bottom"
                                                                                     ,"height" => "30px"
                                                                                     ,"doLayout" => "false"
                                                                                     ,"splitter" => "true"
                                                                                   )
                                                                       ,"items" => array(
                                                                                      array(
                                                                                         "class" => "WikiIocTextContentPane"
                                                                                        ,"parms" => array(
                                                                                                       "id" => "zonaMissatges"
                                                                                                      ,"missatge" => "estoy aquí"
                                                                                                    )
                                                                                      )
                                                                                   )
                                                                       )
                                                      )
                                        )
                            )
    );
    
    private $arrIds;
    private $arrTpl;

    /*SINGLETON CLASS*/
    public static function Instance($soloArrayIoc){
        static $inst = null;
        if ($inst === null) {
            $inst = new WikiIocCfg($soloArrayIoc);
        }
        return $inst;
    }

    private function __construct($soloArrayIoc){
        if ($soloArrayIoc !== "soloArrIocCfg") {

            //LoginResponseHandler utilitza els id's: zN_index_id, zonaMetaInfo
            $this->arrIds = array(
            		"mainContent" => "mainContent"
                	,"bodyContent" => "bodyContent"
    				//id's de les Zones/Contenidors principals
        			,"zonaAccions"   => "zonaAccions"	
            		,"zonaNavegacio" => "zonaNavegacio" //ojo, ojito, musho cuidadito, antes se llamaba "nav"
                	,"zonaMetaInfo"  => "zonaMetaInfo"
                    ,"zonaMissatges" => "zonaMissatges"
    				,"zonaCanvi"     => "zonaCanvi"
        			,"barraMenu"     => "barraMenu"
            		//id's de les pestanyes (tabs) de la zona de Navegació
                	,"zN_index_id"  => "tb_index"	
                    ,"zN_perfil_id" => "tb_perfil"	
    				,"zN_admin_id"  => "tb_admin"	
        			,"zN_docum_id"  => "tb_docu"	
            		//id's dels botons de la zona de Canvi
                	,"loginDialog"   => "loginDialog"
                    ,"loginButton"   => "loginButton"
    				,"exitButton"    => "exitButton"
        			,"editButton"    => "editButton"
            		,"newButton"     => "newButton"
                	,"saveButton"    => "saveButton"
                    ,"previewButton" => "previewButton"
    				,"cancelButton"  => "cancelButton"
        			,"edparcButton"  => "edparcButton"
                	,"userDialog"       => "userDialog"
                    ,"userButton"       => "userButton"
                    ,"userMenu"         => "userMenu"
                    ,"userMenuItem"     => "userMenuItem"
                    ,"talkUserMenuItem" => "talkUserMenuItem"
                    ,"logoffMenuItem"   => "logoffMenuItem"
            	);

    		$this->arrTpl = array(
        			"%%ID%%" => "ajax"
            		,'%%SECTOK%%'            => getSecurityToken()
                	,'@@MAIN_CONTENT@@'      => $this->getArrIds("mainContent")
                    ,'@@BODY_CONTENT@@'      => $this->getArrIds("bodyContent")
    				,'@@NAVEGACIO_NODE_ID@@' => $this->getArrIds("zonaNavegacio")
        			,'@@METAINFO_NODE_ID@@'  => $this->getArrIds("zonaMetaInfo")
            		,'@@INFO_NODE_ID@@'      => $this->getArrIds("zonaMissatges")
                	,'@@CANVI_NODE_ID@@'     => $this->getArrIds("zonaCanvi")
                    ,'@@TAB_INDEX@@'         => $this->getArrIds("zN_index_id")
    				,'@@TAB_DOCU@@'          => $this->getArrIds("zN_docum_id")
        			,'@@LOGIN_DIALOG@@'      => $this->getArrIds("loginDialog")
            		,'@@LOGIN_BUTTON@@'      => $this->getArrIds("loginButton")
                	,'@@EXIT_BUTTON@@'       => $this->getArrIds("exitButton")
                    ,'@@EDIT_BUTTON@@'       => $this->getArrIds("editButton")
    				,'@@NEW_BUTTON@@'        => $this->getArrIds("newButton")
        			,'@@SAVE_BUTTON@@'       => $this->getArrIds("saveButton")
            		,'@@PREVIEW_BUTTON@@'    => $this->getArrIds("previewButton")
                	,'@@CANCEL_BUTTON@@'     => $this->getArrIds("cancelButton")
                    ,'@@ED_PARC_BUTTON@@'    => $this->getArrIds("edparcButton")
                    ,'@@USER_BUTTON@@'       => $this->getArrIds("userButton")
                    ,'@@USER_MENU@@'         => $this->getArrIds("userMenu")
                    ,'@@USER_MENUITEM@@'     => $this->getArrIds("userMenuItem")
                    ,'@@TALKUSER_MENUITEM@@' => $this->getArrIds("talkUserMenuItem")
                    ,'@@LOGOFF_MENUITEM@@'   => $this->getArrIds("logoffMenuItem")
                    ,'@@DOJO_SOURCE@@'       => $this->getJsPackage("dojo")
        	);
        }
	}

    public function getJsPackage($id){
        global $js_packages;
        return $js_packages[$id];
    }
	
	public function getArrIds($key){
		return $this->arrIds[$key];
	}

	public function getIocCfg(){
		return $this->arrIocCfg;
	}

	public function getArrayTpl(){
		return $this->arrTpl;
	}
}