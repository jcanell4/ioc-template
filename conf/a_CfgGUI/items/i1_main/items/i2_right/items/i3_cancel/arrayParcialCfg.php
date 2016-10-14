<?php
$arrParcial = array(
                 "class" => "WikiEventButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::CANCEL_BUTTON
                                          ,"title" => "Tornar"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                          "autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconClose'"
//                                          ,"query" => "'do=cancel'"
//                                          ,"standbyId" => cfgIdConstants::getConstantToString(cfgIdConstants::BODY_CONTENT)
//                                          ,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=cancel_'"
//                                          ,"eventId" => "'cancel_partial'" // S'ha d'afegir el id del document que correspongui
                                        )
                            )
              );
