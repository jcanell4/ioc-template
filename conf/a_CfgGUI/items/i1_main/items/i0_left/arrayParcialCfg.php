<?php
$arrParcial = array(
                  "class" => "WikiIocItemsPanel"
                 ,"parms" => array(
                     "DOM" => array(
                        "id" => cfgIdConstants::LEFT_PANEL
                       ,"region" => "left"
                       ,"doLayout" => "true"
                       ,"splitter" => "true"
                       ,"minSize" => "150"
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
                               "id"=> cfgIdConstants::TB_CONTAINER
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
                                       "id" => cfgIdConstants::ZONA_NAVEGACIO
                                      ,"label" => "tabsNavegacio"
                                      ,"tabType" => cfgIdConstants::RESIZING_TAB_TYPE
                                      ,"useMenu" => true
                                    )
                                 )
                               ,"items" => array(
                                  array(
                                      "class" => "WikiIocTreeContainer"
                                     ,"parms" => array(
                                         "DOM" => array(
                                             "id" => cfgIdConstants::TB_INDEX
                                            ,"label" => "Índex"
                                         )
                                        ,"DJO" => array(
                                            "treeDataSource" => "'lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/'"
                                           ,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=page'"
                                           ,"standbyId" => "'bodyContent'"        
                                         )
                                      )
                                  )
                                 ,array(
                                     "class" => "WikiIocContainerFromPage"
                                    ,"parms" => array(
                                         "DOM" => array(
                                             "id" => cfgIdConstants::TB_DOCU
                                            ,"label" => "documentació"
                                         )
                                        ,"DJO" => array(
                                            "urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=page'"
                                           ,"standbyId" => "'bodyContent'"        
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
                               "id"=> cfgIdConstants::ZONA_METAINFO_DIV
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
                                      "id" => cfgIdConstants::ZONA_METAINFO
                                     ,"label" => "ContainerMetaInfo"
                                   )
                                )
                            )
                         )
                      )
                  )
               );

