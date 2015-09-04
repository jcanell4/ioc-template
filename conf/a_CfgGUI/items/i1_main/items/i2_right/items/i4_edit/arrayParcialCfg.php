<?php
$arrParcial = array(
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
                                          /*,"standbyId" => "'bodyContent'"*/
                                          ,"standbyId" => cfgIdConstants::getConstantToString(cfgIdConstants::BODY_CONTENT)
                                          ,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=edit'"
                                        )
                            )
              );
