<?php
$arrParcial = array(
                 "class" => "WikiEventButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::REVERT_BUTTON
                                          ,"title" => "Revertir"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                          "autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconUndo'"
//                                          ,"query" => "'do=save'"
//                                          ,"standbyId" => cfgIdConstants::getConstantToString(cfgIdConstants::BODY_CONTENT)
//                                          ,"urlBase" => "'ajax.php?call=save'"
//                                          ,"eventId" => "'save_partial'"// S'ha d'afegir el id del document que correspongui
                                        )
                            )
              );
