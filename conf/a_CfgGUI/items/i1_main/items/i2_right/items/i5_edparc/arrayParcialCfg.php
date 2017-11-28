<?php
$arrParcial = array(
                 "class" => "WikiEventButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::ED_PARC_BUTTON
                                          ,"title" => "Edició Parcial"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconPartialEdit'"
//                                          ,"query" => "'do=edit_partial'"
//                                          ,"standbyId" => cfgIdConstants::getConstantToString(cfgIdConstants::BODY_CONTENT)
//                                          ,"urlBase" => "'ajax.php?call=edit_partial'"
//                                          ,"eventId" => "'edit_partial'" // S'ha d'afegir el id del document que correspongui
                                        )
                            )
              );
