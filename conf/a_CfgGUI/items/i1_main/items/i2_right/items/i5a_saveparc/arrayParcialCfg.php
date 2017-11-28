<?php
$arrParcial = array(
                 "class" => "WikiEventButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::SAVE_PARC_BUTTON
                                          ,"title" => "Desar Parcial"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconSave'"
//                                          ,"query" => "'do=save_partial'"
//                                          ,"standbyId" => cfgIdConstants::getConstantToString(cfgIdConstants::BODY_CONTENT)
//                                          ,"urlBase" => "'ajax.php?call=save_partial'"
//                                          ,"eventId" => "'save_partial'"// S'ha d'afegir el id del document que correspongui
                                        )
                            )
              );
