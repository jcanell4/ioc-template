<?php
$arrParcial = array(
                  "class" => "WikiIocItemsPanel"
                 ,"parms" => array(
                     "DOM" => array(
                        "id" => cfgIdConstants::ZONA_CANVI
                       ,"region" => "right"
                       ,"doLayout" => "true"
                       ,"splitter" => "true"
                       ,"minSize" => "50"
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
                              "id" => cfgIdConstants::NEW_BUTTON
                             ,"label" => "Nou"
                             ,"class" => "iocDisplayBlock"
                           )
                          ,"DJO" => array(
                              "query" => "'do=new'"
                             ,"autoSize" => true
                             ,"visible" => false
                             ,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=new_page'"       
                           )
                        )
                     )
                    ,array(
                        "class" => "WikiIocButton"
                       ,"parms" => array(
                           "DOM" => array(
                              "id" => cfgIdConstants::SAVE_BUTTON
                             ,"label" => "Desar"
                             ,"class" => "iocDisplayBlock"
                           )
                          ,"DJO" => array(
                              "query" => "'do=save'"
                             ,"autoSize" => true
                             ,"visible" => false
                             ,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=save'"       
                           )
                        )
                     )
                    ,array(
                        "class" => "WikiIocButton"
                       ,"parms" => array(
                           "DOM" => array(
                              "id" => cfgIdConstants::PREVIEW_BUTTON
                             ,"label" => "Previsualitza"
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
                              "id" => cfgIdConstants::MEDIA_DETAIL_BUTTON
                             ,"label" => "Detall"
                             ,"class" => "iocDisplayBlock"
                           )
                          ,"DJO" => array(
                              "query" => "'lib/plugins/ajaxcommand/ajax.php?call=media'"
                             ,"autoSize" => true
                             ,"visible" => false
                           )
                        )
                     )
                    ,array(
                        "class" => "WikiIocButton"
                       ,"parms" => array(
                           "DOM" => array(
                              "id" => cfgIdConstants::CANCEL_BUTTON
                             ,"label" => "Cancel·la"
                             ,"class" => "iocDisplayBlock"
                           )
                          ,"DJO" => array(
                              "query" => "'do=cancel'"
                             ,"autoSize" => true
                             ,"visible" => false
                             ,"standbyId" => "'bodyContent'"       
                             ,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=cancel'"       
                           )
                        )
                     )
                    ,array(
                        "class" => "WikiIocButton"
                       ,"parms" => array(
                           "DOM" => array(
                              "id" => cfgIdConstants::EDIT_BUTTON
                             ,"label" => "Edició"
                             ,"class" => "iocDisplayBlock"
                           )
                          ,"DJO" => array(
                              "query" => "'do=edit'"
                             ,"autoSize" => true
                             ,"visible" => false
                             ,"standbyId" => "'bodyContent'"       
                             ,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=edit'"       
                           )
                        )
                     )
                    ,array(
                        "class" => "WikiIocButton"
                       ,"parms" => array(
                           "DOM" => array(
                              "id" => cfgIdConstants::ED_PARC_BUTTON
                             ,"label" => "Ed. Parc."
                             ,"class" => "iocDisplayBlock"
                           )
                          ,"DJO" => array(
                              "query" => "'do=edparc'"
                             ,"autoSize" => true
                             ,"visible" => false
                             ,"standbyId" => "'bodyContent'"       
                             ,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=edit'"       
                           )
                        )
                     )
                    ,array(
                        "class" => "WikiIocButton"
                       ,"parms" => array(
                           "DOM" => array(
                              "id" => cfgIdConstants::EXIT_BUTTON
                             ,"label" => "Sortir"
                             ,"class" => "iocDisplayBlock"
                           )
                          ,"DJO" => array(
                              "query" => "'do=logoff'"
                             ,"autoSize" => true
                             ,"visible" => false
                             ,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=login'"
                           )
                        )
                     )
                  )
               );
