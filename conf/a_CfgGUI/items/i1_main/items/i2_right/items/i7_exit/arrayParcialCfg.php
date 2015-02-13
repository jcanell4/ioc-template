<?php
$arrParcial = array(
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
              );
