<?php
$arrParcial = array(
                 "class" => "WikiEventButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::SAVE_FORM_BUTTON
                                          ,"title" => "Desar Formulari"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
//                                           "query" => "'do=save'", // Alerta[Xavi] Si es posa així no funciona
                                          "autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconSave'"
//                                          ,"standbyId" => cfgIdConstants::getConstantToString(cfgIdConstants::BODY_CONTENT)
//                                          ,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=project&do=save'"
//                                          ,"eventId" => "'save_partial'"// S'ha d'afegir el id del document que correspongui
                                        )
                            )
              );
