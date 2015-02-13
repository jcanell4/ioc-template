<?php
$arrParcial = array(
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
                                          /*,"standbyId" => "'bodyContent'"*/
                                          ,"standbyId" => cfgIdConstants::getConstantToString(cfgIdConstants::BODY_CONTENT)
                                          ,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=cancel'"
                                        )
                            )
              );
