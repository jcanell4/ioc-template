<?php
$arrParcial = array(
                 "class" => "WikiEventButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::SAVE_PARC_BUTTON
                                          ,"label" => "Desar Parc."
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "query" => "'do=save_partial'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                          /*,"standbyId" => "'bodyContent'"*/
                                          ,"standbyId" => cfgIdConstants::getConstantToString(cfgIdConstants::BODY_CONTENT)
                                          ,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=save_partial'"
                                          ,"eventId" => "'save_partial'"
                                        )
                            )
              );
