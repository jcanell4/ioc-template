<?php
$arrParcial = array(
                  "class" => "WikiIocItemsPanel"
                 ,"parms" => array(
                     "DOM" => array(
                        "id" => "zonaCanvi"
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
                              "id" => "newButton"
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
                              "id" => "saveButton"
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
                              "id" => "previewButton"
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
                              "id" => "mediaDetailButton"
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
                              "id" => "cancelButton"
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
                              "id" => "editButton"
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
                              "id" => "edparcButton"
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
                              "id" => "exitButton"
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
