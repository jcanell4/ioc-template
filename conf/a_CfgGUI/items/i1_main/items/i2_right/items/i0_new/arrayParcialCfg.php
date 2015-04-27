<?php
$arrParcial = array(
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
              );
