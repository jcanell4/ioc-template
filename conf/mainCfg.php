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
                              "DOM" => array(
                                         "id" => "main"
                                       )
                            )
                ,"items" => array(
                              "top" => array(
                                          "class" => "WikiIocDivBloc"
                                         ,"parms" => array(
                                                       "DOM" => array(
                                                                  "id" => "TopBloc"
                                                                )
                                                      ,"CSS" => array(
                                                                  "height" => "55px"
                                                                 ,"width" => "100%"
                                                                )
                                                     )
                                         ,"items" => array(
                                                      "logo" => array(
                                                                  "class" => "WikiIocImage"
                                                                 ,"parms" => array(
                                                                               "CSS" => array(
                                                                                          "position"=> "absolute"
                                                                                         ,"top"=> "2px"
                                                                                         ,"left"=> "0px"
                                                                                         ,"width"=> "240px"
                                                                                         ,"height"=> "50px"
                                                                                         ,"z-index"=> "900"
                                                                                        )
                                                                              ,"PRP" => array(
                                                                                          "src" => "img/logo.png"
                                                                                        )
                                                                             )
                                                                )
                                                     ,"menu" => array(
                                                                  "class" => "WikiDojoToolBar"
                                                                 ,"parms" => array(
                                                                               "DOM" => array(
                                                                                          "id"=> "barraMenu"
                                                                                         ,"label"=> "BarraMenu"
                                                                                        )
                                                                              ,"CSS" => array(
                                                                                          "position" => "fixed"
                                                                                         ,"top" => "28px"
                                                                                         ,"left" => "270px"
                                                                                         ,"z-index" => "900"
                                                                                        )
                                                                             )
                                                                 ,"items" => array(
                                                                                array(
                                                                                  "class" => "WikiDojoButton"
                                                                                 ,"parms" => array(
                                                                                               "DOM" => array(
                                                                                                          "id"=> "menu_vista"
                                                                                                         ,"label"=> "VISTA"
                                                                                                         ,"class" => "dijitInline"
                                                                                                        )
                                                                                              ,"DJO" => array(
                                                                                                          "onClick" => "function(){alert('VISTA')}"
                                                                                                         ,"visible" => true
                                                                                                        )
                                                                                             )
                                                                                )
                                                                               ,array(
                                                                                  "class" => "WikiDojoButton"
                                                                                 ,"parms" => array(
                                                                                               "DOM" => array(
                                                                                                          "id"=> "menu_edicio"
                                                                                                         ,"label"=> "EDICIÓ"
                                                                                                         ,"class" => "dijitInline"
                                                                                                        )
                                                                                              ,"DJO" => array(
                                                                                                          "onClick" => "function(){alert('EDICIÓ')}"
                                                                                                         ,"visible" => true
                                                                                                        )
                                                                                             )
                                                                                )
                                                                               ,array(
                                                                                  "class" => "WikiDojoButton"
                                                                                 ,"parms" => array(
                                                                                               "DOM" => array(
                                                                                                          "id"=> "menu_correccio"
                                                                                                         ,"label"=> "CORRECCIÓ"
                                                                                                         ,"class" => "dijitInline"
                                                                                                        )
                                                                                              ,"DJO" => array(
                                                                                                          "onClick" => "function(){alert('CORRECCIÓ')}"
                                                                                                         ,"visible" => true
                                                                                                        )
                                                                                             )
                                                                                )
                                                                             )
                                                                )

                                                     ,"login" => array(
                                                                   "class" => "WikiIocDivBloc"
                                                                  ,"parms" => array(
                                                                                "DOM" => array(
                                                                                           "id"=> "zonaLogin"
                                                                                         )
                                                                               ,"CSS" => array(
                                                                                           "width" => "80px"
                                                                                          ,"height" => "60px"
                                                                                          ,"float" => "right"
                                                                                         )
                                                                              )
                                                                  ,"items" => array(
                                                                                 array(
                                                                                    "class" => "WikiIocDropDownButton"
                                                                                   ,"parms" => array(
                                                                                                 "DOM" => array(
                                                                                                            "id" => "loginButton"
                                                                                                           ,"label" => "Entrar"
                                                                                                           ,"class" => "iocDisplayBlock"
                                                                                                          )
                                                                                                ,"DJO" => array(
                                                                                                            "autoSize" => true
                                                                                                           ,"visible" => true
                                                                                                          )
                                                                                               )
                                                                                   ,"items" => array(
                                                                                                  "class" => "WikiIocHiddenDialog"
                                                                                                 ,"parms" => array(
                                                                                                               "DOM" => array(
                                                                                                                          "id"=> "loginDialog"
                                                                                                                        )
                                                                                                             )
                                                                                                 ,"items" => array(
                                                                                                                array(
                                                                                                                   "class" => "WikiIocFormInputField"
                                                                                                                  ,"parms" => array(
                                                                                                                                "DOM" => array(
                                                                                                                                           "id" => "name"
                                                                                                                                          ,"label" => "Usuari:"
                                                                                                                                          ,"name" => "u"
                                                                                                                                         )
                                                                                                                              )
                                                                                                                )
                                                                                                               ,array(
                                                                                                                   "class" => "WikiIocFormInputField"
                                                                                                                  ,"parms" => array(
                                                                                                                                "DOM" => array(
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
                                                                                ,array(
                                                                                    "class" => "WikiIocDropDownButton"
                                                                                   ,"parms" => array(
                                                                                                 "DOM" => array(
                                                                                                            "id" => "userButton"
                                                                                                           ,"label" => "Menú User"
                                                                                                           ,"class" => "iocDisplayBlock"
                                                                                                          )
                                                                                                ,"DJO" => array(
                                                                                                            "autoSize" => true
                                                                                                           ,"visible" => true
                                                                                                          )
                                                                                               )
                                                                                   ,"items" => array(
                                                                                                  "class" => "WikiIocDropDownMenu"
                                                                                                 ,"parms" => array(
                                                                                                               "DOM" => array(
                                                                                                                           "id" => "userDialog"
                                                                                                                        )
                                                                                                             )
                                                                                                 ,"items" => array(
                                                                                                                array(
                                                                                                                   "class" => "WikiIocMenuItem"
                                                                                                                  ,"parms" => array(
                                                                                                                                "DOM" => array(
                                                                                                                                           "id" => "userMenuItem"
                                                                                                                                          ,"label" => "La meva pàgina"
                                                                                                                                         )
                                                                                                                               ,"DJO" => array(
                                                                                                                                           "query" => "'id=user'"
                                                                                                                                          ,"autoSize" => true
                                                                                                                                          ,"disabled" => false
                                                                                                                                         )
                                                                                                                              )
                                                                                                                )
                                                                                                               ,array(
                                                                                                                   "class" => "WikiIocMenuItem"
                                                                                                                  ,"parms" => array(
                                                                                                                                "DOM" => array(
                                                                                                                                           "id" => "talkUserMenuItem"
                                                                                                                                          ,"label" => "Discussió"
                                                                                                                                         )
                                                                                                                               ,"DJO" => array(
                                                                                                                                           "query" => "'id=talkUser'"
                                                                                                                                          ,"autoSize" => true
                                                                                                                                          ,"disabled" => false
                                                                                                                                         )
                                                                                                                              )
                                                                                                                )
                                                                                                               ,array(
                                                                                                                   "class" => "WikiIocMenuItem"
                                                                                                                  ,"parms" => array(
                                                                                                                                "DOM" => array(
                                                                                                                                           "id" => "logoffMenuItem"
                                                                                                                                          ,"label" => "Desconnectar"
                                                                                                                                         )
                                                                                                                               ,"DJO" => array(
                                                                                                                                           "query" => "'do=logoff'"
                                                                                                                                          ,"autoSize" => true
                                                                                                                                          ,"disabled" => false
                                                                                                                                         )
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
                                                        "DOM" => array(
                                                                   "id" => "mainContent"
                                                                 )
                                                      )
                                          ,"items" => array(
                                                         "left" => array(
                                                                      "class" => "WikiIocItemsPanel"
                                                                     ,"parms" => array(
                                                                                   "DOM" => array(
                                                                                              "id" => "id_LeftPanel"
                                                                                             ,"region" => "left"
                                                                                             ,"doLayout" => "true"
                                                                                             ,"splitter" => "true"
                                                                                             ,"minSize" => "150px"
                                                                                             ,"closable" => "false"
                                                                                            )
                                                                                  ,"CSS" => array(
                                                                                              "width" => "190px"
                                                                                            )
                                                                                 )
                                                                     ,"items" => array(
                                                                                    "zN" => array(
                                                                                               "class" => "WikiIocDivBloc"
                                                                                              ,"parms" => array(
                                                                                                            "DOM" => array(
                                                                                                                       "id"=> "tb_container"
                                                                                                                     )
                                                                                                           ,"CSS" => array(
                                                                                                                        "height" => "40%"
                                                                                                                     )
                                                                                                          )
                                                                                              ,"items" => array(
                                                                                                             array(
                                                                                                                "class" => "WikiIocTabsContainer"
                                                                                                               ,"parms" => array(
                                                                                                                             "DOM" => array(
                                                                                                                                        "id" => "zonaNavegacio"
                                                                                                                                       ,"label" => "tabsNavegacio"
                                                                                                                                       ,"tabType" => WikiIocTabsContainer::RESIZING_TAB_TYPE
                                                                                                                                       ,"useMenu" => true
                                                                                                                                      )
                                                                                                                           )
                                                                                                               ,"items" => array(
                                                                                                                              array(
                                                                                                                                 "class" => "WikiIocTreeContainer"
                                                                                                                                ,"parms" => array(
                                                                                                                                              "DOM" => array(
                                                                                                                                                         "id" => "tb_index"
                                                                                                                                                        ,"label" => "Índex"
                                                                                                                                                       )
                                                                                                                                             ,"DJO" => array(
                                                                                                                                                         "treeDataSource" => "'lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/'"
                                                                                                                                                       )
                                                                                                                                            )
                                                                                                                              )
                                                                                                                             ,array(
                                                                                                                                 "class" => "WikiIocContentPane"
                                                                                                                                ,"parms" => array(
                                                                                                                                              "DOM" => array(
                                                                                                                                                         "id" => "tb_perfil"
                                                                                                                                                        ,"label" => "Perfil"
                                                                                                                                                       )
                                                                                                                                            )
                                                                                                                              )
                                                                                                                             ,array(
                                                                                                                                 "class" => "WikiIocContentPane"
                                                                                                                                ,"parms" => array(
                                                                                                                                              "DOM" => array(
                                                                                                                                                         "id" => "tb_admin"
                                                                                                                                                        ,"label" => "Admin"
                                                                                                                                                       )
                                                                                                                                            )
                                                                                                                              )
                                                                                                                             ,array(
                                                                                                                                 "class" => "WikiIocContainerFromPage"
                                                                                                                                ,"parms" => array(
                                                                                                                                              "DOM" => array(
                                                                                                                                                         "id" => "tb_docu"
                                                                                                                                                        ,"label" => "documentació"
                                                                                                                                                       )
                                                                                                                                             ,"PRP" => array(
                                                                                                                                                         "page" => ":wiki:navigation"
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
                                                                                                            "DOM" => array(
                                                                                                                       "id"=> "zonaMetaInfo_DivBloc"
                                                                                                                     )
                                                                                                           ,"CSS" => array(
                                                                                                                        "height" => "60%"
                                                                                                                     )
                                                                                                          )
                                                                                              ,"items" => array(
                                                                                                             array(
                                                                                                                "class" => "WikiIocAccordionContainer"
                                                                                                               ,"parms" => array(
                                                                                                                             "DOM" => array(
                                                                                                                                        "id" => "zonaMetaInfo"
                                                                                                                                       ,"label" => "ContainerMetaInfo"
                                                                                                                                      )
                                                                                                                           )
                                                                                                             )
                                                                                                          )
                                                                                            )
                                                                                 )
                                                                   )
                                                        ,"center" => array(
                                                                        "class" => "WikiIocItemsPanelDiv"
                                                                       ,"parms" => array(
                                                                                     "DOM" => array(
                                                                                                "id" => "content"
                                                                                               ,"label" => "CentralPanel"
                                                                                               ,"region" => "center"
                                                                                               ,"class" => "ioc_content dokuwiki"
                                                                                               ,"doLayout" => "false"
                                                                                               ,"splitter" => "false"
                                                                                              )
                                                                                   )
                                                                       ,"items" => array(
                                                                                      array(
                                                                                         "class" => "WikiIocTabsContainer"
                                                                                        ,"parms" => array(
                                                                                                      "DOM" => array(
                                                                                                                 "id" => "bodyContent"
                                                                                                                ,"label" => "bodyContent"
                                                                                                                ,"tabType" => WikiIocTabsContainer::SCROLLING_TAB_TYPE
                                                                                                                ,"useMenu" => true
                                                                                                                ,"useSlider" => true
                                                                                                               )
                                                                                                    )
                                                                                      )
                                                                                   )
                                                                     )
                                                        ,"right" => array(
                                                                       "class" => "WikiIocItemsPanel"
                                                                      ,"parms" => array(
                                                                                    "DOM" => array(
                                                                                               "id" => "zonaCanvi"
                                                                                              ,"region" => "right"
                                                                                              ,"doLayout" => "true"
                                                                                              ,"splitter" => "true"
                                                                                              ,"minSize" => "0px"
                                                                                              ,"closable" => "true"
                                                                                             )
                                                                                   ,"CSS" => array(
                                                                                               "width" => "65px"
                                                                                              ,"padding" => "0px"
                                                                                             )
                                                                                  )
                                                                      ,"items" => array(
                                                                                     array(
                                                                                        "class" => "WikiIocButton"
                                                                                       ,"parms" => array(
                                                                                                      "DOM" => array(
                                                                                                                 "id"=> "newButton"
                                                                                                                ,"label"=> "Nou"
                                                                                                                ,"class" => "iocDisplayBlock"
                                                                                                               )
                                                                                                     ,"DJO" => array(
                                                                                                                 "query" => "'do=new'"
                                                                                                                ,"autoSize" => true
                                                                                                                ,"visible" => false
                                                                                                               )
                                                                                                   )
                                                                                      )
                                                                                     ,array(
                                                                                        "class" => "WikiIocButton"
                                                                                       ,"parms" => array(
                                                                                                      "DOM" => array(
                                                                                                                 "id"=> "saveButton"
                                                                                                                ,"label"=> "Desar"
                                                                                                                ,"class" => "iocDisplayBlock"
                                                                                                               )
                                                                                                     ,"DJO" => array(
                                                                                                                 "query" => "'do=save'"
                                                                                                                ,"autoSize" => true
                                                                                                                ,"visible" => false
                                                                                                               )
                                                                                                   )
                                                                                      )
                                                                                     ,array(
                                                                                        "class" => "WikiIocButton"
                                                                                       ,"parms" => array(
                                                                                                      "DOM" => array(
                                                                                                                 "id"=> "previewButton"
                                                                                                                ,"label"=> "Previsualitza"
                                                                                                                ,"class" => "iocDisplayBlock"
                                                                                                               )
                                                                                                     ,"DJO" => array(
                                                                                                                 "query" => "'do=preview'"
                                                                                                                ,"autoSize" => true
                                                                                                                ,"visible" => false
                                                                                                               )
                                                                                                   )
                                                                                      )
                                                                                     ,array(
                                                                                        "class" => "WikiIocButton"
                                                                                       ,"parms" => array(
                                                                                                      "DOM" => array(
                                                                                                                 "id"=> "cancelButton"
                                                                                                                ,"label"=> "Cancel·la"
                                                                                                                ,"class" => "iocDisplayBlock"
                                                                                                               )
                                                                                                     ,"DJO" => array(
                                                                                                                 "query" => "'do=cancel'"
                                                                                                                ,"autoSize" => true
                                                                                                                ,"visible" => false
                                                                                                               )
                                                                                                   )
                                                                                      )
                                                                                     ,array(
                                                                                        "class" => "WikiIocButton"
                                                                                       ,"parms" => array(
                                                                                                      "DOM" => array(
                                                                                                                 "id"=> "editButton"
                                                                                                                ,"label"=> "Edició"
                                                                                                                ,"class" => "iocDisplayBlock"
                                                                                                               )
                                                                                                     ,"DJO" => array(
                                                                                                                 "query" => "'do=edit'"
                                                                                                                ,"autoSize" => true
                                                                                                                ,"visible" => false
                                                                                                               )
                                                                                                   )
                                                                                      )
                                                                                     ,array(
                                                                                        "class" => "WikiIocButton"
                                                                                       ,"parms" => array(
                                                                                                      "DOM" => array(
                                                                                                                 "id"=> "edparcButton"
                                                                                                                ,"label"=> "Ed. Parc."
                                                                                                                ,"class" => "iocDisplayBlock"
                                                                                                               )
                                                                                                     ,"DJO" => array(
                                                                                                                 "query" => "'do=edparc'"
                                                                                                                ,"autoSize" => true
                                                                                                                ,"visible" => false
                                                                                                               )
                                                                                                   )
                                                                                      )
                                                                                     ,array(
                                                                                        "class" => "WikiIocButton"
                                                                                       ,"parms" => array(
                                                                                                      "DOM" => array(
                                                                                                                 "id"=> "exitButton"
                                                                                                                ,"label"=> "Sortir"
                                                                                                                ,"class" => "iocDisplayBlock"
                                                                                                               )
                                                                                                     ,"DJO" => array(
                                                                                                                 "query" => "'do=logoff'"
                                                                                                                ,"autoSize" => true
                                                                                                                ,"visible" => false
                                                                                                               )
                                                                                                   )
                                                                                      )
                                                                                  )
                                                                    )
                                                        ,"bottom" => array(
                                                                        "class" => "WikiIocItemsPanel"
                                                                       ,"parms" => array(
                                                                                     "DOM" => array(
                                                                                                "id" => "id_BottomPanel"
                                                                                               ,"region" => "bottom"
                                                                                               ,"doLayout" => "false"
                                                                                               ,"splitter" => "true"
                                                                                              )
                                                                                    ,"CSS" => array(
                                                                                                "height" => "30px"
                                                                                              )
                                                                                   )
                                                                       ,"items" => array(
                                                                                      array(
                                                                                         "class" => "WikiIocTextContentPane"
                                                                                        ,"parms" => array(
                                                                                                      "DOM" => array(
                                                                                                                 "id" => "zonaMissatges"
                                                                                                               )
                                                                                                     ,"PRP" => array(
                                                                                                                 "missatge" => "estoy aquí"
                                                                                                               )
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
                    ,'@@USER_DIALOG@@'       => $this->getArrIds("userDialog")
                    ,'@@USER_BUTTON@@'       => $this->getArrIds("userButton")
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