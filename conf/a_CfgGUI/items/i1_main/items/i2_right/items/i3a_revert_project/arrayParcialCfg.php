<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::REVERT_PROJECT_BUTTON
                                          ,"title" => "Revertir"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                          "query" => "'do=revert'"
                                          ,"urlBase" => "'lib/exe/ioc_ajax.php?call=project'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconUndo'"
                                        )
                            )
              );
