<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::PRINT_BUTTON
                                          ,"title" => "Versió per imprimir"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "query" => "'do=print'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconPreviewPrint'"
                                          ,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=print'"
                                        )
                            )
              );
