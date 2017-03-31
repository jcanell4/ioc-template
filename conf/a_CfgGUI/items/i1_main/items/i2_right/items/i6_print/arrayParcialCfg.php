<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::PRINT_BUTTON
                                          ,"title" => "Imprimeix"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "query" => "'do=print'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconPrint'"
                                          ,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=print'"
                                        )
                            )
              );
