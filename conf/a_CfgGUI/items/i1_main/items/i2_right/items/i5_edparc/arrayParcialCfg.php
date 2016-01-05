<?php
$arrParcial = array(
                 "class" => "WikiEventButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::ED_PARC_BUTTON
                                          ,"label" => "Ed. Parc."
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "query" => "'do=edit_partial'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                          /*,"standbyId" => "'bodyContent'"*/
                                          ,"standbyId" => cfgIdConstants::getConstantToString(cfgIdConstants::BODY_CONTENT)
                                          ,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=edit_partial'"
                                          ,"eventId" => "'edit_partial'"
                                        )
                            )
              );
