<?php
$arrParcial = array(
                 "class" => "WikiEventButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::SAVE_BUTTON
                                          ,"label" => "Desar"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "query" => "'do=save'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                          ,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=save'"
                                          ,"eventId" => "'saveAction'"
                                        )
                            )
              );

