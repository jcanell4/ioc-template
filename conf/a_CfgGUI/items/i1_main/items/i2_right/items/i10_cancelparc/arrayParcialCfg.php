<?php
$arrParcial = array(
                 "class" => "WikiEventButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::CANCEL_PARC_BUTTON
                                          ,"label" => "Tornar Parc."
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "query" => "'do=cancel_partial'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                          /*,"standbyId" => "'bodyContent'"*/
                                          ,"standbyId" => cfgIdConstants::getConstantToString(cfgIdConstants::BODY_CONTENT)
                                          ,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=cancel_partial'"
                                          ,"eventId" => "'cancel_partial'"
                                        )
                            )
              );
