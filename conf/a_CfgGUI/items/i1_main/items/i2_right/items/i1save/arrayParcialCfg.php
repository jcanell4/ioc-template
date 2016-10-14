<?php
$arrParcial = array(
                 "class" => "WikiEventButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::SAVE_BUTTON
                                          ,"title" => "Desar"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                          "autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconSave'"
//                                          ,"query" => "'do=save'"
//                                          ,"standbyId" => cfgIdConstants::getConstantToString(cfgIdConstants::BODY_CONTENT)
//                                          ,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=save'"
//                                          ,"eventId" => "'save_partial'"// S'ha d'afegir el id del document que correspongui
                                        )
                            )
              );
